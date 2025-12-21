<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $query = RequestModel::with(['user', 'service'])->latest();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(10);

        return view('admin.requests.index', compact('requests'));
    }

    public function show(Request $request, $id)
    {
        $requestModel = RequestModel::findOrFail($id);
        return view('admin.requests.show', compact('requestModel'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,in_progress,completed,cancelled,rejected'
        ]);

        $requestModel = RequestModel::findOrFail($id);
        $requestModel->update(['status' => $request->status]);

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح.');
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
        
        $acceptedProposal = $request->proposals->first();
        
        return view('admin.requests.invoice', compact('request', 'acceptedProposal'));
    }
}
