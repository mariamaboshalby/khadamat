@extends('layouts.admin')

@section('title', 'تفاصيل العميل')

@section('content')
<style>
.info-card {
    transition: all 0.3s ease;
    border: none;
    overflow: hidden;
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.15) !important;
}

.info-item {
    padding: 16px;
    border-radius: 12px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(16, 185, 129, 0.05) 100%);
    transform: translateX(5px);
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease forwards;
}
</style>

<div class="mb-4 animate-fade-in">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 16px -4px rgba(102, 126, 234, 0.4);">
                    <i class="fa-solid fa-user text-white fa-2x"></i>
                </div>
                <div>
                    <h1 class="fw-bold mb-1" style="font-size: 28px; color: #2d3748;"> تفاصيل العميل</h1>
                    <p class="text-muted mb-0" style="font-size: 15px;">{{ $customer->name }}</p>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.customers.edit', $customer) }}" 
               class="btn px-4 py-2"
               style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 12px; font-weight: 600; box-shadow: 0 4px 8px -2px rgba(16, 185, 129, 0.4); transition: all 0.3s;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px -4px rgba(16, 185, 129, 0.5)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 8px -2px rgba(16, 185, 129, 0.4)'">
                <i class="fa-solid fa-pen me-2"></i>تعديل
            </a>
            <a href="{{ route('admin.customers.index') }}" 
               class="btn px-4 py-2"
               style="background: #718096; color: white; border: none; border-radius: 12px; font-weight: 600; transition: all 0.3s;"
               onmouseover="this.style.background='#5a6c7d'"
               onmouseout="this.style.background='#718096'">
                <i class="fa-solid fa-arrow-right me-2"></i>عودة
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Personal Info Card -->
    <div class="col-lg-6 animate-fade-in" style="animation-delay: 0.1s;">
        <div class="card info-card shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-id-card text-white fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-0" style="font-size: 20px; color: #2d3748;"> البيانات الشخصية</h3>
                </div>
                
                <div class="d-flex flex-column gap-3">
                    <div class="info-item">
                        <label class="d-flex align-items-center gap-2 text-muted small fw-bold mb-2">
                            <i class="fa-solid fa-user" style="color: #667eea;"></i>
                            الاسم
                        </label>
                        <p class="fw-bold mb-0" style="color: #2d3748; font-size: 16px;">{{ $customer->name }}</p>
                    </div>
                    
                    <div class="info-item">
                        <label class="d-flex align-items-center gap-2 text-muted small fw-bold mb-2">
                            <i class="fa-solid fa-envelope" style="color: #667eea;"></i>
                            البريد الإلكتروني
                        </label>
                        <p class="fw-bold mb-0" style="color: #2d3748; font-size: 16px; direction: ltr; text-align: right;">{{ $customer->email }}</p>
                    </div>
                    
                    <div class="info-item">
                        <label class="d-flex align-items-center gap-2 text-muted small fw-bold mb-2">
                            <i class="fa-solid fa-phone" style="color: #667eea;"></i>
                            رقم الهاتف
                        </label>
                        <p class="fw-bold mb-0" style="color: #2d3748; font-size: 16px; font-family: monospace;">{{ $customer->phone }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Account Info Card -->
    <div class="col-lg-6 animate-fade-in" style="animation-delay: 0.2s;">
        <div class="card info-card shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-user-shield text-white fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-0" style="font-size: 20px; color: #2d3748;"> معلومات الحساب</h3>
                </div>
                
                <div class="d-flex flex-column gap-3">
                    <div class="info-item">
                        <label class="d-flex align-items-center gap-2 text-muted small fw-bold mb-2">
                            <i class="fa-solid fa-calendar-plus" style="color: #667eea;"></i>
                            تاريخ التسجيل
                        </label>
                        <p class="fw-bold mb-1" style="color: #2d3748; font-size: 16px;">{{ $customer->created_at->format('Y-m-d') }}</p>
                        <small class="text-muted">
                            <i class="fa-solid fa-clock me-1"></i>
                            {{ $customer->created_at->format('h:i A') }}
                        </small>
                    </div>
                    
                    <div class="info-item">
                        <label class="d-flex align-items-center gap-2 text-muted small fw-bold mb-2">
                            <i class="fa-solid fa-clock-rotate-left" style="color: #667eea;"></i>
                            آخر تحديث
                        </label>
                        <p class="fw-bold mb-1" style="color: #2d3748; font-size: 16px;">{{ $customer->updated_at->format('Y-m-d') }}</p>
                        <small class="text-muted">
                            <i class="fa-solid fa-clock me-1"></i>
                            {{ $customer->updated_at->diffForHumans() }}
                        </small>
                    </div>
                    
                    <div class="info-item">
                        <label class="d-flex align-items-center gap-2 text-muted small fw-bold mb-2">
                            <i class="fa-solid fa-signal" style="color: #667eea;"></i>
                            الحالة
                        </label>
                        @if($customer->status == 'active')
                            <span class="badge px-4 py-2 rounded-pill" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; font-weight: 600; font-size: 14px;">
                                <i class="fa-solid fa-circle-check me-1"></i> نشط
                            </span>
                        @elseif($customer->status == 'inactive')
                            <span class="badge px-4 py-2 rounded-pill" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; font-weight: 600; font-size: 14px;">
                                <i class="fa-solid fa-circle-pause me-1"></i> غير نشط
                            </span>
                        @else
                            <span class="badge px-4 py-2 rounded-pill" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; font-weight: 600; font-size: 14px;">
                                <i class="fa-solid fa-circle-xmark me-1"></i> موقوف
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
