<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\Review;
use App\Models\Service;
use App\Models\Technician;
use App\Models\User;
use App\Models\WarehouseItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalCustomers = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['admin', 'technician']);
        })->count();

        $totalTechnicians = Technician::count();
        $totalRequests = RequestModel::count();
        $newOrders = RequestModel::where('status', 'pending')->count();
        $completedRequests = RequestModel::where('status', 'completed')->count();
        $totalRevenue = RequestModel::where('status', 'completed')->sum('proposed_price');
        $lowStock = WarehouseItem::lowStock()->count();
        $pendingReviews = Review::where('status', 'pending')->count();

        $statusLabels = [
            'pending' => 'قيد الانتظار',
            'approved' => 'مقبولة',
            'pricing_pending' => 'انتظار السعر',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتملة',
            'cancelled' => 'ملغية',
            'rejected' => 'مرفوضة',
        ];

        $requestsByStatus = RequestModel::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $chartStatusLabels = [];
        $chartStatusData = [];
        $chartStatusColors = [
            'pending' => '#f59e0b',
            'approved' => '#06b6d4',
            'pricing_pending' => '#8b5cf6',
            'in_progress' => '#0b5f8a',
            'completed' => '#10b981',
            'cancelled' => '#ef4444',
            'rejected' => '#64748b',
        ];
        $chartStatusBg = [];

        foreach ($requestsByStatus as $status => $count) {
            $chartStatusLabels[] = $statusLabels[$status] ?? $status;
            $chartStatusData[] = $count;
            $chartStatusBg[] = $chartStatusColors[$status] ?? '#94a3b8';
        }

        $dailyRange = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            return [
                'label' => $date->format('d/m'),
                'date' => $date->toDateString(),
            ];
        });

        $dailyCounts = RequestModel::where('created_at', '>=', Carbon::today()->subDays(6)->startOfDay())
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date');

        $chartDailyLabels = $dailyRange->pluck('label')->values();
        $chartDailyData = $dailyRange->map(fn ($day) => $dailyCounts[$day['date']] ?? 0)->values();

        $topServices = RequestModel::query()
            ->select('service_id', DB::raw('count(*) as total'))
            ->groupBy('service_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $service = Service::find($row->service_id);
                return [
                    'name' => $service->name ?? 'غير محدد',
                    'count' => $row->total,
                ];
            });

        $chartServiceLabels = $topServices->pluck('name')->values();
        $chartServiceData = $topServices->pluck('count')->values();

        $latestRequests = RequestModel::with(['user', 'service', 'assignedTechnician.user'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalTechnicians',
            'totalRequests',
            'newOrders',
            'completedRequests',
            'totalRevenue',
            'lowStock',
            'pendingReviews',
            'latestRequests',
            'chartStatusLabels',
            'chartStatusData',
            'chartStatusBg',
            'chartDailyLabels',
            'chartDailyData',
            'chartServiceLabels',
            'chartServiceData',
        ));
    }
}
