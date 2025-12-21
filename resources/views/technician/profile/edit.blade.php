@extends('layouts.admin')

@section('title', 'تعديل الملف الشخصي')
@section('header_title', 'الملف الشخصي')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">تعديل الملف الشخصي</h4>
        <p class="text-muted mb-0">قم بتحديث بياناتك الشخصية</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('technician.profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <!-- User Info (Read-only) -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">المعلومات الأساسية</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">الاسم</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">رقم الهاتف</label>
                                <input type="text" class="form-control font-monospace" value="{{ Auth::user()->phone }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">التخصص</label>
                                <input type="text" class="form-control" value="{{ $technician->specialization->name ?? 'غير محدد' }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">التقييم</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="text" class="form-control" value="{{ $technician->rating }}" disabled style="max-width: 80px;">
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $technician->rating)
                                                <i class="fa-solid fa-star"></i>
                                            @else
                                                <i class="fa-regular fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Bio -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">نبذة عني</h6>
                        <label for="bio" class="form-label text-muted small">نبذة تعريفية</label>
                        <textarea 
                            name="bio" 
                            id="bio" 
                            class="form-control @error('bio') is-invalid @enderror" 
                            rows="4" 
                            placeholder="اكتب نبذة مختصرة عنك وعن خبراتك...">{{ old('bio', $technician->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">الحد الأقصى 1000 حرف</small>
                    </div>

                    <hr class="my-4">

                    <!-- Location Info -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">معلومات الموقع</h6>
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            <strong>مهم:</strong> إضافة موقعك يساعد في عرض الطلبات الأقرب إليك أولاً
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">العنوان الكامل</label>
                            <textarea 
                                name="address" 
                                id="address" 
                                class="form-control @error('address') is-invalid @enderror" 
                                rows="2" 
                                placeholder="مثال: شارع الجامعة، المعادي، القاهرة">{{ old('address', $technician->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="latitude" class="form-label">خط العرض (Latitude)</label>
                                <input 
                                    type="number" 
                                    step="0.00000001" 
                                    name="latitude" 
                                    id="latitude" 
                                    class="form-control font-monospace @error('latitude') is-invalid @enderror" 
                                    value="{{ old('latitude', $technician->latitude) }}"
                                    placeholder="30.0444196">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="longitude" class="form-label">خط الطول (Longitude)</label>
                                <input 
                                    type="number" 
                                    step="0.00000001" 
                                    name="longitude" 
                                    id="longitude" 
                                    class="form-control font-monospace @error('longitude') is-invalid @enderror" 
                                    value="{{ old('longitude', $technician->longitude) }}"
                                    placeholder="31.2357116">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fa-solid fa-lightbulb me-1"></i>
                                يمكنك الحصول على الإحداثيات من 
                                <a href="https://www.google.com/maps" target="_blank" class="text-decoration-none">Google Maps</a>
                                بالنقر بزر الماوس الأيمن على موقعك واختيار "نسخ الإحداثيات"
                            </small>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('dashboard') }}" class="btn btn-light px-4">إلغاء</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa-solid fa-save me-2"></i>حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">إحصائيات</h6>
                
                <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-light rounded">
                    <div class="avatar rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fa-solid fa-check-circle fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">المهام المكتملة</div>
                        <div class="h4 mb-0 fw-bold">{{ $technician->completed_tasks }}</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded">
                    <div class="avatar rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fa-solid fa-star fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">التقييم</div>
                        <div class="h4 mb-0 fw-bold">{{ number_format($technician->rating, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">الحالة</h6>
                <div class="d-flex align-items-center gap-2">
                    @if($technician->availability_status === 'available')
                        <span class="badge bg-success-subtle text-success px-3 py-2">
                            <i class="fa-solid fa-circle-check me-1"></i>متاح
                        </span>
                    @elseif($technician->availability_status === 'busy')
                        <span class="badge bg-warning-subtle text-warning px-3 py-2">
                            <i class="fa-solid fa-clock me-1"></i>مشغول
                        </span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                            <i class="fa-solid fa-moon me-1"></i>في إجازة
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
