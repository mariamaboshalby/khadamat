@extends('layouts.admin')

@section('title', 'إدارة الفنيين')
@section('header_title', 'إدارة الفنيين')

@section('content')
<style>
    
</style>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">قائمة الفنيين</h4>
        <p class="text-muted mb-0">إدارة فريق العمل والفنيين</p>
    </div>
    <a href="{{ route('admin.techs.create') }}" class="btn-primary rounded-pill px-4 fw-bold shadow-sm">
        <i class="fa-solid fa-plus me-2"></i> إضافة فني جديد
    </a>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-bold text-muted small">بحث</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-light border-start-0" placeholder="الاسم أو الهاتف...">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold text-muted small">التخصص</label>
                <select name="specialization_id" class="form-select bg-light">
                    <option value="">جميع التخصصات</option>
                    @foreach($specializations as $spec)
                        <option value="{{ $spec->id }}" {{ request('specialization_id') == $spec->id ? 'selected' : '' }}>
                            {{ $spec->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold text-muted small">الحالة</label>
                <select name="availability_status" class="form-select bg-light">
                    <option value="">جميع الحالات</option>
                    <option value="available" {{ request('availability_status') == 'available' ? 'selected' : '' }}>متاح</option>
                    <option value="busy" {{ request('availability_status') == 'busy' ? 'selected' : '' }}>مشغول</option>
                    <option value="on_leave" {{ request('availability_status') == 'on_leave' ? 'selected' : '' }}>في إجازة</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class=" btn-primary rounded-3 w-100 fw-bold">
                    <i class="fa-solid fa-filter me-2"></i> تصفية
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Technicians Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($technicians->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-muted small fw-bold text-uppercase">الاسم</th>
                            <th class="px-4 py-3 text-muted small fw-bold text-uppercase">التخصص</th>
                            <th class="px-4 py-3 text-muted small fw-bold text-uppercase">الهاتف</th>
                            <th class="px-4 py-3 text-muted small fw-bold text-uppercase">الحالة</th>
                            <th class="px-4 py-3 text-muted small fw-bold text-uppercase">التقييم</th>
                            <th class="px-4 py-3 text-muted small fw-bold text-uppercase">المهام</th>
                            <th class="px-4 py-3 text-muted small fw-bold text-uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($technicians as $technician)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                            {{ substr($technician->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $technician->user->name }}</div>
                                            @if($technician->badges->count() > 0)
                                                <div class="d-flex gap-1 mt-1">
                                                    @foreach($technician->badges as $badge)
                                                        <span class="badge" style="background: {{ $badge->badge_color }}; font-size: 10px; padding: 2px 6px;">
                                                            <i class="fa-solid {{ $badge->badge_icon }}"></i> {{ $badge->badge_name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge bg-light text-dark border">{{ $technician->specialization->name }}</span>
                                </td>
                                <td class="px-4 py-3 font-monospace text-muted">{{ $technician->user->phone }}</td>
                                <td class="px-4 py-3">
                                    @if($technician->availability_status == 'available')
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3">متاح</span>
                                    @elseif($technician->availability_status == 'busy')
                                        <span class="badge bg-warning-subtle text-warning rounded-pill px-3">مشغول</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-3">في إجازة</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-1 text-warning">
                                        <i class="fa-solid fa-star"></i>
                                        <span class="text-dark fw-bold">{{ number_format($technician->rating, 1) }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="fw-bold text-dark">{{ $technician->completed_tasks }}</span>
                                    <small class="text-muted">مهمة</small>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.techs.show', $technician) }}" 
                                           class="btn btn-sm btn-info text-white" 
                                           title="عرض"
                                           style="transition: all 0.2s;"
                                           onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 8px rgba(13, 110, 253, 0.3)';"
                                           onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.techs.edit', $technician) }}" 
                                           class="btn btn-sm btn-warning text-white" 
                                           title="تعديل"
                                           style="transition: all 0.2s;"
                                           onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 8px rgba(255, 193, 7, 0.3)';"
                                           onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('admin.techs.destroy', $technician) }}" 
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الفني؟')"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger text-white" 
                                                    title="حذف"
                                                    style="transition: all 0.2s;"
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
                {{ $technicians->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <div class="avatar rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-user-gear text-muted fa-2x"></i>
                    </div>
                </div>
                <h5 class="fw-bold text-dark">لا يوجد فنيين</h5>
                <p class="text-muted">لم يتم العثور على أي فنيين حالياً.</p>
                <a href="{{ route('admin.techs.create') }}" class="btn-primary mt-2">
                    <i class="fa-solid fa-plus me-2"></i> إضافة فني جديد
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
