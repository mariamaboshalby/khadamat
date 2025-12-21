<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the technician profile edit form
     */
    public function edit()
    {
        $user = Auth::user();
        
        // Check if user is admin or technician
        if ($user->hasRole('admin')) {
            // Admin can view any technician profile or create a dummy one
            $technician = $user->technician ?: new \App\Models\Technician();
            return view('technician.profile.edit', compact('technician'));
        }
        
        $technician = $user->technician;
        
        if (!$technician) {
            abort(403, 'Unauthorized');
        }

        return view('technician.profile.edit', compact('technician'));
    }

    /**
     * Update the technician profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Check if user is admin or technician
        if ($user->hasRole('admin')) {
            // Admin can update profile but doesn't need a technician record
            return back()->with('success', 'المشرف لا يحتاج لملف فني');
        }
        
        $technician = $user->technician;
        
        if (!$technician) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'bio' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $technician->update($validated);

        return back()->with('success', 'تم تحديث البيانات بنجاح');
    }
}
