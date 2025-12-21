@extends('layouts.admin')

@section('title', 'طلباتي')
@section('header_title', 'طلباتي')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">طلباتي</h4>
        <p class="text-muted mb-0">الطلبات التي قدمت عليها</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Debug Info --}}
<div class="alert alert-info">
    <strong>Debug:</strong> عدد الطلبات: {{ $requests->count() }}<br>
    <strong>Technician ID:</strong> {{ $technician->id ?? 'N/A' }}<br>
    <strong>User ID:</strong> {{ auth()->user()->id }}
</div>

@if($requests->count() > 0)
    <div class="row g-4">
        @foreach($requests as $request)
            <div class="col-lg-6 col-xl-4">
                <div class="request-card h-100">
                    <div class="request-header">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="request-id">#{{ $request->id }}</div>
                            @php
                                $statusBadges = [
                                    'pricing_pending' => ['class' => 'warning', 'text' => 'في انتظار الموافقة'],
                                    'in_progress' => ['class' => 'info', 'text' => 'قيد التنفيذ'],
                                    'completed' => ['class' => 'success', 'text' => 'مكتمل'],
                                ];
                                $badge = $statusBadges[$request->status] ?? ['class' => 'secondary', 'text' => $request->status];
                            @endphp
                            <span class="badge bg-{{ $badge['class'] }}">{{ $badge['text'] }}</span>
                        </div>
                        <span class="service-badge mt-2">{{ $request->service->name }}</span>
                    </div>
                    
                    <div class="request-body">
                        <div class="customer-info mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="customer-avatar">
                                    {{ substr($request->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="customer-name">{{ $request->user->name }}</div>
                                    <div class="customer-phone">{{ $request->user->phone }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="request-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                <span>{{ Str::limit($request->address, 50) }}</span>
                            </div>
                            
                            @if($request->scheduled_at)
                            <div class="detail-item">
                                <i class="fas fa-calendar text-primary"></i>
                                <span>{{ $request->scheduled_at->format('Y-m-d H:i') }}</span>
                            </div>
                            @endif
                        </div>
                        
                        @if($request->proposed_price)
                        <div class="price-info mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">السعر المقترح:</span>
                                <span class="fw-bold text-success">{{ number_format($request->total_price, 2) }} جنيه</span>
                            </div>
                            @if($request->requestItems->count() > 0)
                            <div class="text-muted small mt-1">
                                يشمل {{ $request->requestItems->count() }} قطعة
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                    
                    <div class="request-actions">
                        @if($request->status === 'in_progress' && $request->assigned_technician_id === $technician->id)
                            <form method="POST" action="{{ route('technician.repair-requests.complete', $request->id) }}" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm w-100">
                                    <i class="fas fa-check-circle me-1"></i>تم الإنجاز
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('technician.repair-requests.show', $request->id) }}" 
                           class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-eye me-1"></i>التفاصيل
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $requests->links() }}
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <h5 class="empty-title">لا توجد طلبات</h5>
        <p class="empty-text">لم تقدم على أي طلبات بعد</p>
        <a href="{{ route('technician.repair-requests.index') }}" class="btn btn-primary mt-3">
            <i class="fas fa-search me-2"></i>تصفح الطلبات المتاحة
        </a>
    </div>
@endif
@endsection

@push('styles')
<style>
.request-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.request-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-color: #cc3333;
}

.request-header {
    padding: 20px 20px 0;
}

.request-id {
    font-size: 18px;
    font-weight: 800;
    color: #1f2937;
}

.service-badge {
    background: linear-gradient(135deg, #cc3333, #992626);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.request-body {
    padding: 20px;
}

.customer-avatar {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #cc3333, #ff6666);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
}

.customer-name {
    font-weight: 700;
    color: #1f2937;
    font-size: 16px;
}

.customer-phone {
    color: #6b7280;
    font-size: 14px;
    font-family: monospace;
}

.request-details {
    margin-bottom: 16px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-size: 14px;
    color: #4b5563;
}

.detail-item i {
    width: 16px;
    text-align: center;
}

.price-info {
    background: #f0fdf4;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #bbf7d0;
}

.request-actions {
    padding: 0 20px 20px;
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: #f3f4f6;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
}

.empty-icon i {
    font-size: 32px;
    color: #9ca3af;
}

.empty-title {
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
}

.empty-text {
    color: #6b7280;
    margin: 0;
}
</style>
@endpush
