<?php

namespace App\Http\Controllers\Admin;

use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends AdminController
{
    /**
     * Display a listing of specializations.
     */
    public function index()
    {
        $specializations = Specialization::withCount('technicians')->latest()->get();

        return view('admin.specializations.index', compact('specializations'));
    }

    /**
     * Show the form for creating a new specialization.
     */
    public function create()
    {
        return view('admin.specializations.create');
    }

    /**
     * Store a newly created specialization in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:specializations,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Specialization::create($validated);

        return redirect()->route('admin.specializations.index')
            ->with('success', 'تم إضافة التخصص بنجاح');
    }

    /**
     * Show the form for editing the specified specialization.
     */
    public function edit(Specialization $specialization)
    {
        return view('admin.specializations.edit', compact('specialization'));
    }

    /**
     * Update the specified specialization in storage.
     */
    public function update(Request $request, Specialization $specialization)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:specializations,name,' . $specialization->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $specialization->update($validated);

        return redirect()->route('admin.specializations.index')
            ->with('success', 'تم تحديث التخصص بنجاح');
    }

    /**
     * Remove the specified specialization from storage.
     */
    public function destroy(Specialization $specialization)
    {
        // Check if specialization has technicians
        if ($specialization->technicians()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف التخصص لأنه مرتبط بفنيين');
        }

        $specialization->delete();

        return redirect()->route('admin.specializations.index')
            ->with('success', 'تم حذف التخصص بنجاح');
    }
}
