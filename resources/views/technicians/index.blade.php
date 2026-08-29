@extends('layouts.mobile')

@section('title', 'نخبة الفنيين - خدمتي')

@section('content')
@php
    $specMeta = [
        'سباكة' => ['icon' => 'fa-wrench', 'color' => '#0b5f8a', 'bg' => '#e0f2fe'],
        'كهرباء' => ['icon' => 'fa-bolt', 'color' => '#eab308', 'bg' => '#fefce8'],
        'تكييف وتبريد' => ['icon' => 'fa-snowflake', 'color' => '#06b6d4', 'bg' => '#ecfeff'],
        'تكييف' => ['icon' => 'fa-snowflake', 'color' => '#06b6d4', 'bg' => '#ecfeff'],
        'نجارة' => ['icon' => 'fa-hammer', 'color' => '#a855f7', 'bg' => '#faf5ff'],
        'دهانات' => ['icon' => 'fa-paint-roller', 'color' => '#ec4899', 'bg' => '#fdf2f8'],
        'أجهزة منزلية' => ['icon' => 'fa-tv', 'color' => '#f97316', 'bg' => '#fff7ed'],
        'نظافة' => ['icon' => 'fa-broom', 'color' => '#14b8a6', 'bg' => '#f0fdfa'],
    ];
    $defaultMeta = ['icon' => 'fa-user-gear', 'color' => '#0b5f8a', 'bg' => '#f1f5f9'];
@endphp

<div class="technicians-page fade-in" dir="rtl">

    <!-- Hero -->
    <section class="tech-page-hero">
        <div class="tech-hero-glow tech-hero-glow-1"></div>
        <div class="tech-hero-glow tech-hero-glow-2"></div>
        <div class="container-fluid max-w-1200 px-3 px-md-5 position-relative z-1">
            <div class="row align-items-center g-4 py-4 py-md-5">
                <div class="col-lg-7">
                    <div class="tech-hero-badge mb-3">
                        <i class="fas fa-medal"></i>
                        <span>فنيون معتمدون وموثوقون</span>
                    </div>
                    <h1 class="tech-hero-title mb-3">نخبة الفنيين</h1>
                    <p class="tech-hero-sub mb-4">
                        تصفّح فريقنا من الفنيين المحترفين، مُصنَّفين حسب التخصص — اختر الأنسب لخدمتك بثقة.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="tech-stat-chip">
                            <span class="tech-stat-num">{{ $technicians->count() }}</span>
                            <span class="tech-stat-label">فني متاح</span>
                        </div>
                        <div class="tech-stat-chip">
                            <span class="tech-stat-num">{{ $specializations->count() }}</span>
                            <span class="tech-stat-label">تخصص</span>
                        </div>
                        <div class="tech-stat-chip">
                            <span class="tech-stat-num">{{ number_format($technicians->avg('rating') ?: 4.8, 1) }}</span>
                            <span class="tech-stat-label">متوسط التقييم</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                    <div class="tech-hero-visual">
                        <img src="{{ asset('images/technician-hero.jpg') }}" alt="فني محترف" class="tech-hero-img">
                        <div class="tech-hero-float-card">
                            <i class="fas fa-shield-check text-success"></i>
                            <div>
                                <strong>100% موثوق</strong>
                                <small>فحص وتقييم مستمر</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters & Search -->
    <section class="tech-filters-section sticky-filters px-3 px-md-5 py-3">
        <div class="container-fluid max-w-1200">
            <div class="tech-search-wrap mb-3">
                <i class="fas fa-search tech-search-icon"></i>
                <input type="text" id="tech-search" class="tech-search-input" placeholder="ابحث عن فني بالاسم أو التخصص...">
            </div>
            <div class="tech-filter-scroll">
                <button type="button" class="tech-filter-btn active" data-filter="all">
                    <i class="fas fa-users"></i>
                    <span>الكل</span>
                    <span class="tech-filter-count">{{ $technicians->count() }}</span>
                </button>
                @foreach ($specializations as $spec)
                    @php $meta = $specMeta[$spec->name] ?? $defaultMeta; @endphp
                    <button type="button" class="tech-filter-btn" data-filter="spec-{{ $spec->id }}">
                        <i class="fas {{ $meta['icon'] }}"></i>
                        <span>{{ $spec->name }}</span>
                        <span class="tech-filter-count">{{ $spec->technicians_count }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Technicians Grid -->
    <section class="px-3 px-md-5 pb-5">
        <div class="container-fluid max-w-1200">

            @if ($technicians->isEmpty())
                <div class="tech-empty-state text-center py-5">
                    <div class="tech-empty-icon mb-3">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <h4 class="fw-bold text-dark">لا يوجد فنيون حالياً</h4>
                    <p class="text-muted">سيتم إضافة فنيين جدد قريباً. يمكنك طلب خدمة وسنُعيّن لك أفضل فني.</p>
                    <a href="{{ route('requests.create') }}" class="btn btn-brand-orange mt-3 px-4 py-2 rounded-pill">
                        <i class="fas fa-plus ms-1"></i> اطلب خدمة الآن
                    </a>
                </div>
            @else
                {{-- Grouped view (all categories) --}}
                <div id="grouped-view">
                    @foreach ($specializations as $spec)
                        @if ($spec->technicians->isNotEmpty())
                            @php $meta = $specMeta[$spec->name] ?? $defaultMeta; @endphp
                            <div class="tech-category-block mb-5" data-category="spec-{{ $spec->id }}">
                                <div class="tech-category-header mb-4">
                                    <div class="tech-category-icon" style="background: {{ $meta['bg'] }}; color: {{ $meta['color'] }};">
                                        <i class="fas {{ $meta['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <h3 class="tech-category-title mb-1">{{ $spec->name }}</h3>
                                        <p class="tech-category-desc mb-0">{{ $spec->description ?? 'فنيون متخصصون في ' . $spec->name }}</p>
                                    </div>
                                    <span class="tech-category-count">{{ $spec->technicians->count() }} فني</span>
                                </div>
                                <div class="row g-4">
                                    @foreach ($spec->technicians as $tech)
                                        @include('technicians.partials.card', ['tech' => $tech, 'specMeta' => $specMeta, 'defaultMeta' => $defaultMeta])
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @php
                        $groupedIds = $specializations->flatMap(fn($s) => $s->technicians->pluck('id'));
                        $ungrouped = $technicians->whereNotIn('id', $groupedIds);
                    @endphp
                    @if ($ungrouped->isNotEmpty())
                        <div class="tech-category-block mb-5" data-category="spec-other">
                            <div class="tech-category-header mb-4">
                                <div class="tech-category-icon" style="background: #f1f5f9; color: #64748b;">
                                    <i class="fas fa-user-gear"></i>
                                </div>
                                <div>
                                    <h3 class="tech-category-title mb-1">تخصصات أخرى</h3>
                                    <p class="tech-category-desc mb-0">فنيون في مجالات متنوعة</p>
                                </div>
                                <span class="tech-category-count">{{ $ungrouped->count() }} فني</span>
                            </div>
                            <div class="row g-4">
                                @foreach ($ungrouped as $tech)
                                    @include('technicians.partials.card', ['tech' => $tech, 'specMeta' => $specMeta, 'defaultMeta' => $defaultMeta])
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Flat filtered view --}}
                <div id="flat-view" class="d-none">
                    <div class="row g-4" id="flat-grid">
                        @foreach ($technicians as $tech)
                            @include('technicians.partials.card', ['tech' => $tech, 'specMeta' => $specMeta, 'defaultMeta' => $defaultMeta])
                        @endforeach
                    </div>
                </div>

                <div id="no-results" class="tech-empty-state text-center py-5 d-none">
                    <div class="tech-empty-icon mb-3">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4 class="fw-bold text-dark">لا توجد نتائج</h4>
                    <p class="text-muted">جرّب تغيير كلمة البحث أو اختيار تخصص آخر.</p>
                </div>
            @endif
        </div>
    </section>
