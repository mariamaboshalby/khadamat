@extends('layouts.mobile')

@section('title', $service->name)

@section('content')
<div class="svc-detail fade-in" dir="rtl">

    <a href="{{ route('services.index') }}" class="sd-back">
        <i class="fas fa-arrow-right"></i> رجوع للخدمات
    </a>

    <!-- Hero -->
    <div class="sd-hero">
        <div class="sd-icon {{ $service->color_class }}">
            <i class="fas {{ $service->icon }}"></i>
        </div>
        <div class="sd-hero-text">
            <h1 class="sd-title">{{ $service->name }}</h1>
            <p class="sd-sub">
                @if($service->specialization)
                    خدمة ضمن تخصص {{ $service->specialization->name }}
                @else
                    خدمة احترافية موثوقة
                @endif
            </p>
        </div>
    </div>

    <!-- Details -->
    <div class="sd-card">
        <h2 class="sd-h2"><i class="fas fa-circle-info"></i> تفاصيل الخدمة</h2>
        <p class="sd-p">
            نقدّم لك خدمة <strong>{{ $service->name }}</strong> على أعلى مستوى بواسطة نخبة من الفنيين المعتمدين،
            مع التزام بالجودة والمواعيد وأسعار واضحة قبل بدء العمل.
        </p>

        @if($service->specialization)
            <div class="sd-spec">
                <span class="sd-spec-ico"><i class="fas fa-sitemap"></i></span>
                <div class="sd-spec-text">
                    <span class="sd-spec-label">التخصص</span>
                    <span class="sd-spec-name">{{ $service->specialization->name }}</span>
                    @if($service->specialization->description)
                        <span class="sd-spec-desc">{{ $service->specialization->description }}</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Trust features -->
    <div class="sd-features">
        <div class="sd-feature">
            <span class="sd-feature-ico"><i class="fas fa-user-check"></i></span>
            <span class="sd-feature-title">فنيون معتمدون</span>
            <span class="sd-feature-desc">تم فحصهم وتقييمهم</span>
        </div>
        <div class="sd-feature">
            <span class="sd-feature-ico"><i class="fas fa-shield-halved"></i></span>
            <span class="sd-feature-title">ضمان الجودة</span>
            <span class="sd-feature-desc">عمل موثوق وآمن</span>
        </div>
        <div class="sd-feature">
            <span class="sd-feature-ico"><i class="fas fa-tags"></i></span>
            <span class="sd-feature-title">أسعار واضحة</span>
            <span class="sd-feature-desc">بدون مفاجآت</span>
        </div>
    </div>

    <!-- CTA -->
    <div class="sd-cta">
        <a href="{{ route('requests.create', ['service_id' => \App\Helpers\EncryptionHelper::encryptId($service->id)]) }}" class="btn-request">
            <i class="fas fa-plus-circle"></i> طلب هذه الخدمة
        </a>
    </div>
</div>

<style>
    .svc-detail {
        max-width: 760px;
        margin: 0 auto;
        padding: 20px 16px 48px;
    }

    .sd-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        margin-bottom: 16px;
        transition: color .2s;
    }
    .sd-back:hover { color: var(--primary-color); }

    /* Hero */
    .sd-hero {
        display: flex;
        align-items: center;
        gap: 16px;
        background: linear-gradient(135deg, #0b5f8a 0%, #188ec9 100%);
        color: #fff;
        border-radius: 20px;
        padding: 22px 24px;
        margin-bottom: 18px;
        box-shadow: 0 12px 28px -12px rgba(11, 95, 138, .4);
    }
    .sd-icon {
        width: 64px; height: 64px; flex-shrink: 0;
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 28px;
        background: rgba(255,255,255,.18);
        color: #fff;
    }
    .sd-title { font-size: 24px; font-weight: 900; margin: 0 0 4px; }
    .sd-sub { font-size: 14px; opacity: .9; margin: 0; }

    /* Card */
    .sd-card {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 22px;
        margin-bottom: 18px;
    }
    .sd-h2 {
        font-size: 16px; font-weight: 800; color: var(--text-primary);
        margin: 0 0 12px; display: flex; align-items: center; gap: 8px;
    }
    .sd-h2 i { color: var(--primary-color); }
    .sd-p { color: var(--text-secondary); font-size: 15px; line-height: 1.8; margin: 0; }

    .sd-spec {
        display: flex; align-items: flex-start; gap: 14px;
        background: #f8fafc; border: 1px solid var(--border-color);
        border-radius: 14px; padding: 14px 16px; margin-top: 16px;
    }
    .sd-spec-ico {
        width: 42px; height: 42px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: #eef5f9; color: var(--primary-color); border-radius: 11px; font-size: 17px;
    }
    .sd-spec-text { display: flex; flex-direction: column; gap: 2px; }
    .sd-spec-label { font-size: 12px; color: var(--text-muted); font-weight: 600; }
    .sd-spec-name { font-size: 15px; color: var(--text-primary); font-weight: 800; }
    .sd-spec-desc { font-size: 13px; color: var(--text-secondary); line-height: 1.6; }

    /* Features */
    .sd-features {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 22px;
    }
    .sd-feature {
        background: #fff; border: 1px solid var(--border-color);
        border-radius: 14px; padding: 16px 12px; text-align: center;
        display: flex; flex-direction: column; align-items: center; gap: 6px;
    }
    .sd-feature-ico {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        background: #eef5f9; color: var(--primary-color); font-size: 18px; margin-bottom: 2px;
    }
    .sd-feature-title { font-weight: 800; color: var(--text-primary); font-size: 13.5px; }
    .sd-feature-desc { color: var(--text-muted); font-size: 11.5px; }

    /* CTA */
    .sd-cta { position: sticky; bottom: 12px; }
    .btn-request {
        display: flex; align-items: center; justify-content: center; gap: 10px;
        width: 100%;
        background: var(--accent-orange);
        color: #fff; font-weight: 800; font-size: 16px;
        padding: 15px; border-radius: 14px; text-decoration: none;
        box-shadow: 0 10px 24px -8px rgba(255, 138, 0, .5);
        transition: all .2s;
    }
    .btn-request:hover { background: var(--accent-orange-hover); color: #fff; transform: translateY(-2px); }

    /* Icon color variants (used on hero icon tint when desired) */
    .sd-icon.blue   { background: rgba(255,255,255,.18); }
    .sd-icon.yellow { background: rgba(255,255,255,.18); }
    .sd-icon.cyan   { background: rgba(255,255,255,.18); }
    .sd-icon.orange { background: rgba(255,255,255,.18); }
    .sd-icon.purple { background: rgba(255,255,255,.18); }
    .sd-icon.red    { background: rgba(255,255,255,.18); }

    @media (max-width: 480px) {
        .sd-title { font-size: 21px; }
        .sd-icon { width: 54px; height: 54px; font-size: 24px; }
        .sd-features { grid-template-columns: 1fr; }
        .sd-feature { flex-direction: row; text-align: right; justify-content: flex-start; gap: 12px; }
        .sd-feature-ico { margin: 0; }
    }
</style>
@endsection
