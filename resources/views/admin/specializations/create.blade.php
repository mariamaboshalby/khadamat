@extends('layouts.admin')

@section('title', 'إضافة تخصص جديد')

@section('content')
<div class="container" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="fa-solid fa-layer-group" style="font-size:20px;"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-1">إضافة تخصص جديد</h4>
                <p class="text-muted mb-0">أدخل بيانات التخصص</p>
            </div>
        </div>
        <a href="{{ route('admin.specializations.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> رجوع للقائمة
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.specializations.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">اسم التخصص</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-control"
                           placeholder="مثال: كهرباء، سباكة، نجارة">
                    @error('name')
                        <p style="color: #e53e3e; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">الوصف (اختياري)</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="وصف التخصص...">{{ old('description') }}</textarea>
                    @error('description')
                        <p style="color: #e53e3e; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" value="1" id="isActive" class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label for="isActive" class="form-check-label">تفعيل التخصص</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-primary rounded-3 ">
                        <i class="fa-solid fa-plus me-1"></i> إضافة التخصص
                    </button>
                    <a href="{{ route('admin.specializations.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
