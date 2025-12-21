@extends('layouts.admin')

@section('title', 'تعديل تخصص')

@section('content')
<div class="container" style="max-width: 800px;">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">تعديل تخصص</h4>
            <p class="text-muted mb-0">قم بتعديل بيانات التخصص ثم احفظ التغييرات</p>
        </div>

        <a href="{{ route('admin.specializations.index') }}"
           class="btn btn-outline-secondary rounded-pill">
            <i class="fa-solid fa-arrow-right"></i> رجوع
        </a>
    </div>

    <!-- Card -->
    <div class="card border-0 shadow-sm rounded-4">

        <!-- Card Header -->
        <div class="card-header" style="background: linear-gradient(135deg, #ff7373 0%, #cc3333 100%); padding: 24px; color: white;">
            <div class="d-flex align-items-center gap-2 fw-bold">
                <i class="fa-solid fa-layer-group"></i>
                بيانات التخصص
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body p-4">
            <form method="POST"
                  action="{{ route('admin.specializations.update', $specialization) }}">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        اسم التخصص <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $specialization->name) }}"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="مثال: كهرباء – سباكة – نجارة"
                           required>

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">الوصف</label>
                    <textarea name="description"
                              rows="3"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="وصف مختصر للتخصص...">{{ old('description', $specialization->description) }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               id="isActive"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $specialization->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="isActive">
                            تفعيل التخصص
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.specializations.index') }}"
                       class="btn btn-outline-secondary">
                        إلغاء
                    </a>

                    <button type="submit"
                            class="btn-primary rounded-3 text-white px-4">
                        <i class="fa-solid fa-floppy-disk me-1"></i>
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
