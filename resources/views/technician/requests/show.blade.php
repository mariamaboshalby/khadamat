@extends('layouts.mobile')

@section('title', 'تفاصيل الطلب #' . $request->id)

@section('mobile_header')
    <div class="header-icon-btn" onclick="history.back()">
        <i class="fas fa-arrow-right"></i>
    </div>
    <div class="app-logo fw-bold">
        طلب #{{ $request->id }}
    </div>
    <div class="header-icon-btn">
        <i class="fas fa-share"></i>
    </div>
@endsection

@php
    $statusMap = [
        'pending'     => ['قيد المراجعة', 'st-pending'],
        'approved'    => ['معتمد', 'st-approved'],
        'in_progress' => ['قيد التنفيذ', 'st-progress'],
        'completed'   => ['مكتمل', 'st-done'],
        'cancelled'   => ['ملغي', 'st-cancel'],
        'rejected'    => ['مرفوض', 'st-cancel'],
    ];
    $stLabel = $statusMap[$request->status][0] ?? $request->status;
    $stClass = $statusMap[$request->status][1] ?? 'st-pending';
    $isMyNegotiation = $request->price_status === 'negotiating'
        && $request->assigned_technician_id
        && auth()->user()->technician
        && $request->assigned_technician_id == auth()->user()->technician->id;
    $canOfferPrice = $request->status === 'approved' && !$request->assigned_technician_id;
@endphp

@section('content')

<div class="tr-wrap">

    <a href="{{ route('technician.repair-requests.index') }}" class="tr-back">
        <i class="fas fa-arrow-right"></i> العودة للطلبات
    </a>

    {{-- Header --}}
    <div class="tr-hero">
        <div class="tr-hero-top">
            <div>
                <div class="tr-hero-id">طلب #{{ $request->id }}</div>
                <span class="tr-service"><i class="fas fa-wrench"></i> {{ $request->service->name ?? 'خدمة' }}</span>
            </div>
            <span class="tr-status {{ $stClass }}">{{ $stLabel }}</span>
        </div>
        <div class="tr-hero-meta">
            <span><i class="far fa-clock"></i> {{ $request->created_at->format('d/m/Y - H:i') }}</span>
            @if($request->scheduled_at)
                <span><i class="far fa-calendar-check"></i> الموعد: {{ $request->scheduled_at->format('d/m/Y - H:i') }}</span>
            @endif
        </div>
    </div>

    {{-- Customer --}}
    <div class="tr-card">
        <div class="tr-card-title"><span class="num">01.</span> معلومات العميل</div>
        <div class="tr-cust">
            <div class="tr-avatar">{{ mb_substr($request->user->name, 0, 1) }}</div>
            <div class="tr-cust-info">
                <div class="tr-cust-name">{{ $request->user->name }}</div>
                <div class="tr-cust-phone" dir="ltr">{{ $request->user->phone }}</div>
            </div>
            @if($request->user->phone)
                <a href="tel:{{ $request->user->phone }}" class="tr-call"><i class="fas fa-phone"></i> اتصال</a>
            @endif
        </div>
    </div>

    {{-- Details --}}
    <div class="tr-card">
        <div class="tr-card-title"><span class="num">02.</span> تفاصيل الطلب</div>

        <div class="tr-row">
            <div class="tr-ic ic-loc"><i class="fas fa-map-marker-alt"></i></div>
            <div class="tr-col">
                <div class="tr-label">العنوان</div>
                <div class="tr-value">{{ $request->address ?: '—' }}</div>
            </div>
        </div>

        @if($request->description)
        <div class="tr-row">
            <div class="tr-ic ic-desc"><i class="fas fa-file-alt"></i></div>
            <div class="tr-col">
                <div class="tr-label">وصف المشكلة</div>
                <div class="tr-desc">{{ $request->description }}</div>
            </div>
        </div>
        @endif
    </div>

    {{-- Images --}}
    @if($request->getMedia('requests')->count() > 0)
    <div class="tr-card">
        <div class="tr-card-title"><span class="num">03.</span> الصور المرفقة</div>
        <div class="tr-imgs">
            @foreach($request->getMedia('requests') as $media)
                <div class="tr-img" onclick="openImageModal('{{ media_url($media) }}')">
                    <img src="{{ media_url($media) }}" alt="صورة الطلب">
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Negotiation alert --}}
    @if($isMyNegotiation)
    <div class="tr-nego">
        <div class="tr-nego-head"><i class="fas fa-hand-holding-usd"></i> العميل قدّم سعرًا مُعدّلًا</div>
        <div class="tr-nego-price">{{ number_format($request->proposed_price, 2) }} <span>ج.م</span></div>
        @if($request->customer_notes)
            <div class="tr-nego-note"><strong>ملاحظات العميل:</strong> {{ $request->customer_notes }}</div>
        @endif
        <div class="tr-nego-actions">
            <form action="{{ route('technician.repair-requests.accept-negotiation', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" method="POST">
                @csrf
                <button type="submit" class="tr-btn tr-btn-ok"><i class="fas fa-check"></i> قبول</button>
            </form>
            <form action="{{ route('technician.repair-requests.reject-negotiation', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" method="POST" onsubmit="return confirm('هل تريد رفض السعر وإلغاء الطلب؟')">
                @csrf
                <button type="submit" class="tr-btn tr-btn-no"><i class="fas fa-times"></i> رفض</button>
            </form>
        </div>
    </div>
    @endif

    {{-- Actions --}}
    <div class="tr-actions">
        @if($canOfferPrice)
            <a href="{{ route('technician.repair-requests.pricing', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" class="tr-cta">
                <i class="fas fa-money-bill-wave"></i> عرض سعر لهذه الخدمة
            </a>
        @endif
    </div>

