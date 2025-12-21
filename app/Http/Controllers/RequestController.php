<?php

namespace App\Http\Controllers;

use App\Models\Request as RequestModel;
use App\Models\Service;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CustomerRequestSubmitted;
use App\Notifications\AdminNewRequestSubmitted;
use App\Notifications\TechnicianProposalAccepted;
use App\Notifications\AdminLowStockAlert;
use App\Notifications\TechnicianNewRequestAvailable;
use App\Models\User;

class RequestController extends Controller
{
    /**
     * Show the form for creating a new request.
     */
    public function create(Request $request)
    {
        $services = Service::all();
        $serviceId = $request->query('service_id');
        return view('requests.create', compact('services', 'serviceId'));
    }

    /**
     * Store a newly created request in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'address' => 'required|string|max:255',
            'scheduled_at' => 'nullable|date|after:now',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Create request
        $requestData = RequestModel::create([
            'user_id' => Auth::id(),
            'service_id' => $request->service_id,
            'address' => $request->address,
            'scheduled_at' => $request->scheduled_at,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        // Save images using Spatie Media Library
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $requestData
                    ->addMedia($image)
                    ->toMediaCollection('requests');
            }
        }

        // Send notifications
        // Notify the customer
        $requestData->user->notify(new CustomerRequestSubmitted($requestData));
        
        // Notify admins
        $admins = User::where('user_type', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new AdminNewRequestSubmitted($requestData));
        }
        
        // Notify technicians with matching specialization
        $technicians = Technician::where('specialization_id', $requestData->service->specialization_id)
            ->where('availability_status', 'available')
            ->with('user')
            ->get();
            
        foreach ($technicians as $technician) {
            $technician->user->notify(new TechnicianNewRequestAvailable($requestData));
        }

        return redirect()->route('dashboard')->with('success', 'تم إرسال طلبك بنجاح! سنتواصل معك قريباً.');
    }

    /**
     * Display a specific request.
     */
    public function show($id)
    {
        $requestData = RequestModel::with(['service', 'user', 'media', 'requestItems.warehouseItem', 'proposals.technician.user', 'proposals.items.warehouseItem'])->findOrFail($id);

        // Only the owner can view
        if ($requestData->user_id !== Auth::id()) {
            abort(403);
        }

        return view('requests.show', compact('requestData'));
    }

    /**
     * Display all requests of the authenticated user.
     */
    public function index()
    {
        $requests = RequestModel::with('service', 'media')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('requests.index', compact('requests'));
    }

    /**
     * Delete a user's request (only if pending).
     */
    public function destroy($id)
    {
        $request = RequestModel::findOrFail($id);

        if ($request->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->status !== 'pending') {
            return back()->with('error', 'لا يمكن إلغاء الطلب لأنه قيد التنفيذ أو مكتمل.');
        }

        // Delete media automatically with model if using Spatie traits
        $request->delete();

        return redirect()->route('requests.index')
            ->with('success', 'تم إلغاء الطلب بنجاح.');
    }

    /**
     * Show the form for editing a request.
     */
    public function edit($id)
    {
        $requestData = RequestModel::with(['service', 'media'])->findOrFail($id);

        if ($requestData->user_id !== Auth::id()) {
            abort(403);
        }

        if ($requestData->status !== 'pending') {
            return back()->with('error', 'لا يمكن تعديل الطلب بعد الموافقة عليه');
        }

        $services = Service::all();
        return view('requests.edit', compact('requestData', 'services'));
    }

    /**
     * Update request.
     */
    public function update(Request $request, $id)
    {
        $requestData = RequestModel::findOrFail($id);

        if ($requestData->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'address' => 'required|string|max:255',
            'scheduled_at' => 'nullable|date|after:now',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $requestData->update([
            'service_id' => $request->service_id,
            'address' => $request->address,
            'scheduled_at' => $request->scheduled_at,
            'description' => $request->description,
        ]);

        // Add new images if uploaded
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $requestData
                    ->addMedia($image)
                    ->toMediaCollection('requests');
            }
        }

