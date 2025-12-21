<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Technician;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Notifications\AdminNewTechnicianSelected;

class TechnicianController extends AdminController
{
    /**
     * Display a listing of technicians.
     */
    public function index(Request $request)
{
    $query = Technician::with(['user', 'specialization', 'badges']);

    // Filter by specialization
    if ($request->has('specialization_id')) {
        $query->where('specialization_id', $request->specialization_id);
    }

    // Filter by availability status
    if ($request->has('availability_status')) {
        $query->where('availability_status', $request->availability_status);
    }

    // Search by name
    if ($request->has('search')) {
        $search = $request->search;
        $query->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    $technicians = $query->latest()->paginate(15);
    $specializations = Specialization::active()->get();

    return view('admin.technicians.index', compact('technicians', 'specializations'));
}


    /**
     * Show the form for creating a new technician.
     */
    public function create()
    {
        $specializations = Specialization::active()->get();
        $roles = ['technician', 'admin']; // Available roles for technicians

        return view('admin.technicians.create', compact('specializations', 'roles'));
    }

    /**
     * Store a newly created technician in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:technician,admin',
            'specialization_id' => 'required|exists:specializations,id',
            'bio' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'badges' => 'nullable|array',
            'badges.*' => 'required|in:beginner,intermediate,expert,master',
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'user_type' => 'technician',
                'status' => 'active',
            ]);

            // Assign role
            $user->assignRole($validated['role']);

            // Create technician profile
            $technician = Technician::create([
                'user_id' => $user->id,
                'specialization_id' => $validated['specialization_id'],
                'bio' => $validated['bio'] ?? null,
                'availability_status' => 'available',
                'rating' => 0,
                'completed_tasks' => 0,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);

            // Add badges
            if (!empty($validated['badges'])) {
                foreach ($validated['badges'] as $badgeType) {
                    $technician->badges()->create(['badge_type' => $badgeType]);
                }
            }

            DB::commit();

            // Notify admins about the new technician
            $admins = User::where('user_type', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new AdminNewTechnicianSelected($technician));
            }

            return redirect()->route('admin.techs.index')
                ->with('success', 'تم إضافة الفني بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء إضافة الفني');
        }
    }

    /**
     * Display the specified technician.
     */
    public function show(Technician $technician)
    {
        $technician->load(['user', 'specialization', 'badges']);
        
        // Check if user exists
        if (!$technician->user) {
            return redirect()->route('admin.techs.index')
                ->with('error', 'الفني ليس لديه حساب مستخدم صالح');
        }

        return view('admin.technicians.show', compact('technician'));
    }

    /**
     * Show the form for editing the specified technician.
     */
    public function edit($id)
    {
        $technician = Technician::with(['user'])->findOrFail($id);
        
        // Check if user exists
        if (!$technician->user) {
            return redirect()->route('admin.techs.index')
                ->with('error', 'الفني ليس لديه حساب مستخدم صالح');
        }
        
        $specializations = Specialization::active()->get();
        $roles = ['technician', 'admin'];
        $currentRole = $technician->user->roles->first()?->name ?? 'technician';

        return view('admin.technicians.edit', compact('technician', 'specializations', 'roles', 'currentRole'));
    }

    /**
     * Update the specified technician in storage.
     */
    public function update(Request $request, $id)
    {
        $technician = Technician::with(['user'])->findOrFail($id);
        
        // Load the user relationship and check if user exists
        if (!$technician->user) {
            return back()->withInput()->with('error', 'الفني ليس لديه حساب مستخدم صالح');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $technician->user_id,
            'phone' => 'required|string|unique:users,phone,' . $technician->user_id,
            'role' => 'required|in:technician,admin',
            'specialization_id' => 'required|exists:specializations,id',
            'availability_status' => 'required|in:available,busy,on_leave',
            'bio' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'badges' => 'nullable|array',
            'badges.*' => 'required|in:beginner,intermediate,expert,master',
        ]);

        DB::beginTransaction();
        try {
            // Update user
            $technician->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ]);

            // Update role if changed
            $currentRole = $technician->user->roles->first()?->name;
            if ($currentRole !== $validated['role']) {
                $technician->user->syncRoles([$validated['role']]);
            }

            // Update technician profile
            $technician->update([
                'specialization_id' => $validated['specialization_id'],
                'availability_status' => $validated['availability_status'],
                'bio' => $validated['bio'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);

            // Update badges
            $technician->badges()->delete();
            if (!empty($validated['badges'])) {
                foreach ($validated['badges'] as $badgeType) {
                    $technician->badges()->create(['badge_type' => $badgeType]);
                }
            }

            DB::commit();

            return redirect()->route('admin.techs.index')
                ->with('success', 'تم تحديث بيانات الفني بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث بيانات الفني');
        }
    }

    /**
     * Remove the specified technician from storage.
     */
    public function destroy($id)
    {
        $technician = Technician::with(['user'])->findOrFail($id);
        
        // Load the user relationship and check if user exists
        if (!$technician->user) {
            // If no user exists, just delete the technician record
            $technician->delete();
            return redirect()->route('admin.techs.index')
                ->with('success', 'تم حذف الفني بنجاح');
        }
        
        DB::beginTransaction();
        try {
            $user = $technician->user;
            $technician->delete();
            $user->delete();

            DB::commit();

            return redirect()->route('admin.techs.index')
                ->with('success', 'تم حذف الفني بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء حذف الفني');
        }
    }
}
