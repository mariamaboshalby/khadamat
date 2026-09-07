<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Offer;
use App\Models\Review;
use App\Models\Strategy;
use App\Models\Request as RequestModel;
use App\Models\Technician;
use App\Models\Specialization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Helpers\EncryptionHelper;

class HomeController extends Controller
{
    /**
     * Homepage — public, highly cacheable.
     * Cached for 10 minutes per-site (not per-user).
     */
    public function index()
    {
        // Services are rarely updated; cache for 15 minutes
        // NOTE: services table has no 'description' column — select only existing columns
        $services = Cache::remember('home.services', 900, function () {
            return Service::select('id', 'name', 'icon', 'color_class', 'specialization_id')
                ->orderBy('name')
                ->get();
        });

        // Only active/current offers; cache 10 minutes
        $offers = Cache::remember('home.offers', 600, function () {
            return Offer::select('id', 'title', 'subtitle_1', 'subtitle_2', 'discount_value', 'discount_label', 'badge_text', 'icon', 'gradient_class')
                ->latest()
                ->get();
        });

        // Only approved reviews, limited to 12 for the carousel; cache 10 minutes
        $reviews = Cache::remember('home.reviews', 600, function () {
            return Review::select('id', 'user_id', 'technician_id', 'rating', 'comment')
                ->with('user:id,name')   // eager-load to prevent N+1 in getUserNameAttribute
                ->where('status', 'approved')
                ->latest()
                ->take(12)
                ->get();
        });

        // Strategies change very rarely; cache 30 minutes
        $strategies = Cache::remember('home.strategies', 1800, function () {
            return Strategy::select('id', 'title', 'description', 'step_number', 'color', 'points')
                ->orderBy('step_number')
                ->get();
        });

        return view('home', compact('services', 'offers', 'reviews', 'strategies'));
    }

    /**
     * Technicians listing page.
     */
    public function technicians(Request $request)
    {
        // This page is user-specific (filters) so keep shorter cache or no cache
        $specializations = Specialization::active()
            ->with(['technicians' => function ($query) {
                $query->select('id', 'user_id', 'specialization_id', 'rating', 'availability_status')
                    ->with([
                        'user:id,name',
                        'specialization:id,name',
                    ])
                    ->withCount('reviews')
                    ->orderByDesc('rating');
            }])
            ->withCount('technicians')
            ->get();

        $technicians = Technician::select('id', 'user_id', 'specialization_id', 'rating', 'availability_status', 'bio')
            ->with([
                'user:id,name',
                'specialization:id,name',
            ])
            ->withCount('reviews')
            ->orderByDesc('rating')
            ->get();

        return view('technicians.index', compact('specializations', 'technicians'));
    }

