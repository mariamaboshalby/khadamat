@extends('layouts.mobile')

@section('title', 'خدمتي - كل خدمات بيتك في مكان واحد')

{{-- ================================================================
     LCP PRELOAD — must be in <head> before anything else.
     This tells the browser to fetch the hero image at highest priority
     the moment it parses the <head>, dramatically improving LCP.
     We use a <picture>-based preload with media queries so mobile
     gets the smaller image (hero-bg-mobile.webp / .jpg).
================================================================ --}}
@push('preloads')
    {{-- ============================================================
         LCP IMAGE PRELOAD — emitted into <head> before @stack('styles').
         Browser spec: <link rel="preload" as="image"> with imagesrcset
         + imagesizes is the Responsive Image Preload (RIP) spec.
         imagesizes is REQUIRED when imagesrcset is used — without it
         the preload is treated as invalid and ignored by Chromium.
         fetchpriority="high" tells the browser to assign the highest
         network priority to this fetch.
    ============================================================ --}}
    {{-- Mobile: preload small WebP hero (≤768px screens) --}}
    <link rel="preload" as="image"
          href="{{ asset('images/hero-bg-mobile.webp') }}"
          imagesrcset="{{ asset('images/hero-bg-mobile.webp') }}"
          imagesizes="100vw"
          media="(max-width: 768px)"
          type="image/webp"
          fetchpriority="high">
    {{-- Desktop: preload full WebP hero (≥769px screens) --}}
    <link rel="preload" as="image"
          href="{{ asset('images/hero-bg.webp') }}"
          imagesrcset="{{ asset('images/hero-bg.webp') }}"
          imagesizes="100vw"
          media="(min-width: 769px)"
          type="image/webp"
          fetchpriority="high">
@endpush

