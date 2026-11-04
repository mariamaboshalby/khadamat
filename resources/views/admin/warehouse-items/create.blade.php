@extends('layouts.admin')

@section('title', 'إضافة صنف جديد')

@section('content')
<div class="wi-wrap">
    <div class="wi-head">
        <div>
            <h1>إضافة صنف جديد للمخزن</h1>
            <p>أدخل بيانات الصنف</p>
        </div>
        <a href="{{ route('admin.warehouse-items.index') }}" class="wi-back">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7"/>
            </svg>
            عودة للقائمة
        </a>
    </div>

    <div class="wi-card">
        <div class="wi-banner">
            <div class="wi-banner-ic">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <h3>بيانات الصنف</h3>
                <p>يرجى ملء جميع الحقول المطلوبة بدقة</p>
            </div>
        </div>

        <div class="wi-body">
            <form method="POST" action="{{ route('admin.warehouse-items.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="wi-field">
                <label class="form-label">اسم الصنف</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-control"
                    placeholder="مثال: مفتاح كهرباء، أنبوب PVC">
                @error('name')<p class="wi-err">{{ $message }}</p>@enderror
            </div>

            <div class="wi-field">
                <label class="form-label">الوصف (اختياري)</label>
                <textarea name="description" rows="3" class="form-control"
                    placeholder="وصف الصنف...">{{ old('description') }}</textarea>
                @error('description')<p class="wi-err">{{ $message }}</p>@enderror
            </div>

            <div class="wi-field">
                <label class="form-label">صورة المنتج (اختياري)</label>
                <input type="file" name="image" accept="image/*" class="form-control" onchange="previewImage(event)">
                @error('image')<p class="wi-err">{{ $message }}</p>@enderror
                <div class="mt-2 d-flex align-items-center gap-3">
                    <img id="imagePreview" src="" alt="Preview" style="display:none; width: 72px; height: 72px; object-fit: cover; border-radius: 8px; border: 1px solid #e6ecf2;">
                    <span id="imageName" class="text-muted small"></span>
                </div>
            </div>

            <div class="wi-row">
                <div>
                    <label class="form-label">الكمية</label>
                    <input type="number" name="quantity" value="{{ old('quantity', 0) }}" required min="0" class="form-control">
                    @error('quantity')<p class="wi-err">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">الوحدة</label>
                    <input type="text" name="unit" value="{{ old('unit', 'قطعة') }}" required class="form-control"
                        placeholder="قطعة، متر، كيلو، لتر">
                    @error('unit')<p class="wi-err">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="wi-row">
                <div>
                    <label class="form-label">السعر (جنيه)</label>
                    <input type="number" name="price" value="{{ old('price', 0) }}" required min="0" step="0.01" class="form-control">
                    @error('price')<p class="wi-err">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">الحد الأدنى للتنبيه</label>
                    <input type="number" name="min_quantity" value="{{ old('min_quantity', 10) }}" required min="0" class="form-control">
                    @error('min_quantity')<p class="wi-err">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="wi-field">
                <label class="form-label">الفئة (اختياري)</label>
                <div class="wi-cat-row">
                    <span class="wi-cat-ic"><i class="fa-solid fa-layer-group"></i></span>
                    <select name="category" id="categorySelect" class="form-select" onchange="updateSelectedSpecName(this)">
                        <option value="">اختر الفئة...</option>
                        @foreach($categories as $id => $name)
                            <option value="{{ $id }}" {{ old('category') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <a href="{{ route('admin.specializations.create') }}" class="wi-cat-add" title="إضافة تخصص" target="_blank">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                </div>
                <small class="form-text text-muted mt-1">التخصص المحدد: <span id="selectedSpecName">لم يتم الاختيار</span></small>
                @error('category')<p class="wi-err">{{ $message }}</p>@enderror
            </div>

            <div class="wi-actions">
                <a href="{{ route('admin.warehouse-items.index') }}" class="wi-cancel">إلغاء</a>
                <button type="submit" class="wi-submit">
                    <i class="fa-solid fa-plus me-1"></i> إضافة الصنف
                </button>
            </div>
        </form>
        </div>
    </div>
</div>

<style>
    .wi-wrap { max-width: 820px; margin: 0 auto; padding: 10px 14px 30px; }

    .wi-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 14px; flex-wrap: wrap; }
    .wi-head h1 { font-size: 21px; margin: 0 0 2px; color: #072540; font-weight: 800; }
    .wi-head p { color: #64748b; margin: 0; font-size: 13px; }
    .wi-back { background: #fff; border: 1px solid #e6ecf2; color: #475569; padding: 9px 16px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; }
    .wi-back:hover { background: #f7fafc; color: #0b5f8a; border-color: #cbd5e0; }

    .wi-card { background: #fff; border: 1px solid #e6ecf2; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 14px rgba(7, 37, 64, 0.05); }

    .wi-banner { background: linear-gradient(135deg, #0b5f8a 0%, #188ec9 100%); padding: 16px 20px; color: #fff; display: flex; align-items: center; gap: 14px; }
    .wi-banner-ic { width: 44px; height: 44px; background: rgba(255,255,255,0.18); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .wi-banner h3 { font-size: 17px; font-weight: 700; margin: 0 0 2px; }
    .wi-banner p { margin: 0; opacity: 0.9; font-size: 13px; }

    .wi-body { padding: 20px; }
    .wi-field { margin-bottom: 14px; }
    .wi-field .form-label, .wi-row label { font-size: 13.5px; font-weight: 600; color: #334155; margin-bottom: 6px; }
    .wi-body .form-control, .wi-body .form-select { border-radius: 10px; border-color: #e6ecf2; padding: 10px 12px; font-size: 14px; }
    .wi-body .form-control:focus, .wi-body .form-select:focus { border-color: #188ec9; box-shadow: 0 0 0 3px rgba(24, 142, 201, 0.12); }
    .wi-err { color: #e03131; font-size: 13px; margin: 4px 0 0; }

    .wi-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }

    .wi-cat-row { display: flex; align-items: stretch; gap: 8px; }
    .wi-cat-ic { display: flex; align-items: center; justify-content: center; width: 42px; border: 1px solid #e6ecf2; border-radius: 10px; background: #f8fafc; color: #0b5f8a; }
    .wi-cat-row .form-select { flex: 1; }
    .wi-cat-add { display: flex; align-items: center; justify-content: center; width: 42px; border: 1px solid #188ec9; color: #0b5f8a; border-radius: 10px; background: #fff; transition: all 0.2s; }
    .wi-cat-add:hover { background: #0b5f8a; color: #fff; }

    .wi-actions { padding-top: 16px; border-top: 1px solid #eef2f6; display: flex; justify-content: flex-end; gap: 12px; }
    .wi-cancel { padding: 10px 22px; border-radius: 10px; border: 1px solid #e6ecf2; background: #fff; color: #475569; text-decoration: none; font-weight: 700; font-size: 14px; transition: all 0.2s; }
    .wi-cancel:hover { background: #f7fafc; }
    .wi-submit { padding: 10px 24px; border-radius: 10px; border: none; background: #0b5f8a; color: #fff; font-weight: 700; font-size: 14px; cursor: pointer; transition: all 0.2s; box-shadow: 0 3px 10px rgba(11, 95, 138, 0.25); }
    .wi-submit:hover { background: #094d70; transform: translateY(-1px); }

    @media (max-width: 640px) {
        .wi-row { grid-template-columns: 1fr; }
        .wi-actions { flex-direction: row; }
    }
</style>

@push('scripts')
<script>
function previewImage(e) {
  const file = e.target.files[0];
  const img = document.getElementById('imagePreview');
  const nameSpan = document.getElementById('imageName');
  if (!file) { img.style.display='none'; nameSpan.textContent=''; return; }
  nameSpan.textContent = file.name;
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
