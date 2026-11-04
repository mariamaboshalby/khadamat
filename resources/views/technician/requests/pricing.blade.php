@extends('layouts.mobile')

@section('title', 'تقديم عرض سعر - طلب #' . $request->id)

@section('mobile_header')
    <div class="header-icon-btn" onclick="history.back()">
        <i class="fas fa-arrow-right"></i>
    </div>
    <div class="app-logo fw-bold">
        عرض سعر
    </div>
    <div class="header-icon-btn">
        <i class="fas fa-calculator"></i>
    </div>
@endsection

@section('content')

<div class="pricing-wrap fade-in">

    <!-- Compact Request Summary (spans full width, top) -->
    <div class="summary-card">
        <div class="sum-top">
            <span class="req-num"><i class="fas fa-clipboard-list ms-1"></i> طلب #{{ $request->id }}</span>
            <span class="service-badge"><i class="fas fa-wrench ms-1"></i>{{ $request->service->name }}</span>
        </div>
        <div class="sum-chips">
            <span class="sum-chip"><i class="fas fa-user"></i>{{ $request->user->name }}</span>
            <span class="sum-chip"><i class="fas fa-phone"></i><span class="ltr-num">{{ $request->user->phone }}</span></span>
            <span class="sum-chip"><i class="fas fa-location-dot"></i>{{ $request->address }}</span>
        </div>
    </div>

    <!-- Two-column working area -->
    <form action="{{ route('technician.repair-requests.submit-pricing', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" method="POST" id="pricing-form" class="pricing-grid">
        @csrf

        <!-- Main: editable fields -->
        <div class="pricing-main">

            <!-- Service Price -->
            <div class="panel">
                <div class="panel-head">
                    <span class="panel-chip"><i class="fas fa-tools"></i></span>
                    <h6 class="panel-title">سعر الخدمة</h6>
                </div>

                <div class="field mb-3">
                    <label class="field-label">السعر المقترح (جنيه)</label>
                    <div class="input-price">
                        <input type="number"
                               name="proposed_price"
                               class="form-control @error('proposed_price') is-invalid @enderror"
                               value="{{ old('proposed_price') }}"
                               step="0.01"
                               min="0"
                               placeholder="0.00"
                               required>
                        <span class="input-suffix">ج.م</span>
                    </div>
                    @error('proposed_price')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">ملاحظات السعر</label>
                    <textarea name="price_notes"
                              class="form-control @error('price_notes') is-invalid @enderror"
                              rows="2"
                              placeholder="اكتب تفاصيل الخدمة والسعر...">{{ old('price_notes') }}</textarea>
                    @error('price_notes')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Warehouse Items -->
            <div class="panel">
                <div class="panel-head">
                    <span class="panel-chip"><i class="fas fa-box"></i></span>
                    <h6 class="panel-title">قطع الغيار والأدوات</h6>
                </div>

                <div id="items-container"></div>

                <div class="empty-hint" id="empty-hint">
                    <i class="fas fa-plus-circle"></i> لم تُضف أي قطع بعد
                </div>

                <button type="button" class="btn-add" onclick="addItem()">
                    <i class="fas fa-plus"></i> إضافة قطعة
                </button>
            </div>
        </div>

        <!-- Aside: sticky checkout -->
        <aside class="pricing-aside">
            <div class="total-card">
                <div class="total-row">
                    <span>سعر الخدمة</span>
                    <span class="ltr-num"><span id="svcSubtotal">0.00</span> ج.م</span>
                </div>
                <div class="total-row">
                    <span>إجمالي القطع</span>
                    <span class="ltr-num"><span id="partsSubtotal">0.00</span> ج.م</span>
                </div>
                <div class="total-divider"></div>
                <div class="total-row total-final">
                    <span>الإجمالي المتوقع</span>
                    <span class="ltr-num"><span id="totalAmount">0.00</span> ج.م</span>
                </div>
            </div>

            <div class="action-buttons">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane me-1"></i> إرسال العرض
                </button>
                <a href="{{ route('technician.repair-requests.index') }}" class="btn-cancel">
                    <i class="fas fa-times me-1"></i> إلغاء
                </a>
            </div>
        </aside>
    </form>

</div>

<!-- Item Template (Hidden) -->
<div id="item-template" style="display: none;">
    <div class="item-row">
        <div class="row g-2 align-items-center">
            <div class="col-5">
                <select name="items[INDEX][warehouse_item_id]" class="form-select item-select" onchange="updateItemPrice(this)">
                    <option value="">اختر القطعة</option>
                    @foreach($warehouseItems as $item)
                        <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                            {{ $item->name }} ({{ $item->price }} ج.م)
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
                       onchange="updateItemPrice(this)"
                       oninput="updateItemPrice(this)">
            </div>
            <div class="col-3">
                <span class="item-total ltr-num">0.00</span>
            </div>
            <div class="col-1 text-center">
                <button type="button" class="btn-remove" onclick="removeItem(this)" aria-label="حذف">
                    <i class="fas fa-trash-can"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.pricing-wrap {
    max-width: 1040px;
    margin: 0 auto;
    padding: 20px 16px 40px;
}

/* Compact summary */
.summary-card {
    background: var(--surface-color);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 14px 18px;
    margin-bottom: 18px;
}
.sum-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}
.req-num { font-size: 17px; font-weight: 800; color: var(--primary-dark); }
.service-badge {
    background: var(--primary-color);
    color: #fff;
    padding: 4px 14px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 700;
}
.sum-chips { display: flex; flex-wrap: wrap; gap: 10px; }
.sum-chip {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #f1f5f9;
    color: var(--text-secondary);
    border-radius: 30px;
    padding: 6px 14px;
    font-size: 13px;
    font-weight: 600;
}
.sum-chip i { color: var(--primary-color); font-size: 12px; }

