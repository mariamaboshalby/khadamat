@extends('layouts.admin')

@section('title', 'تعديل صنف')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 24px;">
    <div style="margin-bottom: 36px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 32px; margin: 0 0 8px; color: #2d3748; font-weight: 700;">تعديل بيانات الصنف</h1>
            <p style="color: #718096; margin: 0; font-size: 16px;">عدّل الحقول المطلوبة ثم احفظ</p>
        </div>
        <a href="{{ route('admin.warehouse-items.index') }}" 
           style="background: white; border: 1px solid #e2e8f0; color: #4a5568; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.05);"
           onmouseover="this.style.background='#f7fafc'; this.style.transform='translateY(-1px)';"
           onmouseout="this.style.background='white'; this.style.transform='translateY(0)';">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7"/>
            </svg>
            عودة للقائمة
        </a>
    </div>
    
    <div class="card" style="padding: 0; overflow: hidden; border: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border-radius: 16px;">
        <div style="background: linear-gradient(135deg, #2a6592 0%, #3498db 50%, #f39c12 100%); padding: 32px; color: white;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 24px; font-weight: 700; margin: 0 0 6px;">بيانات الصنف</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 15px;">يمكنك تعديل بيانات الصنف وحفظ التغييرات</p>
                </div>
            </div>
        </div>

        <div style="padding: 40px;">

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.warehouse-items.update', $encryptedId) }}" enctype="multipart/form-data">
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

                <div style="padding-top: 32px; border-top: 2px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 16px;">
                    <a href="{{ route('admin.warehouse-items.index') }}"
                        style="padding: 14px 32px; border-radius: 10px; border: 2px solid #e2e8f0; background: white; color: #4a5568; text-decoration: none; font-weight: 700; transition: all 0.3s ease; font-size: 16px;"
                        onmouseover="this.style.background='#f7fafc'; this.style.borderColor='#cbd5e0'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';"
                        onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        إلغاء
                    </a>
                    <button type="submit"
                        style="padding: 14px 32px; border-radius: 10px; border: none; background: linear-gradient(135deg, #2a6592 0%, #3498db 50%, #f39c12 100%); color: white; font-weight: 700; transition: all 0.3s ease; font-size: 16px; cursor: pointer; box-shadow: 0 4px 15px rgba(42, 101, 146, 0.3);"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(42, 101, 146, 0.4)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(42, 101, 146, 0.3)';">
                        <i class="fa-solid fa-floppy-disk me-1"></i> حفظ التعديلات
                    </button>
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
