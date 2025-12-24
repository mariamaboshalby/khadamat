@extends('layouts.admin')

@section('title', 'إدارة التخصصات')
@section('header_title', 'إدارة التخصصات')

@section('content')
<style>
 
</style>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">قائمة التخصصات</h4>
        <p class="text-muted mb-0">إدارة تخصصات الفنيين في النظام</p>
    </div>
    <a href="{{ route('admin.specializations.create') }}" class="btn-primary rounded-pill px-4 fw-bold shadow-sm">
        <i class="fa-solid fa-plus me-2"></i> إضافة تخصص جديد
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($specializations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-muted small fw-bold text-uppercase">اسم التخصص</th>
                            <th class="px-4 py-3 text-muted small fw-bold text-uppercase">الوصف</th>
                            <th class="px-4 py-3 text-muted small fw-bold text-uppercase">عدد الفنيين</th>
                            <th class="px-4 py-3 text-muted small fw-bold text-uppercase">الحالة</th>
                            <th class="px-4 py-3 text-muted small fw-bold text-uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($specializations as $specialization)
                            <tr>
                                <td class="px-4 py-3 fw-bold text-dark">{{ $specialization->name }}</td>
                                <td class="px-4 py-3 text-muted">{{ $specialization->description ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="badge bg-light text-dark border rounded-pill px-3">
                                        {{ $specialization->technicians_count }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($specialization->is_active)
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3">نشط</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-3">غير نشط</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.specializations.edit', \App\Helpers\EncryptionHelper::encryptId($specialization->id)) }}" class="btn btn-sm btn-light text-primary" title="تعديل">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        
                                        @if($specialization->technicians_count == 0)
                                            <form method="POST" action="{{ route('admin.specializations.destroy', \App\Helpers\EncryptionHelper::encryptId($specialization->id)) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا التخصص؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light text-danger" title="حذف">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button disabled class="btn btn-sm btn-light text-muted" title="لا يمكن الحذف (يوجد فنيين)">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <div class="avatar rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-briefcase text-muted fa-2x"></i>
                    </div>
                </div>
                <h5 class="fw-bold text-dark">لا يوجد تخصصات</h5>
                <p class="text-muted">لم يتم إضافة أي تخصصات بعد.</p>
                <a href="{{ route('admin.specializations.create') }}" class="btn-primary mt-2">
                    <i class="fa-solid fa-plus me-2"></i> إضافة تخصص
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