</div>

<style>
    .technicians-page {
        background: #f8fafc;
        min-height: 100vh;
    }

    .max-w-1200 {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Hero */
    .tech-page-hero {
        background: linear-gradient(135deg, #072540 0%, #0b5f8a 55%, #1e40af 100%);
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .tech-hero-glow {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.35;
        pointer-events: none;
    }

    .tech-hero-glow-1 {
        width: 300px;
        height: 300px;
        background: #f97316;
        top: -80px;
        left: -60px;
    }

    .tech-hero-glow-2 {
        width: 250px;
        height: 250px;
        background: #06b6d4;
        bottom: -60px;
        right: 10%;
    }

    .tech-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
    }

    .tech-hero-title {
        font-size: clamp(1.8rem, 4vw, 2.8rem);
        font-weight: 900;
        line-height: 1.2;
    }

    .tech-hero-sub {
        font-size: 16px;
        opacity: 0.85;
        max-width: 520px;
        line-height: 1.7;
    }

    .tech-stat-chip {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 12px 20px;
        text-align: center;
        min-width: 90px;
    }

    .tech-stat-num {
        display: block;
        font-size: 22px;
        font-weight: 800;
    }

    .tech-stat-label {
        font-size: 12px;
        opacity: 0.75;
    }

    .tech-hero-visual {
        position: relative;
        width: 280px;
    }

    .tech-hero-img {
        width: 100%;
        height: 280px;
        object-fit: cover;
        border-radius: 24px;
        border: 3px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }

    .tech-hero-float-card {
        position: absolute;
        bottom: -16px;
        right: -20px;
        background: #fff;
        color: #0f172a;
        border-radius: 16px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        font-size: 13px;
    }

    .tech-hero-float-card strong {
        display: block;
        font-size: 14px;
    }

    .tech-hero-float-card small {
        color: #64748b;
    }

    /* Filters */
    .sticky-filters {
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    }

    .tech-search-wrap {
        position: relative;
    }

    .tech-search-icon {
        position: absolute;
        top: 50%;
        right: 16px;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .tech-search-input {
        width: 100%;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px 44px 12px 16px;
        font-size: 15px;
        background: #f8fafc;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .tech-search-input:focus {
        outline: none;
        border-color: #0b5f8a;
        box-shadow: 0 0 0 3px rgba(11, 95, 138, 0.1);
        background: #fff;
    }

    .tech-filter-scroll {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: none;
    }

    .tech-filter-scroll::-webkit-scrollbar {
        display: none;
    }

    .tech-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        color: #475569;
        border-radius: 50px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .tech-filter-btn:hover {
        border-color: #0b5f8a;
        color: #0b5f8a;
    }

    .tech-filter-btn.active {
        background: #0b5f8a;
        border-color: #0b5f8a;
        color: #fff;
    }

    .tech-filter-count {
        background: rgba(0, 0, 0, 0.08);
        border-radius: 20px;
        padding: 1px 8px;
        font-size: 11px;
    }

    .tech-filter-btn.active .tech-filter-count {
        background: rgba(255, 255, 255, 0.25);
    }

    /* Category blocks */
    .tech-category-header {
        display: flex;
        align-items: center;
        gap: 16px;
        padding-bottom: 16px;
        border-bottom: 2px solid #e2e8f0;
    }

    .tech-category-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .tech-category-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
    }

    .tech-category-desc {
        font-size: 13px;
        color: #64748b;
    }

    .tech-category-count {
        margin-right: auto;
        background: #f1f5f9;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 20px;
        white-space: nowrap;
    }

    /* Technician cards */
    .technician-card-box {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .technician-card-box:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 35px rgba(11, 95, 138, 0.1);
        border-color: #cbd5e1;
    }

    .tech-image-wrap {
        height: 200px;
        overflow: hidden;
        background: #f1f5f9;
        position: relative;
    }

    .tech-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .technician-card-box:hover .tech-avatar-img {
        transform: scale(1.05);
    }

    .tech-rating-pill {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(8px);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .tech-status-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .tech-status-available {
        background: #dcfce7;
        color: #15803d;
    }

    .tech-status-busy {
        background: #fef3c7;
        color: #b45309;
    }

    .tech-status-offline {
        background: #f1f5f9;
        color: #64748b;
    }

    .tech-card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .tech-name {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
    }

    .tech-spec-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .tech-stats-row {
        display: flex;
        justify-content: center;
        gap: 16px;
        font-size: 13px;
        color: #64748b;
    }

    .tech-stats-row span {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .btn-outline-tech {
        display: block;
        border: 1.5px solid #0b5f8a;
        color: #0b5f8a;
        background: transparent;
        font-weight: 700;
        font-size: 14px;
        padding: 10px 20px;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.25s ease;
        text-align: center;
        margin-top: auto;
    }

    .btn-outline-tech:hover {
        background: #0b5f8a;
        color: #ffffff;
    }

    .tech-empty-state .tech-empty-icon {
        width: 80px;
        height: 80px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 32px;
        color: #94a3b8;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterBtns = document.querySelectorAll('.tech-filter-btn');
    const searchInput = document.getElementById('tech-search');
    const groupedView = document.getElementById('grouped-view');
    const flatView = document.getElementById('flat-view');
    const noResults = document.getElementById('no-results');

    if (!filterBtns.length) return;

    let activeFilter = 'all';

    function getAllCards() {
        return document.querySelectorAll('.tech-card-col');
    }

    function applyFilters() {
        const search = (searchInput?.value || '').toLowerCase().trim();
        let visibleCount = 0;

        if (activeFilter === 'all' && !search) {
            groupedView?.classList.remove('d-none');
            flatView?.classList.add('d-none');
            document.querySelectorAll('.tech-category-block').forEach(b => b.style.display = '');
            getAllCards().forEach(c => c.style.display = '');
            noResults?.classList.add('d-none');
            return;
        }

        groupedView?.classList.add('d-none');
        flatView?.classList.remove('d-none');

        getAllCards().forEach(function (card) {
            const name = (card.dataset.name || '').toLowerCase();
            const spec = (card.dataset.spec || '').toLowerCase();
            const category = card.dataset.category || '';
            const matchesSearch = !search || name.includes(search) || spec.includes(search);
            const matchesFilter = activeFilter === 'all' || category === activeFilter;
            const show = matchesSearch && matchesFilter;
            card.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        noResults?.classList.toggle('d-none', visibleCount > 0);
    }

    filterBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeFilter = btn.dataset.filter;
            applyFilters();
        });
    });

    searchInput?.addEventListener('input', applyFilters);
});
</script>
@endsection
