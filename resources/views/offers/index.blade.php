@extends('layouts.mobile')

@section('title', 'العروض')

@section('content')
<div class="app-content fade-in  p-3">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="fw-bold m-0">العروض الحصرية</h4>
        <div class="p-3 bg-white rounded-circle shadow-sm">
            <i class="fas fa-percent text-danger"></i>
        </div>
    </div>

    <!-- Offers List -->
    <div class="row g-4">
        @php
            $gradients = [
                'linear-gradient(135deg, #0b5f8a 0%, #ff6666 100%)',
                'linear-gradient(135deg, #0b5f8a 0%, #ff6666 100%)',
                'linear-gradient(135deg, #f97316 0%, #ec4899 100%)',
                'linear-gradient(135deg, #10b981 0%, #ff6666 100%)',
            ];
        @endphp

        @foreach($offers as $index => $offer)
            <div class="col-12 col-md-6">
                <div class="promo-banner w-100 mb-0" style="background: {{ $gradients[$index % count($gradients)] }}; height: 200px; border-radius: 20px; position: relative; overflow: hidden; color: white; padding: 25px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                    <div class="promo-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(90deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0) 100%); z-index: 1;"></div>
                    
                    <div class="promo-content position-relative" style="z-index: 2; width: 70%;">
                        <div class="badge bg-white text-dark mb-2 px-3 py-2 rounded-pill fw-bold" style="backdrop-filter: blur(5px); background: rgba(255,255,255,0.9);">{{ $offer->badge_text ?? 'عرض مميز' }}</div>
                        <h3 class="fw-bold mb-1">{{ $offer->title }}</h3>
                        <p class="mb-3 opacity-75">{{ $offer->subtitle_1 }}</p>
                        
                        @if($offer->discount_value)
                        <div class="d-inline-flex align-items-center bg-white text-dark px-3 py-2 rounded-3 shadow-sm">
                            <span class="small">خصم</span>
                            <span class="fw-bold ms-2 fs-5">{!! $offer->discount_value !!}</span>
                        </div>
                        @endif
                    </div>

                    @if($offer->icon)
                    <i class="fas {{ $offer->icon }}" style="position: absolute; bottom: -20px; left: -20px; font-size: 120px; opacity: 0.15; transform: rotate(15deg); z-index: 0;"></i>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
