<?php

namespace App\Http\Controllers;

use App\Models\Request as RequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerPricingController extends Controller
{
    public function show($id)
    {
        $request = RequestModel::with(['user', 'service', 'assignedTechnician.user', 'requestItems.warehouseItem'])
            ->findOrFail($id);
        
        // Check if user owns this request
        if ($request->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('customers.pricing-review', compact('request'));
    }
    
    public function accept($id)
    {
        $request = RequestModel::findOrFail($id);
        
        if ($request->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->update([
            'price_status' => 'accepted',
            'status' => 'in_progress',
        ]);
        
        return redirect()->route('dashboard')->with('success', 'تم قبول العرض بنجاح');
    }
    
    public function reject(Request $httpRequest, $id)
    {
        $request = RequestModel::findOrFail($id);
        
        if ($request->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $httpRequest->validate([
            'customer_notes' => 'required|string',
        ]);
        
        $request->update([
            'price_status' => 'rejected',
            'status' => 'approved',
            'customer_notes' => $validated['customer_notes'],
            'assigned_technician_id' => null,
            'proposed_price' => null,
            'price_notes' => null,
        ]);
        
        // Delete request items
        $request->requestItems()->delete();
        
        return redirect()->route('dashboard')->with('success', 'تم رفض العرض وإعادة الطلب للفنيين');
    }
}