        return redirect()->route('requests.show', $requestData->id)
            ->with('success', 'تم تعديل الطلب بنجاح.');
    }

    /**
     * Customer accepts the technician's price
     */
    public function acceptPrice($id)
    {
        $request = RequestModel::findOrFail($id);

        if ($request->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->price_status !== 'pending') {
            return back()->with('error', 'لا يمكن قبول السعر في الحالة الحالية');
        }

        $request->update([
            'price_status' => 'accepted',
            'status' => 'in_progress',
        ]);

        // Deduct warehouse items
        foreach ($request->requestItems as $item) {
            $warehouseItem = $item->warehouseItem;
            $warehouseItem->decrement('quantity', $item->quantity);
            
            // Check if item is low stock after deduction and notify admins
            if ($warehouseItem->isLowStock()) {
                $admins = User::where('user_type', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new AdminLowStockAlert($warehouseItem));
                }
            }
        }

        return redirect()->route('requests.show', $request->id)
            ->with('success', 'تم قبول السعر بنجاح! سيبدأ الفني العمل قريباً.');
    }

    /**
     * Customer rejects the price and releases the technician
     */
    public function rejectPrice($id)
    {
        $request = RequestModel::findOrFail($id);

        if ($request->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->price_status !== 'pending') {
            return back()->with('error', 'لا يمكن رفض السعر في الحالة الحالية');
        }

        // Delete request items and reset request
        $request->requestItems()->delete();
        
        $request->update([
            'assigned_technician_id' => null,
            'status' => 'approved',
            'proposed_price' => null,
            'price_notes' => null,
            'price_status' => null,
            'customer_notes' => null,
        ]);

        return redirect()->route('requests.show', $request->id)
            ->with('success', 'تم رفض العرض. الطلب متاح الآن للفنيين الآخرين.');
    }

    /**
     * Delete a media file from request
     */
    public function deleteMedia($id, $mediaId)
    {
        $request = RequestModel::findOrFail($id);

        if ($request->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($request->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'لا يمكن حذف الصور بعد الموافقة على الطلب'], 400);
        }

        $media = $request->getMedia('requests')->where('id', $mediaId)->first();
        
        if ($media) {
            $media->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'الصورة غير موجودة'], 404);
    }

    /**
     * Customer negotiates a different price
     */
    public function negotiatePrice(Request $validateRequest, $id)
    {
        $request = RequestModel::findOrFail($id);

        if ($request->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->price_status !== 'pending') {
            return back()->with('error', 'لا يمكن التفاوض على السعر في الحالة الحالية');
        }

        $validated = $validateRequest->validate([
            'proposed_price' => 'required|numeric|min:0',
            'customer_notes' => 'nullable|string',
        ]);

        $request->update([
            'proposed_price' => $validated['proposed_price'],
            'customer_notes' => $validated['customer_notes'],
            'price_status' => 'negotiating',
        ]);

        return redirect()->route('requests.show', $request->id)
            ->with('success', 'تم إرسال السعر المعدل للفني. في انتظار موافقته.');
    }

    public function acceptProposal($proposalId)
    {
        $proposal = \App\Models\RequestProposal::with('request')->findOrFail($proposalId);
        $request = $proposal->request;

        if ($request->user_id !== Auth::id()) {
            abort(403);
        }

        \App\Models\RequestProposal::where('request_id', $request->id)
            ->where('id', '!=', $proposalId)
            ->update(['status' => 'rejected']);

        $proposal->update(['status' => 'accepted']);

        // Notify the technician that their proposal was accepted
        $proposal->technician->user->notify(new TechnicianProposalAccepted($proposal));

        $request->update([
            'assigned_technician_id' => $proposal->technician_id,
            'status' => 'in_progress',
        ]);

        return back()->with('success', 'تم قبول العرض بنجاح!');
    }

    public function rejectProposal($proposalId)
    {
        $proposal = \App\Models\RequestProposal::with('request')->findOrFail($proposalId);
        $request = $proposal->request;

        if ($request->user_id !== Auth::id()) {
            abort(403);
        }

        $proposal->update(['status' => 'rejected']);

        return back()->with('success', 'تم رفض العرض');
    }

    public function submitReview(Request $validateRequest, $id)
    {
        $request = RequestModel::findOrFail($id);

        if ($request->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->status !== 'completed') {
            return back()->with('error', 'لا يمكن تقييم الطلب قبل اكتماله');
        }

        $validated = $validateRequest->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        \App\Models\Review::create([
            'request_id' => $request->id,
            'technician_id' => $request->assigned_technician_id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'تم إرسال التقييم بنجاح');
    }

    public function invoice($id)
    {
        $request = RequestModel::with([
            'user', 
            'service', 
            'assignedTechnician.user',
            'assignedTechnician.specialization',
            'proposals' => function($q) {
                $q->where('status', 'accepted')->with('items.warehouseItem');
            }
        ])->findOrFail($id);
        
        if ($request->user_id !== Auth::id()) {
            abort(403);
        }
        
        $acceptedProposal = $request->proposals->first();
        
        return view('requests.invoice', compact('request', 'acceptedProposal'));
    }
}