<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends AdminController
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $query = User::where('user_type', 'customer');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->latest()->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create customer user
        $validated['user_type'] = 'customer';
        $validated['password'] = bcrypt($validated['password']);
        $validated['status'] = 'active';

        User::create($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'تم إضافة العميل بنجاح');
    }

    /**
     * Display the specified customer.
     */
    public function show(User $customer)
    {
        // Ensure we're viewing a customer
        if ($customer->user_type !== 'customer') {
            abort(404);
        }

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(User $customer)
    {
        if ($customer->user_type !== 'customer') {
            abort(404);
        }

        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, User $customer)
    {
        if ($customer->user_type !== 'customer') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'required|string|unique:users,phone,' . $customer->id,
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'تم تحديث بيانات العميل بنجاح');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(User $customer)
    {
        if ($customer->user_type !== 'customer') {
            abort(404);
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'تم حذف العميل بنجاح');
    }
}