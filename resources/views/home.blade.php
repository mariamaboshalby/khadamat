@extends('layouts.mobile')

@section('title', 'خدمتي - كل خدمات بيتك في مكان واحد')

@section('content')
    <!-- Mobile App Header (Only on small screens) -->
    <div class="app-header d-md-none">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="خدمتي" style="height: 38px; width: auto;">
            </a>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('requests.create') }}" class="btn-brand-orange py-1 px-3" style="font-size: 13px;">
                <i class="fas fa-plus"></i> طلب
            </a>
            <a href="{{ route('notifications.index') }}" class="header-icon-btn position-relative text-decoration-none">
                <i class="fas fa-bell"></i>
                <span id="notification-badge"
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    style="font-size: 0.6rem; display: none;">
                    0
                </span>
            </a>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- HERO SECTION                                                              -->
    <!-- ========================================================================= -->
    <section class="hero-section position-relative overflow-hidden text-center text-white">
        <div class="hero-bg-overlay"></div>
        <div class="hero-ambient-glow glow-1"></div>
        <div class="hero-ambient-glow glow-2"></div>

        <!-- Floating Decorative Badges for GSAP Parallax/Floating Effect -->
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
            <!-- Guarantee Badge -->
            <div class="d-inline-flex align-items-center gap-2 hero-pill-badge mb-4">
                <i class="fas fa-shield-check text-warning hero-badge-icon"></i>
                <span>مضمون 100%</span>
            </div>

            <!-- Main Heading -->
            <h1 class="hero-main-title mb-3">
                <span class="hero-title-line d-inline-block">كل خدمات بيتك</span><br>
                <span class="hero-title-highlight d-inline-block">في مكان واحد</span>
            </h1>

            <!-- Subtitle -->
            <p class="hero-subtitle mb-4 mx-auto">
                اطلب فني متخصص للصيانة والسباكة والكهرباء والتكييف وغيرها، بسهولة وأمان، خدمات موثوقة بضغطة زر.
            </p>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mt-4 hero-action-buttons">
                <a href="{{ route('requests.create') }}" class="hero-btn-primary gsap-hover-lift">
                    <span>اطلب خدمة الآن</span>
                    <i class="fas fa-arrow-left"></i>
                </a>
                <a href="#services" class="hero-btn-secondary gsap-hover-lift">
                    <span>استكشف الخدمات</span>
                </a>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- POPULAR SERVICES SECTION                                                  -->
    <!-- ========================================================================= -->
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
                        'سباكة' => [
                            'icon' => 'fa-wrench',
                            'bg' => 'icon-bg-slate',
                            'desc' => 'إصلاح تسريبات، تركيب أدوات صحية، وتسليك مجاري بكفاءة عالية.'
                        ],
                        'كهرباء' => [
                            'icon' => 'fa-bolt',
                            'bg' => 'icon-bg-amber',
                            'desc' => 'تأسيس وصيانة كهرباء، تركيب إضاءات، فحص وحل أعطال الكابلات.'
                        ],
                        'تكييف' => [
                            'icon' => 'fa-snowflake',
                            'bg' => 'icon-bg-cyan',
                            'desc' => 'تنظيف، شحن فريون، وصيانة شاملة لجميع أنواع المكيفات.'
                        ],
                        'نجارة' => [
                            'icon' => 'fa-hammer',
                            'bg' => 'icon-bg-purple',
                            'desc' => 'صيانة وتصليح الأثاث، تركيب أبواب ونوافذ بدقة واحترافية.'
                        ],
                        'نظافة' => [
                            'icon' => 'fa-broom',
                            'bg' => 'icon-bg-teal',
                            'desc' => 'خدمات تنظيف شاملة للمنازل والواجهات بأحدث المعدات.'
                        ],
                        'أجهزة منزلية' => [
                            'icon' => 'fa-tv',
                            'bg' => 'icon-bg-rose',
                            'desc' => 'صيانة الغسالات، الثلاجات، والأفران بقطع غيار أصلية.'
                        ],
                    ];
                @endphp

                @forelse ($services as $service)
                    @php
                        $meta = $defaultServiceMeta[$service->name] ?? [
                            'icon' => $service->icon ?? 'fa-tools',
                            'bg' => 'icon-bg-slate',
                            'desc' => $service->description ?? 'خدمات صيانة متخصصة وعالية الجودة لكافة احتياجات منزلك.'
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

    <!-- ========================================================================= -->
    <!-- HOW IT WORKS SECTION (كيف تعمل خدمتي؟)                                     -->
    <!-- ========================================================================= -->
    <section id="how-it-works" class="py-5 px-3 px-md-5 how-it-works-bg">
        <div class="container-fluid max-w-1200 text-center">
            <div class="section-badge mx-auto mb-2">خطوات بسيطة</div>
            <h2 class="section-heading mb-5">كيف تعمل خدمتي؟</h2>

            <div class="stepper-container position-relative">
                <div class="stepper-line d-none d-md-block">
                    <div class="stepper-line-fill"></div>
                </div>
                
                <div class="row g-4 justify-content-center">
                    <!-- Step 1 -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="step-card">
                            <div class="step-number-circle step-circle-dark">1</div>
                            <h4 class="step-title">اختر الخدمة</h4>
                            <p class="step-desc">حدد نوع الصيانة أو الخدمة التي تحتاجها من قائمتنا الشاملة</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="step-card">
                            <div class="step-number-circle step-circle-white">2</div>
                            <h4 class="step-title">حدد الموقع والوقت</h4>
                            <p class="step-desc">أدخل عنوانك واختر الموعد المناسب لزيارة الفني.</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="step-card">
                            <div class="step-number-circle step-circle-white">3</div>
                            <h4 class="step-title">اختر الفني</h4>
                            <p class="step-desc">قارن بين الفنيين المتاحين بناءً على التقييمات والأسعار.</p>
                        </div>
                    </div>

                    <!-- Step 4 -->
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

    <!-- ========================================================================= -->
    <!-- WHY CHOOSE US / QUALITY STANDARDS (معايير جودة لا نساوم عليها)              -->
    <!-- ========================================================================= -->
    <section id="why-us" class="py-5 px-3 px-md-5">
        <div class="container-fluid max-w-1200">
            <div class="row align-items-center g-5">
                <!-- Left Details & Features -->
                <div class="col-lg-6 order-2 order-lg-1">
                    <div class="section-badge mb-2">لماذا نحن</div>
                    <h2 class="section-heading mb-4">معايير جودة لا نساوم عليها</h2>

                    <div class="row g-4 mt-2">
                        <!-- Quality 1 -->
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

                        <!-- Quality 2 -->
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

                        <!-- Quality 3 -->
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

                        <!-- Quality 4 -->
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

                <!-- Right Photo with Floating Rating -->
                <div class="col-lg-6 order-1 order-lg-2">
                    <div class="technician-hero-wrapper position-relative">
                        <div class="technician-img-container">
                            <img src="{{ asset('images/technician-hero.jpg') }}" alt="فني خدمتي المعتمد" class="img-fluid rounded-4 shadow-lg w-100">
                        </div>
                        
                        <!-- Floating Experience Badge -->
                        <div class="floating-exp-badge shadow-lg d-none d-sm-flex">
                            <i class="fas fa-certificate text-primary"></i>
                            <span>فنيون معتمدون ومفحوصون 100%</span>
                        </div>

                        <!-- Floating Rating Card -->
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

    <!-- ========================================================================= -->
    <!-- OFFERS PROMO SECTION                                                      -->
    <!-- ========================================================================= -->
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

    <!-- ========================================================================= -->
    <!-- CUSTOMER REVIEWS SECTION                                                  -->
    <!-- ========================================================================= -->
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

    <!-- ========================================================================= -->
    <!-- STRATEGY / VALUE PILLARS SECTION                                          -->
    <!-- ========================================================================= -->
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

    <!-- ========================================================================= -->
    <!-- FOOTER                                                                    -->
    <!-- ========================================================================= -->
    <footer class="main-footer py-5">
        <div class="container-fluid max-w-1200 px-4">
            <div class="row g-5 mb-4">
                <!-- Brand Info -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="خدمتي" style="height: 48px; width: auto;">
                    </div>
                    <p class="text-secondary fs-6 mb-4 leading-relaxed">
                        منصتك الأولى المعتمدة لخدمات الصيانة والتشغيل المنزلي. نربطك بأفضل الفنيين المحترفين لضمان جودة العمل، راحة البال، وتوفير الوقت.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="#" class="social-icon-btn"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon-btn"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon-btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon-btn"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
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

                <!-- Popular Services -->
                <div class="col-lg-3 col-md-6 col-6">
                    <h5 class="footer-col-title">خدمات الصيانة</h5>
                    <ul class="list-unstyled footer-nav-list">
                        <li><a href="{{ route('services.index') }}">صيانة وتأسيس السباكة</a></li>
                        <li><a href="{{ route('services.index') }}">أعمال الكهرباء والإضاءة</a></li>
                        <li><a href="{{ route('services.index') }}">صيانة وتنظيف المكيفات</a></li>
                        <li><a href="{{ route('services.index') }}">النجارة وتركيب الأثاث</a></li>
                    </ul>
                </div>

                <!-- Contact & Support -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-col-title">تواصل معنا</h5>
                    <ul class="list-unstyled footer-contact-list">
                        <li class="d-flex align-items-center gap-3 mb-3">
                            <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                            <span>920000000</span>
                        </li>
                        <li class="d-flex align-items-center gap-3 mb-3">
                            <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                            <span>support@khadamat.com</span>
                        </li>
                        <li class="d-flex align-items-center gap-3">
                            <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <span>المملكة العربية السعودية / مصر</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom-bar text-center pt-4 mt-4 border-top">
                <p class="mb-0 text-muted fs-6">
                    &copy; 2026 خدمتي المحدودة. جميع الحقوق محفوظة.
                </p>
            </div>
        </div>
    </footer>

    <!-- Floating Scroll To Top Button -->
    <button id="scrollTopBtn" class="scroll-top-btn shadow-lg" title="الرجوع للأعلى" aria-label="الرجوع للأعلى">
        <i class="fas fa-arrow-up"></i>
    </button>
@endsection

@push('styles')
    <style>
        /* Utility */
        .max-w-1200 {
            max-width: 1200px;
            margin: 0 auto;
        }

        .bg-slate-subtle {
            background-color: #f8fafc;
        }

        /* Section Header Styles */
        .section-badge {
            display: inline-block;
            background: #e0f2fe;
            color: #0369a1;
            font-size: 13px;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 20px;
            letter-spacing: 0.3px;
        }

        .section-heading {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.3;
        }

        .section-subheading {
            font-size: 16px;
            color: #64748b;
        }

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

        .view-all-btn:hover {
            color: var(--accent-orange);
            transform: translateX(-4px);
        }

        /* ========================================================================= */
        /* HERO SECTION                                                              */
        /* ========================================================================= */
        .hero-section {
            background: #081d33 url("{{ asset('images/hero-bg.jpg') }}") center center / cover no-repeat;
            min-height: 520px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 90px 20px 80px;
            border-radius: 0 0 32px 32px;
            box-shadow: 0 15px 35px -10px rgba(8, 29, 51, 0.3);
        }

        .hero-bg-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, rgba(8, 26, 46, 0.82) 0%, rgba(9, 33, 58, 0.92) 75%, rgba(11, 44, 74, 0.98) 100%);
            z-index: 1;
        }

        .hero-content-wrapper {
            max-width: 820px;
        }

        .hero-pill-badge {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
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

        .hero-title-highlight {
            color: #ffffff;
            position: relative;
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
            background: linear-gradient(135deg, #ff8500 0%, #e66a00 100%);
        }

        .hero-btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
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
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 15px 50px;
                min-height: 420px;
                border-radius: 0 0 24px 24px;
            }
            .hero-main-title {
                font-size: 32px;
            }
            .hero-subtitle {
                font-size: 15px;
            }
            .hero-btn-primary, .hero-btn-secondary {
                width: 100%;
                justify-content: center;
                padding: 11px 24px;
            }
        }

        /* ========================================================================= */
        /* POPULAR SERVICES CARDS                                                    */
        /* ========================================================================= */
        .service-feature-card {
            background: #ffffff;
            border: 1px solid #eef2f6;
            border-radius: 20px;
            padding: 28px 24px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            height: 100%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .service-feature-card:hover {
            transform: translateY(-6px);
            border-color: #cbd5e1;
            box-shadow: 0 15px 30px rgba(11, 95, 138, 0.08);
        }

        .service-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .service-feature-card:hover .service-icon-box {
            transform: scale(1.1) rotate(4deg);
        }

        .icon-bg-slate {
            background: #f1f5f9;
            color: #475569;
        }

        .icon-bg-amber {
            background: #fef3c7;
            color: #d97706;
        }

        .icon-bg-cyan {
            background: #e0f2fe;
            color: #0284c7;
        }

        .icon-bg-purple {
            background: #f3e8ff;
            color: #9333ea;
        }

        .icon-bg-teal {
            background: #ccfbf1;
            color: #0d9488;
        }

        .icon-bg-rose {
            background: #ffe4e6;
            color: #e11d48;
        }

        .service-card-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .service-card-desc {
            font-size: 13.5px;
            line-height: 1.6;
            color: #64748b;
            margin-bottom: 0;
        }

        /* ========================================================================= */
        /* HOW IT WORKS (STEPPER)                                                    */
        /* ========================================================================= */
        .how-it-works-bg {
            background: #fbfcfe;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
        }

        .stepper-container {
            position: relative;
            padding: 20px 0;
        }

        .stepper-line {
            position: absolute;
            top: 45px;
            left: 12%;
            right: 12%;
            height: 2px;
            background: #e2e8f0;
            z-index: 1;
        }

        .step-card {
            position: relative;
            z-index: 2;
            padding: 0 10px;
        }

        .step-number-circle {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 900;
            margin: 0 auto 20px;
            transition: all 0.3s ease;
        }

        .step-circle-dark {
            background: #09233f;
            color: #ffffff;
            box-shadow: 0 6px 18px rgba(9, 35, 63, 0.25);
        }

        .step-circle-white {
            background: #ffffff;
            color: #0b5f8a;
            border: 2px solid #cbd5e1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .step-circle-orange {
            background: linear-gradient(135deg, #ff9500 0%, #ff7a00 100%);
            color: #ffffff;
            box-shadow: 0 6px 18px rgba(255, 122, 0, 0.35);
        }

        .step-title {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .step-desc {
            font-size: 13.5px;
            color: #64748b;
            line-height: 1.5;
            max-width: 220px;
            margin: 0 auto;
        }

        /* ========================================================================= */
        /* WHY CHOOSE US & TECHNICIAN HERO                                           */
        /* ========================================================================= */
        .quality-icon-box {
            width: 44px;
            height: 44px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .quality-title {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .quality-desc {
            font-size: 13.5px;
            color: #64748b;
            margin-bottom: 0;
        }

        .technician-hero-wrapper {
            position: relative;
            max-width: 460px;
            margin: 0 auto;
        }

        .technician-img-container img {
            border-radius: 28px;
            object-fit: cover;
            aspect-ratio: 3/4;
        }

        .floating-rating-badge {
            position: absolute;
            bottom: 24px;
            left: 24px;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 16px 24px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
            text-align: right;
        }

        .floating-rating-badge .rating-number {
            font-size: 22px;
            font-weight: 900;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 2px;
        }

        .floating-rating-badge .rating-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
        }

        /* ========================================================================= */
        /* PROMO & REVIEWS SWIPER                                                    */
        /* ========================================================================= */
        .promo-swiper {
            padding: 10px 0 45px !important;
        }

        .promo-banner {
            border-radius: 24px;
            position: relative;
            overflow: hidden;
            height: 200px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .promo-content {
            position: relative;
            z-index: 2;
            padding: 28px;
            color: white;
        }

        .promo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, rgba(0, 0, 0, 0.35) 0%, rgba(0, 0, 0, 0) 100%);
            z-index: 1;
        }

        .promo-badge-sm {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(6px);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 8px;
            display: inline-block;
        }

        .promo-title {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .promo-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 0;
        }

        .promo-discount-chip {
            background: #ffffff;
            color: #0f172a;
            padding: 6px 16px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            font-weight: 800;
            font-size: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .promo-illustration {
            position: absolute;
            left: -20px;
            bottom: -20px;
            font-size: 140px;
            opacity: 0.12;
            color: white;
            z-index: 0;
            transform: rotate(10deg);
        }

        /* Reviews Cards */
        .modern-review-card {
            background: #ffffff;
            border-radius: 22px;
            padding: 28px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f5f9;
            height: 100%;
        }

        .review-avatar-circle {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0b5f8a 0%, #38bdf8 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .review-client-name {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }

        .review-quote-text {
            font-size: 14.5px;
            line-height: 1.7;
            color: #475569;
            margin-bottom: 0;
        }

        .strategy-badge-step {
            color: #ffffff;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
        }

        /* ========================================================================= */
        /* FOOTER                                                                    */
        /* ========================================================================= */
        .main-footer {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
        }

        .footer-col-title {
            font-size: 17px;
            font-weight: 800;
            color: #0b5f8a;
            margin-bottom: 20px;
        }

        .footer-nav-list li {
            margin-bottom: 12px;
        }

        .footer-nav-list a {
            color: #64748b;
            text-decoration: none;
            font-size: 14.5px;
            transition: color 0.2s ease, transform 0.2s ease;
            display: inline-block;
        }

        .footer-nav-list a:hover {
            color: var(--primary-color);
            transform: translateX(-4px);
        }

        .contact-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #fee2e2;
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
        }

        .social-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #f1f5f9;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .social-icon-btn:hover {
            background: #0b5f8a;
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* ========================================================================= */
        /* GSAP ANIMATION STYLES & FLOATING ELEMENTS                                */
        /* ========================================================================= */
        .hero-ambient-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 1;
            pointer-events: none;
            opacity: 0.45;
        }

        .glow-1 {
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(255, 138, 0, 0.45) 0%, rgba(255, 138, 0, 0) 70%);
            top: -60px;
            right: 5%;
        }

        .glow-2 {
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.35) 0%, rgba(56, 189, 248, 0) 70%);
            bottom: -80px;
            left: 5%;
        }

        .hero-float-badge {
            position: absolute;
            z-index: 3;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 18px;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.18);
            color: #0f172a;
            pointer-events: auto;
            cursor: default;
            transition: box-shadow 0.3s ease;
        }

        .hero-float-badge:hover {
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
        }

        .hero-float-1 {
            top: 22%;
            right: 6%;
        }

        .hero-float-2 {
            top: 32%;
            left: 6%;
        }

        .hero-float-3 {
            bottom: 18%;
            right: 10%;
        }

        .badge-icon-wrap {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .badge-icon-wrap.bg-amber {
            background: #fef3c7;
        }

        .badge-icon-wrap.bg-cyan {
            background: #e0f2fe;
        }

        .badge-icon-wrap.bg-purple {
            background: #f3e8ff;
        }

        .badge-title {
            display: block;
            font-weight: 800;
            font-size: 13.5px;
            line-height: 1.2;
            color: #0f172a;
        }

        .badge-sub {
            display: block;
            font-size: 11.5px;
            color: #64748b;
            font-weight: 600;
        }

        /* Stepper Line Progress */
        .stepper-line {
            overflow: hidden;
        }

        .stepper-line-fill {
            position: absolute;
            top: 0;
            right: 0;
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #ff8a00 0%, #0b5f8a 100%);
            border-radius: 4px;
        }

        /* Floating experience badge on technician photo */
        .floating-exp-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 800;
            color: #0b5f8a;
            border: 1px solid rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 3;
        }

        /* Rating Star Pulse */
        .rating-star-pulse {
            display: inline-block;
            animation: starSparkle 2.5s infinite ease-in-out;
        }

        @keyframes starSparkle {
            0%, 100% {
                transform: scale(1) rotate(0deg);
            }
            50% {
                transform: scale(1.25) rotate(15deg);
            }
        }

        /* Scroll To Top Button */
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
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .scroll-top-btn:hover {
            background: linear-gradient(135deg, #ff8a00 0%, #ff6b00 100%);
            transform: scale(1.1);
        }

        @media (min-width: 769px) {
            .scroll-top-btn {
                bottom: 35px;
            }
        }
    </style>
@endpush

@push('scripts')
    <!-- GSAP 3 & Plugins from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Notifications badge count
            fetchNotificationsCount();

            // Swiper init for promos
            new Swiper('.promo-swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                autoplay: {
                    delay: 4500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1.5,
                        spaceBetween: 20,
                    },
                    1024: {
                        slidesPerView: 2.2,
                        spaceBetween: 25,
                    }
                }
            });

            // Swiper init for reviews
            new Swiper('.reviews-swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 25,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    }
                }
            });

            // Initialize GSAP Animations safely
            if (typeof gsap !== 'undefined') {
                try {
                    gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);
                    initGsapAnimations();
                } catch (e) {
                    console.error('GSAP Init Error:', e);
                }
            }
        });

        window.addEventListener('load', function() {
            if (typeof ScrollTrigger !== 'undefined') {
                ScrollTrigger.refresh();
            }
        });

        /* ========================================================================= */
        /* MASTER GSAP ANIMATIONS CONTROLLER                                         */
        /* ========================================================================= */
        function initGsapAnimations() {
            // 1. HERO SECTION ENTRANCE TIMELINE
            const heroTl = gsap.timeline({
                defaults: { ease: 'power3.out' },
                onComplete: function() {
                    gsap.set('.hero-pill-badge, .hero-main-title, .hero-subtitle, .hero-action-buttons a, .hero-float-badge', {
                        clearProps: 'opacity,transform'
                    });
                }
            });

            // Hero Pill Badge
            heroTl.fromTo('.hero-pill-badge', 
                { y: -30, opacity: 0, scale: 0.85 },
                { y: 0, opacity: 1, scale: 1, duration: 0.7, ease: 'back.out(1.7)' }
            );

            // Hero Main Title
            heroTl.fromTo('.hero-main-title',
                { y: 35, opacity: 0 },
                { y: 0, opacity: 1, duration: 0.8 },
                '-=0.4'
            );

            // Hero Subtitle
            heroTl.fromTo('.hero-subtitle',
                { y: 20, opacity: 0 },
                { y: 0, opacity: 1, duration: 0.6 },
                '-=0.4'
            );

            // Hero Action Buttons
            heroTl.fromTo('.hero-action-buttons a',
                { y: 25, opacity: 0, scale: 0.92 },
                { y: 0, opacity: 1, scale: 1, stagger: 0.12, duration: 0.6, ease: 'back.out(1.4)' },
                '-=0.3'
            );

            // Hero Floating Badges
            heroTl.fromTo('.hero-float-badge',
                { scale: 0, opacity: 0, y: 25 },
                { scale: 1, opacity: 1, y: 0, stagger: 0.15, duration: 0.7, ease: 'back.out(1.8)' },
                '-=0.4'
            );

            // 2. CONTINUOUS FLOATING SINE WAVE ANIMATIONS
            gsap.to('.hero-float-1', {
                y: '-=12',
                rotation: '+=2',
                duration: 2.8,
                repeat: -1,
                yoyo: true,
                ease: 'sine.inOut'
            });

            gsap.to('.hero-float-2', {
                y: '+=14',
                rotation: '-=2.5',
                duration: 3.2,
                repeat: -1,
                yoyo: true,
                ease: 'sine.inOut',
                delay: 0.3
            });

            gsap.to('.hero-float-3', {
                y: '-=10',
                rotation: '+=1.5',
                duration: 3.5,
                repeat: -1,
                yoyo: true,
                ease: 'sine.inOut',
                delay: 0.6
            });

            // Ambient Glow Pulsing
            gsap.to('.hero-ambient-glow', {
                scale: 1.2,
                opacity: 0.6,
                duration: 4,
                repeat: -1,
                yoyo: true,
                stagger: 1.5,
                ease: 'sine.inOut'
            });

            // 3. HERO 3D PARALLAX ON MOUSEMOVE
            const heroSection = document.querySelector('.hero-section');
            if (heroSection) {
                heroSection.addEventListener('mousemove', function(e) {
                    const rect = heroSection.getBoundingClientRect();
                    const x = (e.clientX - rect.left) / rect.width - 0.5;
                    const y = (e.clientY - rect.top) / rect.height - 0.5;

                    gsap.to('.hero-float-1', { x: x * 30, y: y * 20, duration: 0.5, ease: 'power1.out' });
                    gsap.to('.hero-float-2', { x: -x * 25, y: -y * 18, duration: 0.5, ease: 'power1.out' });
                    gsap.to('.hero-float-3', { x: x * 20, y: -y * 22, duration: 0.5, ease: 'power1.out' });
                    gsap.to('.hero-content-wrapper', { x: x * 12, y: y * 8, duration: 0.7, ease: 'power1.out' });
                });

                heroSection.addEventListener('mouseleave', function() {
                    gsap.to('.hero-float-1, .hero-float-2, .hero-float-3, .hero-content-wrapper', {
                        x: 0,
                        y: 0,
                        duration: 0.8,
                        ease: 'power2.out'
                    });
                });
            }

            // 4. SMOOTH SCROLL FOR ALL ANCHORS
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (targetId && targetId.length > 1) {
                        const targetEl = document.querySelector(targetId);
                        if (targetEl) {
                            e.preventDefault();
                            gsap.to(window, {
                                duration: 0.8,
                                scrollTo: { y: targetEl, offsetY: 70 },
                                ease: 'power3.inOut'
                            });
                        }
                    }
                });
            });

            // 5. SERVICES SECTION ANIMATION
            const serviceCards = document.querySelectorAll('.service-feature-card');
            if (serviceCards.length > 0) {
                gsap.fromTo('#services .section-badge, #services .section-heading, #services .view-all-btn',
                    { y: 30, opacity: 0 },
                    {
                        scrollTrigger: {
                            trigger: '#services',
                            start: 'top 90%',
                            once: true
                        },
                        y: 0,
                        opacity: 1,
                        stagger: 0.1,
                        duration: 0.6,
                        ease: 'power2.out',
                        clearProps: 'opacity,transform'
                    }
                );

                gsap.fromTo(serviceCards,
                    { y: 40, opacity: 0, scale: 0.95 },
                    {
                        scrollTrigger: {
                            trigger: '#services .row',
                            start: 'top 92%',
                            once: true
                        },
                        y: 0,
                        opacity: 1,
                        scale: 1,
                        stagger: 0.07,
                        duration: 0.65,
                        ease: 'power2.out',
                        clearProps: 'opacity,transform'
                    }
                );

                // Micro-hover interactive animation on service cards
                serviceCards.forEach(card => {
                    card.addEventListener('mouseenter', () => {
                        const iconBox = card.querySelector('.service-icon-box i');
                        if (iconBox) {
                            gsap.to(iconBox, { scale: 1.2, rotate: 10, duration: 0.25, ease: 'back.out(2)' });
                        }
                    });
                    card.addEventListener('mouseleave', () => {
                        const iconBox = card.querySelector('.service-icon-box i');
                        if (iconBox) {
                            gsap.to(iconBox, { scale: 1, rotate: 0, duration: 0.25, ease: 'power2.out' });
                        }
                    });
                });
            }

            // 6. HOW IT WORKS (STEPPER)
            const stepCards = document.querySelectorAll('.step-card');
            if (stepCards.length > 0) {
                gsap.fromTo('#how-it-works .section-badge, #how-it-works .section-heading',
                    { y: 30, opacity: 0 },
                    {
                        scrollTrigger: {
                            trigger: '#how-it-works',
                            start: 'top 90%',
                            once: true
                        },
                        y: 0,
                        opacity: 1,
                        stagger: 0.1,
                        duration: 0.6,
                        ease: 'power2.out',
                        clearProps: 'opacity,transform'
                    }
                );

                // Animate line fill on scroll
                gsap.to('.stepper-line-fill', {
                    scrollTrigger: {
                        trigger: '.stepper-container',
                        start: 'top 85%',
                        end: 'bottom 65%',
                        scrub: 0.5
                    },
                    width: '100%',
                    ease: 'none'
                });

                gsap.fromTo(stepCards,
                    { y: 35, opacity: 0, scale: 0.9 },
                    {
                        scrollTrigger: {
                            trigger: '.stepper-container',
                            start: 'top 88%',
                            once: true
                        },
                        y: 0,
                        opacity: 1,
                        scale: 1,
                        stagger: 0.12,
                        duration: 0.7,
                        ease: 'back.out(1.5)',
                        clearProps: 'opacity,transform'
                    }
                );
            }

            // 7. WHY US & QUALITY STANDARDS
            const whyUsSection = document.querySelector('#why-us');
            if (whyUsSection) {
                gsap.fromTo('#why-us .section-badge, #why-us .section-heading',
                    { y: 30, opacity: 0 },
                    {
                        scrollTrigger: {
                            trigger: '#why-us',
                            start: 'top 90%',
                            once: true
                        },
                        y: 0,
                        opacity: 1,
                        stagger: 0.1,
                        duration: 0.6,
                        ease: 'power2.out',
                        clearProps: 'opacity,transform'
                    }
                );

                gsap.fromTo('.quality-item',
                    { x: 30, opacity: 0 },
                    {
                        scrollTrigger: {
                            trigger: '#why-us',
                            start: 'top 85%',
                            once: true
                        },
                        x: 0,
                        opacity: 1,
                        stagger: 0.1,
                        duration: 0.6,
                        ease: 'power2.out',
                        clearProps: 'opacity,transform'
                    }
                );

                gsap.fromTo('.technician-hero-wrapper',
                    { scale: 0.92, opacity: 0 },
                    {
                        scrollTrigger: {
                            trigger: '#why-us',
                            start: 'top 85%',
                            once: true
                        },
                        scale: 1,
                        opacity: 1,
                        duration: 0.8,
                        ease: 'power2.out',
                        clearProps: 'opacity,transform'
                    }
                );

                gsap.fromTo('.floating-rating-badge',
                    { y: 30, scale: 0.85, opacity: 0 },
                    {
                        scrollTrigger: {
                            trigger: '#why-us',
                            start: 'top 85%',
                            once: true
                        },
                        y: 0,
                        scale: 1,
                        opacity: 1,
                        delay: 0.3,
                        duration: 0.7,
                        ease: 'back.out(1.6)'
                    }
                );

                // Continuous Hovering on Rating Badge
                gsap.to('.floating-rating-badge', {
                    y: '-=8',
                    duration: 2.2,
                    repeat: -1,
                    yoyo: true,
                    ease: 'sine.inOut'
                });

                // Rating Counter (0.0 -> 4.9)
                const ratingCounter = document.querySelector('.rating-counter-val');
                if (ratingCounter) {
                    const counterObj = { val: 0.0 };
                    gsap.to(counterObj, {
                        scrollTrigger: {
                            trigger: '.floating-rating-badge',
                            start: 'top 92%',
                            once: true
                        },
                        val: 4.9,
                        duration: 1.5,
                        ease: 'power2.out',
                        onUpdate: function() {
                            ratingCounter.innerText = counterObj.val.toFixed(1);
                        }
                    });
                }
            }

            // 8. OFFERS & REVIEWS SECTIONS
            const offersEl = document.querySelector('#offers-section');
            if (offersEl) {
                gsap.fromTo('#offers-section',
                    { y: 35, opacity: 0 },
                    {
                        scrollTrigger: {
                            trigger: '#offers-section',
                            start: 'top 90%',
                            once: true
                        },
                        y: 0,
                        opacity: 1,
                        duration: 0.7,
                        ease: 'power2.out',
                        clearProps: 'opacity,transform'
                    }
                );
            }

            const reviewsEl = document.querySelector('.reviews-swiper');
            if (reviewsEl) {
                gsap.fromTo('.reviews-swiper',
                    { y: 35, opacity: 0 },
                    {
                        scrollTrigger: {
                            trigger: '.reviews-swiper',
                            start: 'top 90%',
                            once: true
                        },
                        y: 0,
                        opacity: 1,
                        duration: 0.7,
                        ease: 'power2.out',
                        clearProps: 'opacity,transform'
                    }
                );
            }

            // 10. STRATEGY ACCORDION
            const strategyEl = document.querySelector('#strategyAccordion');
            if (strategyEl) {
                gsap.fromTo('#strategyAccordion .accordion-item',
                    { y: 25, opacity: 0 },
                    {
                        scrollTrigger: {
                            trigger: '#strategyAccordion',
                            start: 'top 90%',
                            once: true
                        },
                        y: 0,
                        opacity: 1,
                        stagger: 0.08,
                        duration: 0.6,
                        ease: 'power2.out',
                        clearProps: 'opacity,transform'
                    }
                );
            }

            // 11. FOOTER
            const footerEl = document.querySelector('.main-footer');
            if (footerEl) {
                gsap.fromTo('.main-footer .col-lg-4, .main-footer .col-lg-2, .main-footer .col-lg-3',
                    { y: 25, opacity: 0 },
                    {
                        scrollTrigger: {
                            trigger: '.main-footer',
                            start: 'top 92%',
                            once: true
                        },
                        y: 0,
                        opacity: 1,
                        stagger: 0.1,
                        duration: 0.6,
                        ease: 'power2.out',
                        clearProps: 'opacity,transform'
                    }
                );
            }

            // 12. SCROLL TO TOP BUTTON
            const scrollTopBtn = document.getElementById('scrollTopBtn');
            if (scrollTopBtn) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 300) {
                        gsap.to(scrollTopBtn, {
                            opacity: 1,
                            scale: 1,
                            pointerEvents: 'auto',
                            duration: 0.3,
                            ease: 'back.out(1.5)'
                        });
                    } else {
                        gsap.to(scrollTopBtn, {
                            opacity: 0,
                            scale: 0.5,
                            pointerEvents: 'none',
                            duration: 0.2,
                            ease: 'power2.in'
                        });
                    }
                });

                scrollTopBtn.addEventListener('click', function() {
                    gsap.to(window, {
                        duration: 0.8,
                        scrollTo: { y: 0 },
                        ease: 'power3.inOut'
                    });
                });
            }
        }

        function fetchNotificationsCount() {
            fetch("{{ route('notifications.unread-count') }}")
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    if (badge && data.count > 0) {
                        badge.innerText = data.count;
                        badge.style.display = 'inline-block';
                    } else if (badge) {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching notifications:', error));
        }
    </script>
@endpush