    /**
     * Services listing page.
     */
    public function services(Request $request)
    {
        $query = Service::select('id', 'name', 'icon', 'color_class', 'specialization_id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $services = $query->get();

        return view('services.index', compact('services'));
    }

    /**
     * Offers listing page.
     */
    public function offers()
    {
        $offers = Cache::remember('offers.all', 600, function () {
            return Offer::select('id', 'title', 'subtitle_1', 'discount_value', 'badge_text', 'icon')
                ->latest()
                ->get();
        });

        return view('offers.index', compact('offers'));
    }

    /**
     * Profile page (auth — just renders view, no DB needed).
     */
    public function profile()
    {
        return view('profile.index');
    }

    /**
     * User dashboard — user-specific data, no shared caching.
     */
    public function userDashboard()
    {
        $userId = Auth::id();

        $requests = RequestModel::select(
                'id', 'user_id', 'service_id', 'assigned_technician_id',
                'status', 'price_status', 'description', 'address',
                'proposed_price', 'scheduled_at', 'created_at'
            )
            ->with([
                'service:id,name,icon',
                'assignedTechnician' => function ($q) {
                    $q->select('id', 'user_id', 'specialization_id')
                      ->with([
                          'user:id,name',
                          'specialization:id,name',
                      ]);
                },
                'requestItems:id,request_id,name,quantity,total_price',
            ])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        $stats = [
            'total'           => $requests->count(),
            'pending'         => $requests->where('status', 'pending')->count(),
            'in_progress'     => $requests->whereIn('status', ['approved', 'in_progress'])->count(),
            'completed'       => $requests->where('status', 'completed')->count(),
            'cancelled'       => $requests->where('status', 'cancelled')->count(),
            'pending_approval'=> $requests->where('price_status', 'pending_customer_approval')->count(),
        ];

        $activeRequest = $requests->first(function ($req) {
            return in_array($req->status, ['in_progress', 'approved', 'pending']);
        });

        // Small lists — use select to avoid loading unnecessary columns
        $services = Cache::remember('home.services', 900, function () {
            return Service::select('id', 'name', 'icon', 'color_class', 'specialization_id')
                ->orderBy('name')
                ->get();
        });

        $offers = Offer::select('id', 'title', 'subtitle_1', 'discount_value', 'badge_text')
            ->latest()
            ->take(2)
            ->get();

        return view('dashboard', compact('requests', 'stats', 'activeRequest', 'services', 'offers'));
    }

    /**
     * Technician public profile page.
     */
    public function technicianProfile($encryptedId)
    {
        $id = EncryptionHelper::decryptId($encryptedId);

        $technician = Technician::select('id', 'user_id', 'specialization_id', 'rating', 'bio', 'availability_status', 'completed_tasks')
            ->with([
                'user:id,name,email',
                'specialization:id,name',
                'reviews' => function ($q) {
                    // Load only approved reviews with minimal columns
                    $q->select('id', 'technician_id', 'user_id', 'rating', 'comment', 'created_at')
                      ->where('status', 'approved')
                      ->with('user:id,name')
                      ->latest()
                      ->take(10);
                },
            ])
            ->findOrFail($id);

        $completedRequests = RequestModel::where('assigned_technician_id', $id)
            ->where('status', 'completed')
            ->count();

        // Use the stored rating column — avoid the redundant AVG() query
        $avgRating = $technician->rating;

        $services = Service::select('id', 'name', 'icon', 'color_class')
            ->where('specialization_id', $technician->specialization_id)
            ->get();

        if ($services->isEmpty()) {
            $services = Cache::remember('home.services', 900, function () {
                return Service::select('id', 'name', 'icon', 'color_class', 'specialization_id')
                    ->orderBy('name')
                    ->get();
            });
        }

        return view('technician-profile', compact('technician', 'completedRequests', 'avgRating', 'services'));
    }

    /**
     * Service detail page.
     */
    public function serviceShow($encryptedId)
    {
        $id = EncryptionHelper::decryptId($encryptedId);
        $service = Service::select('id', 'name', 'icon', 'color_class', 'specialization_id')
            ->with('specialization:id,name')
            ->findOrFail($id);

        return view('services.show', compact('service'));
    }

    /**
     * Submit review for a technician.
     */
    public function submitTechnicianReview(Request $request, $encryptedId)
    {
        $id = EncryptionHelper::decryptId($encryptedId);

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if (!Auth::check()) {
            return back()->with('error', 'يجب تسجيل الدخول لإضافة تقييم.');
        }

        $technician = Technician::select('id')->findOrFail($id);

        $existingReview = Review::where('technician_id', $id)
            ->where('user_id', Auth::id())
            ->exists(); // exists() is cheaper than first()

        if ($existingReview) {
            return back()->with('error', 'لقد قمت بتقييم هذا الفني مسبقاً.');
        }

        Review::create([
            'technician_id' => $id,
            'user_id'       => Auth::id(),
            'rating'        => $validated['rating'],
            'comment'       => $validated['comment'],
            'status'        => 'approved',
        ]);

        // Bust the home reviews cache so new review appears
        Cache::forget('home.reviews');

        return back()->with('success', 'تم إضافة التقييم بنجاح!');
    }
}
