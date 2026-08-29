@extends('layouts.mobile')

@section('title', 'لوحة تحكم العميل | خدمتي')

@section('content')

    <div class="customer-dashboard py-3 py-md-4">
        <div class="container-fluid px-3 px-md-4 px-lg-5">

            <!-- Hero Welcome Card -->
            <div class="welcome-hero-card mb-4">
                <div class="row align-items-center g-3">
                    <div class="col-lg-8 col-md-7">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="user-avatar-badge">
                                <i class="fas fa-user text-white fs-4"></i>
                            </div>
                            <div>
                                <div class="badge bg-white/20 text-white rounded-pill px-3 py-1 mb-1 font-monospace" style="background: rgba(255,255,255,0.18); font-size: 11px;">
                                    <i class="fas fa-shield-alt text-warning me-1"></i> حساب عميل موثق
                                </div>
                                <h3 class="fw-bold text-white mb-0 hero-greeting">
                                    مرحباً بك، {{ Auth::user()->name }} 👋
                                </h3>
                            </div>
                        </div>
                        <p class="text-white-50 mb-0 fs-6 pe-lg-4">
                            تابع حالة طلبات الصيانة الحالية، استعرض عروض الأسعار، واطلب فنيين متخصصين بضغطة واحدة.
                        </p>
                    </div>
                    <div class="col-lg-4 col-md-5 text-md-end">
                        <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                            <a href="{{ route('requests.create') }}" class="btn-hero-action btn-hero-primary shadow">
                                <i class="fas fa-plus-circle"></i>
                                <span>طلب خدمة جديدة</span>
                            </a>
                            <a href="{{ route('services.index') }}" class="btn-hero-action btn-hero-outline">
                                <i class="fas fa-th-large"></i>
                                <span>الخدمات</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Price Approval Alert (if any pending pricing) -->
            @if (($stats['pending_approval'] ?? 0) > 0)
                <div class="alert-action-card mb-4 fade-in">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="alert-icon-pulse">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">يوجد عروض أسعار بانتظار موافقتك!</h6>
                                <p class="mb-0 text-muted small">
                                    قام الفني بتقديم تسعيرة لـ {{ $stats['pending_approval'] }} طلب، يرجى مراجعتها للبدء بالتنفيذ.
                                </p>
                            </div>
                        </div>
                        @php
                            $pendingPriceReq = $requests->firstWhere('price_status', 'pending_customer_approval');
                        @endphp
                        @if($pendingPriceReq)
                            <a href="{{ route('pricing.show', \App\Helpers\EncryptionHelper::encryptId($pendingPriceReq->id)) }}" class="btn btn-warning btn-sm rounded-pill fw-bold px-4 py-2 text-dark shadow-sm">
                                <i class="fas fa-eye me-1"></i> مراجعة السعر الآن
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Quick Stats Grid -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="stat-box glass-card stat-total" onclick="filterByStatus('all')">
                        <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-number">{{ $stats['total'] ?? 0 }}</span>
                            <span class="stat-title">إجمالي الطلبات</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-box glass-card stat-progress" onclick="filterByStatus('in_progress')">
                        <div class="stat-icon-wrapper bg-info-subtle text-info">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-number">{{ $stats['in_progress'] ?? 0 }}</span>
                            <span class="stat-title">جاري التنفيذ</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-box glass-card stat-pending" onclick="filterByStatus('pending')">
                        <div class="stat-icon-wrapper bg-warning-subtle text-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-number">{{ $stats['pending'] ?? 0 }}</span>
                            <span class="stat-title">قيد المراجعة</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-box glass-card stat-completed" onclick="filterByStatus('completed')">
                        <div class="stat-icon-wrapper bg-success-subtle text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-number">{{ $stats['completed'] ?? 0 }}</span>
                            <span class="stat-title">الطلبات المكتملة</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Live Tracking Hero (If user has ongoing request) -->
            @if ($activeRequest)
                <div class="active-tracker-card glass-card mb-4 fade-in">
                    <div class="tracker-header d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <span class="live-indicator"></span>
                            <h6 class="fw-bold mb-0 text-dark">متابعة حية للطلب الحالي</h6>
                            <span class="badge bg-light text-muted border rounded-pill">#REQ-{{ $activeRequest->id }}</span>
                        </div>
                        @php
                            $stMap = [
                                'pending' => ['label' => 'قيد الانتظار', 'badge' => 'warning'],
                                'approved' => ['label' => 'تم قبول الطلب', 'badge' => 'info'],
                                'in_progress' => ['label' => 'جاري العمل', 'badge' => 'primary'],
                                'completed' => ['label' => 'مكتمل', 'badge' => 'success'],
                                'cancelled' => ['label' => 'ملغي', 'badge' => 'danger'],
                            ];
                            $currSt = $stMap[$activeRequest->status] ?? ['label' => $activeRequest->status, 'badge' => 'secondary'];
                        @endphp
                        <span class="badge bg-{{ $currSt['badge'] }}-subtle text-{{ $currSt['badge'] }} rounded-pill px-3 py-2 fw-bold">
                            {{ $currSt['label'] }}
                        </span>
                    </div>

                    <!-- Progress Stepper -->
                    @php
                        $step = 1;
                        if ($activeRequest->status === 'pending') $step = 1;
                        elseif ($activeRequest->status === 'approved') $step = 2;
                        elseif ($activeRequest->status === 'in_progress') $step = 3;
                        elseif ($activeRequest->status === 'completed') $step = 4;
                    @endphp
                    <div class="stepper-wrapper mb-4">
                        <div class="stepper-item {{ $step >= 1 ? 'active' : '' }} {{ $step > 1 ? 'completed' : '' }}">
                            <div class="step-circle"><i class="fas fa-clipboard-check"></i></div>
                            <div class="step-label">تم الاستلام</div>
                        </div>
                        <div class="stepper-line {{ $step >= 2 ? 'active' : '' }}"></div>
                        <div class="stepper-item {{ $step >= 2 ? 'active' : '' }} {{ $step > 2 ? 'completed' : '' }}">
                            <div class="step-circle"><i class="fas fa-user-check"></i></div>
                            <div class="step-label">تعيين الفني</div>
                        </div>
                        <div class="stepper-line {{ $step >= 3 ? 'active' : '' }}"></div>
                        <div class="stepper-item {{ $step >= 3 ? 'active' : '' }} {{ $step > 3 ? 'completed' : '' }}">
                            <div class="step-circle"><i class="fas fa-wrench"></i></div>
                            <div class="step-label">جاري التنفيذ</div>
                        </div>
                        <div class="stepper-line {{ $step >= 4 ? 'active' : '' }}"></div>
                        <div class="stepper-item {{ $step >= 4 ? 'active' : '' }}">
                            <div class="step-circle"><i class="fas fa-flag-checkered"></i></div>
                            <div class="step-label">إنجاز الخدمة</div>
                        </div>
                    </div>

                    <!-- Active Request Quick Summary -->
                    <div class="row g-3 align-items-center pt-2">
                        <div class="col-md-7 col-lg-8">
                            <div class="d-flex align-items-start gap-3">
                                <div class="service-icon-box {{ $activeRequest->service->color_class ?? 'blue' }}">
                                    <i class="fas {{ $activeRequest->service->icon ?? 'fa-tools' }}"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">{{ $activeRequest->service->name ?? 'خدمة عامة' }}</h5>
                                    <p class="text-muted small mb-2">{{ Str::limit($activeRequest->description, 90, '...') }}</p>
                                    <div class="d-flex flex-wrap gap-3 text-secondary small">
                                        @if($activeRequest->scheduled_at)
                                            <div><i class="fas fa-calendar-alt text-primary me-1"></i> {{ $activeRequest->scheduled_at->format('Y-m-d h:i A') }}</div>
                                        @endif
                                        @if($activeRequest->address)
                                            <div><i class="fas fa-map-marker-alt text-danger me-1"></i> {{ Str::limit($activeRequest->address, 30) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 col-lg-4">
                            <div class="d-flex flex-column gap-2">
                                @if($activeRequest->assignedTechnician)
                                    <div class="tech-mini-card p-2 rounded-3 bg-light d-flex align-items-center gap-2 border">
                                        <div class="tech-avatar-sm">
                                            <i class="fas fa-user-tie text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold small text-dark">{{ $activeRequest->assignedTechnician->user->name ?? 'الفني المختص' }}</div>
                                            <div class="text-muted" style="font-size: 11px;">
                                                <i class="fas fa-star text-warning"></i> {{ $activeRequest->assignedTechnician->rating ?? '5.0' }} | {{ $activeRequest->assignedTechnician->specialization->name ?? 'صيانة' }}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="d-flex gap-2 mt-1">
                                    <a href="{{ route('requests.show', \App\Helpers\EncryptionHelper::encryptId($activeRequest->id)) }}" class="btn btn-primary btn-sm rounded-pill flex-grow-1 py-2 fw-bold">
                                        تفاصيل ومتابعة <i class="fas fa-arrow-left ms-1"></i>
                                    </a>
                                    @if($activeRequest->price_status === 'pending_customer_approval')
                                        <a href="{{ route('pricing.show', \App\Helpers\EncryptionHelper::encryptId($activeRequest->id)) }}" class="btn btn-warning btn-sm rounded-pill fw-bold py-2 px-3">
                                            قبول السعر
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Service Booking Carousel/Grid -->
            @if(isset($services) && $services->count() > 0)
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="text-uppercase fw-bold text-muted" style="font-size: 11px; letter-spacing: 1px;">خدمات سريعة</span>
                            <h5 class="fw-bold mb-0 text-dark">طلب صيانة فورية</h5>
                        </div>
                        <a href="{{ route('services.index') }}" class="text-primary text-decoration-none fw-bold small">
                            عرض كل الخدمات <i class="fas fa-chevron-left ms-1" style="font-size: 10px;"></i>
                        </a>
                    </div>

                    <div class="row g-3">
                        @foreach($services->take(4) as $srv)
                            <div class="col-6 col-md-3">
                                <a href="{{ route('requests.create', ['service_id' => $srv->id]) }}" class="quick-service-card text-decoration-none d-block h-100">
                                    <div class="quick-srv-icon {{ $srv->color_class ?? 'blue' }} mb-2">
                                        <i class="fas {{ $srv->icon ?? 'fa-tools' }}"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">{{ $srv->name }}</h6>
                                    <p class="text-muted small mb-0 quick-srv-desc">طلب سريع ومضمون 100%</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Requests Management Section -->
            <div class="requests-section">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                    <div>
                        <span class="text-uppercase fw-bold text-muted" style="font-size: 11px; letter-spacing: 1px;">سجل المعاملات</span>
                        <h5 class="fw-bold mb-0 text-dark">طلباتي</h5>
                    </div>

                    <!-- Filter Tabs -->
                    <div class="filter-tabs-wrapper">
                        <button class="filter-tab-btn active" data-filter="all">
                            الكل <span class="tab-badge">{{ $stats['total'] ?? 0 }}</span>
                        </button>
                        <button class="filter-tab-btn" data-filter="in_progress">
                            جاري التنفيذ <span class="tab-badge">{{ $stats['in_progress'] ?? 0 }}</span>
                        </button>
                        <button class="filter-tab-btn" data-filter="pending">
                            قيد الانتظار <span class="tab-badge">{{ $stats['pending'] ?? 0 }}</span>
                        </button>
                        <button class="filter-tab-btn" data-filter="completed">
                            المكتملة <span class="tab-badge">{{ $stats['completed'] ?? 0 }}</span>
                        </button>
                        <button class="filter-tab-btn" data-filter="cancelled">
                            الملغية <span class="tab-badge">{{ $stats['cancelled'] ?? 0 }}</span>
                        </button>
                    </div>
                </div>

                @if ($requests->count() > 0)
                    <div class="requests-grid" id="requestsContainer">
                        @foreach ($requests as $request)
                            @php
                                $statusKey = $request->status;
                                // Group approved & in_progress together for filter convenience
                                $filterGroup = in_array($statusKey, ['approved', 'in_progress']) ? 'in_progress' : $statusKey;

                                $statusBadgeStyles = [
                                    'pending' => ['bg' => 'rgba(245, 158, 11, 0.12)', 'color' => '#d97706', 'icon' => 'fa-clock', 'text' => 'قيد الانتظار'],
                                    'approved' => ['bg' => 'rgba(6, 182, 212, 0.12)', 'color' => '#0891b2', 'icon' => 'fa-check', 'text' => 'تم قبول الطلب'],
                                    'in_progress' => ['bg' => 'rgba(11, 95, 138, 0.12)', 'color' => '#0b5f8a', 'icon' => 'fa-tools', 'text' => 'جاري التنفيذ'],
                                    'completed' => ['bg' => 'rgba(16, 185, 129, 0.12)', 'color' => '#059669', 'icon' => 'fa-check-circle', 'text' => 'مكتمل'],
                                    'cancelled' => ['bg' => 'rgba(239, 68, 68, 0.12)', 'color' => '#dc2626', 'icon' => 'fa-times-circle', 'text' => 'ملغي'],
                                ];
                                $st = $statusBadgeStyles[$statusKey] ?? ['bg' => 'rgba(100, 116, 139, 0.1)', 'color' => '#475569', 'icon' => 'fa-circle', 'text' => $statusKey];
                            @endphp

                            <div class="request-item-card glass-card mb-3" data-status="{{ $filterGroup }}">
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start gap-2 mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="service-icon-sm {{ $request->service->color_class ?? 'blue' }}">
                                            <i class="fas {{ $request->service->icon ?? 'fa-tools' }}"></i>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="fw-bold mb-0 text-dark">{{ $request->service->name ?? 'خدمة صيانة عامة' }}</h6>
                                                <span class="req-id-pill">#{{ $request->id }}</span>
                                            </div>
                                            <div class="text-muted small mt-1">
                                                <i class="fas fa-calendar-alt me-1 text-secondary"></i>
                                                تاريخ الطلب: {{ $request->created_at->format('Y/m/d') }}
                                            </div>
                                        </div>
                                    </div>

                                    <span class="custom-status-badge" style="background: {{ $st['bg'] }}; color: {{ $st['color'] }};">
                                        <i class="fas {{ $st['icon'] }} me-1"></i> {{ $st['text'] }}
                                    </span>
                                </div>

                                <!-- Request Details snippet -->
                                <div class="req-details-grid p-3 rounded-3 bg-light border mb-3">
                                    <div class="row g-2">
                                        @if($request->scheduled_at)
                                            <div class="col-sm-6">
                                                <div class="d-flex align-items-center gap-2 text-secondary small">
                                                    <i class="fas fa-clock text-primary"></i>
                                                    <span><strong>الموعد:</strong> {{ $request->scheduled_at->format('Y-m-d h:i A') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        @if($request->address)
                                            <div class="col-sm-6">
                                                <div class="d-flex align-items-center gap-2 text-secondary small">
                                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                                    <span class="text-truncate"><strong>العنوان:</strong> {{ Str::limit($request->address, 35) }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        @if($request->assignedTechnician)
                                            <div class="col-sm-6">
                                                <div class="d-flex align-items-center gap-2 text-secondary small">
                                                    <i class="fas fa-user-cog text-info"></i>
                                                    <span><strong>الفني:</strong> {{ $request->assignedTechnician->user->name ?? 'فني معتمد' }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        @if($request->proposed_price || $request->total_price)
                                            <div class="col-sm-6">
                                                <div class="d-flex align-items-center gap-2 text-secondary small">
                                                    <i class="fas fa-coins text-warning"></i>
                                                    <span><strong>التكلفة:</strong> <span class="fw-bold text-dark">{{ number_format($request->total_price ?: $request->proposed_price, 2) }} جنيه</span></span>
                                                    @if($request->price_status === 'pending_customer_approval')
                                                        <span class="badge bg-warning text-dark py-0" style="font-size: 10px;">بانتظار الموافقة</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Buttons Footer -->
                                <div class="d-flex justify-content-between align-items-center pt-2">
                                    <div class="req-description-snippet text-muted small text-truncate pe-2">
                                        {{ Str::limit($request->description, 60) }}
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($request->status === 'pending')
                                            <form action="{{ route('requests.destroy', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" method="POST"
                                                onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟');" class="d-inline m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                                    <i class="fas fa-trash-alt me-1"></i> إلغاء
                                                </button>
                                            </form>
                                        @endif

                                        @if($request->price_status === 'pending_customer_approval')
                                            <a href="{{ route('pricing.show', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark shadow-sm">
                                                <i class="fas fa-tag me-1"></i> مراجعة السعر
                                            </a>
                                        @endif

                                        <a href="{{ route('requests.show', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}"
                                            class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                                            التفاصيل <i class="fas fa-arrow-left ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Empty Filter Results -->
                    <div id="noFilterResults" class="text-center py-5 glass-card d-none">
                        <i class="fas fa-search fa-3x text-muted opacity-25 mb-3"></i>
                        <h6 class="fw-bold text-muted">لا توجد طلبات تطابق هذا التصنيف</h6>
                    </div>
                @else
                    <div class="text-center py-5 glass-card">
                        <div class="mb-3">
                            <div class="empty-icon-circle mx-auto">
                                <i class="fas fa-clipboard-check text-muted fa-3x"></i>
                            </div>
                        </div>
                        <h5 class="text-dark fw-bold mb-1">لا توجد لديك طلبات صيانة حالياً</h5>
                        <p class="text-muted small mb-4">اختر إحدى خدماتنا وسنصلك فوراً أينما كنت بأفضل الفنيين المعتمدين</p>
                        <a href="{{ route('requests.create') }}" class="btn-brand-orange">
                            <i class="fas fa-plus-circle me-2"></i> طلب خدمة جديدة الآن
                        </a>
                    </div>
                @endif
            </div>

            <!-- Need Help Support Card -->
            <div class="help-support-card glass-card mt-4 p-4">
                <div class="row align-items-center g-3">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-3">
                            <div class="support-icon">
                                <i class="fas fa-headset text-primary fs-3"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">هل تحتاج إلى مساعدة أو استفسار بخصوص طلبك؟</h6>
                                <p class="text-muted small mb-0">فريق خدمة العملاء متاح لمساعدتك على مدار الساعة لضمان أفضل تجربة صيانة منزلية.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="https://wa.me/966500000000" target="_blank" class="btn btn-outline-success rounded-pill px-3 py-2 fw-bold small">
                            <i class="fab fa-whatsapp me-1"></i> تحدث مع الدعم الفني
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* Customer Dashboard Specific Styling */
        .customer-dashboard {
            background-color: var(--bg-color, #f8fafc);
            min-height: calc(100vh - 80px);
        }

        /* Glass Card Utility */
        .glass-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px -2px rgba(11, 95, 138, 0.05), 0 2px 6px -2px rgba(0, 0, 0, 0.03);
            padding: 20px;
            transition: all 0.25s ease;
        }

        .glass-card:hover {
            box-shadow: 0 8px 24px -4px rgba(11, 95, 138, 0.09);
        }

        /* Welcome Hero Card */
        .welcome-hero-card {
            background: linear-gradient(135deg, #072540 0%, #0b5f8a 100%);
            border-radius: 24px;
            padding: 28px 24px;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 28px -6px rgba(11, 95, 138, 0.35);
        }

        .welcome-hero-card::after {
            content: '';
            position: absolute;
            top: -40px;
            left: -40px;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(255, 138, 0, 0.25) 0%, rgba(255, 138, 0, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .user-avatar-badge {
            width: 52px;
            height: 52px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-greeting {
            font-size: 1.45rem;
            letter-spacing: -0.3px;
        }

        .btn-hero-action {
            padding: 10px 22px;
            border-radius: 30px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            transition: all 0.25s ease;
        }

        .btn-hero-primary {
            background: linear-gradient(135deg, #ff9500 0%, #ff7a00 100%);
            color: #ffffff !important;
            border: none;
            box-shadow: 0 4px 14px rgba(255, 122, 0, 0.4);
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 122, 0, 0.55);
            background: linear-gradient(135deg, #ff8500 0%, #e66a00 100%);
        }

        .btn-hero-outline {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(8px);
        }

        .btn-hero-outline:hover {
            background: rgba(255, 255, 255, 0.22);
            transform: translateY(-2px);
        }

        /* Alert Action Card */
        .alert-action-card {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 18px;
            padding: 16px 20px;
            border-right: 5px solid #f59e0b;
        }

        .alert-icon-pulse {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #fef3c7;
            color: #d97706;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            animation: pulse-ring 2s infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(1); }
            50% { transform: scale(1.06); }
            100% { transform: scale(1); }
        }

        /* Stats Boxes */
        .stat-box {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 18px 20px;
            cursor: pointer;
            border-radius: 18px;
        }

        .stat-box:hover {
            transform: translateY(-3px);
            border-color: #cbd5e1;
        }

        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-number {
            display: block;
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }

        .stat-title {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
        }

        /* Active Tracker Card */
        .active-tracker-card {
            border: 1px solid #bae6fd;
            background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%);
            position: relative;
        }

        .live-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #10b981;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: live-pulse 1.8s infinite;
        }

        @keyframes live-pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Stepper */
        .stepper-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            padding: 10px 0;
        }

        .stepper-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            z-index: 2;
        }

        .step-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .stepper-item.active .step-circle {
            background: #0b5f8a;
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(11, 95, 138, 0.2);
        }

        .stepper-item.completed .step-circle {
            background: #10b981;
            color: #ffffff;
        }

        .step-label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
        }

        .stepper-item.active .step-label {
            color: #0b5f8a;
        }

        .stepper-line {
            flex-grow: 1;
            height: 3px;
            background: #e2e8f0;
            margin: 0 8px;
            margin-bottom: 22px;
            z-index: 1;
            transition: background 0.3s ease;
        }

        .stepper-line.active {
            background: #0b5f8a;
        }

        /* Service Icons Box */
        .service-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            background: #e0f2fe;
            color: #0b5f8a;
            flex-shrink: 0;
        }

        .service-icon-sm {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            background: #e0f2fe;
            color: #0b5f8a;
            flex-shrink: 0;
        }

        .service-icon-box.blue, .service-icon-sm.blue { background: #e0f2fe; color: #0284c7; }
        .service-icon-box.orange, .service-icon-sm.orange { background: #fff7ed; color: #ea580c; }
        .service-icon-box.green, .service-icon-sm.green { background: #ecfdf5; color: #059669; }
        .service-icon-box.purple, .service-icon-sm.purple { background: #faf5ff; color: #9333ea; }

        /* Quick Service Cards */
        .quick-service-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px;
            text-align: center;
            transition: all 0.25s ease;
        }

        .quick-service-card:hover {
            transform: translateY(-4px);
            border-color: #0b5f8a;
            box-shadow: 0 8px 20px -3px rgba(11, 95, 138, 0.12);
        }

        .quick-srv-icon {
            width: 44px;
            height: 44px;
            margin: 0 auto;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            background: #f1f5f9;
            color: #0b5f8a;
        }

        .quick-srv-desc {
            font-size: 11px;
            line-height: 1.3;
        }

        /* Filter Tabs */
        .filter-tabs-wrapper {
            display: flex;
            gap: 6px;
            overflow-x: auto;
            padding-bottom: 4px;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .filter-tabs-wrapper::-webkit-scrollbar {
            display: none;
        }

        .filter-tab-btn {
            background: #f1f5f9;
            border: 1px solid transparent;
            color: #475569;
            padding: 7px 16px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .filter-tab-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .filter-tab-btn.active {
            background: #0b5f8a;
            color: #ffffff;
            border-color: #0b5f8a;
        }

        .tab-badge {
            background: rgba(0, 0, 0, 0.08);
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 11px;
        }

        .filter-tab-btn.active .tab-badge {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }

        /* Request Item Card */
        .request-item-card {
            transition: all 0.2s ease;
        }

        .request-item-card:hover {
            transform: translateY(-2px);
            border-color: #cbd5e1;
        }

        .req-id-pill {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 6px;
            font-family: monospace;
        }

        .custom-status-badge {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
        }

        .empty-icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tech-avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e0f2fe;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Subtle subtle colors */
        .bg-primary-subtle { background-color: rgba(11, 95, 138, 0.1) !important; }
        .bg-info-subtle { background-color: rgba(6, 182, 212, 0.1) !important; }
        .bg-warning-subtle { background-color: rgba(245, 158, 11, 0.1) !important; }
        .bg-success-subtle { background-color: rgba(16, 185, 129, 0.1) !important; }

        @media (max-width: 576px) {
            .stepper-wrapper {
                overflow-x: auto;
            }
            .step-label {
                font-size: 9px;
            }
            .step-circle {
                width: 32px;
                height: 32px;
                font-size: 12px;
            }
            .welcome-hero-card {
                padding: 20px 16px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function filterByStatus(status) {
            // Update active tab styling
            const tabs = document.querySelectorAll('.filter-tab-btn');
            tabs.forEach(tab => {
                if (tab.getAttribute('data-filter') === status) {
                    tab.classList.add('active');
                } else {
                    tab.classList.remove('active');
                }
            });

            // Filter items
            const items = document.querySelectorAll('.request-item-card');
            let visibleCount = 0;

            items.forEach(item => {
                const itemStatus = item.getAttribute('data-status');
                if (status === 'all' || itemStatus === status) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            const noResults = document.getElementById('noFilterResults');
            if (noResults) {
                if (visibleCount === 0 && items.length > 0) {
                    noResults.classList.remove('d-none');
                } else {
                    noResults.classList.add('d-none');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const tabButtons = document.querySelectorAll('.filter-tab-btn');
            tabButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const filter = this.getAttribute('data-filter');
                    filterByStatus(filter);
                });
            });
        });
    </script>
@endpush
