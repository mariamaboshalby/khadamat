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

    <!-- Compact Hero -->
    <section class="tech-hero">
        <div class="tech-hero-inner">
            <div class="tech-hero-text">
                <span class="tech-hero-badge"><i class="fas fa-medal"></i> فنيون معتمدون وموثوقون</span>
                <h1 class="tech-hero-title">نخبة الفنيين</h1>
                <p class="tech-hero-sub">تصفّح فريقنا من الفنيين المحترفين حسب التخصص — اختر الأنسب لخدمتك بثقة.</p>
            </div>
            <div class="tech-hero-stats">
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
    </section>

    <!-- Filters & Search -->
    <section class="tech-filters-section">
        <div class="tech-search-wrap">
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
    </section>

    <!-- Technicians Grid -->
    <section class="tech-grid-section">
        <div class="tech-container">

            @if ($technicians->isEmpty())
                <div class="tech-empty-state text-center py-5">
                    <div class="tech-empty-icon mb-3"><i class="fas fa-user-slash"></i></div>
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
                            <div class="tech-category-block mb-4" data-category="spec-{{ $spec->id }}">
                                <div class="tech-category-header mb-3">
                                    <div class="tech-category-icon" style="background: {{ $meta['bg'] }}; color: {{ $meta['color'] }};">
                                        <i class="fas {{ $meta['icon'] }}"></i>
                                    </div>
                                    <div class="tech-category-info">
                                        <h3 class="tech-category-title mb-0">{{ $spec->name }}</h3>
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
                        <div class="tech-category-block mb-4" data-category="spec-other">
                            <div class="tech-category-header mb-3">
                                <div class="tech-category-icon" style="background: #f1f5f9; color: #64748b;">
                                    <i class="fas fa-user-gear"></i>
                                </div>
                                <div class="tech-category-info">
                                    <h3 class="tech-category-title mb-0">تخصصات أخرى</h3>
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
                    <div class="tech-empty-icon mb-3"><i class="fas fa-search"></i></div>
                    <h4 class="fw-bold text-dark">لا توجد نتائج</h4>
                    <p class="text-muted">جرّب تغيير كلمة البحث أو اختيار تخصص آخر.</p>
                </div>
            @endif
        </div>
    </section>
</div>

<style>
    .technicians-page { background: #f8fafc; min-height: 100vh; }

    .tech-container { max-width: 1120px; margin: 0 auto; }

    /* Compact hero */
    .tech-hero {
        background: linear-gradient(135deg, #0b5f8a 0%, #188ec9 100%);
        color: #fff;
        padding: 22px 16px;
    }
    .tech-hero-inner {
        max-width: 1120px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }
    .tech-hero-badge {
        display: inline-flex; align-items: center; gap: 7px;
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.22);
        padding: 4px 12px; border-radius: 30px;
        font-size: 12px; font-weight: 600; margin-bottom: 8px;
    }
    .tech-hero-title { font-size: 24px; font-weight: 900; margin: 0 0 6px; line-height: 1.2; }
    .tech-hero-sub { font-size: 13.5px; opacity: .9; margin: 0; max-width: 480px; line-height: 1.6; }
    .tech-hero-stats { display: flex; gap: 10px; flex-wrap: wrap; }
    .tech-stat-chip {
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.18);
        border-radius: 14px; padding: 8px 16px; text-align: center; min-width: 78px;
    }
    .tech-stat-num { display: block; font-size: 19px; font-weight: 800; }
    .tech-stat-label { font-size: 11.5px; opacity: .8; }

    /* Filters */
    .tech-filters-section {
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 16px;
    }
    .tech-search-wrap { position: relative; max-width: 1120px; margin: 0 auto 10px; }
    .tech-search-icon { position: absolute; top: 50%; right: 16px; transform: translateY(-50%); color: #94a3b8; }
    .tech-search-input {
        width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px;
        padding: 10px 44px 10px 16px; font-size: 14px; background: #f8fafc;
        transition: border-color .2s, box-shadow .2s;
    }
    .tech-search-input:focus {
        outline: none; border-color: #0b5f8a;
        box-shadow: 0 0 0 3px rgba(11,95,138,.1); background: #fff;
    }
    .tech-filter-scroll {
        display: flex; gap: 8px; overflow-x: auto;
        max-width: 1120px; margin: 0 auto; padding-bottom: 2px; scrollbar-width: none;
    }
    .tech-filter-scroll::-webkit-scrollbar { display: none; }
    .tech-filter-btn {
        display: inline-flex; align-items: center; gap: 6px; white-space: nowrap;
        border: 1.5px solid #e2e8f0; background: #fff; color: #475569;
        border-radius: 50px; padding: 7px 14px; font-size: 13px; font-weight: 600;
        cursor: pointer; transition: all .2s ease;
    }
    .tech-filter-btn:hover { border-color: #0b5f8a; color: #0b5f8a; }
    .tech-filter-btn.active { background: #0b5f8a; border-color: #0b5f8a; color: #fff; }
    .tech-filter-count { background: rgba(0,0,0,.08); border-radius: 20px; padding: 1px 8px; font-size: 11px; }
    .tech-filter-btn.active .tech-filter-count { background: rgba(255,255,255,.25); }

    /* Grid section */
    .tech-grid-section { padding: 22px 16px 40px; }

    /* Category blocks */
    .tech-category-header {
        display: flex; align-items: center; gap: 14px;
        padding-bottom: 12px; border-bottom: 2px solid #e2e8f0;
    }
    .tech-category-icon {
        width: 46px; height: 46px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }
    .tech-category-info { min-width: 0; }
    .tech-category-title { font-size: 18px; font-weight: 800; color: #0f172a; }
    .tech-category-desc { font-size: 12.5px; color: #64748b; }
    .tech-category-count {
        margin-right: auto; background: #f1f5f9; color: #475569;
        font-size: 12px; font-weight: 700; padding: 6px 14px;
        border-radius: 20px; white-space: nowrap;
    }

    /* Technician cards */
    .technician-card-box {
        background: #fff; border: 1px solid #eef2f6; border-radius: 20px;
        overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,.03);
        transition: all .3s cubic-bezier(.4,0,.2,1);
        height: 100%; display: flex; flex-direction: column;
    }
    .technician-card-box:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 35px rgba(11,95,138,.1); border-color: #cbd5e1;
    }
    .tech-image-wrap { height: 170px; overflow: hidden; background: #f1f5f9; position: relative; }
    .tech-avatar-img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease; }
    .technician-card-box:hover .tech-avatar-img { transform: scale(1.05); }
    .tech-avatar-icon-placeholder {
        width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg,#e8f0fe 0%,#d0dff8 100%); color: #5a7fbf;
        font-size: 54px; transition: transform .4s ease;
    }
    .technician-card-box:hover .tech-avatar-icon-placeholder { transform: scale(1.05); }
    .tech-rating-pill {
        position: absolute; bottom: 10px; right: 10px;
        background: rgba(255,255,255,.92); backdrop-filter: blur(8px);
        padding: 4px 11px; border-radius: 20px; font-size: 12px; font-weight: 700;
        color: #0f172a; box-shadow: 0 4px 10px rgba(0,0,0,.1); display: flex; align-items: center; gap: 5px;
    }
    .tech-status-badge {
        position: absolute; top: 10px; right: 10px; font-size: 11px; font-weight: 700;
        padding: 4px 10px; border-radius: 20px;
    }
    .tech-status-available { background: #dcfce7; color: #15803d; }
    .tech-status-busy { background: #fef3c7; color: #b45309; }
    .tech-status-offline { background: #f1f5f9; color: #64748b; }
    .tech-card-body { flex: 1; display: flex; flex-direction: column; padding: 16px !important; }
    .tech-name { font-size: 17px; font-weight: 800; color: #0f172a; }
    .tech-spec-badge {
        display: inline-flex; align-items: center; gap: 5px; font-size: 12px;
        font-weight: 600; padding: 4px 12px; border-radius: 20px;
    }
    .tech-stats-row { display: flex; justify-content: center; gap: 14px; font-size: 12.5px; color: #64748b; }
    .tech-stats-row span { display: flex; align-items: center; gap: 4px; }
    .btn-outline-tech {
        display: block; border: 1.5px solid #0b5f8a; color: #0b5f8a; background: transparent;
        font-weight: 700; font-size: 14px; padding: 9px 18px; border-radius: 12px;
        text-decoration: none; transition: all .25s ease; text-align: center; margin-top: auto;
    }
    .btn-outline-tech:hover { background: #0b5f8a; color: #fff; }

    .tech-empty-state .tech-empty-icon {
        width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; margin: 0 auto;
        font-size: 32px; color: #94a3b8;
    }

    @media (max-width: 575px) {
        .tech-hero-inner { flex-direction: column; align-items: flex-start; }
        .tech-hero-stats { width: 100%; }
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
