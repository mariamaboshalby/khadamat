@extends('layouts.admin')

@section('title', 'إدارة العملاء')

@section('content')
    <style>
        .table-row td,
        .table-row th {
            padding: 10px 14px !important;
        }
        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            background: #0b5f8a;
            color: white;
        }
       
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">قائمة العملاء</h4>
            <p class="text-muted mb-0">إدارة العملاء المسجلين</p>
        </div>
        <a href="{{ route('admin.customers.create') }}" class=" btn-primary rounded-pill px-4 fw-bold shadow-sm">
            <i class="fa-solid fa-plus me-2"></i> إضافة عميل جديد
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted small">بحث</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control bg-light border-start-0" placeholder="الاسم أو الهاتف أو البريد...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted small">الحالة</label>
                    <select name="status" class="form-select bg-light">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>موقوف</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn-primary rounded-3 w-100 fw-bold">
                        <i class="fa-solid fa-filter me-2"></i> تصفية
                    </button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary w-100 fw-bold">
                        <i class="fa-solid fa-rotate-right me-2"></i> إعادة تعيين
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">

            @if ($customers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-muted small fw-bold text-uppercase">العميل</th>
                                <th class="px-4 py-3 text-muted small fw-bold text-uppercase">البريد</th>
                                <th class="px-4 py-3 text-muted small fw-bold text-uppercase">الهاتف</th>
                                <th class="px-4 py-3 text-muted small fw-bold text-uppercase">التسجيل</th>
                                <th class="px-4 py-3 text-muted small fw-bold text-uppercase">الحالة</th>
                                <th class="px-4 py-3 text-muted small fw-bold text-uppercase">الإجراءات</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($customers as $customer)
                                <tr class="table-row" style="border-bottom: 1px solid #f0f0f0;">
                                    <td style="padding: 16px 20px;">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold"
                                                style="width: 40px; height: 40px;">
                                                {{ substr($customer->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size: 15px;">
                                                    {{ $customer->name }}</div>
                                                <small class="text-muted" style="font-size: 12px;">ID:
                                                    #{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-muted" style="padding: 16px 20px; font-size: 14px;">
                                        <i class="fa-solid fa-envelope text-primary me-1"></i>
                                        {{ $customer->email }}
                                    </td>

                                    <td class="text-muted"
                                        style="padding: 16px 20px; font-family: monospace; font-size: 14px;">
                                        <i class="fa-solid fa-phone text-success me-1"></i>
                                        {{ $customer->phone }}
                                    </td>

                                    <td class="text-muted" style="padding: 16px 20px;">
                                        <div style="font-size: 14px; font-weight: 600;">
                                            {{ $customer->created_at->format('Y-m-d') }}</div>
                                        <small class="d-block" style="font-size: 12px; color: #a0aec0;">
                                            <i class="fa-solid fa-clock me-1"></i>
                                            {{ $customer->created_at->diffForHumans() }}
                                        </small>
                                    </td>

                                    <td style="padding: 16px 20px;">
                                        @if ($customer->status == 'active')
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3">نشط</span>
                                        @elseif($customer->status == 'inactive')
                                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3">غير
                                                نشط</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3">موقوف</span>
                                        @endif
                                    </td>

                                    <td style="padding: 16px 20px;">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.customers.show', \App\Helpers\EncryptionHelper::encryptId($customer->id)) }}"
                                                class="btn btn-sm btn-info text-white" title="عرض"
                                                style="transition: all 0.2s;"
                                                onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 8px rgba(13, 110, 253, 0.3)';"
                                                onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="{{ route('admin.customers.edit', \App\Helpers\EncryptionHelper::encryptId($customer->id)) }}"
                                                class="btn btn-sm btn-warning text-white" title="تعديل"
                                                style="transition: all 0.2s;"
                                                onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 8px rgba(255, 193, 7, 0.3)';"
                                                onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>

                                            <form method="POST" action="{{ route('admin.customers.destroy', \App\Helpers\EncryptionHelper::encryptId($customer->id)) }}"
                                                onsubmit="return confirm('⚠️ هل أنت متأكد من حذف هذا العميل؟')"
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
                    {{ $customers->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <div class="avatar rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-users text-muted fa-2x"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark">لا يوجد عملاء</h5>
                    <p class="text-muted">لم يتم العثور على أي عملاء حالياً.</p>
                    <a href="{{ route('admin.customers.create') }}" class=" btn-primary mt-2">
                        <i class="fa-solid fa-plus me-2"></i> إضافة عميل جديد
                    </a>
                </div>
            @endif

        </div>
    </div>

@endsection
