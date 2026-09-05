@extends('layouts.admin')

@section('title', 'إدارة الطلبات')

@section('content')
@php
    $statusColors = [
        'pending' => 'warning',
        'approved' => 'info',
        'in_progress' => 'primary',
        'completed' => 'success',
        'cancelled' => 'danger',
        'rejected' => 'secondary',
    ];
    $statusLabels = [
        'pending' => 'قيد الانتظار',
        'approved' => 'تم القبول',
        'in_progress' => 'جاري التنفيذ',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي',
        'rejected' => 'مرفوض',
    ];
    $filters = [
        '' => ['label' => 'الكل', 'class' => 'primary'],
        'pending' => ['label' => 'قيد الانتظار', 'class' => 'warning'],
        'approved' => ['label' => 'مقبولة', 'class' => 'info'],
        'completed' => ['label' => 'مكتملة', 'class' => 'success'],
    ];
@endphp

<div class="admin-requests-page">

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">إدارة الطلبات</h4>
            <p class="text-muted mb-0 small">متابعة وإدارة جميع طلبات العملاء</p>
        </div>
        <span class="badge bg-light text-dark border px-3 py-2 fw-bold">
            {{ $requests->total() }} طلب
        </span>
    </div>

    <div class="requests-filter-scroll mb-4">
        @foreach ($filters as $value => $filter)
            <a href="{{ route('admin.requests.index', $value ? ['status' => $value] : []) }}"
                class="requests-filter-chip {{ request('status', '') === $value ? 'active filter-' . $filter['class'] : '' }}">
                {{ $filter['label'] }}
            </a>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if ($requests->isEmpty())
                <div class="text-center py-5">
                    <div class="requests-empty-icon mb-3">
                        <i class="fa-solid fa-inbox"></i>
                    </div>
                    <h5 class="fw-bold text-muted">لا توجد طلبات</h5>
                    <p class="text-muted small mb-0">لم يتم العثور على طلبات بهذا التصفية.</p>
                </div>
            @else
                <div class="table-responsive requests-table-wrap">
                    <table class="table table-hover align-middle mb-0 requests-table">
                        <thead class="bg-light">
                            <tr>
                                <th class="requests-th">#</th>
                                <th class="requests-th">العميل</th>
                                <th class="requests-th d-none d-md-table-cell">الخدمة</th>
                                <th class="requests-th d-none d-lg-table-cell">العنوان</th>
                                <th class="requests-th d-none d-xl-table-cell">الموعد</th>
                                <th class="requests-th">الحالة</th>
                                <th class="requests-th d-none d-sm-table-cell">التاريخ</th>
                                <th class="requests-th text-center">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                <tr>
                                    <td class="requests-td fw-bold text-muted">#{{ $request->id }}</td>
                                    <td class="requests-td">
                                        <div class="fw-bold">{{ $request->user->name ?? 'غير معروف' }}</div>
                                        <small class="text-muted d-md-none">{{ $request->service->name ?? 'خدمة عامة' }}</small>
                                    </td>
                                    <td class="requests-td d-none d-md-table-cell">{{ $request->service->name ?? 'خدمة عامة' }}</td>
                                    <td class="requests-td d-none d-lg-table-cell text-muted">{{ Str::limit($request->address, 30) }}</td>
                                    <td class="requests-td d-none d-xl-table-cell text-muted small">
                                        @if ($request->scheduled_at)
                                            {{ $request->scheduled_at->format('Y-m-d H:i') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="requests-td">
                                        <span class="badge bg-{{ $statusColors[$request->status] ?? 'secondary' }} rounded-pill">
                                            {{ $statusLabels[$request->status] ?? $request->status }}
                                        </span>
                                    </td>
                                    <td class="requests-td d-none d-sm-table-cell text-muted small">{{ $request->created_at->format('Y-m-d') }}</td>
                                    <td class="requests-td text-center">
                                        @include('admin.requests.partials.actions', ['request' => $request])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center p-3 border-top">
                    {{ $requests->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .admin-requests-page {
        max-width: 100%;
        overflow-x: hidden;
    }

    .requests-filter-scroll {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: none;
    }

    .requests-filter-scroll::-webkit-scrollbar {
        display: none;
    }

    .requests-filter-chip {
        white-space: nowrap;
        padding: 8px 18px;
        border-radius: 50px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        flex-shrink: 0;
    }

    .requests-filter-chip.active {
        color: #fff;
        border-color: transparent;
    }

    .requests-filter-chip.active.filter-primary { background: #0b5f8a; }
    .requests-filter-chip.active.filter-warning { background: #f59e0b; }
    .requests-filter-chip.active.filter-info { background: #06b6d4; }
    .requests-filter-chip.active.filter-success { background: #10b981; }

    .requests-table-wrap {
        -webkit-overflow-scrolling: touch;
    }

    .requests-table {
        min-width: 580px;
    }

    .requests-th {
        padding: 12px 14px;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        white-space: nowrap;
    }

    .requests-td {
        padding: 12px 14px;
        font-size: 14px;
        vertical-align: middle;
    }

    .requests-empty-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: #94a3b8;
    }

    .request-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: nowrap;
        gap: 4px;
    }

    .btn-request-view {
        background: #083a56;
        color: #fff;
        border: none;
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-request-view:hover {
        background: #0b5f8a;
        color: #fff;
    }

    .btn-request-invoice {
        padding: 5px 8px;
        font-size: 12px;
    }

    .request-actions .dropdown-toggle {
        font-size: 12px;
        padding: 5px 8px;
        white-space: nowrap;
    }

    @media (max-width: 576px) {
        .requests-th,
        .requests-td {
            padding: 10px 8px;
            font-size: 13px;
        }

        .requests-table {
            min-width: 520px;
        }

        .request-actions .dropdown-toggle span,
        .btn-request-view span {
            display: none;
        }

        .admin-requests-page .pagination {
            flex-wrap: wrap;
            justify-content: center;
            gap: 4px;
        }
    }
</style>
@endpush
@endsection
