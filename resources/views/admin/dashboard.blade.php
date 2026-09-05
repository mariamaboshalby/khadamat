@extends('layouts.admin')

@section('title', 'لوحة التحكم')

@section('content')
@php
    $statusBadges = [
        'pending' => 'warning',
        'approved' => 'info',
        'pricing_pending' => 'primary',
        'in_progress' => 'primary',
        'completed' => 'success',
        'cancelled' => 'danger',
        'rejected' => 'secondary',
    ];
    $statusNames = [
        'pending' => 'قيد الانتظار',
        'approved' => 'مقبولة',
        'pricing_pending' => 'انتظار السعر',
        'in_progress' => 'قيد التنفيذ',
        'completed' => 'مكتملة',
        'cancelled' => 'ملغية',
        'rejected' => 'مرفوضة',
    ];
@endphp

<div class="admin-dashboard">

    <!-- Header -->
    <div class="dashboard-header mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">لوحة التحكم</h4>
            <p class="text-muted mb-0 small">مرحباً {{ auth()->user()->name }} — نظرة عامة على أداء المنصة</p>
        </div>
        <div class="dashboard-date-badge">
            <i class="fa-regular fa-calendar me-1"></i>
            {{ now()->format('d/m/Y') }}
        </div>
    </div>

    @if ($lowStock > 0)
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center gap-3 mb-4 rounded-3">
            <i class="fa-solid fa-triangle-exclamation fs-5"></i>
            <div class="flex-grow-1">
                <strong>تنبيه مخزون:</strong> يوجد {{ $lowStock }} صنف بكمية منخفضة.
            </div>
            <a href="{{ route('admin.warehouse-items.low-stock') }}" class="btn btn-sm btn-danger rounded-pill px-3">عرض</a>
        </div>
    @endif

    <!-- Stats -->
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-6 col-lg-4 col-xl-2">
            <div class="dash-stat-card">
                <div class="dash-stat-icon bg-primary-subtle text-primary"><i class="fa-solid fa-clipboard-list"></i></div>
                <div>
                    <span class="dash-stat-label">إجمالي الطلبات</span>
                    <h3 class="dash-stat-value">{{ $totalRequests }}</h3>
                    <small class="text-success">{{ $completedRequests }} مكتمل</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-4 col-xl-2">
            <div class="dash-stat-card">
                <div class="dash-stat-icon bg-warning-subtle text-warning"><i class="fa-solid fa-clock"></i></div>
                <div>
                    <span class="dash-stat-label">طلبات جديدة</span>
                    <h3 class="dash-stat-value">{{ $newOrders }}</h3>
                    <small class="text-muted">بانتظار المراجعة</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-4 col-xl-2">
            <div class="dash-stat-card">
                <div class="dash-stat-icon bg-success-subtle text-success"><i class="fa-solid fa-users"></i></div>
                <div>
                    <span class="dash-stat-label">العملاء</span>
                    <h3 class="dash-stat-value">{{ $totalCustomers }}</h3>
                    <small class="text-muted">مسجّل</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-4 col-xl-2">
            <div class="dash-stat-card">
                <div class="dash-stat-icon bg-info-subtle text-info"><i class="fa-solid fa-user-gear"></i></div>
                <div>
                    <span class="dash-stat-label">الفنيين</span>
                    <h3 class="dash-stat-value">{{ $totalTechnicians }}</h3>
                    <small class="text-muted">نشط</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-4 col-xl-2">
            <div class="dash-stat-card">
                <div class="dash-stat-icon bg-purple-subtle text-purple"><i class="fa-solid fa-coins"></i></div>
                <div>
                    <span class="dash-stat-label">الإيرادات</span>
                    <h3 class="dash-stat-value">{{ number_format($totalRevenue, 0) }}</h3>
                    <small class="text-muted">ج.م مكتملة</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-4 col-xl-2">
            <div class="dash-stat-card">
                <div class="dash-stat-icon bg-danger-subtle text-danger"><i class="fa-solid fa-star"></i></div>
                <div>
                    <span class="dash-stat-label">تقييمات معلقة</span>
                    <h3 class="dash-stat-value">{{ $pendingReviews }}</h3>
                    <small class="text-muted">للمراجعة</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="dash-chart-card">
                <div class="dash-chart-header">
                    <h6 class="fw-bold mb-0">الطلبات — آخر 7 أيام</h6>
                    <span class="badge bg-light text-dark border">أسبوعي</span>
                </div>
                <div class="dash-chart-body">
                    <canvas id="dailyRequestsChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="dash-chart-card h-100">
                <div class="dash-chart-header">
                    <h6 class="fw-bold mb-0">توزيع حالات الطلبات</h6>
                </div>
                <div class="dash-chart-body d-flex align-items-center justify-content-center">
                    <div style="max-width: 260px; width: 100%;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 + Quick Links -->
    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="dash-chart-card">
                <div class="dash-chart-header">
                    <h6 class="fw-bold mb-0">أكثر الخدمات طلباً</h6>
                </div>
                <div class="dash-chart-body">
                    <canvas id="servicesChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="dash-chart-card h-100">
                <div class="dash-chart-header">
                    <h6 class="fw-bold mb-0">اختصارات سريعة</h6>
                </div>
                <div class="dash-quick-links">
                    <a href="{{ route('admin.requests.index') }}" class="dash-quick-link">
                        <i class="fa-solid fa-list-check text-primary"></i>
                        <span>إدارة الطلبات</span>
                        <i class="fa-solid fa-chevron-left ms-auto"></i>
                    </a>
                    <a href="{{ route('admin.techs.index') }}" class="dash-quick-link">
                        <i class="fa-solid fa-user-gear text-success"></i>
                        <span>الفنيين</span>
                        <i class="fa-solid fa-chevron-left ms-auto"></i>
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="dash-quick-link">
                        <i class="fa-solid fa-users text-info"></i>
                        <span>العملاء</span>
                        <i class="fa-solid fa-chevron-left ms-auto"></i>
                    </a>
                    <a href="{{ route('admin.reviews.index') }}" class="dash-quick-link">
                        <i class="fa-solid fa-star text-warning"></i>
                        <span>التقييمات</span>
                        <i class="fa-solid fa-chevron-left ms-auto"></i>
                    </a>
                    <a href="{{ route('admin.warehouse-items.index') }}" class="dash-quick-link">
                        <i class="fa-solid fa-boxes-stacked text-danger"></i>
                        <span>المخزون</span>
                        <i class="fa-solid fa-chevron-left ms-auto"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Requests -->
    <div class="dash-chart-card">
        <div class="dash-chart-header">
            <h6 class="fw-bold mb-0">آخر الطلبات</h6>
            <a href="{{ route('admin.requests.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">عرض الكل</a>
        </div>
        <div class="table-responsive dash-table-wrap">
            <table class="table table-hover align-middle mb-0 dash-table">
                <thead class="bg-light">
                    <tr>
                        <th class="dash-th">#</th>
                        <th class="dash-th">العميل</th>
                        <th class="dash-th d-none d-md-table-cell">الفني</th>
                        <th class="dash-th d-none d-sm-table-cell">الخدمة</th>
                        <th class="dash-th">الحالة</th>
                        <th class="dash-th d-none d-lg-table-cell">التاريخ</th>
                        <th class="dash-th"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestRequests as $request)
                        <tr>
                            <td class="dash-td fw-bold text-muted">#{{ $request->id }}</td>
                            <td class="dash-td fw-bold">{{ $request->user->name ?? '—' }}</td>
                            <td class="dash-td d-none d-md-table-cell text-muted">
                                {{ $request->assignedTechnician->user->name ?? 'غير معين' }}
                            </td>
                            <td class="dash-td d-none d-sm-table-cell">{{ $request->service->name ?? '—' }}</td>
                            <td class="dash-td">
                                <span class="badge bg-{{ $statusBadges[$request->status] ?? 'secondary' }} rounded-pill">
                                    {{ $statusNames[$request->status] ?? $request->status }}
                                </span>
                            </td>
                            <td class="dash-td d-none d-lg-table-cell text-muted small">{{ $request->created_at->format('Y-m-d') }}</td>
                            <td class="dash-td">
                                <a href="{{ route('admin.requests.show', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">عرض</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">لا توجد طلبات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    .admin-dashboard { max-width: 100%; overflow-x: hidden; }

    .dashboard-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .dashboard-date-badge {
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .dash-stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        height: 100%;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .dash-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .dash-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .bg-primary-subtle { background: rgba(11, 95, 138, 0.1); }
    .bg-warning-subtle { background: rgba(245, 158, 11, 0.12); }
    .bg-success-subtle { background: rgba(16, 185, 129, 0.12); }
    .bg-info-subtle { background: rgba(6, 182, 212, 0.12); }
    .bg-danger-subtle { background: rgba(239, 68, 68, 0.12); }
    .bg-purple-subtle { background: rgba(139, 92, 246, 0.12); }
    .text-purple { color: #8b5cf6; }

    .dash-stat-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
    }

    .dash-stat-value {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        margin: 2px 0;
        line-height: 1.2;
    }

    .dash-chart-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }

    .dash-chart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .dash-chart-body {
        padding: 20px;
    }

    .dash-quick-links {
        padding: 8px 12px 16px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .dash-quick-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 12px;
        text-decoration: none;
        color: #0f172a;
        font-weight: 600;
        font-size: 14px;
        transition: background 0.15s;
    }

    .dash-quick-link:hover {
        background: #f8fafc;
        color: #0b5f8a;
    }

    .dash-quick-link i:first-child {
        width: 20px;
        text-align: center;
    }

    .dash-table-wrap { -webkit-overflow-scrolling: touch; }
    .dash-table { min-width: 560px; }
    .dash-th { padding: 12px 14px; font-size: 12px; font-weight: 700; color: #64748b; }
    .dash-td { padding: 12px 14px; font-size: 14px; }

    @media (max-width: 576px) {
        .dash-stat-value { font-size: 18px; }
        .dash-chart-body { padding: 12px; }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    Chart.defaults.font.family = 'Cairo, sans-serif';
    Chart.defaults.font.size = 12;

    const primary = '#0b5f8a';

    new Chart(document.getElementById('dailyRequestsChart'), {
        type: 'line',
        data: {
            labels: @json($chartDailyLabels),
            datasets: [{
                label: 'الطلبات',
                data: @json($chartDailyData),
                borderColor: primary,
                backgroundColor: 'rgba(11, 95, 138, 0.08)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: primary,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: @json($chartStatusLabels),
            datasets: [{
                data: @json($chartStatusData),
                backgroundColor: @json($chartStatusBg),
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, padding: 10 }
                }
            }
        }
    });

    new Chart(document.getElementById('servicesChart'), {
        type: 'bar',
        data: {
            labels: @json($chartServiceLabels),
            datasets: [{
                label: 'عدد الطلبات',
                data: @json($chartServiceData),
                backgroundColor: [
                    'rgba(11, 95, 138, 0.85)',
                    'rgba(255, 138, 0, 0.85)',
                    'rgba(16, 185, 129, 0.85)',
                    'rgba(139, 92, 246, 0.85)',
                    'rgba(6, 182, 212, 0.85)',
                ],
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } },
                y: { grid: { display: false } }
            }
        }
    });
});
</script>
@endpush
@endsection
