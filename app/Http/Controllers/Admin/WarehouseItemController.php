<?php

namespace App\Http\Controllers\Admin;

use App\Models\WarehouseItem;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Notifications\AdminLowStockAlert;
use App\Helpers\EncryptionHelper;

class WarehouseItemController extends AdminController
{
    /**
     * Display a listing of warehouse items.
     */
    public function index(Request $request)
    {
        $query = WarehouseItem::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Low stock filter
        if ($request->has('low_stock') && $request->low_stock == '1') {
            $query->lowStock();
        }

        $items = $query->latest()->paginate(20);
        $specializations = Specialization::active()->pluck('name', 'id');
                
        return view('admin.warehouse-items.index', compact('items', 'specializations'));
    }

    /**
     * Show the form for creating a new warehouse item.
     */
    public function create()
    {
        $categories = Specialization::active()->pluck('name', 'id');
        
        return view('admin.warehouse-items.create', compact('categories'));
    }

    /**
     * Store a newly created warehouse item in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'min_quantity' => 'required|integer|min:0',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('warehouse-items', 'public');
            $validated['image_path'] = $path;
        }

        WarehouseItem::create($validated);

        return redirect()->route('admin.warehouse-items.index')
            ->with('success', 'تم إضافة الصنف بنجاح');
    }

    /**
     * Show the form for editing the specified warehouse item.
     */
    public function edit($encryptedId)
    {
        $id = EncryptionHelper::decryptId($encryptedId);
        $warehouseItem = WarehouseItem::findOrFail($id);
        
        $categories = Specialization::active()->pluck('name', 'id');
        
        return view('admin.warehouse-items.edit', compact('warehouseItem', 'categories', 'encryptedId'));
    }

    /**
     * Update the specified warehouse item in storage.
     */
    public function update(Request $request, $encryptedId)
    {
        $id = EncryptionHelper::decryptId($encryptedId);
        $warehouseItem = WarehouseItem::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'min_quantity' => 'required|integer|min:0',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($warehouseItem->image_path && Storage::disk('public')->exists($warehouseItem->image_path)) {
                Storage::disk('public')->delete($warehouseItem->image_path);
            }
            $path = $request->file('image')->store('warehouse-items', 'public');
            $validated['image_path'] = $path;
        }

        $warehouseItem->update($validated);

        // Check if item is low stock after update and notify admins
        if ($warehouseItem->isLowStock()) {
            $admins = User::where('user_type', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new AdminLowStockAlert($warehouseItem));
            }
        }

        return redirect()->route('admin.warehouse-items.index')
            ->with('success', 'تم تحديث الصنف بنجاح');
    }

    /**
     * Remove the specified warehouse item from storage.
     */
    public function destroy($encryptedId)
    {
        $id = EncryptionHelper::decryptId($encryptedId);
        $warehouseItem = WarehouseItem::findOrFail($id);
        
        $warehouseItem->delete();

        return redirect()->route('admin.warehouse-items.index')
            ->with('success', 'تم حذف الصنف بنجاح');
    }

    /**
     * Display items with low stock.
     */
    public function lowStock()
    {
        $items = WarehouseItem::lowStock()->get();

        return view('admin.warehouse-items.low-stock', compact('items'));
    }
}
