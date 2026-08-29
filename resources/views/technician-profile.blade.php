@extends('layouts.mobile')

@section('title', ($technician->user?->name ?? $technician->full_name ?? 'الملف الشخصي للفني') . ' - خدمتي')

@section('content')
<div class="technician-profile-page py-4">
    <div class="container max-w-1200" dir="rtl">

        <!-- Breadcrumb Navigation -->
        <nav class="tech-breadcrumb mb-4" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('technicians.index') }}">الفنيين</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $technician->user?->name ?? $technician->full_name ?? 'أحمد ك.' }}</li>
            </ol>
        </nav>

        <div class="row g-4 align-items-start">

            <!-- ========================================================================= -->
            <!-- LEFT MAIN CONTENT: BIO + SERVICES + REVIEWS                              -->
            <!-- ========================================================================= -->
            <div class="col-lg-8 order-2 order-lg-1">

                <!-- 1. About Technician (نبذة عن الفني) -->
                <div class="tech-card-box p-4 p-md-4 mb-4">
                    <h3 class="section-sub-title mb-3">نبذة عن الفني</h3>
                    <p class="tech-bio-text mb-0">
                        {{ $technician->bio ?: ($technician->user?->name ?? 'الفني') . ' فني معتمد ذو خبرة واسعة في مجال الصيانة العامة والإصلاحات المنزلية. يمتلك مهارات استثنائية في تشخيص الأعطال وحلها بكفاءة عالية. يتميز بالدقة في المواعيد والالتزام بأعلى معايير الجودة والسلامة في جميع الأعمال التي يقوم بها. يسعى دائماً لتقديم أفضل تجربة للعملاء وضمان رضاهم التام.' }}
                    </p>
                </div>

                <!-- 2. Available Services (الخدمات المتاحة) -->
                <div class="mb-4">
                    <h3 class="section-sub-title mb-3">الخدمات المتاحة</h3>
                    <div class="row g-3">
                        @php
                            $defaultServiceIcons = [
                                'سباكة' => [
                                    'icon' => 'fa-wrench',
                                    'title' => 'سباكة وصيانة الأنابيب',
                                    'desc' => 'إصلاح التسريبات، تركيب الأطقم، وتنظيف المواسير بدقة.'
                                ],
                                'كهرباء' => [
                                    'icon' => 'fa-bolt',
                                    'title' => 'تأسيس وصيانة كهرباء',
                                    'desc' => 'فحص الأعطال، تمديد الأسلاك، وتركيب الإضاءة والأجهزة.'
                                ],
                                'تكييف' => [
                                    'icon' => 'fa-snowflake',
                                    'title' => 'تكييف وتبريد',
                                    'desc' => 'تنظيف فلاتر، شحن فريون، وصيانة دورية للمكيفات.'
                                ],
                                'نجارة' => [
                                    'icon' => 'fa-hammer',
                                    'title' => 'أعمال صيانة عامة',
                                    'desc' => 'تركيب أثاث، إصلاح الأبواب، وأعمال النجارة الخفيفة.'
                                ],
                                'أجهزة منزلية' => [
                                    'icon' => 'fa-tv',
                                    'title' => 'صيانة أجهزة منزلية',
                                    'desc' => 'إصلاح الغسالات، الأفران، والثلاجات بدقة واحتراف.'
                                ],
                                'نظافة' => [
                                    'icon' => 'fa-broom',
                                    'title' => 'تنظيف وتعقيم شامل',
                                    'desc' => 'تنظيف شامل للمنازل، الواجهات، والمفروشات بأحدث المعدات.'
                                ],
                            ];
                        @endphp

                        @forelse($services ?? [] as $serv)
                            @php
                                $sName = $serv->name;
                                $iconMeta = $defaultServiceIcons[$sName] ?? [
                                    'icon' => $serv->icon ?? 'fa-tools',
                                    'title' => $serv->name,
                                    'desc' => $serv->description ?? 'صيانة وإصلاح بجودة عالية وسرعة فائقة.'
                                ];
                                $iconClass = str_starts_with($serv->icon ?? '', 'fa-') ? $serv->icon : ($iconMeta['icon'] ?? 'fa-tools');
                            @endphp
                            <div class="col-12 col-md-6">
                                <a href="{{ route('service.show', \App\Helpers\EncryptionHelper::encryptId($serv->id)) }}" class="service-chip-card text-decoration-none d-flex align-items-center justify-content-between">
                                    <div class="service-chip-content text-start">
                                        <h5 class="service-chip-title mb-1">{{ $iconMeta['title'] }}</h5>
                                        <p class="service-chip-desc mb-0">{{ $serv->description ?: $iconMeta['desc'] }}</p>
                                    </div>
                                    <div class="service-chip-icon">
                                        <i class="fas {{ $iconClass }}"></i>
                                    </div>
                                </a>
                            </div>
                        @empty
                            @foreach($defaultServiceIcons as $defKey => $defItem)
                                <div class="col-12 col-md-6">
                                    <div class="service-chip-card d-flex align-items-center justify-content-between">
                                        <div class="service-chip-content text-start">
                                            <h5 class="service-chip-title mb-1">{{ $defItem['title'] }}</h5>
                                            <p class="service-chip-desc mb-0">{{ $defItem['desc'] }}</p>
                                        </div>
                                        <div class="service-chip-icon">
                                            <i class="fas {{ $defItem['icon'] }}"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforelse
                    </div>
                </div>

                <!-- 3. Customer Reviews (آراء العملاء) -->
                <div class="reviews-section-wrapper">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="section-sub-title mb-0">آراء العملاء</h3>
                        <div class="d-flex align-items-center gap-3">
                            @auth
                                <button class="btn btn-sm btn-outline-warning text-dark fw-bold rounded-pill px-3 py-1" data-bs-toggle="modal" data-bs-target="#addReviewModal">
                                    <i class="fas fa-plus me-1"></i> أضف تقييم
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1">
                                    أضف تقييم
                                </a>
                            @endauth
                            <span class="text-muted small fw-bold cursor-pointer">عرض الكل <i class="fas fa-arrow-left ms-1"></i></span>
                        </div>
                    </div>

                    <div class="reviews-list-container">
                        @forelse($technician->reviews as $review)
                            @php
                                $reviewerName = $review->user_name ?? $review->user?->name ?? $review->customer?->name ?? 'عميل موثق';
                            @endphp
                            <div class="review-item-card mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="review-avatar-circle">
                                            {{ mb_substr($reviewerName, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="reviewer-name mb-0">{{ $reviewerName }}</h6>
                                            <small class="review-time-ago">{{ $review->created_at ? $review->created_at->diffForHumans() : 'مؤخراً' }}</small>
                                        </div>
                                    </div>
                                    <div class="review-star-box">
                                        @for($s = 1; $s <= 5; $s++)
                                            <i class="fas fa-star {{ $s <= $review->rating ? 'star-filled' : 'star-empty' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="review-comment-text mb-0">
                                        {{ $review->comment }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <div class="review-item-card text-center py-5 text-muted">
                                <i class="fas fa-comment-slash fs-2 mb-2 opacity-25"></i>
                                <p class="mb-1 fw-bold">لا توجد تقييمات مسجلة بعد</p>
                                <small>كن أول من يقيّم هذا الفني بعد إنجاز الخدمة</small>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- ========================================================================= -->
            <!-- RIGHT SIDEBAR: TECHNICIAN INFO CARD + SERVICE AREAS                       -->
            <!-- ========================================================================= -->
            <div class="col-lg-4 order-1 order-lg-2">
                
                <!-- Main Technician Card -->
                <div class="tech-profile-card">
                    <div class="tech-profile-header-gradient text-center">
                        <div class="tech-avatar-circle-wrapper">
                            <img src="{{ asset('images/tech-avatar.jpg') }}" alt="{{ $technician->user?->name ?? $technician->full_name }}" class="tech-avatar-img-circle">
                            <div class="tech-verified-check-badge" title="فني معتمد وموثق 100%">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                    </div>

                    <div class="tech-profile-body-content text-center p-4">
                        <h4 class="tech-profile-name mb-1">
                            {{ $technician->user?->name ?? $technician->full_name ?? 'أحمد ك.' }}
                        </h4>

                        <p class="tech-profile-specialization mb-3">
                            فني أول - {{ $technician->specialization?->name ?? 'صيانة عامة' }}
                        </p>

                        <div class="tech-rating-pill-container mb-4">
                            <i class="fas fa-star text-warning"></i>
                            <span class="rating-val fw-bold">{{ number_format($avgRating ?: ($technician->rating ?: 4.9), 1) }}</span>
                            <span class="rating-count-text">({{ $technician->reviews->count() ?: '120+' }} تقييم)</span>
                        </div>

                        <!-- Statistics Grid (عمل منجز + سنوات خبرة) -->
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <div class="tech-stat-chip-box">
                                    <div class="stat-number-val">+{{ $completedRequests ?: ($technician->completed_tasks ?: '120') }}</div>
                                    <div class="stat-label-val">عمل منجز</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="tech-stat-chip-box">
                                    <div class="stat-number-val">{{ $technician->experience_years ?: 5 }}</div>
                                    <div class="stat-label-val">سنوات خبرة</div>
                                </div>
                            </div>
                        </div>

                        <!-- Primary CTA Button (طلب خدمة من هذا الفني) -->
                        <a href="{{ route('requests.create', ['technician_id' => $technician->id]) }}" class="btn-book-technician-cta">
                            <i class="far fa-calendar-alt ms-1"></i>
                            <span>طلب خدمة من هذا الفني</span>
                        </a>
                    </div>
                </div>

                <!-- Service Areas Card (مناطق الخدمة) -->
                <div class="tech-service-areas-box p-4 mt-4">
                    <h5 class="service-areas-heading mb-3">
                        <i class="fas fa-map-marker-alt text-dark me-1"></i>
                        <span>مناطق الخدمة</span>
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        @php
                            $rawAddress = $technician->address ?: 'الرياض، جدة';
                            $areas = array_filter(array_map('trim', explode('،', str_replace(',', '،', $rawAddress))));
                            if (empty($areas)) {
                                $areas = ['الرياض', 'جدة'];
                            }
                        @endphp
                        @foreach($areas as $area)
                            <span class="service-area-pill">{{ $area }}</span>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

@auth
<!-- Add Review Modal -->
<div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="addReviewModalLabel">إضافة تقييم للفني</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('technician.review', \App\Helpers\EncryptionHelper::encryptId($technician->id)) }}" method="POST">
                @csrf
                <div class="modal-body py-3">
                    <div class="text-center mb-3">
                        <label class="form-label fw-bold d-block mb-2">تقييمك للفني</label>
                        <div class="rating-stars-input justify-content-center d-flex gap-2">
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
                        <label for="comment" class="form-label fw-semibold">تعليقك (اختياري)</label>
                        <textarea class="form-control rounded-3" id="comment" name="comment" rows="4" placeholder="شارك تجربتك مع هذا الفني لمساعدة العملاء الآخرين..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-brand-orange px-4">إرسال التقييم</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth
@endsection

@push('styles')
<style>
    /* Global Page Structure */
    .technician-profile-page {
        background-color: #fafbfd;
        min-height: calc(100vh - 80px);
    }

    .max-w-1200 {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Breadcrumbs */
    .tech-breadcrumb .breadcrumb {
        font-size: 13.5px;
        font-weight: 600;
        background: transparent;
        padding: 0;
    }

    .tech-breadcrumb .breadcrumb-item a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .tech-breadcrumb .breadcrumb-item a:hover {
        color: #0f172a;
    }

    .tech-breadcrumb .breadcrumb-item.active {
        color: #0f172a;
        font-weight: 700;
    }

    .tech-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        content: "‹";
        color: #94a3b8;
        padding: 0 8px;
        font-size: 14px;
    }

    /* Headings */
    .section-sub-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
    }

    /* Card Boxes */
    .tech-card-box {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.02);
    }

    .tech-bio-text {
        font-size: 14.5px;
        line-height: 1.85;
        color: #475569;
    }

    /* Service Chip Cards (Grid 2x2) */
    .service-chip-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 16px;
        padding: 16px 20px;
        height: 100%;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.015);
    }

    .service-chip-card:hover {
        transform: translateY(-3px);
        border-color: #cbd5e1;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
    }

    .service-chip-icon {
        width: 48px;
        height: 48px;
        background: #1e293b;
        color: #ffffff;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
        margin-inline-start: 14px;
        transition: transform 0.25s ease;
    }

    .service-chip-card:hover .service-chip-icon {
        transform: scale(1.08) rotate(3deg);
        background: #0f172a;
    }

    .service-chip-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
    }

    .service-chip-desc {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.5;
    }

    /* Review Cards */
    .review-item-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 18px;
        padding: 20px 24px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }

    .review-avatar-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .reviewer-name {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
    }

    .review-time-ago {
        font-size: 12px;
        color: #94a3b8;
    }

    .review-star-box {
        display: flex;
        gap: 3px;
        font-size: 14px;
    }

    .star-filled {
        color: #f59e0b;
    }

    .star-empty {
        color: #e2e8f0;
    }

    .review-comment-text {
        font-size: 14px;
        color: #334155;
        line-height: 1.7;
        margin-top: 10px;
    }

    /* Right Sidebar: Tech Profile Card */
    .tech-profile-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.03);
    }

    .tech-profile-header-gradient {
        background: linear-gradient(180deg, #dbeafe 0%, #f1f5f9 60%, #ffffff 100%);
        padding: 35px 20px 10px;
    }

    .tech-avatar-circle-wrapper {
        position: relative;
        width: 125px;
        height: 125px;
        margin: 0 auto;
    }

    .tech-avatar-img-circle {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #ffffff;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        background: #f1f5f9;
    }

    .tech-verified-check-badge {
        position: absolute;
        bottom: 3px;
        left: 4px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #0f172a;
        color: #ffffff;
        border: 2.5px solid #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .tech-profile-name {
        font-size: 23px;
        font-weight: 900;
        color: #0f172a;
    }

    .tech-profile-specialization {
        font-size: 14.5px;
        color: #64748b;
        font-weight: 600;
    }

    .tech-rating-pill-container {
        background: #fefce8;
        border: 1px solid #fef08a;
        color: #854d0e;
        font-size: 13.5px;
        font-weight: 700;
        padding: 6px 18px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .tech-stat-chip-box {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 14px;
        padding: 14px 10px;
        text-align: center;
    }

    .stat-number-val {
        font-size: 20px;
        font-weight: 900;
        color: #0f172a;
        line-height: 1.2;
    }

    .stat-label-val {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
        margin-top: 2px;
    }

    .btn-book-technician-cta {
        background: #ff8a00;
        background: linear-gradient(135deg, #ff9500 0%, #ff7a00 100%);
        color: #ffffff !important;
        font-weight: 800;
        font-size: 15.5px;
        padding: 14px 20px;
        border-radius: 14px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        box-shadow: 0 8px 22px rgba(255, 122, 0, 0.35);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
    }

    .btn-book-technician-cta:hover {
        background: linear-gradient(135deg, #ff8500 0%, #e66a00 100%);
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(255, 122, 0, 0.45);
    }

    /* Service Areas Card */
    .tech-service-areas-box {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }

    .service-areas-heading {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .service-area-pill {
        background: #eff6ff;
        color: #3b82f6;
        font-size: 13px;
        font-weight: 700;
        padding: 6px 18px;
        border-radius: 20px;
        display: inline-block;
    }

    /* Rating Stars Input for Modal */
    .rating-stars-input {
        flex-direction: row-reverse;
    }

    .rating-stars-input input[type="radio"] {
        display: none;
    }

    .rating-stars-input label {
        font-size: 2.2rem;
        color: #e2e8f0;
        cursor: pointer;
        transition: color 0.2s;
    }

    .rating-stars-input input[type="radio"]:checked ~ label,
    .rating-stars-input label:hover,
    .rating-stars-input label:hover ~ label {
        color: #f59e0b;
    }
</style>
@endpush
