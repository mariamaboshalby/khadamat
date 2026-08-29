@extends('layouts.mobile')

@section('title', 'تفاصيل الطلب #' . $requestData->id . ' | خدمتي')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* Leaflet tiles must be above glass panels but below modals */
        .leaflet-pane { z-index: 10 !important; }
        .leaflet-tile-pane { z-index: 10 !important; }
        .leaflet-overlay-pane { z-index: 20 !important; }
        .leaflet-marker-pane { z-index: 30 !important; }
        .leaflet-popup-pane { z-index: 40 !important; }
        .leaflet-control { z-index: 50 !important; }
        .map-floating-distance { z-index: 60 !important; }
        .map-expand-btn { z-index: 60 !important; }
    </style>
@endpush

@section('content')

    <div class="request-details-page py-3 py-md-4">
        <div class="container-fluid px-3 px-md-4 px-lg-5">

            <!-- Top Header & Breadcrumb Bar -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-2">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <span class="badge bg-light text-secondary border px-3 py-1 rounded-pill font-monospace fw-bold">
                            رقم الطلب #REQ-{{ $requestData->id }}
                        </span>
                        <span class="text-muted small">•</span>
                        <span class="text-muted small fw-bold">
                            {{ $requestData->service->name ?? 'خدمة صيانة عامة' }}
                        </span>
                    </div>
                    <h2 class="fw-bold text-dark mb-0">تفاصيل الطلب</h2>
                </div>

                <!-- Header Action Buttons -->
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @if(in_array($requestData->status, ['in_progress', 'completed']))
                        <a href="{{ route('requests.invoice', \App\Helpers\EncryptionHelper::encryptId($requestData->id)) }}" target="_blank" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-bold small d-inline-flex align-items-center gap-2 bg-white shadow-sm">
                            <i class="fas fa-print"></i>
                            <span>طباعة الفاتورة</span>
                        </a>
                    @else
                        <button onclick="window.print()" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-bold small d-inline-flex align-items-center gap-2 bg-white shadow-sm">
                            <i class="fas fa-print"></i>
                            <span>طباعة الفاتورة</span>
                        </button>
                    @endif

                    <a href="https://wa.me/966500000000?text={{ urlencode('مرحباً، أود الاستفسار عن طلبي رقم #REQ-' . $requestData->id) }}" target="_blank" class="btn btn-dark rounded-pill px-4 py-2 fw-bold small d-inline-flex align-items-center gap-2 shadow-sm" style="background-color: #072540; border-color: #072540;">
                        <i class="fas fa-headset"></i>
                        <span>الدعم الفني</span>
                    </a>
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- 🔔 Price Quotation Approval Banner -->
            @if(in_array($requestData->price_status, ['pending_customer_approval', 'pending']) && $requestData->proposed_price)
                <div class="quotation-hero-banner mb-4 p-4 rounded-4 shadow-sm fade-in">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div class="d-flex align-items-start gap-3">
                            <div class="quote-icon-pulse">
                                <i class="fas fa-file-invoice-dollar fs-3"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold" style="font-size: 11px;">
                                        <i class="fas fa-bell me-1"></i> بانتظار موافقتك على عرض السعر
                                    </span>
                                    <span class="text-muted small">خطوة ضرورية للبدء بالصيانة</span>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">قدم الفني تسعيرة لإنجاز هذا الطلب</h5>
                                <div class="text-muted small">
                                    سعر الخدمة: <strong class="text-primary">{{ number_format($requestData->proposed_price, 2) }} جنيه</strong>
                                    @if($requestData->requestItems->count() > 0)
                                        + قطع الغيار ({{ $requestData->requestItems->count() }} قطع): <strong class="text-info">{{ number_format($requestData->total_items_price, 2) }} جنيه</strong>
                                    @endif
                                    • الإجمالي: <strong class="text-success fs-6">{{ number_format($requestData->total_price ?: $requestData->proposed_price, 2) }} جنيه</strong>
                                </div>
                                @if($requestData->price_notes)
                                    <div class="mt-2 text-secondary small bg-white p-2 rounded-3 border">
                                        <i class="fas fa-comment-alt text-warning me-1"></i> <strong>ملاحظة الفني:</strong> {{ $requestData->price_notes }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <form method="POST" action="{{ route('requests.accept-price', \App\Helpers\EncryptionHelper::encryptId($requestData->id)) }}" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                                    <i class="fas fa-check-circle"></i>
                                    <span>قبول العرض والبدء</span>
                                </button>
                            </form>
                            <button type="button" class="btn btn-outline-danger rounded-pill px-3 py-2 fw-bold small" data-bs-toggle="modal" data-bs-target="#rejectPriceModal">
                                <i class="fas fa-times-circle me-1"></i> رفض العرض
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 📑 Multiple Proposals from Technicians (if any) -->
            @if($requestData->proposals && $requestData->proposals->where('status', 'pending')->count() > 0)
                <div class="glass-panel p-4 mb-4 border-warning">
                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                        <i class="fas fa-tags text-warning fs-5"></i>
                        <h5 class="fw-bold mb-0 text-dark">عروض الأسعار المقدمة من الفنيين</h5>
                    </div>
                    <div class="row g-3">
                        @foreach($requestData->proposals->where('status', 'pending') as $proposal)
                            <div class="col-md-6">
                                <div class="p-3 rounded-4 bg-light border h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong class="text-dark">{{ $proposal->technician->user->name ?? 'فني' }}</strong>
                                            <span class="badge bg-warning-subtle text-warning rounded-pill px-2">عرض جديد</span>
                                        </div>
                                        <div class="fs-5 fw-bold text-success mb-2">
                                            {{ number_format($proposal->proposed_price + ($proposal->items ? $proposal->items->sum('total_price') : 0), 2) }} جنيه
                                        </div>
                                        @if($proposal->price_notes)
                                            <p class="text-muted small mb-3">{{ $proposal->price_notes }}</p>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2">
                                        <form method="POST" action="{{ route('requests.accept-proposal', \App\Helpers\EncryptionHelper::encryptId($proposal->id)) }}" class="flex-grow-1">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm w-100 rounded-pill fw-bold">
                                                <i class="fas fa-check me-1"></i> قبول هذا الفني
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('requests.reject-proposal', \App\Helpers\EncryptionHelper::encryptId($proposal->id)) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Main 2-Column Layout -->
            <div class="row g-4">

                <!-- Left Column (Sidebar: Technician, Cost Summary, Review & Actions) -->
                <div class="col-lg-4 col-md-5 order-lg-1 order-2">

                    <!-- 1. Technician Card (Only if assigned) -->
                    @if($requestData->assignedTechnician && $requestData->assignedTechnician->user)
                        <div class="technician-profile-card glass-panel mb-4 overflow-hidden fade-in">
                            <div class="tech-pattern-banner"></div>
                            <div class="tech-card-body text-center px-4 pb-4">
                                <div class="tech-avatar-wrapper mx-auto mb-3">
                                    @if($requestData->assignedTechnician->user->avatar)
                                        <img src="{{ asset('storage/' . $requestData->assignedTechnician->user->avatar) }}" alt="Technician" class="tech-avatar-img">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($requestData->assignedTechnician->user->name) }}&background=072540&color=fff&size=128" alt="Technician" class="tech-avatar-img">
                                    @endif
                                </div>

                                <h5 class="fw-bold text-dark mb-1">
                                    {{ $requestData->assignedTechnician->user->name }}
                                </h5>
                                <p class="tech-specialization mb-3">
                                    {{ $requestData->assignedTechnician->specialization->name ?? ($requestData->service->name ?? 'فني صيانة معتمد') }}
                                </p>

                                <!-- Tech Stats -->
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="tech-stat-pill">
                                            <div class="stat-num">
                                                <i class="fas fa-star text-warning"></i>
                                                <span>{{ number_format($requestData->assignedTechnician->rating ?: 5.0, 1) }}</span>
                                            </div>
                                            <div class="stat-lbl">التقييم</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="tech-stat-pill">
                                            <div class="stat-num">
                                                <span>{{ $requestData->assignedTechnician->completed_tasks ?: 0 }}</span>
                                            </div>
                                            <div class="stat-lbl">خدمة مكتملة</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tech Action Buttons -->
                                <div class="d-flex flex-column gap-2">
                                    @php
                                        $techPhone = $requestData->assignedTechnician->user->phone;
                                    @endphp
                                    @if($techPhone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $techPhone) }}?text={{ urlencode('مرحباً، بخصوص طلب الصيانة رقم #REQ-' . $requestData->id) }}" target="_blank" class="btn btn-navy-action w-100 py-2 fw-bold rounded-pill">
                                            <i class="fas fa-comment-dots me-2"></i> التواصل مع الفني
                                        </a>
                                        <a href="tel:{{ $techPhone }}" class="btn btn-outline-navy w-100 py-2 fw-bold rounded-pill">
                                            <i class="fas fa-phone-alt me-2"></i> اتصال هاتفي
                                        </a>
                                    @else
                                        <span class="text-muted small">سيتم إتاحة رقم التواصل فور تحرك الفني</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Awaiting Technician Card -->
                        <div class="glass-panel p-4 mb-4 text-center border-warning">
                            <div class="awaiting-tech-icon mx-auto mb-3">
                                <i class="fas fa-user-clock text-warning fs-3"></i>
                            </div>
                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1 fw-bold mb-2">
                                <i class="fas fa-search me-1"></i> بانتظار تعيين الفني
                            </span>
                            <h6 class="fw-bold text-dark mb-2">طلبك قيد المراجعة والتوزيع</h6>
                            <p class="text-muted small mb-0">
                                يتم حالياً مطابقة طلبك مع أقرب وأفضل فني متخصص في <strong>{{ $requestData->service->name ?? 'الخدمة المطلوبة' }}</strong>.
                            </p>
                        </div>
                    @endif

                    <!-- 2. Cost Summary Dark Card -->
                    <div class="cost-summary-card mb-4 p-4">
                        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom border-white/10">
                            <span class="cost-summary-icon">📑</span>
                            <h6 class="fw-bold mb-0 text-white">ملخص التكلفة</h6>
                        </div>

                        @php
                            $hasTech    = !is_null($requestData->assigned_technician_id);
                            $hasPrice   = $hasTech
                                          && !empty($requestData->proposed_price)
                                          && (float)$requestData->proposed_price > 0;

                            $serviceCost  = $hasPrice ? (float)$requestData->proposed_price : 0.00;
                            $itemsCost    = $hasPrice ? (float)($requestData->total_items_price ?? 0) : 0.00;
                            $totalAmount  = $serviceCost + $itemsCost;
                        @endphp

                        {{-- ===== حالة 1: لا يوجد فني ===== --}}
                        @if(!$hasTech)
                            <div class="awaiting-total-box py-3 text-center">
                                <div class="awaiting-price-badge">
                                    <i class="fas fa-user-clock me-1"></i>
                                    جارٍ البحث عن فني مناسب لطلبك
                                </div>
                                <p class="text-white-50 small mt-2 mb-0">سيظهر ملخص التكلفة بعد تعيين الفني</p>
                            </div>

                        {{-- ===== حالة 2: فني موجود لكن لم يقدم سعراً بعد ===== --}}
                        @elseif($hasTech && !$hasPrice)
                            <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3"
                                 style="background: rgba(255,255,255,0.07); border: 1px dashed rgba(255,255,255,0.15);">
                                <div style="width:44px;height:44px;border-radius:50%;background:rgba(251,191,36,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-file-invoice-dollar text-warning" style="font-size:20px;"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white" style="font-size:13px;">
                                        الفني في الطريق لمعاينة الطلب
                                    </div>
                                    <div class="text-white-50 small mt-1">
                                        سيصلك عرض السعر بعد المعاينة وتستطيع قبوله أو رفضه
                                    </div>
                                </div>
                            </div>
                            <div class="cost-breakdown-list mb-3">
                                <div class="cost-row">
                                    <span class="cost-label">تكلفة الخدمة</span>
                                    <span class="cost-pending">⏳ بعد المعاينة</span>
                                </div>
                                <div class="cost-row">
                                    <span class="cost-label">قطع الغيار</span>
                                    <span class="cost-pending">—</span>
                                </div>
                            </div>
                            <div class="awaiting-total-box text-center pt-3"
                                 style="border-top: 1px solid rgba(255,255,255,0.12);">
                                <div class="awaiting-price-badge">
                                    <i class="fas fa-hourglass-half me-1"></i>
                                    سيظهر الإجمالي بعد تقديم التسعيرة
                                </div>
                            </div>

                        {{-- ===== حالة 3: فني موجود + سعر موجود ===== --}}
                        @else
                            <div class="cost-breakdown-list mb-3">
                                <div class="cost-row">
                                    <span class="cost-label">تكلفة الخدمة</span>
                                    <span class="cost-val">{{ number_format($serviceCost, 2) }} جنيه</span>
                                </div>
                                @if($itemsCost > 0)
                                    <div class="cost-row">
                                        <span class="cost-label">
                                            قطع الغيار
                                            <small class="text-white-50">({{ $requestData->requestItems->count() }} قطع)</small>
                                        </span>
                                        <span class="cost-val">{{ number_format($itemsCost, 2) }} جنيه</span>
                                    </div>
                                @endif
                            </div>

                            <div class="total-cost-box mb-3 pt-3 d-flex justify-content-between align-items-baseline"
                                 style="border-top: 1px solid rgba(255,255,255,0.15);">
                                <span class="total-label">الإجمالي</span>
                                <span class="total-price-tag">
                                    {{ number_format($totalAmount, 2) }}
                                    <small class="curr">جنيه</small>
                                </span>
                            </div>

                            @if($requestData->price_notes)
                                <div class="mb-3 px-3 py-2 rounded-3"
                                     style="background: rgba(255,255,255,0.07); font-size: 12.5px; color: rgba(255,255,255,0.75);">
                                    <i class="fas fa-comment-dots me-1 text-warning"></i>
                                    <strong>ملاحظة الفني:</strong> {{ $requestData->price_notes }}
                                </div>
                            @endif

                            {{-- زر الموافقة --}}
                            @if($requestData->price_status === 'pending_customer_approval')
                                <div class="mt-3 d-flex flex-column gap-2">
                                    <form method="POST"
                                          action="{{ route('requests.accept-price', \App\Helpers\EncryptionHelper::encryptId($requestData->id)) }}">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-warning w-100 rounded-pill fw-bold text-dark py-2">
                                            <i class="fas fa-check-circle me-1"></i> موافقة على السعر والبدء
                                        </button>
                                    </form>
                                    <button type="button"
                                            class="btn btn-outline-danger w-100 rounded-pill btn-sm text-white border-danger"
                                            data-bs-toggle="modal" data-bs-target="#rejectPriceModal">
                                        رفض عرض السعر
                                    </button>
                                </div>
                            @endif
                        @endif

                        <div class="d-flex align-items-center justify-content-between text-white-50 small pt-3 mt-2"
                             style="border-top: 1px solid rgba(255,255,255,0.08);">
                            <span><i class="fas fa-shield-alt text-warning me-1"></i> دفع آمن</span>
                            <span style="background:rgba(255,255,255,0.06);padding:3px 10px;border-radius:20px;font-size:11px;">
                                الدفع بعد الخدمة
                            </span>
                        </div>
                    </div>

                    <!-- 3. Review Service Section / Button -->
                    <div class="mb-3">
                        @if($requestData->status === 'completed')
                            @php
                                $hasReviewed = $requestData->reviews()->where('user_id', auth()->id())->exists();
                            @endphp
                            @if(!$hasReviewed)
                                <button class="btn btn-rate-service w-100 py-3 rounded-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                    <i class="fas fa-star text-warning me-2"></i> تقييم الخدمة
                                </button>
                                <p class="text-center text-muted small mt-2 mb-0">شاركنا رأيك لتحسين جودة الخدمات</p>
                            @else
                                <div class="p-3 bg-light rounded-4 text-center border">
                                    <i class="fas fa-check-circle text-success fs-4 mb-1"></i>
                                    <h6 class="fw-bold mb-1 text-dark">تم تقييم هذه الخدمة</h6>
                                    <p class="text-muted small mb-0">شكراً لمشاركتك تقييمك معنا!</p>
                                </div>
                            @endif
                        @else
                            <button class="btn btn-light w-100 py-3 rounded-4 fw-bold border text-muted opacity-75 cursor-not-allowed" disabled>
                                <i class="far fa-star me-2"></i> تقييم الخدمة
                            </button>
                            <p class="text-center text-muted small mt-2 mb-0">التقييم متاح بعد اكتمال الخدمة</p>
                        @endif
                    </div>

                    <!-- 4. Cancel Request Button (if still pending) -->
                    @if($requestData->status === 'pending')
                        <div class="text-center mt-3">
                            <form action="{{ route('requests.destroy', \App\Helpers\EncryptionHelper::encryptId($requestData->id)) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب نهائياً؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger text-decoration-none fw-bold small">
                                    <i class="fas fa-times-circle me-1"></i> إلغاء الطلب
                                </button>
                            </form>
                        </div>
                    @endif

                </div>

                <!-- Right Column (Main: Status Stepper, Live Map, Service Details) -->
                <div class="col-lg-8 col-md-7 order-lg-2 order-1">

                    <!-- 1. Request Status Stepper Card -->
                    <div class="glass-panel p-4 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-chart-line text-primary fs-5"></i>
                                <h5 class="fw-bold mb-0 text-dark">حالة الطلب</h5>
                            </div>
                        </div>

                        <!-- 5-Step Progress Timeline -->
                        @php
                            $stepIndex = 1;
                            if ($requestData->status === 'pending') $stepIndex = 1;
                            elseif ($requestData->status === 'approved') $stepIndex = 2;
                            elseif ($requestData->status === 'in_progress') $stepIndex = 3;
                            elseif ($requestData->status === 'completed') $stepIndex = 5;
                            elseif ($requestData->status === 'cancelled') $stepIndex = 0;
                        @endphp

                        <div class="order-stepper-container mb-4">
                            <!-- Step 1 -->
                            <div class="stepper-node {{ $stepIndex >= 1 ? 'completed' : '' }}">
                                <div class="node-circle">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="node-title">تم إرسال الطلب</div>
                                <div class="node-time">{{ $requestData->created_at->format('h:i A') }}</div>
                            </div>
                            <div class="stepper-connector {{ $stepIndex >= 2 ? 'active' : '' }}"></div>

                            <!-- Step 2 -->
                            <div class="stepper-node {{ $stepIndex >= 2 ? 'completed' : ($stepIndex == 1 && $requestData->assignedTechnician ? 'current' : '') }}">
                                <div class="node-circle">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="node-title">تم قبول الطلب</div>
                                <div class="node-time">{{ $stepIndex >= 2 ? ($requestData->updated_at ? $requestData->updated_at->format('h:i A') : '-') : '-' }}</div>
                            </div>
                            <div class="stepper-connector {{ $stepIndex >= 3 ? 'active' : '' }}"></div>

                            <!-- Step 3 -->
                            <div class="stepper-node {{ $stepIndex == 3 ? 'current' : ($stepIndex > 3 ? 'completed' : '') }}">
                                <div class="node-circle">
                                    <i class="fas fa-truck-moving"></i>
                                </div>
                                <div class="node-title">الفني في الطريق</div>
                                <div class="node-time {{ $stepIndex == 3 ? 'text-warning fw-bold' : '' }}">
                                    @if($stepIndex == 3)
                                        @if($requestData->scheduled_at)
                                            المتوقع: {{ $requestData->scheduled_at->format('h:i A') }}
                                        @else
                                            خلال 30 دقيقة
                                        @endif
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="stepper-connector {{ $stepIndex >= 4 ? 'active' : '' }}"></div>

                            <!-- Step 4 -->
                            <div class="stepper-node {{ $stepIndex == 4 ? 'current' : ($stepIndex > 4 ? 'completed' : '') }}">
                                <div class="node-circle">
                                    <i class="fas fa-wrench"></i>
                                </div>
                                <div class="node-title">بدأت الخدمة</div>
                                <div class="node-time">-</div>
                            </div>
                            <div class="stepper-connector {{ $stepIndex >= 5 ? 'active' : '' }}"></div>

                            <!-- Step 5 -->
                            <div class="stepper-node {{ $stepIndex >= 5 ? 'completed' : '' }}">
                                <div class="node-circle">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="node-title">تم الانتهاء</div>
                                <div class="node-time">-</div>
                            </div>
                        </div>

                        <!-- Status Update Notification Box -->
                        <div class="status-info-box d-flex align-items-center gap-3 p-3 rounded-4">
                            <div class="status-info-icon">
                                <i class="fas fa-info-circle text-primary fs-4"></i>
                            </div>
                            <div>
                                <strong class="text-dark d-block mb-1">تحديث الحالة:</strong>
                                <p class="mb-0 text-secondary small">
                                    @if($requestData->status === 'pending')
                                        تم استلام طلبك بنجاح، جاري مراجعة الطلب وتعيين الفني الأنسب لموقعك.
                                    @elseif($requestData->status === 'approved')
                                        تم اعتماد طلبك وتعيين الفني المختص، يستعد الفني للتحرك في الموعد المحدد.
                                    @elseif($requestData->status === 'in_progress')
                                        الفني متوجه إليك الآن، يمكنك تتبع مسار الوصول والتواصل معه مباشرة.
                                    @elseif($requestData->status === 'completed')
                                        تم إنجاز الخدمة بنجاح! نأمل أن تكون راضياً عن جودة العمل المقدم.
                                    @elseif($requestData->status === 'cancelled')
                                        تم إلغاء هذا الطلب. يمكنك إنشاء طلب جديد في أي وقت.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Live Map Card -->
                    <div class="glass-panel mb-4" style="overflow: hidden; border-radius: 20px; position: relative; padding: 0;">
                        <!-- Map Container -->
                        <div id="liveTrackingMap" style="height: 320px; width: 100%; display: block; position: relative; z-index: 1; border-radius: 0;"></div>

                        <!-- Floating Info Badge -->
                        <div class="map-floating-distance">
                            @if($requestData->assignedTechnician && in_array($requestData->status, ['in_progress', 'approved']))
                                <i class="fas fa-truck-moving text-warning me-1"></i>
                                <span>الفني في الطريق: <strong>2.5 كم</strong> (حوالي 15 دقيقة)</span>
                            @else
                                <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                <span>موقع تقديم الخدمة</span>
                            @endif
                        </div>

                        <!-- Expand Button -->
                        <button type="button" class="map-expand-btn" onclick="toggleMapFullscreen()" title="تكبير الخريطة">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>

                    <!-- 3. Service Details Card -->
                    <div class="glass-panel p-4 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                            <h5 class="fw-bold mb-0 text-dark">تفاصيل الخدمة</h5>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                <i class="fas {{ $requestData->service->icon ?? 'fa-tools' }} me-1"></i>
                                {{ $requestData->service->name ?? 'خدمة عامة' }}
                            </span>
                        </div>

                        <div class="service-details-grid">
                            <!-- Service Type -->
                            <div class="detail-block mb-3">
                                <div class="detail-label text-muted small mb-1">نوع الخدمة</div>
                                <div class="detail-value fw-bold text-dark">
                                    {{ $requestData->service->name ?? 'صيانة منزلية' }}
                                </div>
                            </div>

                            <!-- Scheduled Date & Time -->
                            <div class="detail-block mb-3">
                                <div class="detail-label text-muted small mb-1">تاريخ ووقت الموعد</div>
                                <div class="detail-value fw-bold text-dark d-flex align-items-center gap-2">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                    <span>
                                        @if($requestData->scheduled_at)
                                            {{ $requestData->scheduled_at->format('Y-m-d') }}، {{ $requestData->scheduled_at->format('h:i A') }}
                                        @else
                                            موعد فوري / بأسرع وقت
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Location / Address -->
                            <div class="detail-block mb-3">
                                <div class="detail-label text-muted small mb-1">الموقع</div>
                                <div class="detail-value fw-bold text-dark d-flex align-items-start gap-2">
                                    <i class="fas fa-home text-danger mt-1"></i>
                                    <span>{{ $requestData->address ?? 'الموقع محدد على الخريطة' }}</span>
                                </div>
                            </div>

                            <!-- Customer Notes -->
                            <div class="detail-block mb-3">
                                <div class="detail-label text-muted small mb-1">ملاحظات العميل</div>
                                <div class="customer-notes-box p-3 rounded-4 bg-light border">
                                    <p class="mb-0 text-secondary">
                                        {{ $requestData->description ?: 'لا توجد ملاحظات إضافية مسجلة للطلب.' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Attached Media Images -->
                            @if ($requestData->getMedia('requests')->count() > 0)
                                <div class="detail-block mt-3 pt-3 border-top">
                                    <div class="detail-label text-muted small mb-2">الصور والمرفقات</div>
                                    <div class="row g-2">
                                        @foreach ($requestData->getMedia('requests') as $media)
                                            <div class="col-4 col-sm-3">
                                                <a href="{{ $media->getUrl() }}" target="_blank" class="d-block attached-img-thumb rounded-3 overflow-hidden border">
                                                    <img src="{{ $media->getUrl() }}" class="img-fluid w-100 h-100 object-fit-cover" alt="مرفق الطلب">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark">تقييم الخدمة والفني</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('requests.submit-review', \App\Helpers\EncryptionHelper::encryptId($requestData->id)) }}">
                    @csrf
                    <div class="modal-body py-4 text-center">
                        <div class="rating-stars-interactive mb-3">
                            <input type="radio" name="rating" value="5" id="star5" required checked>
                            <label for="star5" title="ممتاز">★</label>
                            <input type="radio" name="rating" value="4" id="star4">
                            <label for="star4" title="جيد جداً">★</label>
                            <input type="radio" name="rating" value="3" id="star3">
                            <label for="star3" title="جيد">★</label>
                            <input type="radio" name="rating" value="2" id="star2">
                            <label for="star2" title="مقبول">★</label>
                            <input type="radio" name="rating" value="1" id="star1">
                            <label for="star1" title="سيء">★</label>
                        </div>
                        <p class="text-muted small mb-3">اختر تقييمك من 1 إلى 5 نجوم</p>
                        
                        <div class="text-start mb-2">
                            <label class="form-label fw-bold small text-dark">تعليقك (اختياري)</label>
                            <textarea name="comment" class="form-control rounded-3" rows="3" placeholder="اكتب رأيك في جودة الصيانة وسرعة الإنجاز..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-navy-action rounded-pill px-4 fw-bold">إرسال التقييم</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Price Modal -->
    <div class="modal fade" id="rejectPriceModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger">رفض عرض السعر</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('requests.reject-price', \App\Helpers\EncryptionHelper::encryptId($requestData->id)) }}">
                    @csrf
                    <div class="modal-body py-3">
                        <p class="text-muted small mb-3">يرجى توضيح سبب رفض السعر ليتم مراجعته أو إعادة تعيين الفني:</p>
                        <textarea name="customer_notes" class="form-control rounded-3" rows="4" required placeholder="السعر أعلى من المتوقع / لم يتم الاتفاق على هذه القطع..."></textarea>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">تأكيد الرفض</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .request-details-page {
            background-color: var(--bg-color, #f8fafc);
            min-height: calc(100vh - 80px);
        }

        /* Glass Panel */
        .glass-panel {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px -2px rgba(11, 95, 138, 0.05), 0 2px 6px -2px rgba(0, 0, 0, 0.03);
            transition: all 0.25s ease;
        }

        /* Quotation Hero Banner */
        .quotation-hero-banner {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 2px solid #fde68a;
            border-right: 6px solid #f59e0b;
        }

        .quote-icon-pulse {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: #fef3c7;
            color: #d97706;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid #fde68a;
            animation: pulse-ring 2s infinite;
        }

        /* 1. Technician Card */
        .technician-profile-card {
            border: 1px solid #e2e8f0;
        }

        .tech-pattern-banner {
            height: 80px;
            background: linear-gradient(135deg, #072540 0%, #0b5f8a 100%);
            position: relative;
            background-image: radial-gradient(#188ec9 1px, transparent 1px);
            background-size: 12px 12px;
        }

        .tech-avatar-wrapper {
            margin-top: -45px;
            position: relative;
            width: 86px;
            height: 86px;
            border-radius: 50%;
            border: 4px solid #ffffff;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
            background: #e2e8f0;
        }

        .tech-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .tech-specialization {
            color: #ff9500;
            font-size: 13px;
            font-weight: 700;
        }

        .tech-stat-pill {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 8px 12px;
            text-align: center;
        }

        .tech-stat-pill .stat-num {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }

        .tech-stat-pill .stat-lbl {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
        }

        .btn-navy-action {
            background: #072540;
            color: #ffffff !important;
            border: 1px solid #072540;
            transition: all 0.25s ease;
        }

        .btn-navy-action:hover {
            background: #0b5f8a;
            border-color: #0b5f8a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(7, 37, 64, 0.25);
        }

        .btn-outline-navy {
            background: #ffffff;
            color: #072540 !important;
            border: 1.5px solid #072540;
            transition: all 0.25s ease;
        }

        .btn-outline-navy:hover {
            background: #072540;
            color: #ffffff !important;
            transform: translateY(-2px);
        }

        /* 2. Cost Summary Dark Card */
        .cost-summary-card {
            background: #072540;
            color: #ffffff;
            border-radius: 24px;
            box-shadow: 0 10px 25px -5px rgba(7, 37, 64, 0.4);
            position: relative;
            overflow: hidden;
        }

        .cost-summary-card::after {
            content: '';
            position: absolute;
            bottom: -30px;
            left: -30px;
            width: 130px;
            height: 130px;
            background: radial-gradient(circle, rgba(255, 149, 0, 0.15) 0%, rgba(255, 149, 0, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .cost-summary-icon {
            font-size: 18px;
        }

        .cost-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: #cbd5e1;
            margin-bottom: 8px;
        }

        .cost-row .cost-val {
            font-weight: 700;
            color: #ffffff;
        }

        .total-cost-box .total-label {
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
        }

        .total-cost-box .total-price-tag {
            font-size: 24px;
            font-weight: 900;
            color: #ffb703;
        }

        .total-cost-box .curr {
            font-size: 13px;
            color: #cbd5e1;
            font-weight: normal;
        }

        .badge-safe-pay, .badge-post-pay {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
        }

        /* 3. Rate Service Button */
        .btn-rate-service {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #0f172a;
            font-size: 14px;
            transition: all 0.25s ease;
        }

        .btn-rate-service:hover {
            border-color: #ffb703;
            background: #fffdf5;
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(255, 183, 3, 0.15);
        }

        /* 4. Stepper Container */
        .order-stepper-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            padding: 10px 0;
        }

        .stepper-node {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            z-index: 2;
            min-width: 65px;
        }

        .stepper-node .node-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #f1f5f9;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-bottom: 8px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .stepper-node.completed .node-circle {
            background: #072540;
            color: #ffffff;
        }

        .stepper-node.current .node-circle {
            background: #ff9500;
            color: #ffffff;
            box-shadow: 0 0 0 6px rgba(255, 149, 0, 0.2);
            animation: pulse-step 2s infinite;
        }

        @keyframes pulse-step {
            0% { transform: scale(1); }
            50% { transform: scale(1.08); }
            100% { transform: scale(1); }
        }

        .stepper-node .node-title {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .stepper-node .node-time {
            font-size: 10px;
            color: #64748b;
        }

        .stepper-connector {
            flex-grow: 1;
            height: 3px;
            background: #e2e8f0;
            margin: 0 4px;
            margin-bottom: 30px;
            z-index: 1;
            transition: background 0.3s ease;
        }

        .stepper-connector.active {
            background: #072540;
        }

        /* Status Info Box */
        .status-info-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        /* Map UI */
        .map-floating-distance {
            position: absolute;
            bottom: 16px;
            left: 16px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
            z-index: 60;
            border: 1px solid rgba(0, 0, 0, 0.06);
            pointer-events: none;
        }

        .map-expand-btn {
            position: absolute;
            bottom: 16px;
            left: 16px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            z-index: 400;
            transition: all 0.2s ease;
        }

        .map-expand-btn:hover {
            background: #072540;
            color: #ffffff;
        }

        /* Rating Stars Interactive */
        .rating-stars-interactive {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 8px;
        }

        .rating-stars-interactive input {
            display: none;
        }

        .rating-stars-interactive label {
            font-size: 42px;
            color: #cbd5e1;
            cursor: pointer;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .rating-stars-interactive input:checked ~ label,
        .rating-stars-interactive label:hover,
        .rating-stars-interactive label:hover ~ label {
            color: #f59e0b;
            transform: scale(1.1);
        }

        .attached-img-thumb {
            height: 80px;
        }

        @media (max-width: 768px) {
            .order-stepper-container {
                overflow-x: auto;
                padding-bottom: 10px;
            }
            .stepper-node {
                min-width: 60px;
            }
            .stepper-node .node-circle {
                width: 36px;
                height: 36px;
                font-size: 13px;
            }
            .stepper-node .node-title {
                font-size: 10px;
            }
        }

        /* === Cost Summary Card === */
        .cost-summary-card {
            background: linear-gradient(135deg, #0a1628 0%, #0f2341 60%, #072540 100%);
            border-radius: 20px;
            color: #fff;
            box-shadow: 0 8px 32px rgba(7, 37, 64, 0.4);
        }

        .cost-summary-icon {
            font-size: 22px;
        }

        .cost-breakdown-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .cost-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }

        .cost-label {
            color: rgba(255,255,255,0.65);
        }

        .cost-val {
            color: #fff;
            font-weight: 600;
        }

        /* بديل القيمة لما مافيش سعر */
        .cost-pending {
            color: #f59e0b;
            font-size: 12px;
            font-weight: 600;
            background: rgba(245,158,11,0.12);
            padding: 2px 10px;
            border-radius: 30px;
        }

        /* مربع الإجمالي لما في سعر */
        .total-cost-box .total-label {
            color: rgba(255,255,255,0.8);
            font-size: 15px;
            font-weight: 600;
        }

        .total-price-tag {
            font-size: 26px;
            font-weight: 800;
            color: #fbbf24;
            line-height: 1;
        }

        .total-price-tag .curr {
            font-size: 14px;
            font-weight: 600;
            color: rgba(255,255,255,0.7);
            margin-right: 4px;
        }

        /* badge لما الإجمالي ما ظهرش بعد */
        .awaiting-total-box {
            /* empty container for centering */
        }

        .awaiting-price-badge {
            display: inline-block;
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px dashed rgba(245,158,11,0.4);
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 12.5px;
            font-weight: 600;
        }

        /* Payment method badges */
        .badge-safe-pay {
            font-size: 12px;
            font-weight: 600;
        }

        .badge-post-pay {
            font-size: 11px;
            background: rgba(255,255,255,0.08);
            padding: 4px 10px;
            border-radius: 20px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ---- الإحداثيات: مصر (القاهرة) كافتراضي إذا لم تُحفظ ----
            const userLat = parseFloat('{{ $requestData->latitude ?? "30.0444" }}') || 30.0444;
            const userLng = parseFloat('{{ $requestData->longitude ?? "31.2357" }}') || 31.2357;

            // ---- إنشاء الخريطة ----
            const mapEl = document.getElementById('liveTrackingMap');
            if (!mapEl) return;

            const map = L.map('liveTrackingMap', {
                zoomControl: true,
                attributionControl: false,
                scrollWheelZoom: true
            }).setView([userLat, userLng], 14);

            // ---- طبقة التايلز (OpenStreetMap) ----
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // ---- أيقونة موقع العميل ----
            const userIcon = L.divIcon({
                className: '',
                html: `<div style="
                    background: #072540;
                    color: #fff;
                    width: 38px;
                    height: 38px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border: 3px solid #fff;
                    box-shadow: 0 4px 12px rgba(7,37,64,0.4);
                "><i class="fas fa-home" style="font-size:16px;"></i></div>`,
                iconSize: [38, 38],
                iconAnchor: [19, 19],
                popupAnchor: [0, -22]
            });

            L.marker([userLat, userLng], { icon: userIcon })
                .addTo(map)
                .bindPopup('<strong>📍 موقع تقديم الخدمة</strong>', { maxWidth: 160 })
                .openPopup();

            @if($requestData->assignedTechnician && in_array($requestData->status, ['in_progress', 'approved']))
                // ---- موقع الفني: إذا كان محفوظاً في قاعدة البيانات نستخدمه، وإلا نحدد موقعاً قريباً منه تلقائياً ----
                const realTechLat = parseFloat('{{ $requestData->assignedTechnician->latitude ?? "" }}');
                const realTechLng = parseFloat('{{ $requestData->assignedTechnician->longitude ?? "" }}');

                const techLat = (!isNaN(realTechLat) && realTechLat !== 0) ? realTechLat : (userLat + 0.012);
                const techLng = (!isNaN(realTechLng) && realTechLng !== 0) ? realTechLng : (userLng + 0.009);

                const techIcon = L.divIcon({
                    className: '',
                    html: `<div style="
                        background: #ff9500;
                        color: #fff;
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border: 3px solid #fff;
                        box-shadow: 0 4px 14px rgba(255,149,0,0.5);
                    "><i class="fas fa-truck-moving" style="font-size:16px;"></i></div>`,
                    iconSize: [40, 40],
                    iconAnchor: [20, 20],
                    popupAnchor: [0, -24]
                });

                L.marker([techLat, techLng], { icon: techIcon })
                    .addTo(map)
                    .bindPopup('<strong>🔧 {{ $requestData->assignedTechnician->user->name ?? "الفني" }}</strong><br><small>في الطريق إليك</small>');

                // ---- خط المسار بين الفني والعميل ----
                const midLat = (techLat + userLat) / 2 + 0.002;
                const midLng = (techLng + userLng) / 2 - 0.002;
                L.polyline(
                    [[techLat, techLng], [midLat, midLng], [userLat, userLng]],
                    { color: '#072540', weight: 4, opacity: 0.85, dashArray: '8, 8' }
                ).addTo(map);

                // اضبط زوم وحدود الخريطة تلقائياً لتظهر النقطتين بوضوح
                map.fitBounds([[userLat, userLng], [techLat, techLng]], { padding: [50, 50] });
            @endif

            // ---- مهم: أعِد حساب الحجم بعد الرسم ----
            setTimeout(() => {
                map.invalidateSize();
            }, 200);

            window.liveMapInstance = map;
        });

        // ---- تكبير الخريطة ----
        function toggleMapFullscreen() {
            const mapContainer = document.getElementById('liveTrackingMap');
            if (!document.fullscreenElement) {
                mapContainer.requestFullscreen && mapContainer.requestFullscreen();
            } else {
                document.exitFullscreen && document.exitFullscreen();
            }
            setTimeout(() => {
                window.liveMapInstance && window.liveMapInstance.invalidateSize();
            }, 400);
        }
    </script>
@endpush
