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
use App\Helpers\EncryptionHelper;

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

    public function technicians(Request $request)
    {
        $specializations = Specialization::active()
            ->with(['technicians' => function ($query) {
                $query->with(['user', 'specialization', 'reviews'])
                    ->orderByDesc('rating');
            }])
            ->withCount('technicians')
            ->get();

        $technicians = Technician::with(['user', 'specialization', 'reviews'])
            ->orderByDesc('rating')
            ->get();

        return view('technicians.index', compact('specializations', 'technicians'));
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
        $userId = Auth::id();
        $requests = RequestModel::with([
            'service', 
            'assignedTechnician.user', 
            'assignedTechnician.specialization', 
            'requestItems'
        ])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        $stats = [
            'total' => $requests->count(),
            'pending' => $requests->where('status', 'pending')->count(),
            'in_progress' => $requests->whereIn('status', ['approved', 'in_progress'])->count(),
            'completed' => $requests->where('status', 'completed')->count(),
            'cancelled' => $requests->where('status', 'cancelled')->count(),
            'pending_approval' => $requests->where('price_status', 'pending_customer_approval')->count(),
        ];

        // Find current most active ongoing request
        $activeRequest = $requests->first(function ($req) {
            return in_array($req->status, ['in_progress', 'approved', 'pending']);
        });

        $services = Service::all();
        $offers = Offer::take(2)->get();

        return view('dashboard', compact('requests', 'stats', 'activeRequest', 'services', 'offers'));
    }

    public function technicianProfile($encryptedId)
    {
        $id = EncryptionHelper::decryptId($encryptedId);
        $technician = Technician::with(['user', 'specialization', 'reviews.user', 'reviews.customer'])->findOrFail($id);
        
        $completedRequests = RequestModel::where('assigned_technician_id', $id)
            ->where('status', 'completed')
            ->count();
        
        $avgRating = Review::where('technician_id', $id)->avg('rating');

        $services = Service::where('specialization_id', $technician->specialization_id)->get();
        if ($services->isEmpty()) {
            $services = Service::all();
        }

        // Build service areas from location data
        $serviceAreas = $this->resolveServiceAreas($technician);

        return view('technician-profile', compact('technician', 'completedRequests', 'avgRating', 'services', 'serviceAreas'));
    }

    /**
     * Resolve service areas for a technician.
     * Priority: lat/lng → reverse geocode via Nominatim → fallback to address field.
     */
    private function resolveServiceAreas(Technician $technician): array
    {
        // 1. Try reverse geocoding if coordinates are available
        if (!empty($technician->latitude) && !empty($technician->longitude)) {
            try {
                $lat = (float) $technician->latitude;
                $lng = (float) $technician->longitude;

                $url = sprintf(
                    'https://nominatim.openstreetmap.org/reverse?format=json&lat=%s&lon=%s&zoom=10&accept-language=ar',
                    $lat,
                    $lng
                );

                $ctx = stream_context_create([
                    'http' => [
                        'timeout'       => 4,
                        'user_agent'    => 'KhadamatApp/1.0 (maintenance service platform)',
                        'ignore_errors' => true,
                    ],
                ]);

                $response = @file_get_contents($url, false, $ctx);

                if ($response !== false) {
                    $data = json_decode($response, true);

                    if (!empty($data['address'])) {
                        $addr    = $data['address'];
                        $areas   = [];

                        // Collect the most useful address components (suburb → city → state)
                        foreach (['suburb', 'neighbourhood', 'quarter', 'city_district', 'district', 'county', 'city', 'town', 'village', 'state'] as $key) {
                            if (!empty($addr[$key])) {
                                $areas[] = $addr[$key];
                            }
                            if (count($areas) >= 3) {
                                break;
                            }
                        }

                        if (!empty($areas)) {
                            return array_unique($areas);
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Geocoding failed silently — fall through to address fallback
            }
        }

        // 2. Fallback: parse the address text field
        if (!empty($technician->address)) {
            $parts = array_filter(
                array_map('trim', preg_split('/[،,\/\-]+/u', $technician->address))
            );
            if (!empty($parts)) {
                return array_values(array_unique(array_slice($parts, 0, 3)));
            }
        }

        // 3. Nothing available
        return [];
    }

    public function serviceShow($encryptedId)
    {
        $id = EncryptionHelper::decryptId($encryptedId);
        $service = Service::with('specialization')->findOrFail($id);
        return view('services.show', compact('service'));
    }

    public function submitTechnicianReview(Request $request, $encryptedId)
    {
        $id = EncryptionHelper::decryptId($encryptedId);
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
