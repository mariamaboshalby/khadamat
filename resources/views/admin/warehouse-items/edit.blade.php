@extends('layouts.admin')

@section('title', 'تعديل صنف')

@section('content')
<style>
    .form-label { font-weight: 600; color: #4a5568; }
    .form-text { color: #718096; }
    .header-avatar { width:48px; height:48px; }
    .header-avatar i { font-size:20px; }
    .form-control:focus { box-shadow: 0 0 0 0.2rem rgba(102,126,234,.15); border-color:#667eea; }
    .thumb { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; }
</style>

<div class="container" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center header-avatar">
                <i class="fa-solid fa-box"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-1">تعديل بيانات الصنف</h4>
                <p class="text-muted mb-0">عدّل الحقول المطلوبة ثم احفظ</p>
            </div>
        </div>
        <a href="{{ route('admin.warehouse-items.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> رجوع للقائمة
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.warehouse-items.update', $warehouseItem) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">اسم الصنف</label>
                    <input type="text" name="name" value="{{ old('name', $warehouseItem->name) }}" required class="form-control" placeholder="مثال: مفتاح كهرباء، أنبوب PVC">
                    @error('name')
                        <p style="color: #e53e3e; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">الوصف (اختياري)</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="وصف الصنف...">{{ old('description', $warehouseItem->description) }}</textarea>
                    @error('description')
                        <p style="color: #e53e3e; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">صورة المنتج (اختياري)</label>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        @if($warehouseItem->image_path)
                            <img src="{{ asset('storage/'.$warehouseItem->image_path) }}" alt="Current" class="thumb">
                        @endif
                        <img id="imagePreview" src="" alt="Preview" class="thumb" style="display:none;">
                    </div>
                    <input type="file" name="image" accept="image/*" class="form-control" onchange="previewImage(event)">
                    @error('image')
                        <p style="color: #e53e3e; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">الكمية</label>
                        <input type="number" name="quantity" value="{{ old('quantity', $warehouseItem->quantity) }}" required min="0" class="form-control">
                        @error('quantity')
                            <p style="color: #e53e3e; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الوحدة</label>
                        <input type="text" name="unit" value="{{ old('unit', $warehouseItem->unit) }}" required class="form-control" placeholder="قطعة، متر، كيلو، لتر">
                        @error('unit')
                            <p style="color: #e53e3e; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">السعر (جنيه)</label>
                        <input type="number" name="price" value="{{ old('price', $warehouseItem->price) }}" required min="0" step="0.01" class="form-control">
                        @error('price')
                            <p style="color: #e53e3e; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الحد الأدنى للتنبيه</label>
                        <input type="number" name="min_quantity" value="{{ old('min_quantity', $warehouseItem->min_quantity) }}" required min="0" class="form-control">
                        @error('min_quantity')
                            <p style="color: #e53e3e; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">الفئة (اختياري)</label>
                    <div class="d-flex align-items-center gap-2">
                        <span class="input-group-text"><i class="fa-solid fa-layer-group"></i></span>
                        <select name="category" id="categorySelect" class="form-select" onchange="updateSelectedSpecName(this)">
                            <option value="">اختر الفئة...</option>
                            @foreach($categories as $id => $name)
                                <option value="{{ $id }}" {{ old('category', $warehouseItem->category) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('admin.specializations.create') }}" class="btn btn-outline-primary" title="إضافة تخصص" target="_blank">
                            <i class="fa-solid fa-plus"></i>
                        </a>
                    </div>
                    <small class="form-text text-muted mt-1">التخصص المحدد: <span id="selectedSpecName">لم يتم الاختيار</span></small>
                    @error('category')
                        <p style="color: #e53e3e; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="fa-solid fa-floppy-disk me-1"></i> حفظ التعديلات
                    </button>
                    <a href="{{ route('admin.warehouse-items.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(e) {
  const file = e.target.files[0];
  const img = document.getElementById('imagePreview');
  if (!file) { img.style.display='none'; return; }
  const reader = new FileReader();
  reader.onload = function(ev) { img.src = ev.target.result; img.style.display='block'; };
  reader.readAsDataURL(file);
}

function updateSelectedSpecName(select) {
  const map = @json($categories);
  const id = select.value;
  const name = map && map[id] ? map[id] : '';
  const el = document.getElementById('selectedSpecName');
  if (el) el.textContent = name ? name : 'لم يتم الاختيار';
}

document.addEventListener('DOMContentLoaded', function() {
  var sel = document.getElementById('categorySelect');
  if (sel) updateSelectedSpecName(sel);
});
</script>
@endpush
@endsection
