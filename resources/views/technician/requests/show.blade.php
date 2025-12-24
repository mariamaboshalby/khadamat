@extends('layouts.mobile')

@section('title', 'تفاصيل الطلب #' . $request->id)

@section('content')

<!-- Mobile Header -->
<div class="app-header glass d-md-none">
    <div class="header-icon-btn" onclick="history.back()">
        <i class="fas fa-arrow-right"></i>
    </div>
    <div class="app-logo">
        طلب #{{ $request->id }}
    </div>
    <div class="header-icon-btn">
        <i class="fas fa-share"></i>
    </div>
</div>

<!-- Content -->
<div class="app-content fade-in">
    
    <!-- Request Header Card -->
    <div class="request-header-card mb-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h4 class="request-title">طلب #{{ $request->id }}</h4>
                <span class="service-badge">{{ $request->service->name }}</span>
            </div>
            <div class="status-badge status-{{ $request->status }}">
                {{ $request->status == 'approved' ? 'معتمد' : $request->status }}
            </div>
        </div>
    </div>

    <!-- Customer Info Card -->
    <div class="info-card mb-4">
        <h6 class="card-title">معلومات العميل</h6>
        <div class="customer-info">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="customer-avatar-lg">
                    {{ substr($request->user->name, 0, 1) }}
                </div>
                <div>
                    <div class="customer-name-lg">{{ $request->user->name }}</div>
                    <div class="customer-phone-lg">{{ $request->user->phone }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Details Card -->
    <div class="info-card mb-4">
        <h6 class="card-title">تفاصيل الطلب</h6>
        
        <div class="detail-row">
            <div class="detail-label">
                <i class="fas fa-map-marker-alt text-danger"></i>
                العنوان
            </div>
            <div class="detail-value">{{ $request->address }}</div>
        </div>
        
        @if($request->scheduled_at)
        <div class="detail-row">
            <div class="detail-label">
                <i class="fas fa-calendar text-primary"></i>
                الموعد المحدد
            </div>
            <div class="detail-value">{{ $request->scheduled_at->format('Y-m-d H:i') }}</div>
        </div>
        @endif
        
        <div class="detail-row">
            <div class="detail-label">
                <i class="fas fa-clock text-muted"></i>
                تاريخ الطلب
            </div>
            <div class="detail-value">{{ $request->created_at->format('Y-m-d H:i') }}</div>
        </div>
        
        @if($request->description)
        <div class="detail-row">
            <div class="detail-label">
                <i class="fas fa-file-text text-info"></i>
                الوصف
            </div>
            <div class="detail-value description-text">{{ $request->description }}</div>
        </div>
        @endif
    </div>

    <!-- Images Card -->
    @if($request->getMedia('requests')->count() > 0)
    <div class="info-card mb-4">
        <h6 class="card-title">الصور المرفقة</h6>
        <div class="images-grid">
            @foreach($request->getMedia('requests') as $media)
                <div class="image-item">
                    <img src="{{ $media->getUrl() }}" alt="صورة الطلب" onclick="openImageModal('{{ $media->getUrl() }}')">
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('technician.repair-requests.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للطلبات
        </a>
        
        @if($request->status === 'approved' && !$request->assigned_technician_id)
        <a href="{{ route('technician.repair-requests.pricing', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" class="btn btn-primary">
            <i class="fas fa-money-bill-wave me-2"></i>عرض سعر
        </a>
        @endif
        
        @if($request->price_status === 'negotiating' && $request->assigned_technician_id == auth()->user()->technician->id)
        <div class="alert alert-info mt-3">
            <h6 class="fw-bold">العميل قدم سعر معدل</h6>
            <p class="mb-2"><strong>السعر المقترح:</strong> {{ number_format($request->proposed_price, 2) }} ج.م</p>
            @if($request->customer_notes)
            <p class="mb-2"><strong>ملاحظات العميل:</strong> {{ $request->customer_notes }}</p>
            @endif
            <div class="d-flex gap-2">
                <form action="{{ route('technician.repair-requests.accept-negotiation', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-check me-1"></i> قبول
                    </button>
                </form>
                <form action="{{ route('technician.repair-requests.reject-negotiation', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" method="POST" onsubmit="return confirm('هل تريد رفض السعر وإلغاء الطلب؟')">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-times me-1"></i> رفض
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

</div>

<!-- Image Modal -->
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
.request-header-card {
    background: linear-gradient(135deg, #ff7373 0%, #cc3333 100%);
    color: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 8px 10px hsla(0, 100.00%, 72.50%, 0.32);
}

.request-title {
    font-weight: 800;
    margin-bottom: 8px;
}

.service-badge {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-approved {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.info-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.card-title {
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid #f3f4f6;
}

.customer-avatar-lg {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #cc3333, #ff6666);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 24px;
}

.customer-name-lg {
    font-weight: 700;
    color: #1f2937;
    font-size: 18px;
}

.customer-phone-lg {
    color: #6b7280;
    font-size: 16px;
    font-family: monospace;
}

.detail-row {
    display: flex;
    margin-bottom: 16px;
    align-items: flex-start;
}

.detail-label {
    min-width: 120px;
    font-weight: 600;
    color: #6b7280;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.detail-value {
    flex: 1;
    color: #1f2937;
    font-weight: 500;
}

.description-text {
    background: #f9fafb;
    padding: 12px;
    border-radius: 8px;
    line-height: 1.6;
}

.images-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 12px;
}

.image-item {
    aspect-ratio: 1;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.2s;
}

.image-item:hover {
    transform: scale(1.05);
}

.image-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.action-buttons {
    display: flex;
    gap: 12px;
    justify-content: center;
    margin-top: 32px;
    padding-bottom: 32px;
}

.action-buttons .btn {
    flex: 1;
    max-width: 200px;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
}
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