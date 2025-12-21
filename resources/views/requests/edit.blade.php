@extends('layouts.mobile')

@section('title', 'تعديل الطلب')

@section('content')

<!-- Mobile Header -->
<div class="app-header glass d-md-none">
    <a href="{{ route('dashboard') }}" class="header-icon-btn">
        <i class="fas fa-arrow-right"></i>
    </a>
    <div class="app-logo">
        <i class="fas fa-plus-circle me-1"></i> طلب جديد
    </div>
    <div class="header-icon-btn"></div>
</div>

<!-- Content -->
<div class="app-content fade-in">
    
    <!-- Page Title -->
    <div class="mb-4">
        <h4 class="fw-bold mb-2" style="color: var(--text-primary);">
            <i class="fas fa-edit text-warning ms-1"></i> تعديل الطلب #{{ $requestData->id }}
        </h4>
        <p class="text-muted mb-0">عدل بيانات طلبك</p>
    </div>

    <!-- Request Form Card -->
    <div class="form-card">
        <form action="{{ route('requests.update', $requestData->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Service Selection -->
            <div class="mb-4">
                <label for="service_id" class="form-label">
                    <i class="fas fa-tools text-primary me-1"></i> الخدمة المطلوبة
                </label>
                <select name="service_id" id="service_id" class="form-select modern-select @error('service_id') is-invalid @enderror" required>
                    <option value="" disabled>اختر الخدمة</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ (old('service_id', $requestData->service_id) == $service->id) ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
                @error('service_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Address -->
            <div class="mb-4">
                <label for="address" class="form-label">
                    <i class="fas fa-map-marker-alt text-danger me-1"></i> العنوان
                </label>
                <div class="position-relative">
                    <input type="text" name="address" id="address" class="form-control modern-input @error('address') is-invalid @enderror" 
                           placeholder="أدخل عنوان الخدمة" value="{{ old('address', $requestData->address) }}" required>
                    <button type="button" id="getLocationBtn" class="btn btn-sm btn-primary location-btn">
                        <i class="fas fa-crosshairs me-1"></i> تحديد موقعي
                    </button>
                </div>
                <small class="text-muted" id="locationStatus"></small>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Scheduled Date/Time -->
            <div class="mb-4">
                <label for="scheduled_at" class="form-label">
                    <i class="fas fa-calendar-alt text-success me-1"></i> الموعد المفضل (اختياري)
                </label>
                <input type="datetime-local" name="scheduled_at" id="scheduled_at" 
                       class="form-control modern-input @error('scheduled_at') is-invalid @enderror" 
                       value="{{ old('scheduled_at', $requestData->scheduled_at ? $requestData->scheduled_at->format('Y-m-d\TH:i') : '') }}">
                @error('scheduled_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="form-label">
                    <i class="fas fa-comment-dots text-info me-1"></i> وصف المشكلة (اختياري)
                </label>
                <textarea name="description" id="description" rows="4" 
                          class="form-control modern-textarea @error('description') is-invalid @enderror" 
                          placeholder="اكتب تفاصيل إضافية عن المشكلة أو الخدمة المطلوبة...">{{ old('description', $requestData->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Current Images -->
            @if($requestData->getMedia('requests')->count() > 0)
            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-images text-info me-1"></i> الصور الحالية
                </label>
                <div class="row g-3">
                    @foreach($requestData->getMedia('requests') as $media)
                    <div class="col-4 col-md-3" id="media-{{ $media->id }}">
                        <div class="position-relative">
                            <img src="{{ $media->getUrl() }}" class="img-fluid rounded" style="aspect-ratio: 1; object-fit: cover;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" 
                                    onclick="deleteMedia({{ $media->id }})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Images Upload -->
            <div class="mb-4">
                <label for="images" class="form-label">
                    <i class="fas fa-camera text-secondary me-1"></i> إضافة صور جديدة (اختياري)
                </label>
                <input type="file" name="images[]" id="images" multiple accept="image/*"
                       class="form-control modern-input @error('images') is-invalid @enderror">
                <div class="form-text text-muted small mt-1">يمكنك إضافة صور جديدة (JPG, PNG)</div>
                @error('images')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @error('images.*')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-warning btn-modern">
                    <i class="fas fa-save me-2"></i> حفظ التعديلات
                </button>
                <a href="{{ route('requests.show', $requestData->id) }}" class="btn btn-outline-secondary btn-modern">
                    <i class="fas fa-times me-2"></i> إلغاء
                </a>
            </div>

        </form>
    </div>

</div>

@endsection

@push('styles')
<style>
    /* Form Card */
    .form-card {
        background: var(--surface-color);
        border-radius: var(--radius-xl);
        padding: 28px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
    }

    /* Form Labels */
    .form-label {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 10px;
        display: block;
    }

    /* Modern Input & Select */
    .modern-input, .modern-select, .modern-textarea {
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 14px 18px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: var(--bg-color);
        color: var(--text-primary);
    }

    .modern-input:focus, .modern-select:focus, .modern-textarea:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        outline: none;
        background: #fff;
    }

    .modern-textarea {
        resize: vertical;
        min-height: 120px;
    }

    /* Modern Buttons */
    .btn-modern {
        padding: 14px 24px;
        font-size: 16px;
        font-weight: 700;
        border-radius: var(--radius-lg);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .btn-primary.btn-modern {
        background: linear-gradient(135deg, var(--primary-color) 0%, #992626 100%);
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(204, 51, 51, 0.3);
    }

    .btn-primary.btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }

    .btn-primary.btn-modern:active {
        transform: translateY(0);
    }

    .btn-outline-secondary.btn-modern {
        border-color: var(--border-color);
        color: var(--text-secondary);
    }

    .btn-outline-secondary.btn-modern:hover {
        background: var(--surface-color);
        border-color: var(--text-muted);
    }

    /* Invalid Feedback */
    .invalid-feedback {
        font-size: 13px;
        font-weight: 600;
        margin-top: 8px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-card {
            padding: 20px;
        }
    }

    /* Location Button */
    .location-btn {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        padding: 8px 16px;
        font-size: 13px;
        border-radius: var(--radius-md);
        white-space: nowrap;
        z-index: 10;
    }

    #address {
        padding-left: 150px;
    }

    #locationStatus {
        display: block;
        margin-top: 8px;
        font-size: 12px;
    }
</style>
@endpush

@push('scripts')
<script>
function deleteMedia(mediaId) {
    if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
        fetch(`/requests/{{ $requestData->id }}/media/${mediaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`media-${mediaId}`).remove();
            } else {
                alert('حدث خطأ أثناء حذف الصورة');
            }
        })
        .catch(error => {
            alert('حدث خطأ أثناء حذف الصورة');
        });
    }
}
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const getLocationBtn = document.getElementById('getLocationBtn');
    const addressInput = document.getElementById('address');
    const locationStatus = document.getElementById('locationStatus');

    function getLocation(highAccuracy = true) {
        if (!navigator.geolocation) {
            showError('المتصفح لا يدعم تحديد الموقع');
            return;
        }

        // Show loading state
        getLocationBtn.disabled = true;
        getLocationBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري التحديد...';
        locationStatus.textContent = highAccuracy ? 'جاري الحصول على موقعك بدقة عالية...' : 'جاري المحاولة بنمط توفير الطاقة...';
        locationStatus.className = 'text-info';

        navigator.geolocation.getCurrentPosition(
            // Success callback
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                fetchAddress(lat, lng);
            },
            // Error callback
            function(error) {
                // If high accuracy failed (timeout or other), try low accuracy
                if (highAccuracy && (error.code === error.TIMEOUT || error.code === error.POSITION_UNAVAILABLE)) {
                    console.log('High accuracy failed, trying low accuracy...');
                    getLocation(false); // Retry with low accuracy
                    return;
                }
                
                handleError(error);
            },
            // Options
            {
                enableHighAccuracy: highAccuracy,
                timeout: 30000, // Increased to 30 seconds
                maximumAge: 0
            }
        );
    }

    function fetchAddress(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=ar`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    addressInput.value = data.display_name;
                    showSuccess('✓ تم تحديد الموقع بنجاح');
                } else {
                    addressInput.value = `${lat}, ${lng}`;
                    showWarning('تم الحصول على الإحداثيات فقط');
                }
            })
            .catch(error => {
                addressInput.value = `${lat}, ${lng}`;
                showWarning('تم الحصول على الإحداثيات (فشل جلب العنوان)');
            })
            .finally(() => {
                resetButton();
            });
    }

    function handleError(error) {
        let errorMessage = '';
        switch(error.code) {
            case error.PERMISSION_DENIED:
                errorMessage = 'يرجى السماح للموقع بالوصول إلى موقعك من إعدادات المتصفح';
                break;
            case error.POSITION_UNAVAILABLE:
                errorMessage = 'تعذر الحصول على معلومات الموقع. تأكد من تفعيل GPS';
                break;
            case error.TIMEOUT:
                errorMessage = 'استغرق تحديد الموقع وقتاً طويلاً. حاول مرة أخرى';
                break;
            default:
                errorMessage = 'حدث خطأ غير معروف';
        }
        showError(errorMessage);
        resetButton();
    }

    function showSuccess(msg) {
        locationStatus.textContent = msg;
        locationStatus.className = 'text-success fw-bold';
    }

    function showWarning(msg) {
        locationStatus.textContent = msg;
        locationStatus.className = 'text-warning fw-bold';
    }

    function showError(msg) {
        locationStatus.textContent = msg;
        locationStatus.className = 'text-danger fw-bold';
    }

    function resetButton() {
        getLocationBtn.disabled = false;
        getLocationBtn.innerHTML = '<i class="fas fa-crosshairs me-1"></i> تحديد موقعي';
    }

    getLocationBtn.addEventListener('click', function() {
        getLocation(true); // Start with high accuracy
    });
});
</script>
