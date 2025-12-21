<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Technician;
use App\Models\WarehouseItem;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        $totalCustomers = User::whereDoesntHave('roles', function($q) {
            $q->whereIn('name', ['admin', 'technician']);
        })->count();
        
        $totalTechnicians = Technician::count();
        
        $newOrders = \App\Models\Request::where('status', 'pending')->count();
        
        $lowStock = WarehouseItem::where('quantity', '<=', 10)->count();
        
        $latestRequests = \App\Models\Request::with(['user', 'service'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('totalCustomers', 'totalTechnicians', 'newOrders', 'lowStock', 'latestRequests'));
    }
}
