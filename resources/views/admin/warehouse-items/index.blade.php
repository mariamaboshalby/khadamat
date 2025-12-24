@extends('layouts.admin')

@section('title', 'إدارة المخزن')
@section('header_title', 'إدارة المخزن')

@section('content')
    <style>
       
    </style>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">قائمة الأصناف</h4>
            <p class="text-muted mb-0">إدارة مخزون قطع الغيار والمعدات</p>
        </div>
        <a href="{{ route('admin.warehouse-items.create') }}" class="btn-primary rounded-pill px-4 fw-bold shadow-sm">
            <i class="fa-solid fa-plus me-2"></i> إضافة صنف جديد
        </a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted small">بحث</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control bg-light border-start-0" placeholder="اسم الصنف...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted small">الفئة</label>
                    <select name="category" class="form-select bg-light">
                        <option value="">جميع الفئات</option>
                        @foreach ($specializations as $id => $name)
                            <option value="{{ $id }}" {{ request('category') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="low_stock" value="1" id="lowStockCheck"
                            {{ request('low_stock') ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-muted" for="lowStockCheck">
                            كمية قليلة فقط
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class=" btn-primary rounded-3 w-100 fw-bold">
                        <i class="fa-solid fa-filter me-2"></i> تصفية
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Items Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if ($items->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-muted small fw-bold text-uppercase">اسم الصنف</th>
                                <th class="px-4 py-3 text-muted small fw-bold text-uppercase">الفئة</th>
                                <th class="px-4 py-3 text-muted small fw-bold text-uppercase">الكمية</th>
                                <th class="px-4 py-3 text-muted small fw-bold text-uppercase">الوحدة</th>
                                <th class="px-4 py-3 text-muted small fw-bold text-uppercase">السعر</th>
                                <th class="px-4 py-3 text-muted small fw-bold text-uppercase">الحالة</th>
                                <th class="px-4 py-3 text-muted small fw-bold text-uppercase">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr class="{{ $item->isLowStock() ? 'table-danger bg-opacity-10' : '' }}">
                                    <td class="px-4 py-3 fw-bold text-dark">{{ $item->name }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="badge bg-light text-dark border">{{ $specializations->get($item->category) ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="fw-bold {{ $item->isLowStock() ? 'text-danger' : 'text-dark' }}">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-muted">{{ $item->unit }}</td>
                                    <td class="px-4 py-3 font-monospace">{{ number_format($item->price, 2) }} ج.م</td>
                                    <td class="px-4 py-3">
                                        @if ($item->quantity == 0)
                                            <span class="badge bg-danger rounded-pill px-3">نفذ</span>
                                        @elseif($item->isLowStock())
                                            <span class="badge bg-warning text-dark rounded-pill px-3">قليل</span>
                                        @else
                                            <span class="badge bg-success rounded-pill px-3">متوفر</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.warehouse-items.edit', \App\Helpers\EncryptionHelper::encryptId($item->id)) }}"
                                                class="btn btn-sm btn-warning text-white" title="تعديل"
                                                style="transition: all 0.2s;"
                                                onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 8px rgba(255, 193, 7, 0.3)';"
                                                onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('admin.warehouse-items.destroy', \App\Helpers\EncryptionHelper::encryptId($item->id)) }}"
                                                onsubmit="return confirm('هل أنت متأكد من حذف هذا الصنف؟')"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger text-white"
                                                    title="حذف" style="transition: all 0.2s;"
                                                    onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 8px rgba(220, 53, 69, 0.3)';"
                                                    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3 border-top">
                    {{ $items->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <div class="avatar rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-boxes-stacked text-muted fa-2x"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark">لا يوجد أصناف</h5>
                    <p class="text-muted">المخزن فارغ حالياً.</p>
                    <a href="{{ route('admin.warehouse-items.create') }}" class=" btn-primary rounded-3 px-4 fw-bold mt-2">
                        <i class="fa-solid fa-plus me-2"></i> إضافة صنف جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
