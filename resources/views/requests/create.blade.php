@extends('layouts.mobile')

@section('title', 'إضافة طلب جديد')

@section('content')

<div class="container py-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">إضافة طلب جديد</h4>
            <small class="text-muted">تسجيل بيانات طلب جديد في النظام</small>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-light rounded-pill px-3">
            <i class="fas fa-arrow-right"></i> عودة
        </a>
    </div>

    <!-- Main Card -->
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

        <!-- Card Header -->
        <div class="card-header text-white border-0 "
             style="background: linear-gradient(135deg, #cc3333,#ff7373); height:auto">
            <div class="d-flex justify-content-between align-items-center m-1">
                <div>
                    <h5 class="fw-bold mb-1 " style="font-size: 30px;">بيانات الطلب</h5>
                    <small  style="font-size: 20px;">يرجى ملء جميع الحقول المطلوبة بدقة</small>
                </div>
                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                    <i class="fas fa-clipboard-list fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body p-4">

            <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Section 01 -->
                <div class="mb-4">
                    <h6 class="fw-bold text-success mb-3">
                        <span class="me-1">01.</span> معلومات الطلب
                    </h6>

                    <!-- Service -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">الخدمة المطلوبة *</label>
                        <select name="service_id" class="form-select" required>
                            <option disabled selected>اختر الخدمة</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">العنوان *</label>
                        <div class="input-group">
                            <input type="text" id="address" name="address"
                                   class="form-control" placeholder="أدخل العنوان" required>
                            <button type="button" id="getLocationBtn" class="btn btn-outline-danger">
                                <i class="fas fa-crosshairs"></i>
                            </button>
                        </div>
                        <small id="locationStatus" class="form-text"></small>
                    </div>
                </div>

                <!-- Section 02 -->
                <div class="mb-4">
                    <h6 class="fw-bold text-success mb-3">
                        <span class="me-1">02.</span> تفاصيل إضافية
                    </h6>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">الموعد المفضل</label>
                        <input type="datetime-local" name="scheduled_at" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">وصف المشكلة</label>
                        <textarea name="description" rows="3"
                                  class="form-control"
                                  placeholder="اكتب تفاصيل إضافية..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">صور (اختياري)</label>
                        <input type="file" name="images[]" multiple class="form-control">
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2">
                    <button class="btn btn-danger px-4 rounded-pill fw-bold">
                        <i class="fas fa-paper-plane"></i> إرسال الطلب
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-light border rounded-pill px-4">
                        إلغاء
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const locationBtn = document.getElementById('getLocationBtn');
    const addressInput = document.getElementById('address');
    const statusText = document.getElementById('locationStatus');

    function setStatus(message, type = 'info') {
        statusText.textContent = message;
        statusText.className = 'form-text text-' + type;
    }

    function resetButton() {
        locationBtn.disabled = false;
        locationBtn.innerHTML = '<i class="fas fa-crosshairs"></i>';
    }

    function getAddressFromCoords(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=ar`)
            .then(response => response.json())
            .then(data => {
                addressInput.value = data.display_name ?? `${lat}, ${lng}`;
                setStatus('تم تحديد الموقع بنجاح', 'success');
            })
            .catch(() => {
                addressInput.value = `${lat}, ${lng}`;
                setStatus('تم الحصول على الإحداثيات فقط', 'warning');
            })
            .finally(() => {
                resetButton();
            });
    }

    function handleLocationError(error) {
        let message = 'حدث خطأ أثناء تحديد الموقع';

        switch (error.code) {
            case error.PERMISSION_DENIED:
                message = 'يرجى السماح بالوصول إلى الموقع';
                break;
            case error.POSITION_UNAVAILABLE:
                message = 'تعذر الحصول على الموقع';
                break;
            case error.TIMEOUT:
                message = 'انتهى وقت تحديد الموقع';
                break;
        }

        setStatus(message, 'danger');
        resetButton();
    }

    locationBtn.addEventListener('click', function () {

        if (!navigator.geolocation) {
            setStatus('المتصفح لا يدعم تحديد الموقع', 'danger');
            return;
        }

        locationBtn.disabled = true;
        locationBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        setStatus('جاري تحديد موقعك...');

        navigator.geolocation.getCurrentPosition(
            function (position) {
                getAddressFromCoords(
                    position.coords.latitude,
                    position.coords.longitude
                );
            },
            function (error) {
                handleLocationError(error);
            },
            {
                enableHighAccuracy: true,
                timeout: 20000,
                maximumAge: 0
            }
        );
    });

});
</script>
@endpush
