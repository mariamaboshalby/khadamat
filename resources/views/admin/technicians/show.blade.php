@extends('layouts.admin')

@section('title', 'عرض بيانات الفني')
@section('header_title', 'عرض بيانات الفني')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 28px; margin: 0 0 10px; color: #2d3748; font-weight: 700;">عرض بيانات الفني</h1>
            <p style="color: #718096; margin: 0;">عرض بيانات الفني: {{ $technician->user->name ?? 'غير معروف' }}</p>
        </div>
        <a href="{{ route('admin.techs.index') }}" class="btn btn-outline-secondary">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            عودة للقائمة
        </a>
    </div>
    
    <div class="row g-4">
        <!-- Personal Information Card -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #ff7373 0%, #cc3333 100%); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-left: 16px;">
                            <i class="fa-solid fa-user text-white fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1 fw-bold text-dark">المعلومات الشخصية</h5>
                            <p class="text-muted mb-0 small">بيانات أساسية للفني</p>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="text-muted small fw-bold">الاسم الكامل</label>
                            <p class="mb-0 fw-bold">{{ $technician->user->name ?? 'غير متوفر' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold">البريد الإلكتروني</label>
                            <p class="mb-0 fw-bold font-monospace small">{{ $technician->user->email ?? 'غير متوفر' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold">رقم الهاتف</label>
                            <p class="mb-0 fw-bold font-monospace">{{ $technician->user->phone ?? 'غير متوفر' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small fw-bold">الدور</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $technician->user->hasRole('admin') ? 'danger' : 'primary' }} bg-opacity-10 text-{{ $technician->user->hasRole('admin') ? 'danger' : 'primary' }} rounded-pill px-3">
                                    {{ $technician->user->hasRole('admin') ? 'أدمن' : 'فني' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Work Information Card -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-left: 16px;">
                            <i class="fa-solid fa-briefcase text-white fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1 fw-bold text-dark">المعلومات الوظيفية</h5>
                            <p class="text-muted mb-0 small">بيانات العمل والتخصص</p>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold">التخصص</label>
                            <p class="mb-0">
                                @if($technician->specialization)
                                    <span class="badge bg-light text-dark border">{{ $technician->specialization->name }}</span>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold">حالة التوفر</label>
                            <p class="mb-0">
                                @if($technician->availability_status == 'available')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3">متاح</span>
                                @elseif($technician->availability_status == 'busy')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3">مشغول</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3">في إجازة</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold">التقييم</label>
                            <p class="mb-0">
                                <div class="d-flex align-items-center gap-1 text-warning">
                                    <i class="fa-solid fa-star"></i>
                                    <span class="text-dark fw-bold">{{ number_format($technician->rating, 1) }}</span>
                                </div>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold">المهام المكتملة</label>
                            <p class="mb-0">
                                <span class="fw-bold text-dark">{{ $technician->completed_tasks }}</span>
                                <small class="text-muted me-1">مهمة</small>
                            </p>
                        </div>
                        @if($technician->badges->count() > 0)
                        <div class="col-12">
                            <label class="text-muted small fw-bold">الشارات</label>
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach($technician->badges as $badge)
                                    <span class="badge" style="background: {{ $badge->badge_color }}; font-size: 13px; padding: 6px 12px;">
                                        <i class="fa-solid {{ $badge->badge_icon }} me-1"></i> {{ $badge->badge_name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Information Card -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-left: 16px;">
                            <i class="fa-solid fa-location-dot text-white fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1 fw-bold text-dark">الموقع الجغرافي</h5>
                            <p class="text-muted mb-0 small">معلومات الموقع والإحداثيات</p>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="text-muted small fw-bold">العنوان</label>
                            <p class="mb-0 fw-bold">{{ $technician->address ?: 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold">خط العرض</label>
                            <p class="mb-0 font-monospace small fw-bold">{{ $technician->latitude ?: 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold">خط الطول</label>
                            <p class="mb-0 font-monospace small fw-bold">{{ $technician->longitude ?: 'غير محدد' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information Card -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-left: 16px;">
                            <i class="fa-solid fa-info-circle text-white fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1 fw-bold text-dark">معلومات إضافية</h5>
                            <p class="text-muted mb-0 small">نبذة وتفاصيل أخرى</p>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="text-muted small fw-bold">نبذة عن الفني</label>
                            <div class="bg-light rounded p-3">
                                <p class="mb-0">{{ $technician->bio ?: 'لا توجد نبذة متاحة' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold">تاريخ الإنشاء</label>
                            <p class="mb-0 fw-bold">{{ $technician->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold">آخر تحديث</label>
                            <p class="mb-0 fw-bold">{{ $technician->updated_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-4 d-flex gap-3 justify-content-end">
        <a href="{{ route('admin.techs.edit', $technician) }}" 
           class="btn btn-primary px-4 fw-bold"
           style="background: linear-gradient(135deg, #ff7373 0%, #cc3333 100%); border: none; box-shadow: 0 4px 6px -1px rgba(102, 126, 234, 0.3);">
            <i class="fa-solid fa-pen-to-square me-2"></i> تعديل البيانات
        </a>
        <form method="POST" action="{{ route('admin.techs.destroy', $technician) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الفني؟')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger px-4 fw-bold">
                <i class="fa-solid fa-trash me-2"></i> حذف الفني
            </button>
        </form>
    </div>
</div>
@endsection
