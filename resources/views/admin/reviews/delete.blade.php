@extends('layouts.admin')

@section('title', 'تأكيد حذف المراجعة')
@section('header_title', 'تأكيد حذف المراجعة')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">تأكيد حذف المراجعة</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h4 class="alert-heading">تحذير!</h4>
                        <p>أنت على وشك حذف المراجعة التالية:</p>
                    </div>
                    
                    <div class="review-details mb-4">
                        <dl class="row">
                            <dt class="col-sm-3">رقم المراجعة:</dt>
                            <dd class="col-sm-9">{{ $review->id }}</dd>
                            
                            <dt class="col-sm-3">المستخدم:</dt>
                            <dd class="col-sm-9">{{ $review->user->name ?? 'N/A' }}</dd>
                            
                            <dt class="col-sm-3">الفني:</dt>
                            <dd class="col-sm-9">{{ $review->technician->user->name ?? 'N/A' }}</dd>
                            
                            <dt class="col-sm-3">التقييم:</dt>
                            <dd class="col-sm-9">
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                    <span class="ms-2">({{ $review->rating }}/5)</span>
                                </div>
                            </dd>
                            
                            <dt class="col-sm-3">العنوان:</dt>
                            <dd class="col-sm-9">{{ $review->title ?? 'بدون عنوان' }}</dd>
                            
                            <dt class="col-sm-3">التعليق:</dt>
                            <dd class="col-sm-9">{{ $review->comment ?? 'بدون تعليق' }}</dd>
                            
                            <dt class="col-sm-3">تاريخ الإنشاء:</dt>
                            <dd class="col-sm-9">{{ $review->created_at->format('Y-m-d H:i') }}</dd>
                        </dl>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>إلغاء
                        </a>
                        
                        <form action="{{ route('admin.reviews.destroy', \App\Helpers\EncryptionHelper::encryptId($review->id)) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>تأكيد الحذف
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.rating-stars i {
    font-size: 1.2rem;
}
.review-details dt {
    font-weight: bold;
}
</style>
@endpush