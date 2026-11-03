@extends('layouts.mobile')

@section('title', 'العروض')

@section('content')
<div class="offers-page">

    <div class="op-head">
        <div>
            <h1>العروض الحصرية</h1>
            <p>خصومات مختارة لفترة محدودة</p>
        </div>
        <div class="op-chip"><i class="fas fa-percent"></i></div>
    </div>

    @if($offers->count())
    <div class="op-grid">
        @foreach($offers as $offer)
            <div class="op-card">
                <div class="op-badge">{{ $offer->badge_text ?? 'عرض مميز' }}</div>
                <h3 class="op-title">{{ $offer->title }}</h3>
                <p class="op-sub">{{ $offer->subtitle_1 }}</p>

                @if($offer->discount_value)
                    <div class="op-discount"><span>خصم</span> <strong>{!! $offer->discount_value !!}</strong></div>
                @endif

                @if($offer->icon)
                    <i class="fas {{ $offer->icon }} op-icon"></i>
                @endif
            </div>
        @endforeach
    </div>
    @else
        <div class="op-empty">
            <i class="far fa-tag"></i>
            <p>لا توجد عروض حالياً</p>
        </div>
    @endif

</div>

<style>
    .offers-page { max-width: 980px; margin: 0 auto; padding: 10px 16px 40px; }
    .op-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; }
    .op-head h1 { font-size: 22px; font-weight: 800; color: #072540; margin: 0; }
    .op-head p { font-size: 13px; color: #64748b; margin: 3px 0 0; }
    .op-chip { width: 46px; height: 46px; border-radius: 14px; background: #e7f1f6; color: #0b5f8a; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }

    .op-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }

    .op-card { position: relative; overflow: hidden; background: linear-gradient(135deg, #0b5f8a 0%, #188ec9 100%); color: #fff; border-radius: 16px; padding: 22px; min-height: 175px; display: flex; flex-direction: column; justify-content: center; gap: 6px; box-shadow: 0 6px 16px rgba(11, 95, 138, .16); }
    .op-badge { align-self: flex-start; background: rgba(255, 255, 255, .92); color: #0b5f8a; font-size: 12px; font-weight: 800; padding: 5px 12px; border-radius: 20px; margin-bottom: 4px; }
    .op-title { font-size: 20px; font-weight: 800; margin: 0; }
    .op-sub { font-size: 13.5px; opacity: .9; margin: 0 0 8px; }
    .op-discount { align-self: flex-start; background: #fff; color: #072540; border-radius: 10px; padding: 8px 14px; display: inline-flex; align-items: center; gap: 8px; font-weight: 700; box-shadow: 0 2px 6px rgba(0, 0, 0, .08); }
    .op-discount span { font-size: 12px; color: #64748b; }
    .op-discount strong { color: #ff8a00; font-size: 18px; }
    .op-icon { position: absolute; bottom: -18px; left: -10px; font-size: 120px; opacity: .14; transform: rotate(12deg); pointer-events: none; }

    .op-empty { text-align: center; color: #94a3b8; padding: 60px 0; }
    .op-empty i { font-size: 40px; margin-bottom: 12px; display: block; }

    @media (max-width: 640px) {
        .op-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection
