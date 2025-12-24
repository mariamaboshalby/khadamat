@extends('layouts.mobile')

@section('title', 'تقديم عرض سعر - طلب #' . $request->id)

@section('content')

<!-- Mobile Header -->
<div class="app-header glass d-md-none">
    <div class="header-icon-btn" onclick="history.back()">
        <i class="fas fa-arrow-right"></i>
    </div>
    <div class="app-logo">
        عرض سعر
    </div>
    <div class="header-icon-btn">
        <i class="fas fa-calculator"></i>
    </div>
</div>

<!-- Content -->
<div class="app-content fade-in">
    
    <!-- Request Info Card -->
    <div class="request-summary-card mb-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h5 class="request-title">طلب #{{ $request->id }}</h5>
                <span class="service-badge">{{ $request->service->name }}</span>
            </div>
        </div>
        
        <div class="customer-info-mini">
            <div class="d-flex align-items-center gap-2">
                <div class="customer-avatar-sm">{{ substr($request->user->name, 0, 1) }}</div>
                <div>
                    <div class="customer-name-sm">{{ $request->user->name }}</div>
                    <div class="customer-phone-sm">{{ $request->user->phone }}</div>
                </div>
            </div>
        </div>
        
        <div class="address-info mt-3">
            <i class="fas fa-map-marker-alt text-danger me-2"></i>
            {{ $request->address }}
        </div>
    </div>

    <!-- Pricing Form -->
    <form action="{{ route('technician.repair-requests.submit-pricing', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" method="POST">
        @csrf
        
        <!-- Service Price Card -->
        <div class="pricing-card mb-4">
            <h6 class="card-title">
                <i class="fas fa-tools me-2"></i>سعر الخدمة
            </h6>
            
            <div class="form-group mb-3">
                <label class="form-label">السعر المقترح (جنيه)</label>
                <input type="number" 
                       name="proposed_price" 
                       class="form-control @error('proposed_price') is-invalid @enderror" 
                       value="{{ old('proposed_price') }}" 
                       step="0.01" 
                       min="0" 
                       required>
                @error('proposed_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">ملاحظات السعر</label>
                <textarea name="price_notes" 
                          class="form-control @error('price_notes') is-invalid @enderror" 
                          rows="3" 
                          placeholder="اكتب تفاصيل الخدمة والسعر...">{{ old('price_notes') }}</textarea>
                @error('price_notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Warehouse Items Card -->
        <div class="pricing-card mb-4">
            <h6 class="card-title">
                <i class="fas fa-box me-2"></i>قطع الغيار والأدوات
            </h6>
            
            <div id="items-container">
                <!-- Items will be added here -->
            </div>
            
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addItem()">
                <i class="fas fa-plus me-1"></i>إضافة قطعة
            </button>
        </div>

        <!-- Total Price Display -->
        <div class="total-card mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <span class="total-label">الإجمالي المتوقع:</span>
                <span class="total-amount" id="totalAmount">0.00 جنيه</span>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="action-buttons">
            <a href="{{ route('technician.repair-requests.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-times me-2"></i>إلغاء
            </a>
            
            <button type="submit" class="btn btn-success">
                <i class="fas fa-paper-plane me-2"></i>إرسال العرض
            </button>
        </div>
    </form>

</div>

<!-- Item Template (Hidden) -->
<div id="item-template" style="display: none;">
    <div class="item-row mb-3">
        <div class="row g-2">
            <div class="col-6">
                <select name="items[INDEX][warehouse_item_id]" class="form-select item-select" onchange="updateItemPrice(this)">
                    <option value="">اختر القطعة</option>
                    @foreach($warehouseItems as $item)
                        <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                            {{ $item->name }} ({{ $item->price }} جنيه)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-3">
                <input type="number" 
                       name="items[INDEX][quantity]" 
                       class="form-control quantity-input" 
                       placeholder="الكمية" 
                       min="1" 
                       onchange="updateItemPrice(this)">
            </div>
            <div class="col-2">
                <span class="item-total">0.00</span>
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeItem(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.request-summary-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.request-title {
    font-weight: 800;
    margin-bottom: 8px;
}

.service-badge {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.customer-avatar-sm {
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
}

.customer-name-sm {
    font-weight: 600;
    font-size: 14px;
}

.customer-phone-sm {
    font-size: 12px;
    opacity: 0.8;
}

.address-info {
    font-size: 14px;
    opacity: 0.9;
}

.pricing-card {
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

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.item-row {
    background: #f9fafb;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.item-total {
    font-weight: 600;
    color: #059669;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 38px;
}

.total-card {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
}

.total-label {
    font-size: 16px;
    font-weight: 600;
}

.total-amount {
    font-size: 20px;
    font-weight: 800;
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
let itemIndex = 0;

function addItem() {
    const template = document.getElementById('item-template').innerHTML;
    const newItem = template.replace(/INDEX/g, itemIndex);
    document.getElementById('items-container').insertAdjacentHTML('beforeend', newItem);
    itemIndex++;
}

function removeItem(button) {
    button.closest('.item-row').remove();
    updateTotal();
}

function updateItemPrice(element) {
    const row = element.closest('.item-row');
    const select = row.querySelector('.item-select');
    const quantityInput = row.querySelector('.quantity-input');
    const totalSpan = row.querySelector('.item-total');
    
    const price = parseFloat(select.options[select.selectedIndex]?.dataset.price || 0);
    const quantity = parseInt(quantityInput.value || 0);
    const total = price * quantity;
    
    totalSpan.textContent = total.toFixed(2);
    updateTotal();
}

function updateTotal() {
    const servicePrice = parseFloat(document.querySelector('input[name="proposed_price"]').value || 0);
    const itemTotals = Array.from(document.querySelectorAll('.item-total'))
        .map(span => parseFloat(span.textContent || 0));
    const itemsTotal = itemTotals.reduce((sum, total) => sum + total, 0);
    const grandTotal = servicePrice + itemsTotal;
    
    document.getElementById('totalAmount').textContent = grandTotal.toFixed(2) + ' جنيه';
}

// Update total when service price changes
document.querySelector('input[name="proposed_price"]').addEventListener('input', updateTotal);
</script>
@endpush
