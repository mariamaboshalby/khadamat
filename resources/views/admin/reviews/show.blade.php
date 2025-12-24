@extends('layouts.admin')

@section('title', 'عرض المراجعة')
@section('header_title', 'عرض المراجعة')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">عرض المراجعة #{{ $review->id }}</h1>
        <div>
            <a href="{{ route('admin.reviews.edit', \App\Helpers\EncryptionHelper::encryptId($review->id)) }}" class="btn btn-warning text-white">
                <i class="fas fa-edit me-2"></i>تعديل
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>عودة إلى القائمة
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">تفاصيل المراجعة</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small fw-bold">المستخدم</label>
                            <p class="mb-0 fw-bold">
                                @if($review->user)
                                    {{ $review->user->name }}
                                    <br>
                                    <span class="text-muted small">{{ $review->user->phone }}</span>
                                @else
                                    <span class="text-muted">غير متوفر</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small fw-bold">الفني</label>
                            <p class="mb-0 fw-bold">
                                @if($review->technician && $review->technician->user)
                                    {{ $review->technician->user->name }}
                                    <br>
                                    <span class="text-muted small">{{ $review->technician->specialization->name ?? 'بدون تخصص' }}</span>
                                @else
                                    <span class="text-muted">غير متوفر</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small fw-bold">التقييم</label>
                            <p class="mb-0">
                                <div class="d-flex align-items-center gap-1 text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                    <span class="text-dark fw-bold ms-2">{{ $review->rating }}/5</span>
                                </div>
                            </p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small fw-bold">الحالة</label>
                            <p class="mb-0">
                                @switch($review->status)
                                    @case('pending')
                                        <span class="badge bg-warning">قيد الانتظار</span>
                                        @break
                                    @case('approved')
                                        <span class="badge bg-success">موافق عليه</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">مرفوض</span>
                                        @break
                                @endswitch
                            </p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small fw-bold">تاريخ الإنشاء</label>
                            <p class="mb-0 fw-bold">{{ $review->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small fw-bold">آخر تحديث</label>
                            <p class="mb-0 fw-bold">{{ $review->updated_at->format('Y-m-d H:i') }}</p>
                        </div>
                        
                        @if($review->title)
                        <div class="col-12 mb-3">
                            <label class="text-muted small fw-bold">العنوان</label>
                            <p class="mb-0 fw-bold">{{ $review->title }}</p>
                        </div>
                        @endif
                        
                        @if($review->comment)
                        <div class="col-12 mb-3">
                            <label class="text-muted small fw-bold">التعليق</label>
                            <p class="mb-0">{{ $review->comment }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">إجراءات</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.reviews.edit', \App\Helpers\EncryptionHelper::encryptId($review->id)) }}" class="btn btn-warning text-white mb-2">
                            <i class="fas fa-edit me-2"></i>تعديل المراجعة
                        </a>
                        
                        <form action="{{ route('admin.reviews.destroy', \App\Helpers\EncryptionHelper::encryptId($review->id)) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه المراجعة؟ هذا الإجراء لا يمكن التراجع عنه.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i>حذف المراجعة
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            @if($review->is_verified)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات التحقق</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="text-muted small fw-bold">حالة التحقق</label>
                        <p class="mb-0">
                            <span class="badge bg-success">تم التحقق</span>
                        </p>
                    </div>
                    
                    <div class="mb-2">
                        <label class="text-muted small fw-bold">تاريخ التحقق</label>
                        <p class="mb-0 fw-bold">{{ $review->verified_at->format('Y-m-d H:i') }}</p>
                    </div>
                    
                    @if($review->ip_address)
                    <div class="mb-0">
                        <label class="text-muted small fw-bold">عنوان IP</label>
                        <p class="mb-0 fw-bold">{{ $review->ip_address }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection