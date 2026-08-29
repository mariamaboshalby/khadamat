@php
    $specName = $tech->specialization->name ?? 'عام';
    $meta = $specMeta[$specName] ?? $defaultMeta;
    $reviewCount = $tech->reviews->count();
    $statusLabels = [
        'available' => ['label' => 'متاح', 'class' => 'tech-status-available'],
        'busy' => ['label' => 'مشغول', 'class' => 'tech-status-busy'],
    ];
    $status = $statusLabels[$tech->availability_status] ?? ['label' => 'غير متاح', 'class' => 'tech-status-offline'];
@endphp
<div class="col-12 col-sm-6 col-lg-4 col-xl-3 tech-card-col"
     data-name="{{ $tech->full_name }}"
     data-spec="{{ $specName }}"
     data-category="spec-{{ $tech->specialization_id ?? 'other' }}">
    <div class="technician-card-box">
        <div class="tech-image-wrap">
            <img src="{{ asset('images/tech-avatar.jpg') }}" alt="{{ $tech->full_name }}" class="tech-avatar-img">
            <span class="tech-status-badge {{ $status['class'] }}">{{ $status['label'] }}</span>
            <div class="tech-rating-pill">
                <i class="fas fa-star text-warning"></i>
                <span>{{ number_format($tech->rating ?? 0, 1) }}</span>
            </div>
        </div>
        <div class="tech-card-body text-center p-4">
            <h4 class="tech-name mb-2">{{ $tech->full_name }}</h4>
            <div class="mb-3">
                <span class="tech-spec-badge" style="background: {{ $meta['bg'] }}; color: {{ $meta['color'] }};">
                    <i class="fas {{ $meta['icon'] }}"></i>
                    {{ $specName }}
                </span>
            </div>
            <div class="tech-stats-row mb-3">
                <span><i class="fas fa-check-circle text-success"></i> {{ $tech->completed_tasks ?? 0 }} مهمة</span>
                <span><i class="fas fa-comment-dots text-primary"></i> {{ $reviewCount }} تقييم</span>
            </div>
            @if ($tech->address)
                <div class="text-muted mb-3 fs-6">
                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                    <span>{{ Str::limit($tech->address, 40) }}</span>
                </div>
            @endif
            <a href="{{ route('technician.profile', \App\Helpers\EncryptionHelper::encryptId($tech->id)) }}" class="btn-outline-tech">
                عرض الملف الشخصي
            </a>
        </div>
    </div>
</div>
