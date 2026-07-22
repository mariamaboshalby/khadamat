@extends('layouts.admin')

@section('title', 'طلبات الإصلاح المتاحة')
@section('header_title', 'طلبات الإصلاح')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            {{ Auth::user()->hasRole('admin') ? 'جميع طلبات الإصلاح' : 'طلبات الإصلاح المتاحة' }}
        </h4>
        <p class="text-muted mb-0">
            {{ Auth::user()->hasRole('admin') ? 'جميع الطلبات المتاحة للتخصيص' : 'الطلبات المتاحة للتقديم في تخصصك' }}
        </p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($requests->count() > 0)
    <div class="row g-4">
        @foreach($requests as $request)
            <div class="col-lg-6 col-xl-4">
                <div class="request-card h-100">
                    <div class="request-header">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="request-id">#{{ $request->id }}</div>
                            <span class="service-badge">{{ $request->service->name }}</span>
                        </div>
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
                            
                            <div class="detail-item">
                                <i class="fas fa-clock text-muted"></i>
                                <span>{{ $request->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        
                        @if($request->description)
                        <div class="request-description">
                            {{ Str::limit($request->description, 100) }}
                        </div>
                        @endif
                    </div>
                    
                    <div class="request-actions">
                        <a href="{{ route('technician.repair-requests.show', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>التفاصيل
                        </a>
                        
                        <a href="{{ route('technician.repair-requests.pricing', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" 
                           class="btn btn-success btn-sm">
                            <i class="fas fa-calculator me-1"></i>تقديم عرض
                        </a>
                    </div>
                </div>

            </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $requests->links() }}
    </div>
@else
    <div class="card justify-content-center align-items-center p-4">
        <div class="empty-icon">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <h5 class="empty-title">
            {{ Auth::user()->hasRole('admin') ? 'لا توجد طلبات متاحة حالياً' : 'لا توجد طلبات متاحة حالياً' }}
        </h5>
        <p class="empty-text text-center">
            {{ Auth::user()->hasRole('admin') ? 'لا توجد طلبات إصلاح متاحة في الوقت الحالي' : 'لا توجد طلبات إصلاح متاحة في تخصصك في الوقت الحالي' }}
        </p>
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
    border-color: #0b5f8a;
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
    background: linear-gradient(135deg, #0b5f8a, #992626);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.request-body {
    padding: 20px;
}

.customer-avatar {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #0b5f8a, #ff6666);
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

.request-description {
    background: #f9fafb;
    padding: 12px;
    border-radius: 8px;
    font-size: 14px;
    color: #6b7280;
    line-height: 1.5;
    margin-top: 12px;
}

.request-actions {
    padding: 0 20px 20px;
    display: flex;
    gap: 8px;
    justify-content: flex-end;
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
