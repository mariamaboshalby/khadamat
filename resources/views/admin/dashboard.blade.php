@extends('layouts.admin')

@section('title', 'الرئيسية')

@section('content')

<div class="container-fluid">

    <!-- Stats Cards -->
    <div class="row g-4">

        <!-- Total Customers -->
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">إجمالي العملاء</h6>
                            <h3 class="fw-bold mb-0">{{ $totalCustomers ?? 0 }}</h3>
                        </div>
                        <div class="stat-icon bg-primary text-white">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Technicians -->
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">عدد الفنيين</h6>
                            <h3 class="fw-bold mb-0">{{ $totalTechnicians ?? 0 }}</h3>
                        </div>
                        <div class="stat-icon bg-success text-white">
                            <i class="fa-solid fa-user-gear"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Orders -->
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">الطلبات الجديدة</h6>
                            <h3 class="fw-bold mb-0">{{ $newOrders }}</h3>
                        </div>
                        <div class="stat-icon bg-warning text-white">
                            <i class="fa-solid fa-cart-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">المخزون المنخفض</h6>
                            <h3 class="fw-bold mb-0">{{ $lowStock }}</h3>
                        </div>
                        <div class="stat-icon bg-danger text-white">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Latest Orders Table -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="fw-bold mb-0">آخر الطلبات</h5>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>العميل</th>
                                    <th>الفني</th>
                                    <th>الخدمة</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($latestRequests as $request)
                                    <tr>
                                        <td>{{ $request->id }}</td>
                                        <td>{{ $request->user->name }}</td>
                                        <td>
                                            @if($request->assigned_technician_id)
                                                @php
                                                    $tech = \App\Models\Technician::with('user')->find($request->assigned_technician_id);
                                                @endphp
                                                {{ $tech ? $tech->user->name : 'غير معين' }}
                                            @else
                                                غير معين
                                            @endif
                                        </td>
                                        <td>{{ $request->service->name }}</td>
                                        <td>
                                            @php
                                                $badges = [
                                                    'pending' => 'secondary',
                                                    'approved' => 'primary',
                                                    'pricing_pending' => 'info',
                                                    'in_progress' => 'warning',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger',
                                                ];
                                                $statusNames = [
                                                    'pending' => 'قيد الانتظار',
                                                    'approved' => 'موافق عليه',
                                                    'pricing_pending' => 'في انتظار السعر',
                                                    'in_progress' => 'قيد التنفيذ',
                                                    'completed' => 'مكتمل',
                                                    'cancelled' => 'ملغي',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $badges[$request->status] ?? 'secondary' }}">
                                                {{ $statusNames[$request->status] ?? $request->status }}
                                            </span>
                                        </td>
                                        <td>{{ $request->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">لا توجد طلبات</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<!-- Styles -->
<style>
    .stat-card {
        border-radius: 16px;
        transition: 0.25s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.07);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 20px;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }
</style>

@endsection
