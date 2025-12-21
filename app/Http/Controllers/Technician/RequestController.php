<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TechnicianNewRequestAvailable;
use App\Models\Technician;
use App\Models\User;

class RequestController extends Controller
{
    /**
     * Display available repair requests for the technician
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check if user is admin or technician
        if ($user->hasRole('admin')) {
            // Admin can see all approved requests
            $requests = RequestModel::with(['user', 'service'])
                ->where('status', 'approved')
                ->whereNull('assigned_technician_id')
                ->latest()
                ->paginate(10);
                
            return view('technician.requests.index', compact('requests'));
        }
        
        $technician = $user->technician;
        
        if (!$technician) {
            abort(403, 'Unauthorized');
        }

        // Get requests that match technician's specialization
        // Show: approved OR pricing_pending (not in_progress or completed)
        $query = RequestModel::with(['user', 'service', 'service.specialization'])
            ->whereIn('status', ['approved', 'pricing_pending'])
            ->whereHas('service', function($q) use ($technician) {
                $q->where('specialization_id', $technician->specialization_id);
            });

        // Sort by latest for now (distance calculation can be added later)
        $query->latest();

        $requests = $query->paginate(10);

        return view('technician.requests.index', compact('requests', 'technician'));
    }

    /**
     * Show technician's requests (proposals submitted)
     */
    public function myRequests()
    {
        $user = Auth::user();
        $technician = $user->technician;
        
        if (!$technician) {
            abort(403, 'غير مصرح لك بالوصول');
        }
        
        // Get requests where technician submitted a proposal
        $requests = RequestModel::with(['user', 'service', 'requestItems.warehouseItem'])
            ->whereHas('proposals', function($q) use ($technician) {
                $q->where('technician_id', $technician->id);
            })
            ->latest()
            ->paginate(10);
        
        return view('technician.requests.my-requests', compact('requests', 'technician'));
    }

    /**
     * Show request details
     */
    public function show($id)
    {
        $request = RequestModel::with(['user', 'service', 'service.specialization', 'media'])->findOrFail($id);
        
        $user = Auth::user();
        
        // Check permissions
        if (!$user->hasRole('admin')) {
            $technician = $user->technician;
            if (!$technician || $request->service->specialization_id !== $technician->specialization_id) {
                abort(403, 'غير مسموح لك بعرض هذا الطلب');
            }
        }
        
        return view('technician.requests.show', compact('request'));
    }

    /**
     * Show pricing form for technician
     */
    public function pricing($id)
    {
        $request = RequestModel::with(['user', 'service', 'requestItems.warehouseItem'])->findOrFail($id);
        
        $user = Auth::user();
        $technician = $user->technician;
        
        // Check permissions
        if (!$user->hasRole('admin') && (!$technician || $request->service->specialization_id !== $technician->specialization_id)) {
            abort(403);
        }
        
        // Check if request is available
        if (!in_array($request->status, ['approved', 'pricing_pending'])) {
            return back()->with('error', 'هذا الطلب غير متاح للتقديم');
        }
        
        // Check if technician already submitted a proposal
        $existingProposal = \App\Models\RequestProposal::where('request_id', $request->id)
            ->where('technician_id', $technician->id)
            ->first();
            
        if ($existingProposal) {
            return back()->with('error', 'لقد قمت بالتقديم على هذا الطلب مسبقاً');
        }
        
        $warehouseItems = \App\Models\WarehouseItem::where('quantity', '>', 0)->get();
        
        return view('technician.requests.pricing', compact('request', 'warehouseItems'));
    }

