@extends('layouts.mobile')

@section('title', 'الخدمات')

@section('content')
<div class="svc-page fade-in" dir="rtl">

    <!-- Compact header -->
    <div class="svc-head">
        <div>
            <h1 class="svc-title">كل الخدمات</h1>
            <p class="svc-sub">اختر الخدمة التي تحتاجها من بين {{ $services->count() }} خدمة</p>
        </div>
        <span class="svc-head-ico"><i class="fas fa-th-large"></i></span>
    </div>

    <!-- Search -->
    <div class="svc-search">
        <i class="fas fa-search svc-search-ico"></i>
        <input type="text" id="search-input" value="{{ request('search') }}" class="svc-search-input" placeholder="ابحث عن خدمة...">
    </div>

    <!-- Services Grid -->
    <div id="services-container" class="svc-grid">
        @foreach($services as $service)
            <div class="service-item" data-service-name="{{ $service->name }}">
                <a href="{{ route('service.show', \App\Helpers\EncryptionHelper::encryptId($service->id)) }}" class="svc-card">
                    <div class="svc-icon {{ $service->color_class }}">
                        <i class="fas {{ $service->icon }}"></i>
                    </div>
                    <h6 class="svc-name">{{ $service->name }}</h6>
                    <p class="svc-desc">{{ Str::limit($service->description ?? 'وصف الخدمة', 40) }}</p>
                    <span class="svc-go">التفاصيل <i class="fas fa-arrow-left"></i></span>
                </a>
            </div>
        @endforeach
    </div>

    <div class="svc-noresults" id="svc-noresults" style="display:none;">
        <i class="fas fa-search"></i>
        <p>لا توجد خدمة مطابقة لبحثك</p>
    </div>
</div>

<style>
    .svc-page {
        max-width: 1080px;
        margin: 0 auto;
        padding: 24px 16px 48px;
    }

    .svc-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
    }
    .svc-title { font-size: 22px; font-weight: 800; color: var(--text-primary); margin: 0; }
    .svc-sub { color: var(--text-muted); font-size: 13px; margin: 3px 0 0; }
    .svc-head-ico {
        width: 46px; height: 46px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: #eef5f9; color: var(--primary-color);
        border-radius: 14px; font-size: 18px;
    }

    .svc-search { position: relative; margin-bottom: 22px; }
    .svc-search-ico {
        position: absolute; top: 50%; right: 16px; transform: translateY(-50%);
        color: var(--text-muted); font-size: 15px; pointer-events: none;
    }
    .svc-search-input {
        width: 100%;
        border: 1.5px solid var(--border-color);
        background: #fff;
        border-radius: 14px;
        padding: 12px 44px 12px 16px;
        font-size: 15px;
        transition: border-color .2s, box-shadow .2s;
    }
    .svc-search-input:focus {
        outline: none; border-color: var(--primary-light);
        box-shadow: 0 0 0 4px rgba(24,142,201,.12);
    }

    .svc-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }
    @media (min-width: 640px) { .svc-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (min-width: 900px) { .svc-grid { grid-template-columns: repeat(4, 1fr); } }

    .svc-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 20px 14px;
        text-decoration: none;
        height: 100%;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s;
    }
    .svc-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 26px -12px rgba(11,95,138,.28);
        border-color: #cfe4ef;
    }

    .svc-icon {
        width: 56px; height: 56px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; margin-bottom: 12px;
    }
    .svc-icon.blue   { background: #e6f0f6; color: #0b5f8a; }
    .svc-icon.yellow { background: #fefce8; color: #eab308; }
    .svc-icon.cyan   { background: #ecfeff; color: #06b6d4; }
    .svc-icon.orange { background: #fff7ed; color: #f97316; }
    .svc-icon.purple { background: #faf5ff; color: #a855f7; }
    .svc-icon.red    { background: #fef2f2; color: #ef4444; }

    .svc-name { font-weight: 800; color: var(--text-primary); margin: 0 0 4px; font-size: 15px; }
    .svc-desc { color: var(--text-muted); font-size: 12.5px; margin: 0 0 10px; line-height: 1.5; flex: 1; }
    .svc-go {
        font-size: 12.5px; font-weight: 700; color: var(--primary-color);
        display: inline-flex; align-items: center; gap: 6px;
    }
    .svc-go i { transition: transform .2s; }
    .svc-card:hover .svc-go i { transform: translateX(-3px); }

    .svc-noresults { text-align: center; color: var(--text-muted); padding: 40px 0; }
    .svc-noresults i { font-size: 30px; opacity: .5; display: block; margin-bottom: 10px; }
</style>

<script>
    // Client-side search filtering without page reload
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const serviceItems = document.querySelectorAll('.service-item');
        const noResults = document.getElementById('svc-noresults');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let visible = 0;

                serviceItems.forEach(function(item) {
                    const serviceName = item.getAttribute('data-service-name').toLowerCase();
                    const show = searchTerm === '' || serviceName.includes(searchTerm);
                    item.style.display = show ? '' : 'none';
                    if (show) visible++;
                });

                if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';
            });
        }
    });
</script>
@endsection
