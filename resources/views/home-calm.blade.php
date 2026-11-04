@extends('layouts.mobile')

@section('title', 'خدمات - فيك المعتمد عند بابك')

@php
    // أيقونات افتراضية مريحة لكل خدمة
    $serviceMeta = [
        'سباكة'        => ['icon' => 'fa-faucet',        'tint' => '#e0f2fe', 'ink' => '#0369a1'],
        'كهرباء'       => ['icon' => 'fa-lightbulb',     'tint' => '#fef3c7', 'ink' => '#b45309'],
        'تكييف'        => ['icon' => 'fa-snowflake',     'tint' => '#e0e7ff', 'ink' => '#4338ca'],
        'نجارة'        => ['icon' => 'fa-hammer',        'tint' => '#fde8e8', 'ink' => '#b91c1c'],
        'نظافة'        => ['icon' => 'fa-broom',         'tint' => '#dcfce7', 'ink' => '#15803d'],
        'أجهزة منزلية' => ['icon' => 'fa-plug',          'tint' => '#f3e8ff', 'ink' => '#7e22ce'],
    ];
    $strategiesList = $strategies ?? collect();
@endphp

@section('content')

    <!-- ========================================================================= -->
    <!-- HERO — هادئ وبسيط                                                          -->
    <!-- ========================================================================= -->
    <section class="calm-hero">
        <div class="calm-hero-bg" style="background-image: url('{{ asset('images/hero-bg.jpg') }}')"></div>
        <div class="calm-hero-overlay"></div>
        <span class="calm-blob calm-blob-1"></span>
        <span class="calm-blob calm-blob-2"></span>

        <div class="calm-hero-inner">
            <div class="calm-hero-grid">
                <div class="calm-hero-content">
                    <span class="calm-hero-pill anim-fade stagger-1">
                        <i class="fas fa-shield-heart"></i>
                        فنيون موثوقون بضمان
                    </span>

                    <h1 class="calm-hero-title anim-fade stagger-2">
                        كل خدمات منزلك<br>
                        <span class="calm-hero-highlight">في مكان واحد، دون عناء</span>
                    </h1>

                    <p class="calm-hero-sub anim-fade stagger-3">
                        اطلب الفني المناسب لمنزلك بسهولة، وقارن بين العروض، ودَعنا نتولّى الباقي.
                    </p>

                    <div class="calm-hero-actions anim-fade stagger-4">
                        <a href="{{ route('requests.create') }}" class="calm-btn calm-btn-amber pulse-btn">
                            <i class="fas fa-plus-circle"></i>
                            اطلب خدمة الآن
                        </a>
                        <a href="#services" class="calm-btn calm-btn-ghost">
                            <i class="fas fa-compass"></i>
                            تصفّح الخدمات
                        </a>
                    </div>

                    <div class="calm-hero-trust anim-fade stagger-5">
                        <div class="trust-item">
                            <i class="fas fa-face-smile"></i>
                            <span><b class="calm-count" data-counter="15000" data-suffix="+">0</b> عميل سعيد</span>
                        </div>
                        <div class="trust-item">
                            <i class="fas fa-star"></i>
                            <span><b class="calm-count" data-counter="4.9" data-decimals="1">0</b> متوسط التقييم</span>
                        </div>
                        <div class="trust-item">
                            <i class="fas fa-house"></i>
                            <span><b class="calm-count" data-counter="25000" data-suffix="+">0</b> مهمة تمت</span>
                        </div>
                    </div>
                </div>

                <div class="calm-hero-visual anim-fade stagger-3">
                    <div class="hero-photo-wrap float-a">
                        <img src="{{ asset('images/hero-bg.jpg') }}" alt="خدمات منزلك" class="hero-photo">
                        <span class="photo-shine"></span>
                    </div>
                    <div class="hero-float-card hero-float-a">
                        <span class="hfc-icon"><i class="fas fa-bolt"></i></span>
                        <b>استجابة سريعة</b>
                        <small>أقل من 30 دقيقة</small>
                    </div>
                    <div class="hero-float-card hero-float-b">
                        <span class="hfc-icon"><i class="fas fa-hand-holding-dollar"></i></span>
                        <b>أسعار واضحة</b>
                        <small>دون مفاجآت</small>
                    </div>
                    <div class="hero-float-card hero-float-c">
                        <span class="hfc-icon"><i class="fas fa-shield-halved"></i></span>
                        <b>ضمان الجودة</b>
                        <small>فنيون معتمدون</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="calm-marquee">
            <div class="calm-marquee-track">
                @php
                    $marqueeIcons = ['fa-faucet' => 'سباكة', 'fa-lightbulb' => 'كهرباء', 'fa-snowflake' => 'تكييف', 'fa-hammer' => 'نجارة', 'fa-broom' => 'نظافة', 'fa-plug' => 'أجهزة منزلية', 'fa-paint-roller' => 'دهانات', 'fa-wrench' => 'صيانة عامة'];
                @endphp
                @foreach (array_merge($marqueeIcons, $marqueeIcons) as $icon => $label)
                    <span class="marquee-item"><i class="fas {{ $icon }}"></i> {{ $label }}</span>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- QUICK SERVICES — اختصارات سريعة                                            -->
    <!-- ========================================================================= -->
    <section class="calm-section" id="services">
        <div class="calm-container">
            <div class="calm-section-head">
                <div>
                    <span class="calm-kicker">خدماتنا</span>
                    <h2 class="calm-heading">احتياجات منزلك.. ابدأ من هنا</h2>
                </div>
                <a href="{{ route('services.index') }}" class="calm-more-link">
                    كل الخدمات <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            @php $displayServices = $services->take(8); @endphp
            <div class="calm-services-grid">
                @forelse ($displayServices as $service)
                    @php
                        $meta = $serviceMeta[$service->name] ?? ['icon' => $service->icon ?: 'fa-screwdriver-wrench', 'tint' => '#f1f5f9', 'ink' => '#334155'];
                    @endphp
                    <a href="{{ route('service.show', \App\Helpers\EncryptionHelper::encryptId($service->id)) }}"
                        class="calm-service-card calm-reveal" style="--d: {{ $loop->index * 70 }}ms">
                        <span class="calm-service-icon tilt-icon" style="background: {{ $meta['tint'] }}; color: {{ $meta['ink'] }};">
                            <i class="fas {{ $meta['icon'] }}"></i>
                        </span>
                        <span class="calm-service-name">{{ $service->name }}</span>
                        <span class="card-arrow"><i class="fas fa-arrow-left"></i></span>
                    </a>
                @empty
                    @foreach ($serviceMeta as $name => $meta)
                        <a href="{{ route('services.index') }}" class="calm-service-card calm-reveal" style="--d: {{ $loop->index * 70 }}ms">
                            <span class="calm-service-icon tilt-icon" style="background: {{ $meta['tint'] }}; color: {{ $meta['ink'] }};">
                                <i class="fas {{ $meta['icon'] }}"></i>
                            </span>
                            <span class="calm-service-name">{{ $name }}</span>
                            <span class="card-arrow"><i class="fas fa-arrow-left"></i></span>
                        </a>
                    @endforeach
                @endforelse
            </div>

      
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- HOW IT WORKS — خطوات واضحة                                                 -->
    <!-- ========================================================================= -->
    <section class="calm-section calm-section-alt" id="how-it-works">
        <div class="calm-container">
            <div class="calm-section-head centered">
                <div>
                    <span class="calm-kicker">أربع خطوات بسيطة</span>
                    <h2 class="calm-heading">من الطلب إلى الإنجاز.. بكل سهولة</h2>
                    <p class="calm-subheading">تجربة سلسة مصمّمة لتوفّر وقتك وجهدك</p>
                </div>
            </div>

            @php
                $processSteps = [
                    ['icon' => 'fa-list-check',     'title' => 'اختر الخدمة',   'desc' => 'حدّد نوع الصيانة الذي تحتاجه من قائمة خدماتنا المتنوّعة.'],
                    ['icon' => 'fa-calendar-check', 'title' => 'حدّد الموعد',   'desc' => 'اختر الوقت والموقع المناسبين، وسنصل إليك حتى باب منزلك.'],
                    ['icon' => 'fa-scale-balanced', 'title' => 'قارن العروض',    'desc' => 'فنيون معتمدون يعرضون أسعارهم، وأنت تختار الأنسب بثقة.'],
                    ['icon' => 'fa-certificate',    'title' => 'استلم مضمونًا',  'desc' => 'ينجز الفني العمل بجودة، مع متابعة وضمان وتقييم.'],
                ];
            @endphp

            <div class="calm-process">
                <span class="process-line" aria-hidden="true"></span>
                @foreach ($processSteps as $step)
                    <div class="process-step calm-reveal" style="--d: {{ $loop->iteration * 130 }}ms">
                        <div class="process-badge">
                            <i class="fas {{ $step['icon'] }}"></i>
                            <span class="process-num">{{ $loop->iteration }}</span>
                        </div>
                        <h3 class="process-title">{{ $step['title'] }}</h3>
                        <p class="process-desc">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- TOP TECHNICIANS — نخبة الفنيين                                             -->
    <!-- ========================================================================= -->
    @if (($topTechnicians ?? collect())->count())
        <section class="calm-section" id="technicians">
            <div class="calm-container">
                <div class="calm-section-head">
                    <div>
                        <span class="calm-kicker">الأعلى تقييماً</span>
                        <h2 class="calm-heading">فنيون يعتنون بمنزلك</h2>
                    </div>
                    <a href="{{ route('technicians.index') }}" class="calm-more-link">
                        كل الفنيين <i class="fas fa-arrow-left"></i>
                    </a>
                </div>

                <div class="calm-tech-grid">
                    @foreach ($topTechnicians as $tech)
                        @php $techAvatar = media_url($tech->getFirstMediaUrl('avatar', 'thumb')) ?: asset('images/tech-avatar.jpg'); @endphp
                        <a href="{{ route('technician.profile', \App\Helpers\EncryptionHelper::encryptId($tech->id)) }}"
                            class="calm-tech-card calm-reveal" style="--d: {{ $loop->index * 100 }}ms">
                            <span class="calm-tech-avatar-wrap">
                                <img src="{{ $techAvatar }}" alt="{{ $tech->full_name ?? 'فني خدمات' }}" class="calm-tech-avatar" loading="lazy">
                                <span class="avatar-ring"></span>
                            </span>
                            <h3 class="calm-tech-name">{{ $tech->full_name ?? $tech->user->name ?? 'فني خدمات' }}</h3>
                            <p class="calm-tech-spec">{{ $tech->specialization->name ?? 'صيانة منزلية' }}</p>
                            <div class="calm-tech-stats">
                                <span class="calm-tech-rating">
                                    <i class="fas fa-star"></i>
                                    {{ number_format((float) $tech->rating, 1) }}
                                </span>
                                <span class="calm-tech-tasks">
                                    <i class="fas fa-check-circle"></i>
                                    {{ $tech->completed_tasks ?? 0 }} مهمة
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ========================================================================= -->
    <!-- OFFERS — عروض حقيقية من قاعدة البيانات                                     -->
    <!-- ========================================================================= -->
    @if (($offers ?? collect())->count())
        <section class="calm-section calm-section-alt" id="offers">
            <div class="calm-container">
                <div class="calm-section-head">
                    <div>
                        <span class="calm-kicker">توفير حقيقي</span>
                        <h2 class="calm-heading">عروض هذه الفترة</h2>
                    </div>
                    <a href="{{ route('offers.index') }}" class="calm-more-link">
                        كل العروض <i class="fas fa-arrow-left"></i>
                    </a>
                </div>

                <div class="calm-offers-grid">
                    @foreach ($offers->take(3) as $offer)
                        <div class="calm-offer-card calm-reveal hover-lift" style="--d: {{ $loop->index * 120 }}ms">
                            <div class="calm-offer-top">
                                <span class="calm-offer-icon">
                                    <i class="fas {{ $offer->icon ?: 'fa-tags' }}"></i>
                                </span>
                                @if ($offer->discount_value)
                                    <span class="calm-offer-badge">
                                        خصم {!! $offer->discount_value !!}
                                        @if ($offer->discount_label)
                                            {{ $offer->discount_label }}
                                        @endif
                                    </span>
                                @endif
                            </div>
                            <h3 class="calm-offer-title">{{ $offer->title }}</h3>
                            <p class="calm-offer-sub">{{ $offer->subtitle_1 ?: $offer->subtitle_2 ?: '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ========================================================================= -->
    <!-- REVIEWS — آراء عملاء حقيقيين                                               -->
    <!-- ========================================================================= -->
    @if (($reviews ?? collect())->count())
        <section class="calm-section" id="reviews">
            <div class="calm-container">
                <div class="calm-section-head centered">
                    <div>
                        <span class="calm-kicker">تقييمات موثوقة</span>
                        <h2 class="calm-heading">ماذا يقول عملاؤنا؟</h2>
                    </div>
                </div>

                <div class="calm-reviews-grid">
                    @foreach ($reviews->take(6) as $review)
                        <div class="calm-review-card calm-reveal hover-lift" style="--d: {{ $loop->index * 90 }}ms">
                            <div class="calm-review-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa{{ $i <= round($review->rating) ? 's' : 'r' }} fa-star"></i>
                                @endfor
                            </div>
                            @if ($review->comment)
                                <p class="calm-review-text">“{{ \Illuminate\Support\Str::limit($review->comment, 110) }}”</p>
                            @endif
                            <div class="calm-review-person">
                                <span class="calm-review-avatar">
                                    <i class="fas fa-user"></i>
                                </span>
                                <div class="calm-review-info">
                                    <span class="calm-review-name">{{ $review->user->name ?? 'عميل خدمات' }}</span>
                                    <span class="calm-review-tech">
                                        @if ($review->technician && $review->technician->user)
                                            فني: {{ $review->technician->user->name }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ========================================================================= -->
    <!-- FINAL CTA — بانر دافي وبسيط                                               -->
    <!-- ========================================================================= -->
    <section class="calm-section">
        <div class="calm-container">
            <div class="calm-cta-banner calm-reveal">
                <span class="cta-spark cta-spark-1"></span>
                <span class="cta-spark cta-spark-2"></span>
                <div class="calm-cta-text">
                    <h2>هل أنت مستعد للراحة؟</h2>
                    <p>اطلب الآن، وسيصلك الفني المعتمد في الموعد الذي تختاره.</p>
                </div>
                <a href="{{ route('requests.create') }}" class="calm-btn calm-btn-light">
                    <i class="fas fa-bolt"></i>
                    اطلب خدمة الآن
                </a>
            </div>
        </div>
    </section>

@endsection

@push('styles')
    <style>
        /* ============================================================
           لوحة ألوان مريحة للعين: خلفات دافئة، تباين منخفض، مساحات واسعة
           ============================================================ */
        :root {
            --calm-bg: #faf9f6;
            --calm-bg-alt: #f0f5f2;
            --calm-ink: #2f3b36;
            --calm-ink-soft: #5f6f68;
            --calm-muted: #9aa8a1;
            --calm-teal: #2e6b5e;
            --calm-teal-soft: #e3efe9;
            --calm-amber: #e08a3c;
            --calm-amber-strong: #d97c2b;
            --calm-card: #ffffff;
            --calm-border: #e7ebe8;
            --calm-shadow: 0 6px 20px -8px rgba(47, 59, 54, 0.12);
            --calm-shadow-hover: 0 12px 28px -10px rgba(47, 59, 54, 0.18);
        }

        body {
            background: var(--calm-bg);
        }

        .calm-container {
            max-width: 1080px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ---------- Hero ---------- */
        .calm-hero {
            position: relative;
            overflow: hidden;
            padding: 64px 20px 0;
            border-bottom: 1px solid var(--calm-border);
            background: linear-gradient(160deg, #f4f8f5 0%, #faf9f6 55%, #fbf6ee 100%);
        }

        .calm-hero-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0.06;
            animation: calm-ken-burns 26s ease-in-out infinite alternate;
            z-index: 0;
        }

        .calm-hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, rgba(250, 249, 246, 0.9), rgba(250, 249, 246, 0.55));
            z-index: 1;
        }

        .calm-hero-inner {
            position: relative;
            z-index: 3;
            max-width: 1080px;
            margin: 0 auto;
        }

        .calm-hero-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 36px;
            align-items: center;
        }

        @media (min-width: 900px) {
            .calm-hero-grid {
                grid-template-columns: 1.05fr 0.95fr;
            }
        }

        .calm-hero-content {
            text-align: center;
        }

        @media (min-width: 900px) {
            .calm-hero-content {
                text-align: start;
            }
        }

        /* decorative blurred blobs */
        .calm-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            z-index: 1;
            opacity: 0.45;
        }
        .calm-blob-1 {
            width: 320px; height: 320px;
            background: #bfe0d5;
            top: -60px; left: -40px;
            animation: calm-blob-move 18s ease-in-out infinite alternate;
        }
        .calm-blob-2 {
            width: 260px; height: 260px;
            background: #f6dcc0;
            bottom: 40px; right: -30px;
            animation: calm-blob-move 22s ease-in-out infinite alternate-reverse;
        }

        .calm-hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--calm-teal-soft);
            color: var(--calm-teal);
            font-weight: 700;
            font-size: 14px;
            padding: 8px 18px;
            border-radius: 999px;
            margin-bottom: 22px;
        }

        .calm-hero-title {
            font-size: clamp(1.9rem, 4.5vw, 3rem);
            font-weight: 800;
            color: var(--calm-ink);
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .calm-hero-highlight {
            color: var(--calm-teal);
            position: relative;
            display: inline-block;
        }
        .calm-hero-highlight::after {
            content: '';
            position: absolute;
            bottom: 6px;
            right: 0;
            height: 10px;
            width: 100%;
            background: rgba(224, 138, 60, 0.28);
            border-radius: 6px;
            z-index: -1;
            transform: scaleX(0);
            transform-origin: right;
            animation: calm-underline 0.9s 0.6s ease forwards;
        }

        .calm-hero-sub {
            font-size: 1.05rem;
            color: var(--calm-ink-soft);
            line-height: 1.9;
            max-width: 560px;
            margin: 0 auto 30px;
        }

        @media (min-width: 900px) {
            .calm-hero-sub {
                margin: 0 0 30px;
            }
        }

        .calm-hero-actions {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 30px;
        }

        @media (min-width: 900px) {
            .calm-hero-actions {
                justify-content: flex-start;
            }
        }

        /* hero visual / photo */
        .calm-hero-visual {
            position: relative;
            display: none;
        }
        @media (min-width: 640px) {
            .calm-hero-visual {
                display: block;
            }
        }

        .hero-photo-wrap {
            position: relative;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 24px 50px -18px rgba(47, 59, 54, 0.35);
            aspect-ratio: 4 / 3;
        }
        .hero-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            animation: calm-ken-burns 20s ease-in-out infinite alternate;
        }
        .photo-shine {
            position: absolute;
            top: 0;
            left: -60%;
            width: 40%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.35), transparent);
            transform: skewX(-18deg);
            animation: calm-shine 5s 1.2s ease-in-out infinite;
        }

        .hero-float-card {
            position: absolute;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border: 1px solid var(--calm-border);
            border-radius: 16px;
            padding: 12px 16px;
            box-shadow: var(--calm-shadow);
            display: flex;
            flex-direction: column;
            gap: 2px;
            text-align: start;
            min-width: 150px;
        }
        .hero-float-card b {
            color: var(--calm-ink);
            font-size: 0.9rem;
        }
        .hero-float-card small {
            color: var(--calm-muted);
            font-size: 0.75rem;
        }
        .hfc-icon {
            position: absolute;
            top: -14px;
            right: 14px;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: var(--calm-teal);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            box-shadow: 0 6px 14px -4px rgba(46, 107, 94, 0.5);
        }
        .hero-float-a { top: 18px; left: 12px; animation: calm-float 5s ease-in-out infinite; }
        .hero-float-b { bottom: 60px; right: 8px; animation: calm-float 6.5s ease-in-out infinite; }
        .hero-float-c { bottom: -8px; left: 30px; animation: calm-float 5.8s ease-in-out infinite; }

        .float-a { animation: calm-float 6s ease-in-out infinite; }

        .calm-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 15px;
            padding: 13px 28px;
            border-radius: 999px;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .calm-btn-amber {
            background: var(--calm-amber);
            color: #fff;
            box-shadow: 0 6px 18px -6px rgba(224, 138, 60, 0.5);
        }

        .calm-btn-amber:hover {
            background: var(--calm-amber-strong);
            color: #fff;
            transform: translateY(-2px);
        }

        .calm-btn-ghost {
            background: transparent;
            color: var(--calm-teal);
            border: 2px solid var(--calm-teal);
        }

        .calm-btn-ghost:hover {
            background: var(--calm-teal-soft);
            color: var(--calm-teal);
        }

        .calm-btn-light {
            background: #fff;
            color: var(--calm-teal);
            box-shadow: var(--calm-shadow);
        }

        .calm-btn-light:hover {
            color: var(--calm-teal);
            transform: translateY(-2px);
            box-shadow: var(--calm-shadow-hover);
        }

        .calm-hero-trust {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px 28px;
        }

        @media (min-width: 900px) {
            .calm-hero-trust {
                justify-content: flex-start;
            }
        }

        .trust-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--calm-ink-soft);
            font-size: 14px;
            font-weight: 600;
        }

        .trust-item i {
            color: var(--calm-teal);
        }

        .trust-item .calm-count {
            color: var(--calm-ink);
            font-weight: 800;
        }

        /* ---------- Sections ---------- */
        .calm-section {
            padding: 56px 0;
        }

        .calm-section-alt {
            background: var(--calm-bg-alt);
        }

        .calm-section-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 16px;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }

        .calm-section-head.centered {
            justify-content: center;
            text-align: center;
        }

        .calm-kicker {
            display: inline-block;
            color: var(--calm-amber-strong);
            font-weight: 800;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .calm-heading {
            font-size: clamp(1.35rem, 3vw, 1.8rem);
            font-weight: 800;
            color: var(--calm-ink);
            margin: 0;
            line-height: 1.6;
        }

        .calm-subheading {
            color: var(--calm-ink-soft);
            font-size: 1rem;
            margin: 8px 0 0;
        }

        .calm-more-link {
            color: var(--calm-teal);
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 4px;
            white-space: nowrap;
        }

        .calm-more-link:hover {
            color: var(--calm-teal);
            text-decoration: underline;
        }

        /* ---------- Services grid ---------- */
        .calm-services-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        @media (min-width: 576px) {
            .calm-services-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .calm-service-card {
            background: var(--calm-card);
            border: 1px solid var(--calm-border);
            border-radius: 20px;
            padding: 22px 16px;
            text-align: center;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .calm-service-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--calm-shadow-hover);
            border-color: var(--calm-teal-soft);
        }

        .calm-service-icon {
            width: 58px;
            height: 58px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .calm-service-name {
            color: var(--calm-ink);
            font-weight: 700;
            font-size: 15px;
        }

        /* ---------- Process timeline ---------- */
        .calm-process {
            position: relative;
            display: grid;
            grid-template-columns: 1fr;
            gap: 28px;
        }

        @media (min-width: 576px) {
            .calm-process {
                grid-template-columns: repeat(2, 1fr);
                gap: 32px 24px;
            }
        }

        @media (min-width: 900px) {
            .calm-process {
                grid-template-columns: repeat(4, 1fr);
                gap: 22px;
            }
        }

        /* dashed connector line behind the badges (desktop only) */
        .process-line {
            display: none;
        }

        @media (min-width: 900px) {
            .process-line {
                display: block;
                position: absolute;
                top: 34px;
                right: 12%;
                left: 12%;
                height: 2px;
                background-image: linear-gradient(90deg, var(--calm-teal) 55%, transparent 0);
                background-size: 14px 2px;
                background-repeat: repeat-x;
                opacity: 0.4;
                z-index: 0;
            }
        }

        .process-step {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .process-badge {
            position: relative;
            width: 68px;
            height: 68px;
            margin: 0 auto 16px;
            border-radius: 22px;
            background: var(--calm-card);
            border: 1px solid var(--calm-border);
            color: var(--calm-teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            box-shadow: var(--calm-shadow);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), background 0.3s ease, color 0.3s ease;
        }

        .process-step:hover .process-badge {
            background: var(--calm-teal);
            color: #fff;
            transform: translateY(-6px) rotate(-5deg);
        }

        .process-num {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--calm-amber);
            color: #fff;
            font-weight: 800;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px -2px rgba(224, 138, 60, 0.6);
        }

        .process-title {
            font-size: 1.12rem;
            font-weight: 800;
            color: var(--calm-ink);
            margin: 0 0 8px;
        }

        .process-desc {
            font-size: 0.92rem;
            color: var(--calm-ink-soft);
            line-height: 1.85;
            margin: 0 auto;
            max-width: 240px;
        }

        /* ---------- Technicians ---------- */
        .calm-tech-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        @media (min-width: 768px) {
            .calm-tech-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .calm-tech-card {
            background: var(--calm-card);
            border: 1px solid var(--calm-border);
            border-radius: 22px;
            padding: 24px 16px;
            text-align: center;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .calm-tech-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--calm-shadow-hover);
        }

                .calm-tech-avatar-wrap {
            position: relative;
            display: inline-block;
            margin-bottom: 12px;
        }

        .calm-tech-avatar {
            width: 74px;
            height: 74px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--calm-teal-soft);
            transition: transform 0.3s ease;
        }

        .calm-tech-card:hover .calm-tech-avatar {
            transform: scale(1.06);
        }

        .avatar-ring {
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            border: 2px dashed var(--calm-amber);
            opacity: 0;
            transition: opacity 0.3s ease;
            animation: calm-spin 9s linear infinite;
        }

        .calm-tech-card:hover .avatar-ring {
            opacity: 0.7;
        }

        .calm-tech-name {
            font-size: 1rem;
            font-weight: 800;
            color: var(--calm-ink);
            margin: 0 0 4px;
        }

        .calm-tech-spec {
            font-size: 0.85rem;
            color: var(--calm-muted);
            margin: 0 0 12px;
        }

        .calm-tech-stats {
            display: flex;
            justify-content: center;
            gap: 14px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .calm-tech-rating {
            color: var(--calm-amber-strong);
        }

        .calm-tech-tasks {
            color: var(--calm-teal);
        }

        /* ---------- Offers ---------- */
        .calm-offers-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        @media (min-width: 768px) {
            .calm-offers-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .calm-offer-card {
            background: var(--calm-card);
            border: 1px solid var(--calm-border);
            border-radius: 22px;
            padding: 24px;
            box-shadow: var(--calm-shadow);
        }

        .calm-offer-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .calm-offer-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: #fbf1e6;
            color: var(--calm-amber-strong);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
        }

        .calm-offer-badge {
            background: var(--calm-teal-soft);
            color: var(--calm-teal);
            font-size: 13px;
            font-weight: 800;
            padding: 6px 14px;
            border-radius: 999px;
        }

        .calm-offer-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--calm-ink);
            margin: 0 0 6px;
        }

        .calm-offer-sub {
            font-size: 0.9rem;
            color: var(--calm-ink-soft);
            line-height: 1.8;
            margin: 0;
        }

        /* ---------- Reviews ---------- */
        .calm-reviews-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        @media (min-width: 576px) {
            .calm-reviews-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 900px) {
            .calm-reviews-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .calm-review-card {
            background: var(--calm-card);
            border: 1px solid var(--calm-border);
            border-radius: 20px;
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .calm-review-stars {
            color: #eab308;
            font-size: 14px;
            display: flex;
            gap: 3px;
        }

        .calm-review-text {
            color: var(--calm-ink-soft);
            font-size: 0.92rem;
            line-height: 1.9;
            margin: 0;
        }

        .calm-review-person {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: auto;
        }

        .calm-review-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--calm-teal-soft);
            color: var(--calm-teal);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .calm-review-info {
            display: flex;
            flex-direction: column;
        }

        .calm-review-name {
            font-weight: 800;
            font-size: 0.9rem;
            color: var(--calm-ink);
        }

        .calm-review-tech {
            font-size: 0.78rem;
            color: var(--calm-muted);
        }

        /* ---------- CTA banner ---------- */
        .calm-cta-banner {
            background: linear-gradient(135deg, var(--calm-teal) 0%, #3d7f6f 100%);
            border-radius: 26px;
            padding: 40px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            flex-wrap: wrap;
        }

        .calm-cta-text h2 {
            color: #fff;
            font-weight: 800;
            font-size: 1.5rem;
            margin: 0 0 6px;
        }

        .calm-cta-text p {
            color: rgba(255, 255, 255, 0.85);
            margin: 0;
            font-size: 0.98rem;
        }

        @media (max-width: 575px) {
            .calm-hero {
                padding: 44px 16px 52px;
            }

            .calm-cta-banner {
                justify-content: center;
                text-align: center;
            }
        }

        /* ---------- Marquee (شريط متحرك تحت الهيرو) ---------- */
        .calm-marquee {
            position: relative;
            z-index: 3;
            margin-top: 44px;
            overflow: hidden;
            border-top: 1px solid var(--calm-border);
            background: rgba(255, 255, 255, 0.55);
            -webkit-mask-image: linear-gradient(90deg, transparent, #000 12%, #000 88%, transparent);
            mask-image: linear-gradient(90deg, transparent, #000 12%, #000 88%, transparent);
        }

        .calm-marquee-track {
            display: flex;
            gap: 40px;
            width: max-content;
            padding: 14px 0;
            animation: calm-marquee 32s linear infinite;
        }

        .calm-marquee:hover .calm-marquee-track {
            animation-play-state: paused;
        }

        .marquee-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--calm-ink-soft);
            font-weight: 700;
            font-size: 14px;
            white-space: nowrap;
        }

        .marquee-item i {
            color: var(--calm-teal);
        }

        /* ---------- Scroll reveal ---------- */
        .calm-reveal {
            opacity: 0;
            transform: translateY(26px);
            transition: opacity 0.6s ease var(--d, 0ms), transform 0.6s cubic-bezier(0.22, 1, 0.36, 1) var(--d, 0ms);
            will-change: opacity, transform;
        }

        .calm-reveal.is-visible {
            opacity: 1;
            transform: none;
        }

        /* entrance for hero elements (played on load) */
        .anim-fade {
            opacity: 0;
            animation: calm-rise 0.7s ease forwards;
        }
        .stagger-1 { animation-delay: 0.05s; }
        .stagger-2 { animation-delay: 0.18s; }
        .stagger-3 { animation-delay: 0.32s; }
        .stagger-4 { animation-delay: 0.46s; }
        .stagger-5 { animation-delay: 0.6s; }

        /* ---------- Photo strip ---------- */
        .calm-photos-strip {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            margin-top: 28px;
        }

        @media (min-width: 768px) {
            .calm-photos-strip {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .calm-photo-tile {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            aspect-ratio: 1 / 1;
            box-shadow: var(--calm-shadow);
        }

        .calm-photo-tile img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .calm-photo-tile:hover img {
            transform: scale(1.1);
        }

        .tile-label {
            position: absolute;
            inset-inline: 0;
            bottom: 0;
            padding: 22px 14px 12px;
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
            background: linear-gradient(0deg, rgba(7, 37, 64, 0.78), transparent);
        }

        .calm-photo-tile.tile-c {
            background: linear-gradient(135deg, var(--calm-teal), #3d7f6f);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tile-stat {
            text-align: center;
            color: #fff;
        }

        .tile-stat b {
            display: block;
            font-size: 1.7rem;
            font-weight: 800;
        }

        .tile-stat span {
            font-size: 0.82rem;
            opacity: 0.9;
        }

        /* ---------- Hover / micro-interactions ---------- */
        .hover-lift {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .hover-lift:hover {
            transform: translateY(-6px);
            box-shadow: var(--calm-shadow-hover);
        }

        .card-arrow {
            position: absolute;
            bottom: 14px;
            left: 14px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--calm-teal-soft);
            color: var(--calm-teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            opacity: 0;
            transform: translateX(8px);
            transition: opacity 0.25s ease, transform 0.25s ease;
        }

        .calm-service-card {
            position: relative;
        }

        .calm-service-card:hover .card-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        .tilt-icon {
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .calm-service-card:hover .tilt-icon {
            transform: rotate(-8deg) scale(1.12);
        }

        .pulse-btn {
            position: relative;
        }

        .pulse-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 999px;
            box-shadow: 0 0 0 0 rgba(224, 138, 60, 0.5);
            animation: calm-pulse 2.4s infinite;
        }

        /* ---------- CTA sparkles ---------- */
        .calm-cta-banner {
            position: relative;
            overflow: hidden;
        }

        .cta-spark {
            position: absolute;
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.14);
            filter: blur(6px);
            animation: calm-float 7s ease-in-out infinite;
        }

        .cta-spark-1 { top: -40px; left: 20%; }
        .cta-spark-2 { bottom: -50px; right: 12%; animation-delay: 1.5s; }

        /* ---------- Keyframes ---------- */
        @keyframes calm-rise {
            from { opacity: 0; transform: translateY(22px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes calm-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        @keyframes calm-ken-burns {
            0% { transform: scale(1) translate(0, 0); }
            100% { transform: scale(1.08) translate(-1.5%, -1%); }
        }

        @keyframes calm-blob-move {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 24px) scale(1.1); }
        }

        @keyframes calm-shine {
            0%, 60% { left: -60%; }
            100% { left: 130%; }
        }

        @keyframes calm-marquee {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }

        @keyframes calm-spin {
            to { transform: rotate(360deg); }
        }

        @keyframes calm-underline {
            to { transform: scaleX(1); }
        }

        @keyframes calm-pulse {
            0% { box-shadow: 0 0 0 0 rgba(224, 138, 60, 0.45); }
            70% { box-shadow: 0 0 0 16px rgba(224, 138, 60, 0); }
            100% { box-shadow: 0 0 0 0 rgba(224, 138, 60, 0); }
        }

        /* احترام تفضيل تقليل الحركة للمستخدمين اللي بيحبوا هدوء أكتر */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.001ms !important;
            }

            .calm-reveal {
                opacity: 1 !important;
                transform: none !important;
            }

            .anim-fade {
                opacity: 1;
            }

            .calm-hero-highlight::after {
                transform: scaleX(1);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            /* 1) Scroll reveal */
            const revealEls = document.querySelectorAll('.calm-reveal');
            if (reduceMotion || !('IntersectionObserver' in window)) {
                revealEls.forEach(el => el.classList.add('is-visible'));
            } else {
                const io = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            io.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.14, rootMargin: '0px 0px -40px 0px' });
                revealEls.forEach(el => io.observe(el));
            }

            /* 2) Number count-up */
            const counters = document.querySelectorAll('.calm-count');
            const animateCount = (el) => {
                const target = parseFloat(el.dataset.counter || '0');
                const decimals = parseInt(el.dataset.decimals || '0', 10);
                const suffix = el.dataset.suffix || '';
                if (reduceMotion) {
                    el.textContent = target.toLocaleString('en-US', {
                        minimumFractionDigits: decimals, maximumFractionDigits: decimals
                    }) + suffix;
                    return;
                }
                const duration = 1400;
                const start = performance.now();
                const step = (now) => {
                    const p = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - p, 3);
                    const val = target * eased;
                    el.textContent = val.toLocaleString('en-US', {
                        minimumFractionDigits: decimals, maximumFractionDigits: decimals
                    }) + suffix;
                    if (p < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            };

            if ('IntersectionObserver' in window && !reduceMotion) {
                const co = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            animateCount(entry.target);
                            co.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.5 });
                counters.forEach(c => co.observe(c));
            } else {
                counters.forEach(animateCount);
            }

            /* 3) Subtle 3D tilt on service & tech cards */
            if (!reduceMotion && window.matchMedia('(hover: hover)').matches) {
                document.querySelectorAll('.calm-service-card, .calm-tech-card').forEach(card => {
                    card.addEventListener('mousemove', (e) => {
                        const r = card.getBoundingClientRect();
                        const x = (e.clientX - r.left) / r.width - 0.5;
                        const y = (e.clientY - r.top) / r.height - 0.5;
                        card.style.transform = `perspective(600px) rotateX(${(-y * 5).toFixed(2)}deg) rotateY(${(x * 5).toFixed(2)}deg) translateY(-4px)`;
                    });
                    card.addEventListener('mouseleave', () => {
                        card.style.transform = '';
                    });
                });
            }
        })();
    </script>
@endpush
