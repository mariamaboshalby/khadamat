@extends('layouts.mobile')

@section('title', 'مراجعة عرض السعر')

@section('content')

<div class="app-header glass d-md-none">
    <div class="header-icon-btn" onclick="history.back()">
        <i class="fas fa-arrow-right"></i>
    </div>
    <div class="app-logo">عرض السعر</div>
    <div class="header-icon-btn"></div>
</div>

<div class="app-content fade-in">
    
    <div class="pricing-header-card mb-4">
        <h4 class="mb-3">عرض سعر من الفني</h4>
        <div class="technician-info">
            <div class="d-flex align-items-center gap-3">
                <div class="tech-avatar">{{ substr($request->assignedTechnician->user->name, 0, 1) }}</div>
                <div>
                    <div class="tech-name">{{ $request->assignedTechnician->user->name }}</div>
                    <div class="tech-rating">
                        <i class="fas fa-star text-warning"></i>
                        {{ $request->assignedTechnician->rating }}
                        ({{ $request->assignedTechnician->completed_tasks }} مهمة)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="price-card mb-4">
        <h6 class="card-title">تفاصيل السعر</h6>
        
        <div class="price-row">
            <span>سعر الخدمة</span>
            <span class="price-value">{{ number_format($request->proposed_price, 2) }} جنيه</span>
        </div>
        
        @if($request->price_notes)
        <div class="notes-section">
            <strong>ملاحظات الفني:</strong>
            <p>{{ $request->price_notes }}</p>
        </div>
        @endif
    </div>

    @if($request->requestItems->count() > 0)
    <div class="price-card mb-4">
        <h6 class="card-title">قطع الغيار والأدوات</h6>
        
        @foreach($request->requestItems as $item)
        <div class="item-row">
            <div class="item-info">
                <div class="item-name">{{ $item->warehouseItem->name }}</div>
                <div class="item-details">{{ $item->quantity }} × {{ number_format($item->unit_price, 2) }} جنيه</div>
            </div>
            <div class="item-price">{{ number_format($item->total_price, 2) }} جنيه</div>
        </div>
        @endforeach
        
        <div class="items-total">
            <span>إجمالي القطع:</span>
            <span>{{ number_format($request->total_items_price, 2) }} جنيه</span>
        </div>
    </div>
    @endif

    <div class="total-price-card mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <span class="total-label">الإجمالي الكلي:</span>
            <span class="total-value">{{ number_format($request->total_price, 2) }} جنيه</span>
        </div>
    </div>

    <div class="action-section">
        <form action="{{ route('requests.accept-price', $request->id) }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-success w-100 btn-lg">
                <i class="fas fa-check-circle me-2"></i>قبول العرض
            </button>
        </form>
        
        <button type="button" class="btn btn-danger w-100 btn-lg" data-bs-toggle="modal" data-bs-target="#rejectModal">
            <i class="fas fa-times-circle me-2"></i>رفض العرض
        </button>
    </div>

</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">رفض العرض</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('requests.reject-price', $request->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        سيتم إعادة الطلب للفنيين الآخرين
                    </div>
                    <div class="form-group">
                        <label class="form-label">سبب الرفض</label>
                        <textarea name="customer_notes" class="form-control" rows="4" required placeholder="اكتب سبب رفض العرض..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">تأكيد الرفض</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.pricing-header-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.tech-avatar {
    width: 56px;
    height: 56px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 24px;
}

.tech-name {
    font-weight: 700;
    font-size: 18px;
}

.tech-rating {
    font-size: 14px;
    opacity: 0.9;
}

.price-card {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
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

.price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    font-size: 16px;
}

.price-value {
    font-weight: 700;
    color: #059669;
}

.notes-section {
    background: #f9fafb;
    padding: 12px;
    border-radius: 8px;
    margin-top: 12px;
}

.notes-section p {
    margin: 8px 0 0;
    color: #6b7280;
}

.item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: #f9fafb;
    border-radius: 8px;
    margin-bottom: 8px;
}

.item-name {
    font-weight: 600;
    color: #1f2937;
}

.item-details {
    font-size: 14px;
    color: #6b7280;
}

.item-price {
    font-weight: 600;
    color: #059669;
}

.items-total {
    display: flex;
    justify-content: space-between;
    padding-top: 12px;
    margin-top: 12px;
    border-top: 2px solid #e5e7eb;
    font-weight: 700;
}

.total-price-card {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
}

.total-label {
    font-size: 18px;
    font-weight: 600;
}

.total-value {
    font-size: 28px;
    font-weight: 800;
}

.action-section {
    padding-bottom: 32px;
}

.btn-lg {
    padding: 14px 24px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
}
</style>
@endpush
