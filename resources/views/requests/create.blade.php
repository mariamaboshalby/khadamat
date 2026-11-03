@extends('layouts.mobile')

@section('title', 'إضافة طلب جديد')

@section('content')

<style>
    .req-wrap { max-width: 720px; margin: 0 auto; padding: 8px 14px 40px; }
    .req-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .req-top h1 { font-size: 20px; font-weight: 800; color: #072540; margin: 0; }
    .req-top p { font-size: 13px; color: #64748b; margin: 3px 0 0; }
    .req-back { color: #0b5f8a; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 6px; }
    .req-card { background: #fff; border: 1px solid #e6ecf2; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(7,37,64,.06); }
    .req-hero { background: linear-gradient(135deg, #0b5f8a 0%, #188ec9 100%); color: #fff; padding: 16px 20px; display: flex; align-items: center; gap: 14px; }
    .req-hero .ic { width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,.18); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
    .req-hero h5 { margin: 0; font-size: 17px; font-weight: 800; }
    .req-hero small { opacity: .9; font-size: 12.5px; }
    .req-body { padding: 20px; }
    .req-sec { margin-bottom: 20px; }
    .req-sec-title { font-size: 13px; font-weight: 800; color: #0b5f8a; margin: 0 0 12px; padding-bottom: 8px; border-bottom: 1px solid #eef2f6; }
    .req-sec-title .num { color: #ff8a00; margin-inline-end: 6px; }
    .req-label { font-size: 13.5px; font-weight: 600; color: #334155; margin-bottom: 6px; display: block; }
    .req-input, .req-select, .req-textarea { width: 100%; border: 1.5px solid #dbe3ea; border-radius: 10px; padding: 11px 13px; font-size: 14px; background: #fbfcfd; transition: border .15s, box-shadow .15s; }
    .req-input:focus, .req-select:focus, .req-textarea:focus { outline: none; border-color: #188ec9; box-shadow: 0 0 0 3px rgba(24,142,201,.12); background: #fff; }
    .req-textarea { resize: vertical; }
    .req-loc { display: flex; gap: 8px; }
    .req-loc .req-input { flex: 1; }
    .btn-loc { border: 1.5px solid #0b5f8a; color: #0b5f8a; background: #fff; border-radius: 10px; padding: 0 14px; font-size: 15px; cursor: pointer; transition: .15s; }
    .btn-loc:hover { background: #0b5f8a; color: #fff; }
    .file-hint { font-size: 12px; color: #94a3b8; margin: 6px 0 0; }
    .req-errors { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; border-radius: 12px; padding: 12px 14px; margin-bottom: 16px; font-size: 13.5px; }
    .req-errors ul { margin: 6px 0 0; padding-inline-start: 18px; }
    .req-err-msg { color: #dc2626; font-size: 12px; margin-top: 5px; display: block; }
    .is-err { border-color: #fca5a5 !important; }
    .req-actions { display: flex; gap: 10px; margin-top: 6px; }
    .btn-submit { flex: 1; background: #ff8a00; color: #fff; border: none; border-radius: 12px; padding: 13px; font-size: 15px; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: .15s; box-shadow: 0 4px 12px rgba(255,138,0,.25); }
    .btn-submit:hover { background: #f97e0a; transform: translateY(-1px); }
    .btn-cancel { background: #fff; color: #475569; border: 1.5px solid #dbe3ea; border-radius: 12px; padding: 13px 22px; font-size: 14px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
    .btn-cancel:hover { background: #f1f5f9; }
    @media (max-width: 520px) { .req-actions { flex-direction: column-reverse; } .btn-cancel { width: 100%; } }
</style>

<div class="req-wrap">

    <div class="req-top">
        <div>
            <h1>إضافة طلب جديد</h1>
            <p>سجّل طلبك وسيصل لأقرب فني مختص</p>
        </div>
        <a href="{{ route('dashboard') }}" class="req-back"><i class="fas fa-arrow-right"></i> عودة</a>
    </div>

    <div class="req-card">
        <div class="req-hero">
            <div class="ic"><i class="fas fa-clipboard-list"></i></div>
            <div>
                <h5>بيانات الطلب</h5>
                <small>املأ الحقول المطلوبة لإرسال طلبك</small>
            </div>
        </div>

        <div class="req-body">
            <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(!empty($technicianId))
                    <input type="hidden" name="technician_id" value="{{ $technicianId }}">
                @endif

                @if ($errors->any())
                    <div class="req-errors">
                        <strong><i class="fas fa-exclamation-circle"></i> يرجى تصحيح الأخطاء التالية:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="req-sec">
                    <div class="req-sec-title"><span class="num">01.</span> معلومات الطلب</div>

                    <div style="margin-bottom:14px;">
                        <label class="req-label">الخدمة المطلوبة *</label>
                        <select name="service_id" class="req-select {{ $errors->has('service_id') ? 'is-err' : '' }}" required>
                            <option disabled {{ old('service_id', $serviceId) ? '' : 'selected' }}>اختر الخدمة</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id', $serviceId) == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                            @endforeach
                        </select>
                        @error('service_id')<span class="req-err-msg">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="req-label">العنوان *</label>
                        <div class="req-loc">
                            <input type="text" id="address" name="address" class="req-input {{ $errors->has('address') ? 'is-err' : '' }}" placeholder="أدخل العنوان" value="{{ old('address') }}" required>
                            <button type="button" id="getLocationBtn" class="btn-loc" title="استخدم موقعي"><i class="fas fa-crosshairs"></i></button>
                        </div>
                        <small id="locationStatus" class="form-text"></small>
                        @error('address')<span class="req-err-msg">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="req-sec">
                    <div class="req-sec-title"><span class="num">02.</span> تفاصيل إضافية</div>

                    <div style="margin-bottom:14px;">
                        <label class="req-label">الموعد المفضل</label>
                        <input type="datetime-local" name="scheduled_at" class="req-input {{ $errors->has('scheduled_at') ? 'is-err' : '' }}" value="{{ old('scheduled_at') }}">
                        @error('scheduled_at')<span class="req-err-msg">{{ $message }}</span>@enderror
                    </div>

                    <div style="margin-bottom:14px;">
                        <label class="req-label">وصف المشكلة</label>
                        <textarea name="description" rows="4" class="req-textarea" placeholder="اكتب تفاصيل إضافية...">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="req-label">صور (اختياري)</label>
                        <input type="file" name="images[]" multiple class="req-input {{ $errors->has('images.*') ? 'is-err' : '' }}">
                        <p class="file-hint">JPEG, PNG, WebP — يمكنك اختيار أكثر من صورة</p>
                        @error('images.*')<span class="req-err-msg">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="req-actions">
                    <a href="{{ route('dashboard') }}" class="btn-cancel">إلغاء</a>
                    <button class="btn-submit"><i class="fas fa-paper-plane"></i> إرسال الطلب</button>
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