/* Grid */
.pricing-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 18px;
    align-items: start;
}
.pricing-main { display: flex; flex-direction: column; gap: 18px; }

@media (min-width: 992px) {
    .pricing-grid { grid-template-columns: 1.5fr 1fr; }
    .pricing-aside {
        position: sticky;
        top: 92px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
}

/* Panels */
.panel {
    background: var(--surface-color);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 18px;
}
.panel-head { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.panel-chip {
    width: 38px; height: 38px;
    display: flex; align-items: center; justify-content: center;
    background: #eef5f9; color: var(--primary-color);
    border-radius: 11px; font-size: 15px;
}
.panel-title { font-weight: 800; color: var(--text-primary); margin: 0; font-size: 15px; }

/* Fields */
.field-label { display: block; font-weight: 600; color: var(--text-secondary); margin-bottom: 7px; font-size: 13px; }
.input-price { position: relative; }
.input-price .form-control { padding-left: 46px; }
.input-suffix {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: var(--text-muted); font-weight: 700; font-size: 13px; pointer-events: none;
}
.pricing-wrap .form-control,
.pricing-wrap .form-select {
    border: 1px solid var(--border-color);
    border-radius: 11px;
    padding: 10px 13px;
    font-size: 14px;
    background: #fcfdfe;
    transition: border-color .2s, box-shadow .2s;
}
.pricing-wrap .form-control:focus,
.pricing-wrap .form-select:focus {
    border-color: var(--primary-light);
    box-shadow: 0 0 0 4px rgba(24, 142, 201, .12);
    outline: none;
}

/* Items */
.item-row {
    background: #f8fafc;
    border: 1px solid var(--border-color);
    border-radius: 11px;
    padding: 10px;
    margin-bottom: 8px;
}
.item-total { font-weight: 800; color: var(--primary-color); display: block; text-align: center; font-size: 14px; }
.btn-remove {
    width: 32px; height: 32px;
    border: 1px solid #fecaca; background: #fff; color: #dc2626;
    border-radius: 9px; display: inline-flex; align-items: center; justify-content: center;
    transition: all .2s;
}
.btn-remove:hover { background: #dc2626; color: #fff; border-color: #dc2626; }
.empty-hint {
    text-align: center; color: var(--text-muted); font-size: 13px;
    padding: 10px 0 14px; display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-add {
    width: 100%;
    border: 1.5px dashed var(--primary-light);
    background: #f4fafd; color: var(--primary-color);
    font-weight: 700; padding: 10px; border-radius: 11px; transition: all .2s;
}
.btn-add:hover { background: #e6f3fa; border-style: solid; }

/* Total card */
.total-card { background: var(--primary-dark); color: #fff; border-radius: var(--radius-md); padding: 16px 18px; }
.total-row { display: flex; justify-content: space-between; align-items: center; font-size: 14px; padding: 4px 0; color: rgba(255,255,255,.82); }
.total-divider { height: 1px; background: rgba(255,255,255,.18); margin: 8px 0; }
.total-final { color: #fff; font-size: 16px; font-weight: 800; }
.total-final #totalAmount { color: var(--accent-orange); font-size: 22px; }

/* Actions */
.action-buttons { display: flex; flex-direction: column; gap: 10px; }
.btn-cancel, .btn-submit {
    text-align: center; padding: 12px; border-radius: 11px;
    font-weight: 700; font-size: 15px; border: none; text-decoration: none; transition: all .2s;
}
.btn-cancel { background: #fff; color: var(--text-secondary); border: 1px solid var(--border-color); }
.btn-cancel:hover { background: #f1f5f9; color: var(--text-primary); }
.btn-submit { background: var(--accent-orange); color: #fff; box-shadow: 0 6px 16px rgba(255,138,0,.28); }
.btn-submit:hover { background: var(--accent-orange-hover); transform: translateY(-1px); }

.ltr-num { direction: ltr; unicode-bidi: embed; }

@media (max-width: 420px) {
    .item-row .col-5 { flex: 0 0 100%; max-width: 100%; }
}

/* Mobile: keep total + submit pinned at bottom while scrolling */
@media (max-width: 991px) {
    .pricing-aside {
        position: sticky;
        bottom: 8px;
        z-index: 20;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .pricing-aside .total-card { padding: 12px 16px; }
    .pricing-aside .total-row:not(.total-final) { display: none; }
    .pricing-aside .total-divider { display: none; }
    .action-buttons { flex-direction: row; }
    .action-buttons .btn-submit,
    .action-buttons .btn-cancel { flex: 1; }
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
    toggleEmptyHint();
}

function removeItem(button) {
    button.closest('.item-row').remove();
    toggleEmptyHint();
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

function toggleEmptyHint() {
    const hasItems = document.querySelectorAll('#items-container .item-row').length > 0;
    document.getElementById('empty-hint').style.display = hasItems ? 'none' : 'flex';
}

function updateTotal() {
    const servicePrice = parseFloat(document.querySelector('input[name="proposed_price"]').value || 0);
    const itemTotals = Array.from(document.querySelectorAll('.item-total'))
        .map(span => parseFloat(span.textContent || 0));
    const itemsTotal = itemTotals.reduce((sum, total) => sum + total, 0);
    const grandTotal = servicePrice + itemsTotal;

    document.getElementById('svcSubtotal').textContent = servicePrice.toFixed(2);
    document.getElementById('partsSubtotal').textContent = itemsTotal.toFixed(2);
    document.getElementById('totalAmount').textContent = grandTotal.toFixed(2);
}

document.querySelector('input[name="proposed_price"]').addEventListener('input', updateTotal);
toggleEmptyHint();
</script>
@endpush
