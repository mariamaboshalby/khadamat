@extends('layouts.mobile')

@section('title', 'لوحة التحكم')

@section('content')

    <!-- Mobile Header -->
    <div class="app-header glass d-md-none">
        <div class="header-icon-btn">
            <i class="fas fa-bars"></i>
        </div>
        <div class="app-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 60px; width: 100px;">
        </div>
        <div class="header-icon-btn">
            <i class="fas fa-bell"></i>
        </div>
    </div>

    <!-- Content -->
    <div class="app-content fade-in p-3">

        <!-- Welcome Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1" style="color: var(--text-primary);">
                    مرحباً، {{ Auth::user()->name }} <i class="fas fa-hand-sparkles text-warning ms-1"></i>
                </h4>
                <p class="text-muted mb-0">تابع حالة طلباتك من هنا</p>
            </div>
            <a href="{{ route('requests.create') }}" class="btn-primary btn-sm rounded-pill  fw-bold shadow-sm">
                <i class="fas fa-plus me-1"></i> طلب جديد
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="stat-card glass">
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stat-value">{{ $requests->count() }}</div>
                    <div class="stat-label">إجمالي الطلبات</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card glass">
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value">{{ $requests->where('status', 'pending')->count() }}</div>
                    <div class="stat-label">قيد الانتظار</div>
                </div>
            </div>
        </div>

        <!-- Recent Requests -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0 section-title">طلباتي</h5>
        </div>

        @if ($requests->count() > 0)
            <div class="requests-list">
                @foreach ($requests as $request)
                    <div class="request-card glass mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center">
                                <div class="service-icon-sm {{ $request->service->color_class ?? 'blue' }}">
                                    <i class="fas {{ $request->service->icon ?? 'fa-tools' }}"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="fw-bold mb-1">{{ $request->service->name ?? 'خدمة عامة' }}</h6>
                                    <div class="text-muted small">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $request->created_at->format('Y-m-d') }}
                                    </div>
                                </div>
                            </div>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'approved' => 'info',
                                    'in_progress' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                ];
                                $statusLabels = [
                                    'pending' => 'قيد الانتظار',
                                    'approved' => 'تم القبول',
                                    'in_progress' => 'جاري التنفيذ',
                                    'completed' => 'مكتمل',
                                    'cancelled' => 'ملغي',
                                ];
                                $status = $request->status;
                                $color = $statusColors[$status] ?? 'secondary';
                                $label = $statusLabels[$status] ?? $status;
                            @endphp
                            <span
                                class="badge bg-{{ $color }}-subtle text-{{ $color }} rounded-pill px-3 py-2">
                                {{ $label }}
                            </span>
                        </div>

                        @if ($request->scheduled_at)
                            <div class="request-detail mt-2">
                                <i class="fas fa-clock text-muted me-2"></i>
                                <span>الموعد: {{ $request->scheduled_at->format('Y-m-d h:i A') }}</span>
                            </div>
                        @endif

                        <div class="request-detail mt-1">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            <span>{{ Str::limit($request->address, 40) }}</span>
                        </div>

                        <div class="mt-3 pt-3 border-top border-light d-flex justify-content-end gap-2">
                            @if ($request->status === 'pending')
                                <form action="{{ route('requests.destroy', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" method="POST"
                                    onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-secondary rounded-pill px-3">
                                        إلغاء
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('requests.show', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}"
                                class="btn-sm btn-outline-primary  rounded-pill ">
                                التفاصيل <i class="fas fa-arrow-left ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-clipboard-check fa-4x text-muted opacity-25"></i>
                </div>
                <h5 class="text-muted fw-bold">لا توجد طلبات حتى الآن</h5>
                <p class="text-muted small mb-4">ابدأ بطلب خدمة جديدة وسنقوم بخدمتك فوراً</p>
                <a href="{{ route('requests.create') }}" class=" btn-primary rounded-3 btn-modern shadow-sm">
                    <i class="fas fa-plus me-2"></i> طلب خدمة جديدة
                </a>
            </div>
        @endif

    </div>

@endsection

@push('styles')
    <style>
        .stat-card {
            padding: 20px;
            border-radius: var(--radius-lg);
            background: #fff;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            border: 1px solid var(--border-color);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 12px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .request-card {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: all 0.2s;
        }

        .request-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .service-icon-sm {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            background: var(--bg-color);
            color: var(--text-secondary);
        }

        .service-icon-sm.blue {
            background: #ffecec;
            color: #cc3333;
        }

        .request-detail {
            font-size: 13px;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
        }

        .bg-primary-subtle {
            background-color: rgba(204, 51, 51, 0.1);
        }

        .bg-warning-subtle {
            background-color: rgba(245, 158, 11, 0.1);
        }

        .bg-success-subtle {
            background-color: rgba(16, 185, 129, 0.1);
        }

        .bg-danger-subtle {
            background-color: rgba(239, 68, 68, 0.1);
        }

        .bg-info-subtle {
            background-color: rgba(6, 182, 212, 0.1);
        }
    </style>
@endpush
