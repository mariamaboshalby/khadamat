<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use App\Models\Specialization;
use Illuminate\Http\Request;
use App\Helpers\EncryptionHelper;

class ServiceController extends AdminController
{
    /**
     * Display a listing of services.
     */
    public function index()
    {
        $services = Service::with('specialization')->latest()->get();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        $specializations = Specialization::active()->get();
        return view('admin.services.create', compact('specializations'));
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'color_class' => 'nullable|string|max:50',
            'route_name' => 'nullable|string|max:100',
            'specialization_id' => 'required|exists:specializations,id',
        ]);

        // Use default values instead of null for required fields
        $validated['icon'] = $validated['icon'] ?: 'fa-handshake';
        $validated['color_class'] = $validated['color_class'] ?: '#e54343';
        $validated['route_name'] = $validated['route_name'] ?: null;

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'تم إضافة الخدمة بنجاح');
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit($encryptedId)
    {
        $id = EncryptionHelper::decryptId($encryptedId);
        $service = Service::findOrFail($id);
        
        $specializations = Specialization::active()->get();
        return view('admin.services.edit', compact('service', 'specializations', 'encryptedId'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, $encryptedId)
    {
        $id = EncryptionHelper::decryptId($encryptedId);
        $service = Service::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'color_class' => 'nullable|string|max:50',
            'route_name' => 'nullable|string|max:100',
            'specialization_id' => 'required|exists:specializations,id',
        ]);

        // Use default values instead of null for required fields
        $validated['icon'] = $validated['icon'] ?: 'fa-handshake';
        $validated['color_class'] = $validated['color_class'] ?: '#e54343';
        $validated['route_name'] = $validated['route_name'] ?: null;

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'تم تحديث الخدمة بنجاح');
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy($encryptedId)
    {
        $id = EncryptionHelper::decryptId($encryptedId);
        $service = Service::findOrFail($id);
        
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'تم حذف الخدمة بنجاح');
    }
}