    /**
     * Submit pricing proposal
     */
    public function submitPricing(Request $request, $id)
    {
        $requestData = RequestModel::findOrFail($id);
        
        $user = Auth::user();
        $technician = $user->technician;
        
        // Validate
        $validated = $request->validate([
            'proposed_price' => 'required|numeric|min:0',
            'price_notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.warehouse_item_id' => 'required|exists:warehouse_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        
        // Create proposal
        $proposal = \App\Models\RequestProposal::create([
            'request_id' => $requestData->id,
            'technician_id' => $technician->id,
            'proposed_price' => $validated['proposed_price'],
            'price_notes' => $validated['price_notes'],
            'status' => 'pending',
        ]);
        
        // Add items if any
        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $item) {
                $warehouseItem = \App\Models\WarehouseItem::find($item['warehouse_item_id']);
                
                \App\Models\ProposalItem::create([
                    'proposal_id' => $proposal->id,
                    'warehouse_item_id' => $item['warehouse_item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $warehouseItem->price,
                    'total_price' => $warehouseItem->price * $item['quantity'],
                ]);
            }
        }
        
        // Update request status to pricing_pending (don't assign technician yet)
        $requestData->update([
            'status' => 'pricing_pending',
        ]);
        
        return redirect()->route('technician.repair-requests.index')
            ->with('success', 'تم إرسال عرض السعر بنجاح');
    }

    /**
     * Apply for a repair request
     */
    public function apply($id)
    {
        $user = Auth::user();
        
        // Check if user is admin or technician
        if ($user->hasRole('admin')) {
            // Admin can assign any technician to any request
            $request = RequestModel::findOrFail($id);

            // Check if request is approved and not assigned
            if ($request->status !== 'approved' || $request->assigned_technician_id) {
                return back()->with('error', 'هذا الطلب غير متاح للتقديم');
            }

            // For admin, we'll assign the first available technician with matching specialization
            $technician = \App\Models\Technician::where('specialization_id', $request->service->specialization_id)
                ->where('availability_status', 'available')
                ->first();

            if (!$technician) {
                return back()->with('error', 'لا يوجد فني متاح لهذا التخصص');
            }

            // Assign technician to request
            $request->update([
                'assigned_technician_id' => $technician->id,
                'status' => 'in_progress'
            ]);

            return back()->with('success', 'تم تعيين فني للطلب بنجاح');
        }
        
        $technician = $user->technician;
        
        if (!$technician) {
            abort(403, 'Unauthorized');
        }

        $request = RequestModel::findOrFail($id);

        // Check if request is approved and not assigned
        if ($request->status !== 'approved' || $request->assigned_technician_id) {
            return back()->with('error', 'هذا الطلب غير متاح للتقديم');
        }

        // Check if service matches technician specialization
        if ($request->service->specialization_id !== $technician->specialization_id) {
            return back()->with('error', 'هذا الطلب لا يتطابق مع تخصصك');
        }

        // Assign technician to request
        $request->update([
            'assigned_technician_id' => $technician->id,
            'status' => 'in_progress'
        ]);

        return back()->with('success', 'تم التقديم على الطلب بنجاح');
    }

    /**
     * Technician accepts customer's negotiated price
     */
    public function acceptNegotiation($id)
    {
        $request = RequestModel::findOrFail($id);
        
        $user = Auth::user();
        $technician = $user->technician;
        
        if (!$technician || $request->assigned_technician_id != $technician->id) {
            abort(403);
        }
        
        if ($request->price_status !== 'negotiating') {
            return back()->with('error', 'لا يمكن قبول السعر في هذه الحالة');
        }
        
        $request->update([
            'price_status' => 'pending',
        ]);
        
        return back()->with('success', 'تم قبول السعر المعدل. في انتظار موافقة العميل.');
    }

    /**
     * Technician rejects customer's negotiated price
     */
    public function rejectNegotiation($id)
    {
        $request = RequestModel::findOrFail($id);
        
        $user = Auth::user();
        $technician = $user->technician;
        
        if (!$technician || $request->assigned_technician_id != $technician->id) {
            abort(403);
        }
        
        if ($request->price_status !== 'negotiating') {
            return back()->with('error', 'لا يمكن رفض السعر في هذه الحالة');
        }
        
        // Delete request items and reset
        $request->requestItems()->delete();
        
        $request->update([
            'assigned_technician_id' => null,
            'status' => 'approved',
            'proposed_price' => null,
            'price_notes' => null,
            'price_status' => null,
            'customer_notes' => null,
        ]);
        
        return redirect()->route('technician.repair-requests.index')
            ->with('success', 'تم رفض السعر المعدل. الطلب متاح للفنيين الآخرين.');
    }

    public function complete($id)
    {
        $request = RequestModel::findOrFail($id);
        $user = Auth::user();
        $technician = $user->technician;

        if (!$technician || $request->assigned_technician_id !== $technician->id) {
            abort(403);
        }

        if ($request->status !== 'in_progress') {
            return back()->with('error', 'لا يمكن إنهاء هذا الطلب');
        }

        $request->update(['status' => 'completed']);

        return back()->with('success', 'تم إنهاء الطلب بنجاح');
    }
}