@section('content')
    {{-- ============================================================
         MOBILE APP HEADER (small screens only)
    ============================================================ --}}
    <div class="app-header d-md-none">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo.webp') }}" alt="خدمتي"
                     style="height: 38px; width: auto;" width="80" height="38"
                     loading="eager" decoding="sync">
            </a>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('requests.create') }}" class="btn-brand-orange py-1 px-3" style="font-size: 13px;">
                <i class="fas fa-plus"></i> طلب
            </a>
            @auth
            <a href="{{ route('notifications.index') }}" class="header-icon-btn position-relative text-decoration-none">
                <i class="fas fa-bell"></i>
                <span id="notification-badge"
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    style="font-size: 0.6rem; display: none;">
                    0
                </span>
            </a>
            @endauth
        </div>
    </div>

    {{-- ============================================================
         HERO SECTION
         KEY CHANGES:
         - Hero background is now a real <img> element with
           fetchpriority="high" so the browser discovers it in HTML
           parsing (not after CSSOM build) — this is the #1 LCP fix.
         - Uses <picture> + srcset for mobile/desktop variants.
         - WebP with JPG fallback.
         - Explicit width/height to prevent CLS.
         - Floating badges hidden on mobile (d-none d-lg-flex) to
           reduce DOM work and GPU layers on small screens.
         - Ambient glows removed on mobile (media query).
    ============================================================ --}}
    <section class="hero-section position-relative overflow-hidden text-center text-white">
        {{-- LCP Image: real <img> so browser discovers it immediately --}}
        <picture>
            <source
                media="(max-width: 768px)"
                srcset="{{ asset('images/hero-bg-mobile.webp') }}"
                type="image/webp">
            <source
                media="(min-width: 769px)"
                srcset="{{ asset('images/hero-bg.webp') }}"
                type="image/webp">
            <img
                src="{{ asset('images/hero-bg.webp') }}"
                alt=""
                class="hero-bg-img"
                width="1440"
                height="900"
                fetchpriority="high"
                decoding="async"
                loading="eager">
        </picture>

        <div class="hero-bg-overlay"></div>
        {{-- Ambient glows: CSS-only, hidden on mobile via media query to save GPU --}}
        <div class="hero-ambient-glow glow-1"></div>
        <div class="hero-ambient-glow glow-2"></div>

        {{-- Floating Decorative Badges — Desktop only (d-none d-lg-flex) --}}
        <div class="hero-float-badge hero-float-1 d-none d-lg-flex">
            <div class="badge-icon-wrap bg-amber">
                <i class="fas fa-bolt text-warning"></i>
            </div>
            <div class="text-start">
                <span class="badge-title">استجابة فائقة</span>
                <small class="badge-sub">أقل من 30 دقيقة</small>
            </div>
        </div>

        <div class="hero-float-badge hero-float-2 d-none d-lg-flex">
            <div class="badge-icon-wrap bg-cyan">
                <i class="fas fa-shield-alt text-info"></i>
            </div>
            <div class="text-start">
                <span class="badge-title">ضمان معتمد 100%</span>
                <small class="badge-sub">صيانة موثوقة وآمنة</small>
            </div>
        </div>

        <div class="hero-float-badge hero-float-3 d-none d-lg-flex">
            <div class="badge-icon-wrap bg-purple">
                <i class="fas fa-star text-warning"></i>
            </div>
            <div class="text-start">
                <span class="badge-title">تقييم 4.9 / 5</span>
                <small class="badge-sub">+15,000 عميل راضٍ</small>
            </div>
        </div>

        <div class="container position-relative z-2 hero-content-wrapper">
            <div class="d-inline-flex align-items-center gap-2 hero-pill-badge mb-4">
                <i class="fas fa-shield-check text-warning hero-badge-icon"></i>
                <span>مضمون 100%</span>
            </div>

            <h1 class="hero-main-title mb-3">
                <span class="hero-title-line d-inline-block">كل خدمات بيتك</span><br>
                <span class="hero-title-highlight d-inline-block">في مكان واحد</span>
            </h1>

            <p class="hero-subtitle mb-4 mx-auto">
                اطلب فني متخصص للصيانة والسباكة والكهرباء والتكييف وغيرها، بسهولة وأمان، خدمات موثوقة بضغطة زر.
            </p>

            <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mt-4 hero-action-buttons">
                <a href="{{ route('requests.create') }}" class="hero-btn-primary">
                    <span>اطلب خدمة الآن</span>
                    <i class="fas fa-arrow-left"></i>
                </a>
                <a href="#services" class="hero-btn-secondary">
                    <span>استكشف الخدمات</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================================
         POPULAR SERVICES SECTION
    ============================================================ --}}
    <section id="services" class="py-5 px-3 px-md-5">
        <div class="container-fluid max-w-1200">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <div class="section-badge mb-2">خدماتنا</div>
                    <h2 class="section-heading mb-0">خدمات الصيانة الأكثر طلباً</h2>
                </div>
                <a href="{{ route('services.index') }}" class="view-all-btn">
                    <span>عرض كل الخدمات</span>
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            <div class="row g-4">
                @php
                    $defaultServiceMeta = [
                        'سباكة' => ['icon' => 'fa-wrench',    'bg' => 'icon-bg-slate',  'desc' => 'إصلاح تسريبات، تركيب أدوات صحية، وتسليك مجاري بكفاءة عالية.'],
                        'كهرباء' => ['icon' => 'fa-bolt',     'bg' => 'icon-bg-amber',  'desc' => 'تأسيس وصيانة كهرباء، تركيب إضاءات، فحص وحل أعطال الكابلات.'],
                        'تكييف' => ['icon' => 'fa-snowflake', 'bg' => 'icon-bg-cyan',   'desc' => 'تنظيف، شحن فريون، وصيانة شاملة لجميع أنواع المكيفات.'],
                        'نجارة' => ['icon' => 'fa-hammer',    'bg' => 'icon-bg-purple', 'desc' => 'صيانة وتصليح الأثاث، تركيب أبواب ونوافذ بدقة واحترافية.'],
                        'نظافة' => ['icon' => 'fa-broom',     'bg' => 'icon-bg-teal',   'desc' => 'خدمات تنظيف شاملة للمنازل والواجهات بأحدث المعدات.'],
                        'أجهزة منزلية' => ['icon' => 'fa-tv','bg' => 'icon-bg-rose',   'desc' => 'صيانة الغسالات، الثلاجات، والأفران بقطع غيار أصلية.'],
                    ];
                @endphp

                @forelse ($services as $service)
                    @php
                        $meta = $defaultServiceMeta[$service->name] ?? [
                            'icon' => $service->icon ?? 'fa-tools',
                            'bg'   => 'icon-bg-slate',
                            'desc' => $service->description ?? 'خدمات صيانة متخصصة وعالية الجودة لكافة احتياجات منزلك.',
                        ];
                        $iconClass = str_starts_with($service->icon ?? '', 'fa-') ? $service->icon : ($meta['icon'] ?? 'fa-tools');
                    @endphp
                    <div class="col-12 col-sm-6 col-lg-3">
                        <a href="{{ route('service.show', \App\Helpers\EncryptionHelper::encryptId($service->id)) }}" class="service-feature-card text-decoration-none">
                            <div class="service-icon-box {{ $meta['bg'] }}">
                                <i class="fas {{ $iconClass }}"></i>
                            </div>
                            <h3 class="service-card-title">{{ $service->name }}</h3>
                            <p class="service-card-desc">{{ $service->description ?: $meta['desc'] }}</p>
                        </a>
                    </div>
                @empty
                    @foreach ($defaultServiceMeta as $name => $meta)
                        <div class="col-12 col-sm-6 col-lg-3">
                            <a href="{{ route('services.index') }}" class="service-feature-card text-decoration-none">
                                <div class="service-icon-box {{ $meta['bg'] }}">
                                    <i class="fas {{ $meta['icon'] }}"></i>
                                </div>
                                <h3 class="service-card-title">{{ $name }}</h3>
                                <p class="service-card-desc">{{ $meta['desc'] }}</p>
                            </a>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    {{-- ============================================================
         HOW IT WORKS SECTION
    ============================================================ --}}
    <section id="how-it-works" class="py-5 px-3 px-md-5 how-it-works-bg">
        <div class="container-fluid max-w-1200 text-center">
            <div class="section-badge mx-auto mb-2">خطوات بسيطة</div>
            <h2 class="section-heading mb-5">كيف تعمل خدمتي؟</h2>

            <div class="stepper-container position-relative">
                <div class="stepper-line d-none d-md-block">
                    <div class="stepper-line-fill"></div>
                </div>

                <div class="row g-4 justify-content-center">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="step-card">
                            <div class="step-number-circle step-circle-dark">1</div>
                            <h4 class="step-title">اختر الخدمة</h4>
                            <p class="step-desc">حدد نوع الصيانة أو الخدمة التي تحتاجها من قائمتنا الشاملة</p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="step-card">
                            <div class="step-number-circle step-circle-white">2</div>
                            <h4 class="step-title">حدد الموقع والوقت</h4>
                            <p class="step-desc">أدخل عنوانك واختر الموعد المناسب لزيارة الفني.</p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="step-card">
                            <div class="step-number-circle step-circle-white">3</div>
                            <h4 class="step-title">اختر الفني</h4>
                            <p class="step-desc">قارن بين الفنيين المتاحين بناءً على التقييمات والأسعار.</p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="step-card">
                            <div class="step-number-circle step-circle-orange">4</div>
                            <h4 class="step-title">التنفيذ والدفع</h4>
                            <p class="step-desc">يتم إنجاز العمل باحترافية ثم ادفع بطريقة آمنة وقيّم الخدمة</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         WHY CHOOSE US SECTION
         technician-hero.jpg: below the fold — lazy loaded with
         explicit width/height to prevent CLS.
    ============================================================ --}}
    <section id="why-us" class="py-5 px-3 px-md-5">
        <div class="container-fluid max-w-1200">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-2 order-lg-1">
                    <div class="section-badge mb-2">لماذا نحن</div>
                    <h2 class="section-heading mb-4">معايير جودة لا نساوم عليها</h2>

                    <div class="row g-4 mt-2">
                        <div class="col-12 col-sm-6">
                            <div class="quality-item d-flex align-items-start gap-3">
                                <div class="quality-icon-box">
                                    <i class="fas fa-shield-alt text-primary"></i>
                                </div>
                                <div>
                                    <h4 class="quality-title">فنيون موثوقون</h4>
                                    <p class="quality-desc">تم فحص خلفياتهم وتدريبهم بعناية.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="quality-item d-flex align-items-start gap-3">
                                <div class="quality-icon-box">
                                    <i class="fas fa-receipt text-warning"></i>
                                </div>
                                <div>
                                    <h4 class="quality-title">أسعار واضحة</h4>
                                    <p class="quality-desc">لا توجد رسوم خفية، التسعير شفاف.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="quality-item d-flex align-items-start gap-3">
                                <div class="quality-icon-box">
                                    <i class="fas fa-stopwatch text-info"></i>
                                </div>
                                <div>
                                    <h4 class="quality-title">استجابة سريعة</h4>
                                    <p class="quality-desc">نصلك في الوقت المحدد دون تأخير.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="quality-item d-flex align-items-start gap-3">
                                <div class="quality-icon-box">
                                    <i class="fas fa-award text-success"></i>
                                </div>
                                <div>
                                    <h4 class="quality-title">ضمان الخدمة</h4>
                                    <p class="quality-desc">ضمان على جميع الإصلاحات المعتمدة.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 order-1 order-lg-2">
                    <div class="technician-hero-wrapper position-relative">
                        <div class="technician-img-container">
                            {{-- Below the fold: lazy loaded, explicit dimensions to prevent CLS --}}
                            <picture>
                                <source srcset="{{ asset('images/technician-hero.webp') }}" type="image/webp">
                                <img src="{{ asset('images/technician-hero.jpg') }}"
                                     alt="فني خدمتي المعتمد"
                                     class="img-fluid rounded-4 shadow-lg w-100"
                                     width="747" height="1000"
                                     loading="lazy"
                                     decoding="async">
                            </picture>
                        </div>

                        <div class="floating-exp-badge shadow-lg d-none d-sm-flex">
                            <i class="fas fa-certificate text-primary"></i>
                            <span>فنيون معتمدون ومفحوصون 100%</span>
                        </div>

                        <div class="floating-rating-badge shadow-xl">
                            <div class="rating-number">
                                <i class="fas fa-star text-warning rating-star-pulse"></i>
                                <span class="rating-counter-val">4.9</span><span>/5</span>
                            </div>
                            <div class="rating-label">متوسط تقييم العملاء</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         OFFERS PROMO SECTION
    ============================================================ --}}
    @if(count($offers) > 0)
    <section id="offers-section" class="py-5 px-3 px-md-5">
        <div class="container-fluid max-w-1200">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <div class="section-badge mb-2">عروض حصرية</div>
                    <h2 class="section-heading mb-0">أحدث العروض والخصومات</h2>
                </div>
                <a href="{{ route('offers.index') }}" class="view-all-btn">
                    <span>كل العروض</span>
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            <div class="swiper promo-swiper">
                <div class="swiper-wrapper">
                    @php
                        $gradients = [
                            'linear-gradient(135deg, #0b5f8a 0%, #1e3a8a 100%)',
                            'linear-gradient(135deg, #ea580c 0%, #f97316 100%)',
                            'linear-gradient(135deg, #0d9488 0%, #06b6d4 100%)',
                            'linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%)',
                        ];
                    @endphp
                    @foreach ($offers as $index => $offer)
                        <div class="swiper-slide">
                            <div class="promo-banner" style="background: {{ $gradients[$index % count($gradients)] }};">
                                <div class="promo-overlay"></div>
                                <div class="promo-content">
                                    <div class="promo-badge-sm">{{ $offer->badge_text ?? 'عرض خاص' }}</div>
                                    <h3 class="promo-title">{{ $offer->title }}</h3>
                                    @if ($offer->subtitle_1)
                                        <p class="promo-subtitle">{{ $offer->subtitle_1 }}</p>
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
        </div>
    </section>
    @endif

    {{-- ============================================================
         CUSTOMER REVIEWS SECTION
    ============================================================ --}}
    @if(count($reviews) > 0)
    <section class="py-5 px-3 px-md-5 bg-slate-subtle">
        <div class="container-fluid max-w-1200 text-center">
            <div class="section-badge mx-auto mb-2">ثقة العملاء</div>
            <h2 class="section-heading mb-2">ماذا يقول عملاؤنا؟</h2>
            <p class="section-subheading mb-5">تجارب حقيقية لعملاء وثقوا بخدماتنا واعتمدوا على جودتنا.</p>

            <div class="swiper reviews-swiper">
                <div class="swiper-wrapper">
                    @foreach ($reviews as $review)
                        <div class="swiper-slide">
                            <div class="modern-review-card text-start">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="review-avatar-circle">
                                            {{ mb_substr($review->user_name ?? 'ع', 0, 1) }}
                                        </div>
                                        <div>
                                            <h5 class="review-client-name mb-0">{{ $review->user_name }}</h5>
                                            <small class="text-muted">عميل موثق <i class="fas fa-check-circle text-success ms-1"></i></small>
                                        </div>
                                    </div>
                                    <div class="review-stars-box">
                                        @for ($s = 1; $s <= 5; $s++)
                                            <i class="fas fa-star {{ $s <= $review->rating ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="review-quote-text">
                                    "{{ Str::limit($review->comment, 160) }}"
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination mt-4"></div>
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================================
         STRATEGY / VALUE PILLARS SECTION
    ============================================================ --}}
    @if(count($strategies) > 0)
    <section class="py-5 px-3 px-md-5">
        <div class="container-fluid max-w-1200">
            <div class="section-badge mb-2">رؤيتنا</div>
            <h2 class="section-heading mb-4">استراتيجيتنا للتميز</h2>

            <div class="accordion modern-accordion" id="strategyAccordion">
                @foreach ($strategies as $strategy)
                    <div class="accordion-item mb-3 border-0 rounded-4 overflow-hidden shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }} fw-bold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $strategy->id }}">
                                <span class="strategy-badge-step me-3" style="background: {{ $strategy->color ?? '#0b5f8a' }};">
                                    {{ $strategy->step_number }}
                                </span>
                                {{ $strategy->title }}
                            </button>
                        </h2>
                        <div id="collapse{{ $strategy->id }}"
                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                            data-bs-parent="#strategyAccordion">
                            <div class="accordion-body bg-white text-secondary p-4">
                                @if ($strategy->description)
                                    <p class="mb-3"><strong>{{ $strategy->description }}</strong></p>
                                @endif
                                @if ($strategy->points)
                                    @foreach ($strategy->points as $point)
                                        <div class="mb-3 p-3 rounded-3" style="background: #f8fafc; border-right: 4px solid {{ $point['color'] ?? '#0b5f8a' }};">
                                            <h6 style="color: {{ $point['color'] ?? '#0b5f8a' }}; font-weight: 700;">
                                                <i class="fas {{ $point['icon'] ?? 'fa-check' }} me-2"></i>{{ $point['title'] }}
                                            </h6>
                                            <ul class="mb-0 pe-3">
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
    </section>
    @endif

    {{-- ============================================================
         FOOTER
    ============================================================ --}}
    <footer class="main-footer py-5">
        <div class="container-fluid max-w-1200 px-4">
            <div class="row g-5 mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand mb-3">
                        <img src="{{ asset('images/logo.webp') }}" alt="خدمتي"
                             style="height: 48px; width: auto;"
                             width="100" height="48"
                             loading="lazy" decoding="async">
                    </div>
                    <p class="text-secondary fs-6 mb-4">
                        منصتك الأولى المعتمدة لخدمات الصيانة والتشغيل المنزلي. نربطك بأفضل الفنيين المحترفين لضمان جودة العمل، راحة البال، وتوفير الوقت.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="#" class="social-icon-btn" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon-btn" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon-btn" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon-btn" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-6">
                    <h5 class="footer-col-title">روابط سريعة</h5>
                    <ul class="list-unstyled footer-nav-list">
                        <li><a href="{{ route('home') }}">الرئيسية</a></li>
                        <li><a href="{{ route('services.index') }}">الخدمات</a></li>
                        <li><a href="#how-it-works">كيف نعمل؟</a></li>
                        <li><a href="#why-us">لماذا نحن</a></li>
                        <li><a href="{{ route('offers.index') }}">العروض</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 col-6">
                    <h5 class="footer-col-title">خدمات الصيانة</h5>
                    <ul class="list-unstyled footer-nav-list">
                        <li><a href="{{ route('services.index') }}">صيانة وتأسيس السباكة</a></li>
                        <li><a href="{{ route('services.index') }}">أعمال الكهرباء والإضاءة</a></li>
                        <li><a href="{{ route('services.index') }}">صيانة وتنظيف المكيفات</a></li>
                        <li><a href="{{ route('services.index') }}">النجارة وتركيب الأثاث</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-col-title">تواصل معنا</h5>
                    <ul class="list-unstyled footer-contact-list">
                        <li class="d-flex align-items-center gap-3 mb-3">
                            <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                            <span>01067596149</span>
                        </li>
                        <li class="d-flex align-items-center gap-3 mb-3">
                            <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                            <span>support@khadamat.com</span>
                        </li>
                        <li class="d-flex align-items-center gap-3">
                            <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <span>مصر</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    {{-- Scroll To Top Button --}}
    <button id="scrollTopBtn" class="scroll-top-btn shadow-lg" title="الرجوع للأعلى" aria-label="الرجوع للأعلى">
        <i class="fas fa-arrow-up"></i>
    </button>
@endsection

@push('styles')
<style>
    /* ── Utilities ─────────────────────────────────────────────── */
    .max-w-1200 { max-width: 1200px; margin: 0 auto; }
    .bg-slate-subtle { background-color: #f8fafc; }

    /* ── Section Headers ───────────────────────────────────────── */
    .section-badge {
        display: inline-block;
        background: #e0f2fe;
        color: #0369a1;
        font-size: 13px;
        font-weight: 700;
        padding: 4px 14px;
        border-radius: 20px;
    }
    .section-heading {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.3;
    }
    .section-subheading { font-size: 16px; color: #64748b; }
    .view-all-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--primary-color);
        font-weight: 700;
        font-size: 15px;
        text-decoration: none;
        transition: transform 0.2s ease, color 0.2s ease;
    }
    .view-all-btn:hover { color: var(--accent-orange); transform: translateX(-4px); }

    /* ── HERO SECTION ──────────────────────────────────────────── */
    .hero-section {
        background: #081d33; /* fallback colour shown while image loads */
        min-height: 520px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 90px 20px 80px;
        border-radius: 0 0 32px 32px;
        box-shadow: 0 15px 35px -10px rgba(8, 29, 51, 0.3);
        /* Explicit contain prevents browser from needing to re-layout
           surrounding content when the hero image loads. */
        contain: layout style;
    }

    /* Hero background image as <img> — absolutely positioned to fill */
    .hero-bg-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
    }

    .hero-bg-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg,
            rgba(8, 26, 46, 0.82) 0%,
            rgba(9, 33, 58, 0.92) 75%,
            rgba(11, 44, 74, 0.98) 100%);
        z-index: 1;
    }

    .hero-content-wrapper { max-width: 820px; }

    .hero-pill-badge {
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.2);
        color: #ffffff;
        padding: 6px 18px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
    }

    .hero-main-title {
        font-size: 48px;
        font-weight: 900;
        line-height: 1.25;
        color: #ffffff;
        letter-spacing: -0.5px;
    }

    .hero-subtitle {
        font-size: 17px;
        line-height: 1.7;
        color: #cbd5e1;
        max-width: 650px;
        font-weight: 400;
    }

    .hero-btn-primary {
        background: linear-gradient(135deg, #ff9500 0%, #ff7a00 100%);
        color: #ffffff !important;
        font-weight: 800;
        font-size: 16px;
        padding: 13px 32px;
        border-radius: 30px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 8px 25px rgba(255, 122, 0, 0.4);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hero-btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(255, 122, 0, 0.55);
    }

    .hero-btn-secondary {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.25);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 16px;
        padding: 13px 30px;
        border-radius: 30px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
    }
    .hero-btn-secondary:hover {
        background: rgba(255,255,255,0.2);
        transform: translateY(-3px);
    }

    /* Ambient glows — hidden on mobile to save GPU */
    .hero-ambient-glow {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        z-index: 1;
        pointer-events: none;
        opacity: 0.45;
    }
    .glow-1 {
        width: 320px; height: 320px;
        background: radial-gradient(circle, rgba(255,138,0,0.45) 0%, rgba(255,138,0,0) 70%);
        top: -60px; right: 5%;
    }
    .glow-2 {
        width: 380px; height: 380px;
        background: radial-gradient(circle, rgba(56,189,248,0.35) 0%, rgba(56,189,248,0) 70%);
        bottom: -80px; left: 5%;
    }
    @media (max-width: 768px) {
        .hero-section { padding: 60px 15px 50px; min-height: 420px; border-radius: 0 0 24px 24px; }
        .hero-main-title { font-size: 32px; }
        .hero-subtitle { font-size: 15px; }
        .hero-btn-primary, .hero-btn-secondary { width: 100%; justify-content: center; padding: 11px 24px; }
        /* Hide glows on mobile: saves two GPU paint layers */
        .hero-ambient-glow { display: none; }
    }

    /* Floating badges (desktop only) */
    .hero-float-badge {
        position: absolute;
        z-index: 3;
        background: rgba(255,255,255,0.95);
        border: 1px solid rgba(255,255,255,0.9);
        border-radius: 18px;
        padding: 12px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 16px 36px rgba(0,0,0,0.18);
        color: #0f172a;
    }
    .hero-float-1 { top: 22%; right: 6%; }
    .hero-float-2 { top: 32%; left: 6%; }
    .hero-float-3 { bottom: 18%; right: 10%; }

    .badge-icon-wrap {
        width: 42px; height: 42px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .badge-icon-wrap.bg-amber { background: #fef3c7; }
    .badge-icon-wrap.bg-cyan  { background: #e0f2fe; }
    .badge-icon-wrap.bg-purple{ background: #f3e8ff; }
    .badge-title { display: block; font-weight: 800; font-size: 13.5px; line-height: 1.2; color: #0f172a; }
    .badge-sub   { display: block; font-size: 11.5px; color: #64748b; font-weight: 600; }

    /* ── SERVICE CARDS ─────────────────────────────────────────── */
    .service-feature-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 20px;
        padding: 28px 24px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        height: 100%;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .service-feature-card:hover {
        transform: translateY(-6px);
        border-color: #cbd5e1;
        box-shadow: 0 15px 30px rgba(11,95,138,0.08);
    }
    .service-icon-box {
        width: 52px; height: 52px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; margin-bottom: 20px;
    }
    .icon-bg-slate  { background: #f1f5f9; color: #475569; }
    .icon-bg-amber  { background: #fef3c7; color: #d97706; }
    .icon-bg-cyan   { background: #e0f2fe; color: #0284c7; }
    .icon-bg-purple { background: #f3e8ff; color: #9333ea; }
    .icon-bg-teal   { background: #ccfbf1; color: #0d9488; }
    .icon-bg-rose   { background: #ffe4e6; color: #e11d48; }
    .service-card-title { font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 8px; }
    .service-card-desc  { font-size: 13.5px; line-height: 1.6; color: #64748b; margin-bottom: 0; }

    /* ── HOW IT WORKS ──────────────────────────────────────────── */
    .how-it-works-bg {
        background: #fbfcfe;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        /* Defer rendering of this below-fold section until near viewport */
        content-visibility: auto;
        contain-intrinsic-size: 0 400px;
    }
    .stepper-container { position: relative; padding: 20px 0; }
    .stepper-line {
        position: absolute; top: 45px; left: 12%; right: 12%;
        height: 2px; background: #e2e8f0; z-index: 1; overflow: hidden;
    }
    .stepper-line-fill {
        position: absolute; top: 0; right: 0; height: 100%; width: 0%;
        background: linear-gradient(90deg, #ff8a00 0%, #0b5f8a 100%);
        border-radius: 4px;
    }
    .step-card { position: relative; z-index: 2; padding: 0 10px; }
    .step-number-circle {
        width: 54px; height: 54px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; font-weight: 900; margin: 0 auto 20px;
    }
    .step-circle-dark   { background: #09233f; color: #ffffff; box-shadow: 0 6px 18px rgba(9,35,63,0.25); }
    .step-circle-white  { background: #ffffff; color: #0b5f8a; border: 2px solid #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
    .step-circle-orange { background: linear-gradient(135deg, #ff9500 0%, #ff7a00 100%); color: #ffffff; box-shadow: 0 6px 18px rgba(255,122,0,0.35); }
    .step-title { font-size: 17px; font-weight: 800; color: #0f172a; margin-bottom: 8px; }
    .step-desc  { font-size: 13.5px; color: #64748b; line-height: 1.5; max-width: 220px; margin: 0 auto; }

    /* ── WHY US ────────────────────────────────────────────────── */
    .quality-icon-box {
        width: 44px; height: 44px; background: #f8fafc;
        border: 1px solid #e2e8f0; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .quality-title { font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
    .quality-desc  { font-size: 13.5px; color: #64748b; margin-bottom: 0; }
    .technician-hero-wrapper { position: relative; max-width: 460px; margin: 0 auto; }
    .technician-img-container img { border-radius: 28px; object-fit: cover; aspect-ratio: 3/4; }

    .floating-rating-badge {
        position: absolute; bottom: 24px; left: 24px;
        background: rgba(255,255,255,0.94);
        border-radius: 20px; padding: 16px 24px;
        border: 1px solid rgba(255,255,255,0.8);
        box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        text-align: right;
    }
    .floating-rating-badge .rating-number {
        font-size: 22px; font-weight: 900; color: #0f172a;
        display: flex; align-items: center; gap: 8px; margin-bottom: 2px;
    }
    .floating-rating-badge .rating-label { font-size: 12px; color: #64748b; font-weight: 600; }
    .floating-exp-badge {
        position: absolute; top: 20px; right: 20px;
        background: rgba(255,255,255,0.95);
        padding: 8px 16px; border-radius: 30px;
        font-size: 13px; font-weight: 800; color: #0b5f8a;
        border: 1px solid rgba(255,255,255,0.8);
        display: flex; align-items: center; gap: 6px; z-index: 3;
    }

    /* Rating star — CSS animation replaces GSAP infinite loop */
    .rating-star-pulse {
        display: inline-block;
        animation: starSparkle 2.5s infinite ease-in-out;
    }
    @keyframes starSparkle {
        0%, 100% { transform: scale(1) rotate(0deg); }
        50%       { transform: scale(1.25) rotate(15deg); }
    }

    /* ── PROMO / SWIPER ────────────────────────────────────────── */
    .promo-swiper { padding: 10px 0 45px !important; }
    .promo-banner {
        border-radius: 24px; position: relative; overflow: hidden;
        height: 200px; box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        display: flex; flex-direction: column; justify-content: center;
    }
    .promo-content { position: relative; z-index: 2; padding: 28px; color: white; }
    .promo-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(90deg, rgba(0,0,0,0.35) 0%, rgba(0,0,0,0) 100%);
        z-index: 1;
    }
    .promo-badge-sm {
        background: rgba(255,255,255,0.25);
        padding: 4px 12px; border-radius: 20px;
        font-size: 11px; font-weight: 700; margin-bottom: 8px; display: inline-block;
    }
    .promo-title   { font-size: 24px; font-weight: 800; margin-bottom: 4px; }
    .promo-subtitle{ font-size: 14px; opacity: 0.9; margin-bottom: 0; }
    .promo-discount-chip {
        background: #ffffff; color: #0f172a; padding: 6px 16px;
        border-radius: 12px; display: inline-flex; align-items: center;
        font-weight: 800; font-size: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .promo-illustration {
        position: absolute; left: -20px; bottom: -20px;
        font-size: 140px; opacity: 0.12; color: white; z-index: 0; transform: rotate(10deg);
    }

    /* ── REVIEWS ───────────────────────────────────────────────── */
    .modern-review-card {
        background: #ffffff; border-radius: 22px; padding: 28px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.04);
        border: 1px solid #f1f5f9; height: 100%;
    }
    .review-avatar-circle {
        width: 46px; height: 46px; border-radius: 50%;
        background: linear-gradient(135deg, #0b5f8a 0%, #38bdf8 100%);
        color: #ffffff; font-weight: 800; font-size: 18px;
        display: flex; align-items: center; justify-content: center;
    }
    .review-client-name { font-size: 16px; font-weight: 800; color: #0f172a; }
    .review-quote-text  { font-size: 14.5px; line-height: 1.7; color: #475569; margin-bottom: 0; }

    /* ── STRATEGY ACCORDION ────────────────────────────────────── */
    .strategy-badge-step {
        color: #ffffff; width: 28px; height: 28px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 800;
    }

    /* ── FOOTER ────────────────────────────────────────────────── */
    .main-footer { background: #ffffff; border-top: 1px solid #e2e8f0; }
    .footer-col-title { font-size: 17px; font-weight: 800; color: #0b5f8a; margin-bottom: 20px; }
    .footer-nav-list li { margin-bottom: 12px; }
    .footer-nav-list a { color: #64748b; text-decoration: none; font-size: 14.5px; transition: color 0.2s ease; display: inline-block; }
    .footer-nav-list a:hover { color: var(--primary-color); }
    .contact-icon {
        width: 34px; height: 34px; border-radius: 50%;
        background: #fee2e2; color: #ef4444;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; flex-shrink: 0;
    }
    .social-icon-btn {
        width: 38px; height: 38px; border-radius: 50%;
        background: #f1f5f9; color: #64748b;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; transition: all 0.25s ease;
    }
    .social-icon-btn:hover { background: #0b5f8a; color: #ffffff; transform: translateY(-2px); }

    /* ── SCROLL TO TOP ─────────────────────────────────────────── */
    .scroll-top-btn {
        position: fixed;
        bottom: 90px;
        left: 25px;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0b5f8a 0%, #188ec9 100%);
        color: #ffffff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        cursor: pointer;
        z-index: 999;
        opacity: 0;
        transform: scale(0.5);
        pointer-events: none;
        transition: opacity 0.3s ease, transform 0.3s ease, background 0.3s ease;
    }
    .scroll-top-btn.visible {
        opacity: 1;
        transform: scale(1);
        pointer-events: auto;
    }
    .scroll-top-btn:hover { background: linear-gradient(135deg, #ff8a00 0%, #ff6b00 100%); }
    @media (min-width: 769px) { .scroll-top-btn { bottom: 35px; } }
</style>
@endpush

@push('scripts')
<script>
    {{-- ============================================================
         DOMContentLoaded: only critical setup (Swiper, notification badge).
         GSAP is loaded async/deferred — guarded by typeof check.
    ============================================================ --}}
    document.addEventListener('DOMContentLoaded', function () {

        // ── Notification badge (auth users only) ──────────────────
        @auth
        // Defer notification fetch by 2s so it never competes with LCP
        setTimeout(function () {
            fetch("{{ route('notifications.unread-count') }}")
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    var badge = document.getElementById('notification-badge');
                    if (badge && data.count > 0) {
                        badge.innerText = data.count;
                        badge.style.display = 'inline-block';
                    }
                })
                .catch(function () {});
        }, 2000);
        @endauth

        // ── Scroll To Top button — pure CSS transition (no GSAP needed) ─
        var scrollBtn = document.getElementById('scrollTopBtn');
        if (scrollBtn) {
            window.addEventListener('scroll', function () {
                if (window.scrollY > 300) {
                    scrollBtn.classList.add('visible');
                } else {
                    scrollBtn.classList.remove('visible');
                }
            }, { passive: true });

            scrollBtn.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // ── Smooth scroll for anchor links — native CSS, no GSAP needed ─
        document.documentElement.style.scrollBehavior = 'smooth';

        // ── Swiper — init only after Swiper library is loaded (defer) ─
        function initSwipers() {
            if (typeof Swiper === 'undefined') {
                // Swiper not yet available (still downloading), retry
                setTimeout(initSwipers, 100);
                return;
            }

            var promoEl = document.querySelector('.promo-swiper');
            if (promoEl) {
                new Swiper('.promo-swiper', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: true,
                    autoplay: {
                        delay: 4500,
                        disableOnInteraction: true, // stop on user interaction (better UX+perf)
                        pauseOnMouseEnter: true,
                    },
                    pagination: { el: '.promo-swiper .swiper-pagination', clickable: true },
                    breakpoints: {
                        640:  { slidesPerView: 1.5, spaceBetween: 20 },
                        1024: { slidesPerView: 2.2, spaceBetween: 25 },
                    }
                });
            }

            var reviewsEl = document.querySelector('.reviews-swiper');
            if (reviewsEl) {
                new Swiper('.reviews-swiper', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: true,
                    autoplay: false, // no autoplay on reviews (better for reading)
                    pagination: { el: '.reviews-swiper .swiper-pagination', clickable: true },
                    breakpoints: {
                        768:  { slidesPerView: 2, spaceBetween: 25 },
                        1024: { slidesPerView: 3, spaceBetween: 30 },
                    }
                });
            }
        }

        initSwipers();

        // ── GSAP Animations — loaded async, only on desktop ──────────
        // On mobile we skip all GSAP to reduce JS execution time (INP/TBT).
        // ScrollTrigger handles entrance animations.
        // ScrollToPlugin REMOVED — replaced by native scroll-behavior: smooth.
        if (window.innerWidth >= 769) {
            loadGsap();
        }
    });

    function loadGsap() {
        // Dynamically load GSAP only when needed (desktop) and only after
        // the page has finished its critical rendering path.
        requestIdleCallback(function () {
            var s1 = document.createElement('script');
            s1.src = 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js';
            s1.onload = function () {
                var s2 = document.createElement('script');
                s2.src = 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js';
                s2.onload = initGsapAnimations;
                document.head.appendChild(s2);
            };
            document.head.appendChild(s1);
        }, { timeout: 3000 });
    }

    // Polyfill requestIdleCallback for Safari
    window.requestIdleCallback = window.requestIdleCallback || function (cb, opts) {
        return setTimeout(cb, (opts && opts.timeout) ? Math.min(opts.timeout, 50) : 50);
    };

    function initGsapAnimations() {
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
        gsap.registerPlugin(ScrollTrigger);

        // ── 1. HERO ENTRANCE ────────────────────────────────────────
        var heroTl = gsap.timeline({ defaults: { ease: 'power3.out' } });
        heroTl
            .fromTo('.hero-pill-badge',    { y: -30, opacity: 0, scale: 0.85 }, { y: 0, opacity: 1, scale: 1, duration: 0.7, ease: 'back.out(1.7)' })
            .fromTo('.hero-main-title',    { y: 35,  opacity: 0 },              { y: 0, opacity: 1, duration: 0.8 }, '-=0.4')
            .fromTo('.hero-subtitle',      { y: 20,  opacity: 0 },              { y: 0, opacity: 1, duration: 0.6 }, '-=0.4')
            .fromTo('.hero-action-buttons a', { y: 25, opacity: 0, scale: 0.92 }, { y: 0, opacity: 1, scale: 1, stagger: 0.12, duration: 0.6, ease: 'back.out(1.4)' }, '-=0.3')
            .fromTo('.hero-float-badge',   { scale: 0, opacity: 0, y: 25 },    { scale: 1, opacity: 1, y: 0, stagger: 0.15, duration: 0.7, ease: 'back.out(1.8)' }, '-=0.4');

        // ── 2. FLOATING BADGES (desktop only, sine wave) ─────────────
        gsap.to('.hero-float-1', { y: '-=12', rotation: '+=2',   duration: 2.8, repeat: -1, yoyo: true, ease: 'sine.inOut' });
        gsap.to('.hero-float-2', { y: '+=14', rotation: '-=2.5', duration: 3.2, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 0.3 });
        gsap.to('.hero-float-3', { y: '-=10', rotation: '+=1.5', duration: 3.5, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 0.6 });

        // ── 3. PARALLAX ON MOUSEMOVE (desktop only) ──────────────────
        var heroSection = document.querySelector('.hero-section');
        if (heroSection) {
            heroSection.addEventListener('mousemove', function (e) {
                var rect = heroSection.getBoundingClientRect();
                var x = (e.clientX - rect.left) / rect.width - 0.5;
                var y = (e.clientY - rect.top)  / rect.height - 0.5;
                gsap.to('.hero-float-1', { x: x * 30,  y: y * 20,  duration: 0.5, ease: 'power1.out' });
                gsap.to('.hero-float-2', { x: -x * 25, y: -y * 18, duration: 0.5, ease: 'power1.out' });
                gsap.to('.hero-float-3', { x: x * 20,  y: -y * 22, duration: 0.5, ease: 'power1.out' });
                gsap.to('.hero-content-wrapper', { x: x * 12, y: y * 8, duration: 0.7, ease: 'power1.out' });
            });
            heroSection.addEventListener('mouseleave', function () {
                gsap.to('.hero-float-1, .hero-float-2, .hero-float-3, .hero-content-wrapper', { x: 0, y: 0, duration: 0.8, ease: 'power2.out' });
            });
        }

        // ── 4. SCROLL-TRIGGERED SECTION ANIMATIONS ──────────────────
        // Services
        gsap.fromTo('.service-feature-card',
            { y: 40, opacity: 0, scale: 0.95 },
            { scrollTrigger: { trigger: '#services .row', start: 'top 92%', once: true },
              y: 0, opacity: 1, scale: 1, stagger: 0.07, duration: 0.65, ease: 'power2.out', clearProps: 'opacity,transform' });

        // How it works
        gsap.fromTo('.step-card',
            { y: 35, opacity: 0, scale: 0.9 },
            { scrollTrigger: { trigger: '.stepper-container', start: 'top 88%', once: true },
              y: 0, opacity: 1, scale: 1, stagger: 0.12, duration: 0.7, ease: 'back.out(1.5)', clearProps: 'opacity,transform' });

        gsap.to('.stepper-line-fill', {
            scrollTrigger: { trigger: '.stepper-container', start: 'top 85%', end: 'bottom 65%', scrub: 0.5 },
            width: '100%', ease: 'none'
        });

        // Why us
        gsap.fromTo('.quality-item',
            { x: 30, opacity: 0 },
            { scrollTrigger: { trigger: '#why-us', start: 'top 85%', once: true },
              x: 0, opacity: 1, stagger: 0.1, duration: 0.6, ease: 'power2.out', clearProps: 'opacity,transform' });

        gsap.fromTo('.technician-hero-wrapper',
            { scale: 0.92, opacity: 0 },
            { scrollTrigger: { trigger: '#why-us', start: 'top 85%', once: true },
              scale: 1, opacity: 1, duration: 0.8, ease: 'power2.out', clearProps: 'opacity,transform' });

        // Rating counter
        var ratingCounter = document.querySelector('.rating-counter-val');
        if (ratingCounter) {
            var counterObj = { val: 0 };
            gsap.to(counterObj, {
                scrollTrigger: { trigger: '.floating-rating-badge', start: 'top 92%', once: true },
                val: 4.9, duration: 1.5, ease: 'power2.out',
                onUpdate: function () { ratingCounter.innerText = counterObj.val.toFixed(1); }
            });
        }

        // Offers & reviews
        [['#offers-section', '#offers-section'], ['.reviews-swiper', '.reviews-swiper']].forEach(function (pair) {
            var el = document.querySelector(pair[0]);
            if (el) {
                gsap.fromTo(pair[1],
                    { y: 35, opacity: 0 },
                    { scrollTrigger: { trigger: pair[1], start: 'top 90%', once: true },
                      y: 0, opacity: 1, duration: 0.7, ease: 'power2.out', clearProps: 'opacity,transform' });
            }
        });

        // Strategy accordion
        var strategyEl = document.querySelector('#strategyAccordion');
        if (strategyEl) {
            gsap.fromTo('#strategyAccordion .accordion-item',
                { y: 25, opacity: 0 },
                { scrollTrigger: { trigger: '#strategyAccordion', start: 'top 90%', once: true },
                  y: 0, opacity: 1, stagger: 0.08, duration: 0.6, ease: 'power2.out', clearProps: 'opacity,transform' });
        }
    }
</script>
@endpush