</div>

{{-- Image Modal --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">صورة الطلب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid rounded" alt="صورة الطلب">
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.tr-wrap { max-width: 760px; margin: 0 auto; padding: 2px 12px 10px; }
.tr-back { display: inline-flex; align-items: center; gap: 6px; color: #0b5f8a; text-decoration: none; font-weight: 600; font-size: 13px; margin-bottom: 6px; }

.tr-hero { background: linear-gradient(135deg, #0b5f8a 0%, #188ec9 100%); color: #fff; border-radius: 12px; padding: 10px 14px; }
.tr-hero-top { display: flex; justify-content: space-between; align-items: center; gap: 10px; }
.tr-hero-id { font-size: 16px; font-weight: 800; margin-bottom: 4px; }
.tr-service { display: inline-flex; align-items: center; gap: 5px; background: rgba(255,255,255,.18); padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; }
.tr-status { padding: 4px 12px; border-radius: 20px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
.st-pending { background: #fff7e6; color: #b7791f; }
.st-approved { background: #e6fffa; color: #0b7285; }
.st-progress { background: #e0f0ff; color: #1971c2; }
.st-done { background: #ebfbee; color: #2b8a3e; }
.st-cancel { background: #fff0f0; color: #c92a2a; }
.tr-hero-meta { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 6px; padding-top: 6px; border-top: 1px solid rgba(255,255,255,.2); font-size: 11.5px; opacity: .95; }
.tr-hero-meta i { margin-inline-end: 3px; }

.tr-card { background: #fff; border: 1px solid #e6ecf2; border-radius: 12px; padding: 10px 14px; margin-top: 8px; }
.tr-card-title { font-size: 12px; font-weight: 800; color: #0b5f8a; margin-bottom: 8px; padding-bottom: 5px; border-bottom: 1px solid #eef2f6; }
.tr-card-title .num { color: #ff8a00; margin-inline-end: 5px; }

.tr-cust { display: flex; align-items: center; gap: 10px; }
.tr-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #0b5f8a, #188ec9); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px; flex-shrink: 0; }
.tr-cust-info { flex: 1; min-width: 0; }
.tr-cust-name { font-weight: 700; color: #072540; font-size: 14px; }
.tr-cust-phone { color: #64748b; font-size: 12.5px; margin-top: 1px; }
.tr-call { background: #0b5f8a; color: #fff; text-decoration: none; font-size: 12px; font-weight: 600; padding: 6px 12px; border-radius: 8px; display: inline-flex; align-items: center; gap: 5px; flex-shrink: 0; }
.tr-call:hover { background: #094d70; }

.tr-row { display: flex; gap: 10px; padding: 6px 0; border-bottom: 1px solid #f4f7fa; }
.tr-row:last-child { border-bottom: none; padding-bottom: 0; }
.tr-row:first-of-type { padding-top: 0; }
.tr-ic { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0; }
.ic-loc { background: #fff0f0; color: #e03131; }
.ic-desc { background: #e7f5ff; color: #1971c2; }
.tr-col { flex: 1; min-width: 0; }
.tr-label { font-size: 11px; color: #94a3b8; font-weight: 600; margin-bottom: 2px; }
.tr-value { font-size: 13.5px; color: #1f2937; font-weight: 600; }
.tr-desc { font-size: 13px; color: #334155; line-height: 1.5; }

.tr-imgs { display: grid; grid-template-columns: repeat(auto-fill, minmax(72px, 1fr)); gap: 6px; }
.tr-img { aspect-ratio: 1; border-radius: 8px; overflow: hidden; cursor: pointer; border: 1px solid #eef2f6; }
.tr-img img { width: 100%; height: 100%; object-fit: cover; }

.tr-nego { background: #fff8e6; border: 1px solid #ffe8a3; border-radius: 12px; padding: 10px 14px; margin-top: 8px; }
.tr-nego-head { font-weight: 700; color: #8a6d1a; font-size: 13px; }
.tr-nego-price { font-size: 20px; font-weight: 800; color: #072540; margin: 4px 0; }
.tr-nego-price span { font-size: 13px; color: #64748b; font-weight: 600; }
.tr-nego-note { font-size: 12.5px; color: #5c4d1a; margin-bottom: 8px; }
.tr-nego-actions { display: flex; gap: 8px; }
.tr-nego-actions form { flex: 1; }
.tr-btn { width: 100%; border: none; border-radius: 8px; padding: 8px; font-weight: 700; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 5px; }
.tr-btn-ok { background: #2b8a3e; color: #fff; }
.tr-btn-no { background: #e03131; color: #fff; }

.tr-actions { margin-top: 10px; }
.tr-cta { display: flex; align-items: center; justify-content: center; gap: 8px; background: #ff8a00; color: #fff; text-decoration: none; font-weight: 800; font-size: 14px; padding: 11px; border-radius: 10px; }
.tr-cta:hover { background: #f97e0a; }
</style>
@endpush

@push('scripts')
<script>
function openImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>
@endpush
