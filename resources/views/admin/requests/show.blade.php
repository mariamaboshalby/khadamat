@extends('layouts.admin')

@section('title', "تفاصيل الطلب #{$requestModel->id}")
@section('header_title', 'تفاصيل الطلب')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">تفاصيل الطلب #{{ $requestModel->id }}</h4>
            <p class="text-muted mb-0">عرض كامل تفاصيل الطلب</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.requests.invoice', $requestModel->id) }}" class="btn btn-success rounded-pill px-4" target="_blank">
                <i class="fa-solid fa-file-invoice me-2"></i> عرض الفاتورة
            </a>
            <a href="{{ route('admin.requests.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fa-solid fa-arrow-right me-2"></i> رجوع
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Customer Info Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="avatar rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-user fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">بيانات العميل</h6>
                            <small class="text-muted">معلومات صاحب الطلب</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small mb-1">الاسم</label>
                        <p class="fw-bold mb-0">{{ $requestModel->user->name ?? 'غير معروف' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small mb-1">رقم الهاتف</label>
                        <p class="fw-bold mb-0 font-monospace">{{ $requestModel->user->phone ?? '---' }}</p>
                    </div>

                    <div>
                        <label class="text-muted small mb-1">تاريخ التسجيل</label>
                        <p class="fw-bold mb-0">{{ $requestModel->created_at->format('Y-m-d') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Details Card -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="avatar rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-clipboard-list fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">تفاصيل الطلب</h6>
                            <small class="text-muted">معلومات الخدمة المطلوبة</small>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">الخدمة</label>
                            <p class="fw-bold mb-0">
                                <span class="badge bg-light text-dark border px-3 py-2">
                                    {{ $requestModel->service->name ?? 'خدمة عامة' }}
                                </span>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1">حالة الطلب</label>
                            <p class="mb-0">
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
                                @endphp
                                <span class="badge bg-{{ $statusColors[$requestModel->status] ?? 'secondary' }} px-3 py-2">
                                    {{ $statusLabels[$requestModel->status] ?? $requestModel->status }}
                                </span>
                            </p>
                        </div>

                        @if ($requestModel->scheduled_at)
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">الموعد المحدد</label>
                                <p class="fw-bold mb-0">
                                    <i class="fa-solid fa-calendar-days text-primary me-2"></i>
                                    {{ $requestModel->scheduled_at->format('Y-m-d H:i') }}
                                </p>
                            </div>
                        @endif

                        <div class="col-12">
                            <label class="text-muted small mb-1">العنوان</label>
                            <p class="fw-bold mb-0">
                                <i class="fa-solid fa-location-dot text-danger me-2"></i>
                                {{ $requestModel->address }}
                            </p>
                        </div>

                        @if ($requestModel->description)
                            <div class="col-12">
                                <label class="text-muted small mb-1">الوصف</label>
                                <div class="bg-light rounded p-3">
                                    <p class="mb-0">{{ $requestModel->description }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Images Card -->
        @if ($requestModel->getMedia('requests')->count() > 0)
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="avatar rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="fa-solid fa-images fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">الصور المرفقة</h6>
                                <small class="text-muted">{{ $requestModel->getMedia('requests')->count() }} صورة</small>
                            </div>
                        </div>

                        <div class="row g-3">
                            @foreach ($requestModel->getMedia('requests') as $media)
                                <div class="col-lg-3 col-md-4 col-6">
                                    <a href="{{ $media->getUrl() }}" target="_blank"
                                        class="d-block overflow-hidden rounded" style="aspect-ratio: 1/1;">
                                        <img src="{{ $media->getUrl() }}" class="img-fluid w-100 h-100"
                                            style="object-fit: cover; transition: transform 0.3s ease;"
                                            onmouseover="this.style.transform='scale(1.05)'"
                                            onmouseout="this.style.transform='scale(1)'" alt="صورة الطلب">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
