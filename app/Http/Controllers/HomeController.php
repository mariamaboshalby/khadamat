<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Offer;
use App\Models\Review;
use App\Models\Strategy;
use App\Models\Request as RequestModel;
use App\Models\Technician;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::all();
        $offers = Offer::all();
        $reviews = Review::all();
        $strategies = Strategy::orderBy('step_number')->get();
        return view('home', compact('services', 'offers', 'reviews', 'strategies'));
    }

    public function services(Request $request)
    {
        $query = Service::query();
        
        // Add search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }
        
        $services = $query->get();
        return view('services.index', compact('services'));
    }

    public function offers()
    {
        $offers = Offer::all();
        return view('offers.index', compact('offers'));
    }

    public function profile()
    {
        return view('profile.index');
    }

    // Dashboard المستخدم مع طلباته
    public function userDashboard()
    {
        $requests = RequestModel::with('service', 'user')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard', compact('requests'));
    }

    public function technicianProfile($id)
    {
        $technician =Technician::with(['user', 'specialization', 'reviews.user'])->findOrFail($id);
        
        $completedRequests = RequestModel::where('assigned_technician_id', $id)
            ->where('status', 'completed')
            ->count();
        
        $avgRating = Review::where('technician_id', $id)->avg('rating');
        
        return view('technician-profile', compact('technician', 'completedRequests', 'avgRating'));
    }

    public function serviceShow($id)
    {
        $service = Service::with('specialization')->findOrFail($id);
        return view('services.show', compact('service'));
    }

    public function submitTechnicianReview(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user is authenticated
        if (!Auth::check()) {
            return back()->with('error', 'يجب تسجيل الدخول لإضافة تقييم.');
        }

        // Get the technician
        $technician = Technician::findOrFail($id);

        // Check if user has already reviewed this technician
        $existingReview = Review::where('technician_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'لقد قمت بتقييم هذا الفني مسبقاً.');
        }

        // Create the review
        Review::create([
            'technician_id' => $id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'status' => 'approved', // Auto-approve for now
        ]);

        return back()->with('success', 'تم إضافة التقييم بنجاح!');
    }
}
