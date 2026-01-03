@extends('layouts.mobile')

@section('title', 'خدمات - الرئيسية')

@section('content')
    <!-- Mobile Header (Hidden on Desktop) -->
    <div class="app-header glass d-md-none">
        <div class="header-icon-btn">
            <i class="fas fa-bars"></i>
        </div>
        <div class="app-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 60px; width: 100px;">
        </div>
        <a href="{{ route('notifications.index') }}" class="header-icon-btn position-relative text-decoration-none">
            <i class="fas fa-bell"></i>
            <span id="notification-badge"
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                style="font-size: 0.6rem; display: none;">
                0
                <span class="visually-hidden">unread messages</span>
            </span>
        </a>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                fetchNotificationsCount();
            });

            function fetchNotificationsCount() {
                fetch("{{ route('notifications.unread-count') }}")
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.getElementById('notification-badge');
                        if (data.count > 0) {
                            badge.innerText = data.count;
                            badge.style.display = 'inline-block';
                        } else {
                            badge.style.display = 'none';
                        }
                    })
                    .catch(error => console.error('Error fetching notifications:', error));
            }
        </script>
    @endpush

    <!-- Content -->
    <div class="app-content fade-in">

        <!-- Welcome Message -->
        <div class="mb-4 text-center text-md-start">
            <h4 class="fw-bold mb-2" style="color: var(--text-primary);">
                مرحباً بك <i class="fas fa-hand-sparkles text-warning ms-1"></i>
            </h4>
            <p class="text-muted mb-0" style="font-size: 1.1rem;">ما الخدمة التي تبحث عنها اليوم؟</p>
        </div>

        <!-- Promo Slider (Swiper) -->
        <div class="swiper promo-swiper mb-5">
            <div class="swiper-wrapper">
                @php
                    $gradients = [
                        'linear-gradient(135deg, #cc3333 0%, #ff6666 100%)',
                        'linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%)',
                        'linear-gradient(135deg, #f97316 0%, #ec4899 100%)',
                        'linear-gradient(135deg, #10b981 0%, #3b82f6 100%)',
                    ];
                @endphp
                @foreach ($offers as $index => $offer)
                    <div class="swiper-slide">
                        <div class="promo-banner" style="background: {{ $gradients[$index % count($gradients)] }};">
                            <div class="promo-overlay"></div>
                            <div class="promo-content">
                                <div class="promo-badge-sm">{{ $offer->badge_text ?? 'عرض خاص' }}</div>
                                <div class="promo-title">{{ $offer->title }}</div>
                                @if ($offer->subtitle_1)
                                    <div class="promo-subtitle">{{ $offer->subtitle_1 }}</div>
                                @endif
                                @if ($offer->discount_value)
                                    <div class="promo-discount-chip mt-3">
                                        <span>خصم</span>
                                        <span class="fw-bold mx-1 fs-5">{!! $offer->discount_value !!}</span>
                                    </div>
                                @endif
                            </div>
                            @if ($offer->icon)
                                <i class="fas {{ $offer->icon }} promo-illustration"></i>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>

        <!-- Services -->
        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
            <h5 class="m-0 fw-bold section-title">الخدمات</h5>
            <a href="{{ route('services.index') }}" class="view-all-link">عرض الكل <i
                    class="fas fa-arrow-left ms-1"></i></a>
        </div>

        <div class="services-grid row g-3 g-md-4 mb-5 px-2">
            @foreach ($services as $service)
                <div class="col-4 col-sm-4 col-md-3 col-lg-2">
                    <a href="{{ route('service.show', \App\Helpers\EncryptionHelper::encryptId($service->id)) }}" class="service-card-modern h-100 ">
                        <div class="icon-wrapper {{ $service->color_class }}">
                            <i class="fas {{ $service->icon }}"></i>
                        </div>
                        <div class="service-name">{{ $service->name }}</div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Reviews Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 px-1">
            <div class="d-flex align-items-center gap-3">
                <h5 class="m-0 fw-bold section-title">آراء العملاء</h5>
                @auth
                    @if (auth()->user()->isAdmin())
                        <div class="admin-badge">
                            <i class="fas fa-shield-alt me-1"></i>
                            <span>وضع الإدارة</span>
                        </div>
                    @endif
                @endauth
            </div>
            @auth
                @if (auth()->user()->isAdmin())
                    <div class="admin-controls">
                        <div class="admin-dropdown">
                            <button class="admin-toggle-btn" onclick="toggleAdminMenu()">
                                <i class="fas fa-tools"></i>
                                <span>أدوات الإدارة</span>
                                <i class="fas fa-chevron-down ms-1"></i>
                            </button>
                            <div class="admin-menu" id="adminMenu">
                                <button class="admin-menu-item" onclick="addNewReview()">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>إضافة مراجعة جديدة</span>
                                </button>
                                <button class="admin-menu-item" onclick="manageReviews()">
                                    <i class="fas fa-list-alt"></i>
                                    <span>إدارة جميع المراجعات</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth
        </div>

        <!-- Reviews Slider (Swiper) -->
        <div class="swiper reviews-swiper mb-4">
            <div class="swiper-wrapper">
                @foreach ($reviews as $review)
                    <div class="swiper-slide">
                        <div class="review-card-modern h-100 position-relative">
                            @auth
                                @if (auth()->user()->isAdmin())
                                    <div class="review-admin-actions">
                                        <div class="admin-actions-overlay">
                                            <button class="admin-action-btn edit-btn" onclick="editReview({{ $review->id }})"
                                                title="تعديل المراجعة">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="admin-action-btn delete-btn"
                                                onclick="deleteReview({{ $review->id }})" title="حذف المراجعة">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="admin-action-btn info-btn"
                                                onclick="reviewDetails({{ $review->id }})" title="تفاصيل المراجعة">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        </div>
                                        <div class="admin-indicator">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                    </div>
                                @endif
                            @endauth
                            <div class="d-flex align-items-center mb-3">
                                <div class="ms-3">
                                    <div class="review-name">{{ $review->user_name }}</div>
                                    <div class="review-rating">
                                        <i class="fas fa-star text-warning"></i> {{ $review->rating }}
                                    </div>
                                </div>
                            </div>
                            <div class="review-text">{{ Str::limit($review->comment, 150) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>

        <!-- Strategy Accordion Section -->
        <div class="mt-5 mb-4 px-2">
            <div class="d-flex justify-content-between align-items-center mb-4 px-1">
                <h5 class="m-0 fw-bold section-title">استراتيجيتنا للتميز</h5>
            </div>

            @php
                if (!function_exists('hex2rgba')) {
                    function hex2rgba($color, $opacity = false)
                    {
                        $default = 'rgb(0,0,0)';
                        if (empty($color)) {
                            return $default;
                        }
                        if ($color[0] == '#') {
                            $color = substr($color, 1);
                        }
                        if (strlen($color) == 6) {
                            $hex = [$color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]];
                        } elseif (strlen($color) == 3) {
                            $hex = [$color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]];
                        } else {
                            return $default;
                        }
                        $rgb = array_map('hexdec', $hex);
                        if ($opacity) {
                            if (abs($opacity) > 1) {
                                $opacity = 1.0;
                            }
                            $output = 'rgba(' . implode(',', $rgb) . ',' . $opacity . ')';
                        } else {
                            $output = 'rgb(' . implode(',', $rgb) . ')';
                        }
                        return $output;
                    }
                }
            @endphp

            <div class="accordion" id="strategyAccordion">
                @foreach ($strategies as $strategy)
                    <div class="accordion-item mb-3"
                        style="background: var(--surface-color); border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden;">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $strategy->id }}"
                                style="background: var(--surface-color); color: var(--text-primary); font-weight: 700;">
                                <span
                                    style="background: linear-gradient(135deg, {{ $strategy->color }}, {{ $strategy->color }}); color: white; width: 32px; height: 32px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-left: 12px; font-size: 14px; font-weight: 800;">{{ $strategy->step_number }}</span>
                                {{ $strategy->title }}
                            </button>
                        </h2>
                        <div id="collapse{{ $strategy->id }}"
                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                            data-bs-parent="#strategyAccordion">
                            <div class="accordion-body"
                                style="background: var(--surface-color); color: var(--text-secondary); padding: 20px;">
                                @if ($strategy->description)
                                    <p class="mb-3"><strong>{{ $strategy->description }}</strong></p>
                                @endif
                                @if ($strategy->points)
                                    @foreach ($strategy->points as $point)
                                        <div class="mb-3"
                                            style="background: {{ hex2rgba($point['color'], 0.05) }}; padding: 15px; border-radius: 8px; border-right: 3px solid {{ $point['color'] }};">
                                            <h6
                                                style="color: {{ $point['color'] }}; font-weight: 700; margin-bottom: 10px;">
                                                <i class="fas {{ $point['icon'] }} me-2"></i>{{ $point['title'] }}
                                            </h6>
                                            <ul style="margin: 0; padding-right: 20px;">
                                                @foreach ($point['items'] as $item)
                                                    <li>{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
    <!-- End of app-content -->

    <footer
        style="background: var(--surface-color); border-top: 1px solid var(--border-color); width: 100%; margin: 0; padding: 3rem 0;">
        <div class="container-fluid px-4 px-lg-5">
            <div class="row g-5 mb-4 ">
                <!-- Logo & About -->
                <div class="col-lg-3 col-md-6 text-center" style="margin-top: 15px;">
                    <div class="mb-2 ">
                        <div class="mb-1">
                            <img src="{{ asset('images/logo.png') }}" alt="logo" style="width: 150px; height:70px;">
                        </div>
                        <p class="text-muted fs-6">
                            منصتك الأولى لخدمات الصيانة المنزلية. نربطك بأفضل الفنيين المحترفين لضمان جودة العمل وراحة
                            البال.
                        </p>
                    </div>
                    <div class="d-flex gap-3 fs-5 justify-content-center">
                        <a href="#" class="text-muted" style="transition: color 0.3s;"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-muted" style="transition: color 0.3s;"><i
                                class="fab fa-twitter"></i></a>
                        <a href="#" class="text-muted" style="transition: color 0.3s;"><i
                                class="fab fa-instagram"></i></a>
                        <a href="#" class="text-muted" style="transition: color 0.3s;"><i
                                class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-3 col-md-6 text-center">
                    <h5 class="fw-bold mb-3" style="color: #cc3333">روابط سريعة</h5>
                    <ul class="list-unstyled fs-6">
                        <li class="mb-2"><a href="{{ url('/') }}" class="text-muted"
                                style="text-decoration: none; transition: color 0.3s;">الرئيسية</a></li>
                        <li class="mb-2"><a href="{{ route('services.index') }}" class="text-muted"
                                style="text-decoration: none; transition: color 0.3s;">الخدمات</a></li>
                        <li class="mb-2"><a href="{{ route('offers.index') }}" class="text-muted"
                                style="text-decoration: none; transition: color 0.3s;">العروض</a></li>
                        <li class="mb-2"><a href="#strategyAccordion" class="text-muted"
                                style="text-decoration: none; transition: color 0.3s;">استراتيجيتنا</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div class="col-lg-3 col-md-6 text-center">
                    <h5 class="fw-bold mb-3" style="color: #cc3333">الخدمات</h5>
                    <ul class="list-unstyled fs-6">
                        <li class="mb-2"><a href="{{ route('services.index') }}" class="text-muted"
                                style="text-decoration: none; transition: color 0.3s;">سباكة</a></li>
                        <li class="mb-2"><a href="{{ route('services.index') }}" class="text-muted"
                                style="text-decoration: none; transition: color 0.3s;">كهرباء</a></li>
                        <li class="mb-2"><a href="{{ route('services.index') }}" class="text-muted"
                                style="text-decoration: none; transition: color 0.3s;">تكييف</a></li>
                        <li class="mb-2"><a href="{{ route('services.index') }}" class="text-muted"
                                style="text-decoration: none; transition: color 0.3s;">نظافة</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="col-lg-3 col-md-6 text-center">
                    <h5 class="fw-bold mb-3" style="color: #cc3333">تواصل معنا</h5>
                    <ul class="list-unstyled fs-6">
                        <li class="mb-3">
                            <i class="fas fa-phone me-2 " style="color: #ff7f7f"></i>
                            <span class="text-muted">01000000000</span>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-envelope me-2 " style="color: #ff7f7f"></i>
                            <span class="text-muted">khadamat@gmail.com</span>
                        </li>
                        <li>
                            <i class="fas fa-map-marker-alt me-2 " style="color: #ff7f7f"></i>
                            <span class="text-muted">جمهورية مصر العربية</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </footer>
@endsection



@push('styles')
    <style>
        /* Section Titles */
        .section-title {
            font-size: 20px;
            color: var(--text-primary);
            font-weight: 800;
            position: relative;
            padding-right: 15px;
        }

        .section-title::before {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 24px;
            background: var(--primary-color);
            border-radius: 4px;
        }

        .view-all-link {
            font-size: 14px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 700;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }

        .view-all-link:hover {
            opacity: 0.8;
            transform: translateX(-5px);
        }

        /* Promo Slider */
        .promo-swiper {
            padding: 20px 0 50px !important;
            width: 100vw;
            position: relative;
            left: 50%;
            margin-left: -50vw;
            overflow-x: hidden;
        }

        .promo-banner {
            border-radius: 20px;
            position: relative;
            overflow: hidden;
            height: 200px;
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Swiper Slide Sizing */
        .promo-swiper .swiper-slide {
            width: 85%;
            max-width: 400px;
        }

        .reviews-swiper .swiper-slide {
            width: 80%;
            max-width: 350px;
            height: auto;
            opacity: 0.5;
            transition: opacity 0.3s ease, transform 0.3s ease;
            transform: scale(0.9);
        }

        .reviews-swiper .swiper-slide-active {
            opacity: 1;
            transform: scale(1);
        }

        .promo-banner:active {
            transform: scale(0.98);
        }

        .promo-content {
            position: relative;
            z-index: 2;
            padding: 28px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            color: white;
        }

        .promo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0) 100%);
            z-index: 1;
        }

        .promo-badge-sm {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(4px);
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 12px;
            display: inline-block;
            letter-spacing: 0.5px;
        }

        .promo-title {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .promo-subtitle {
            font-size: 15px;
            opacity: 0.95;
            font-weight: 500;
            margin-bottom: 12px;
        }

        .promo-discount-chip {
            background: white;
            color: var(--text-primary);
            padding: 8px 20px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            font-weight: 800;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            margin-top: auto;
        }

        .promo-illustration {
            position: absolute;
            left: -30px;
            bottom: -30px;
            font-size: 160px;
            opacity: 0.12;
            color: white;
            z-index: 0;
            transform: rotate(10deg);
        }

        .service-card-modern {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .service-card-modern:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-color);
        }

        .icon-wrapper {
            width: 65px;
            height: 65px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin-bottom: 16px;
            transition: transform 0.3s ease;
            position: relative;
            z-index: 1;
            /* Fallback if tailwind classes fail */
            background-color: var(--bg-color);
            color: var(--primary-color);
        }

        /* Tailwind Fallback Colors */
        .bg-red-100 {
            background-color: #fee2e2 !important;
        }

        .text-red-600 {
            color: #dc2626 !important;
        }

        .bg-blue-100 {
            background-color: #dbeafe !important;
        }

        .text-blue-600 {
            color: #2563eb !important;
        }

        .bg-green-100 {
            background-color: #dcfce7 !important;
        }

        .text-green-600 {
            color: #16a34a !important;
        }

        .bg-amber-100 {
            background-color: #fef3c7 !important;
        }

        .text-amber-600 {
            color: #d97706 !important;
        }

        .bg-purple-100 {
            background-color: #f3e8ff !important;
        }

        .text-purple-600 {
            color: #9333ea !important;
        }

        .service-card-modern:hover .icon-wrapper {
            transform: scale(1.1) rotate(5deg);
        }

        .service-name {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 15px;
            text-align: center;
        }

        /* Simple Color Classes (Fallback for non-Tailwind) */
        .icon-wrapper.blue {
            background: #e0f2fe;
            color: #0284c7;
        }

        .icon-wrapper.red {
            background: #ffe4e6;
            color: #e11d48;
        }

        .icon-wrapper.yellow {
            background: #fef9c3;
            color: #ca8a04;
        }

        .icon-wrapper.green {
            background: #dcfce7;
            color: #16a34a;
        }

        .icon-wrapper.purple {
            background: #f3e8ff;
            color: #9333ea;
        }

        .icon-wrapper.orange {
            background: #ffedd5;
            color: #ea580c;
        }

        .icon-wrapper.cyan {
            background: #cffafe;
            color: #0891b2;
        }

        .icon-wrapper.teal {
            background: #ccfbf1;
            color: #0d9488;
        }

        /* Review Cards */
        .reviews-swiper {
            padding-bottom: 50px !important;
        }

        .review-card-modern {
            background: #ffffff;
            border: none;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .review-avatar {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f8fafc;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .review-name {
            font-weight: 800;
            color: var(--text-primary);
            font-size: 17px;
            margin-bottom: 2px;
        }

        .review-rating {
            font-size: 14px;
            color: #f59e0b;
            margin-top: 4px;
        }

        .review-text {
            color: var(--text-secondary);
            font-size: 15px;
            line-height: 1.7;
            margin-top: 15px;
        }

        /* Custom Pagination */
        .swiper-pagination-bullet {
            width: 8px;
            height: 8px;
            background: #cbd5e1;
            opacity: 1;
            transition: all 0.3s ease;
        }

        .swiper-pagination-bullet-active {
            width: 24px;
            border-radius: 4px;
            background: #3b82f6;
            /* Blue like in image */
        }

        /* Admin Badges */
        .admin-badge {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .admin-toggle-btn {
            background: white;
            border: 1px solid var(--border-color);
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .admin-toggle-btn:hover {
            background: #f8fafc;
            border-color: var(--text-muted);
        }

        /* Admin Menu Dropdown */
        .admin-dropdown {
            position: relative;
        }

        .admin-menu {
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 8px;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            width: 220px;
            padding: 8px;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.2s;
        }

        .admin-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .admin-menu-item {
            width: 100%;
            text-align: right;
            background: none;
            border: none;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--text-secondary);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
        }

        .admin-menu-item:hover {
            background: #f1f5f9;
            color: var(--primary-color);
        }

        .admin-menu-divider {
            height: 1px;
            background: var(--border-color);
            margin: 6px 0;
        }

        /* Review Admin Actions */
        .review-admin-actions {
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 10;
        }

        .admin-indicator {
            width: 32px;
            height: 32px;
            background: rgba(15, 23, 42, 0.1);
            color: var(--text-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            backdrop-filter: blur(4px);
            transition: all 0.3s ease;
        }

        .review-card-modern:hover .admin-indicator {
            opacity: 0;
        }

        .admin-actions-overlay {
            position: absolute;
            top: 0;
            left: 0;
            display: flex;
            gap: 8px;
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            pointer-events: none;
        }

        .review-card-modern:hover .admin-actions-overlay {
            opacity: 1;
            transform: scale(1);
            pointer-events: auto;
        }

        .admin-action-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            font-size: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .admin-action-btn:hover {
            transform: translateY(-2px);
        }

        .edit-btn {
            background: #3b82f6;
        }

        .delete-btn {
            background: #ef4444;
        }

        .info-btn {
            background: #64748b;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Promo Slider
            const promoSwiper = new Swiper('.promo-swiper', {
                slidesPerView: 'auto',
                centeredSlides: true,
                spaceBetween: 25,
                loop: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.promo-swiper .swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        spaceBetween: 40,
                    }
                }
            });

            // Reviews Slider
            const reviewsSwiper = new Swiper('.reviews-swiper', {
                slidesPerView: 'auto',
                centeredSlides: true,
                spaceBetween: 25,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.reviews-swiper .swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        spaceBetween: 40,
                    }
                }
            });
        });

        // ... admin functions remain ...

        function toggleAdminMenu() {
            const menu = document.getElementById('adminMenu');
            menu.classList.toggle('show');

            // Close menu when clicking outside
            document.addEventListener('click', function closeMenu(e) {
                if (!e.target.closest('.admin-dropdown')) {
                    menu.classList.remove('show');
                    document.removeEventListener('click', closeMenu);
                }
            });
        }

        function addNewReview() {
            // Navigate to the create review page
            window.location.href = '/admin/reviews/create';
        }

        function manageReviews() {
            // Navigate to the reviews management page
            window.location.href = '/admin/reviews';
        }

        function exportReviews() {
            // Trigger the export route
            window.location.href = '/admin/reviews/export';
        }

        function reviewSettings() {
            // For now, show an alert but this could navigate to settings page
            alert('سيتم فتح إعدادات المراجعات والتحكم بالمظهر');
        }

        function deleteReview(reviewId) {
            if (confirm('هل أنت متأكد من حذف هذه المراجعة؟\nلا يمكن التراجع عن هذا الإجراء.')) {
                // Create a form dynamically and submit it via POST with DELETE method
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/reviews/${reviewId}`;

                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = csrfToken.content;
                    form.appendChild(tokenInput);
                }

                // Add method spoofing for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Submit the form
                document.body.appendChild(form);
                form.submit();
            }
        }

        function editReview(reviewId) {
            // Navigate to the edit review page
            window.location.href = `/admin/reviews/${reviewId}/edit`;
        }

        function reviewDetails(reviewId) {
            // Navigate to the review details page
            window.location.href = `/admin/reviews/${reviewId}`;
        }
    </script>
@endpush
