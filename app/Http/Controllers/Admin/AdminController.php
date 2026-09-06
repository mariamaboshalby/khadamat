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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // ── Aggregate stats — cached 5 minutes ───────────────────────────────────
        // These counters are non-critical real-time values; a 5-minute cache
        // eliminates ~8 separate DB COUNT/SUM queries on every page load.
        $stats = Cache::remember('admin.dashboard.stats', 300, function () {
            // Combine all request status counts in a single query
            $requestCounts = RequestModel::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status');

            return [
                'totalCustomers'    => User::whereDoesntHave('roles', fn ($q) => $q->whereIn('name', ['admin', 'technician']))->count(),
                'totalTechnicians'  => Technician::count(),
                'totalRequests'     => $requestCounts->sum(),
                'newOrders'         => $requestCounts->get('pending', 0),
                'completedRequests' => $requestCounts->get('completed', 0),
                'totalRevenue'      => RequestModel::where('status', 'completed')->sum('proposed_price'),
                'lowStock'          => WarehouseItem::lowStock()->count(),
                'pendingReviews'    => Review::where('status', 'pending')->count(),
                'requestsByStatus'  => $requestCounts,
            ];
        });

        // ── Chart data — status distribution ─────────────────────────────────────
        $statusLabels = [
            'pending'        => 'قيد الانتظار',
            'approved'       => 'مقبولة',
            'pricing_pending'=> 'انتظار السعر',
            'in_progress'    => 'قيد التنفيذ',
            'completed'      => 'مكتملة',
            'cancelled'      => 'ملغية',
            'rejected'       => 'مرفوضة',
        ];

        $chartStatusColors = [
            'pending'        => '#f59e0b',
            'approved'       => '#06b6d4',
            'pricing_pending'=> '#8b5cf6',
            'in_progress'    => '#0b5f8a',
            'completed'      => '#10b981',
            'cancelled'      => '#ef4444',
            'rejected'       => '#64748b',
        ];

        $chartStatusLabels = [];
        $chartStatusData   = [];
        $chartStatusBg     = [];

        foreach ($stats['requestsByStatus'] as $status => $count) {
            $chartStatusLabels[] = $statusLabels[$status] ?? $status;
            $chartStatusData[]   = $count;
            $chartStatusBg[]     = $chartStatusColors[$status] ?? '#94a3b8';
        }

        // ── Daily request chart — last 7 days (cached 10 minutes) ─────────────
        $dailyChartData = Cache::remember('admin.dashboard.daily', 600, function () {
            $dailyRange = collect(range(6, 0))->map(function ($daysAgo) {
                $date = Carbon::today()->subDays($daysAgo);
                return ['label' => $date->format('d/m'), 'date' => $date->toDateString()];
            });

            $dailyCounts = RequestModel::where('created_at', '>=', Carbon::today()->subDays(6)->startOfDay())
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                ->groupBy('date')
                ->pluck('count', 'date');

            return [
                'labels' => $dailyRange->pluck('label')->values(),
                'data'   => $dailyRange->map(fn ($day) => $dailyCounts[$day['date']] ?? 0)->values(),
            ];
        });

        $chartDailyLabels = $dailyChartData['labels'];
        $chartDailyData   = $dailyChartData['data'];

        // ── Top services chart (cached 10 minutes) ────────────────────────────
        // FIX: replaced N+1 (Service::find() inside map()) with a single JOIN query
        $chartServiceLabels = Cache::remember('admin.dashboard.top_services', 600, function () {
            return RequestModel::query()
                ->select('services.name', DB::raw('count(requests.id) as total'))
                ->join('services', 'services.id', '=', 'requests.service_id')
                ->groupBy('services.id', 'services.name')
                ->orderByDesc('total')
                ->limit(5)
                ->pluck('name');
        });

        $chartServiceData = Cache::remember('admin.dashboard.top_services_counts', 600, function () {
            return RequestModel::query()
                ->select('services.name', DB::raw('count(requests.id) as total'))
                ->join('services', 'services.id', '=', 'requests.service_id')
                ->groupBy('services.id', 'services.name')
                ->orderByDesc('total')
                ->limit(5)
                ->pluck('total');
        });

        // ── Latest requests — real-time, no cache ────────────────────────────
        $latestRequests = RequestModel::select(
                'id', 'user_id', 'service_id', 'assigned_technician_id',
                'status', 'proposed_price', 'created_at'
            )
            ->with([
                'user:id,name',
                'service:id,name',
                'assignedTechnician' => fn ($q) => $q->select('id', 'user_id')->with('user:id,name'),
            ])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'latestRequests',
            'chartStatusLabels',
            'chartStatusData',
            'chartStatusBg',
            'chartDailyLabels',
            'chartDailyData',
            'chartServiceLabels',
            'chartServiceData',
            // Unpack stats array for view compatibility
        ) + $stats);
    }
}
