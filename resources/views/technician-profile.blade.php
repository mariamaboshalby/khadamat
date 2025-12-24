@extends('layouts.mobile')

@section('title', 'بروفايل الفني')

@section('content')
<div class="container pb-4" dir="rtl">

    <div class="row g-4">

        {{-- التقييمات (الشمال) --}}
        <div class="col-lg-8 order-2 order-lg-1">
            <div class="card h-100">

                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">⭐ التقييمات وآراء العملاء</h5>
                    @auth
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#addReviewModal">+ أضف تقييم</button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-danger btn-sm">+ أضف تقييم</a>
                    @endauth
                </div>

                <div class="card-body">

                    @forelse($technician->reviews as $review)
                        <div class="bg-light p-3 rounded-3 mb-3">

                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex gap-2 align-items-center">
                                    <div class="user-avatar">
                                        {{ substr($review->user->name,0,1) }}
                                    </div>

                                    <div>
                                        <div class="fw-semibold small">
                                            {{ $review->user->name }}
                                        </div>
                                        <div class="review-stars">
                                            @for($i=1;$i<=5;$i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'filled' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>

                                <small class="text-muted">
                                    {{ $review->created_at->diffForHumans() }}
                                </small>
                            </div>

                            @if($review->comment)
                                <p class="mb-0 small text-muted">
                                    {{ $review->comment }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-comment-slash fs-1 mb-3 opacity-25"></i>
                            <p class="fw-semibold mb-1">لا توجد تقييمات بعد</p>
                            <small>كن أول من يقيّم هذا الفني</small>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>

        {{-- بروفايل الفني (اليمين) --}}
        <div class="col-lg-4 order-1 order-lg-2">
            <div class="card technician-card">

                <div class="technician-header text-center">
                    <div class="profile-avatar-lg">
                        {{ substr($technician->user->name,0,1) }}
                    </div>
                </div>

                <div class="card-body text-center">

                    <h5 class="fw-bold mb-1">
                        {{ $technician->user->name }}
                    </h5>

                    <span class="badge bg-danger mb-3">
                        {{ $technician->specialization->name }}
                    </span>

                    <div class="rating-box mb-3">
                        <span class="fw-bold">
                            {{ number_format($avgRating ?? 0,1) }}
                        </span>
                        <small class="text-muted">
                            ({{ $technician->reviews->count() }} تقييم)
                        </small>

                        <div class="review-stars justify-content-center mt-1">
                            @for($i=1;$i<=5;$i++)
                                <i class="fas fa-star {{ $i <= round($avgRating) ? 'filled' : '' }}"></i>
                            @endfor
                        </div>
                    </div>

                    <button class="btn btn-danger w-100 mb-3">
                        💬 تواصل معي
                    </button>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="stat-card">
                                <i class="fas fa-briefcase"></i>
                                <div class="fw-bold">
                                    {{ $technician->experience_years ?? 0 }}
                                </div>
                                <small>سنوات</small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="stat-card">
                                <i class="fas fa-check-circle text-success"></i>
                                <div class="fw-bold">
                                    {{ $completedRequests }}
                                </div>
                                <small>مكتملة</small>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-white">
                    <h6 class="fw-bold mb-2">نبذة عني</h6>
                    <p class="small text-muted mb-0">
                        {{ $technician->bio ?? 'لا توجد نبذة شخصية لهذا الفني حالياً.' }}
                    </p>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection


@push('styles')
<style>
.technician-card {
    border-radius: 16px;
    overflow: hidden;
}

.technician-header {
    background: linear-gradient(135deg, #e54343,#fd7777);
    padding: 2.5rem 0;
}

.profile-avatar-lg {
    width: 90px;
    height: 90px;
    background: #fff;
    color: #e54343;
    border-radius: 50%;
    font-size: 2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: auto;
    border: 4px solid #fff;
}

.user-avatar {
    width: 36px;
    height: 36px;
    background: #fd7777;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    font-weight: 600;
}

.review-stars {
    display: flex;
    gap: 2px;
}

.review-stars i {
    font-size: .85rem;
    color: #d1d5db;
}

.review-stars i.filled {
    color: #fbbf24;
}

.stat-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
}

.stat-card i {
    color: #0d6efd;
    margin-bottom: .25rem;
}

/* Rating Stars */
.rating-stars {
    display: flex;
    justify-content: center;
    gap: 5px;
    margin: 1rem 0;
}

.rating-stars input[type="radio"] {
    display: none;
}

.rating-stars label {
    font-size: 2rem;
    color: #d1d5db;
    cursor: pointer;
    transition: color 0.2s;
}

.rating-stars input[type="radio"]:checked ~ label,
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #fbbf24;
}
</style>
@endpush

@auth
<!-- Add Review Modal -->
<div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReviewModalLabel">إضافة تقييم للفني</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('technician.review', \App\Helpers\EncryptionHelper::encryptId($technician->id)) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">تقييمك *</label>
                        <div class="rating-stars">
                            <input type="radio" name="rating" value="5" id="star5" required>
                            <label for="star5">★</label>
                            <input type="radio" name="rating" value="4" id="star4">
                            <label for="star4">★</label>
                            <input type="radio" name="rating" value="3" id="star3">
                            <label for="star3">★</label>
                            <input type="radio" name="rating" value="2" id="star2">
                            <label for="star2">★</label>
                            <input type="radio" name="rating" value="1" id="star1">
                            <label for="star1">★</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">تعليقك (اختياري)</label>
                        <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="شارك تجربتك مع هذا الفني..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">إرسال التقييم</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth
