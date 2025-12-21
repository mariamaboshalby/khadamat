@extends('layouts.mobile')

@section('title', 'حسابي')

@section('content')
<style>
    .profile-page {
        background: #f8f9fa;
        min-height: 100vh;
        padding-bottom: 2rem;
    }
    
    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    
    .profile-avatar-section {
        text-align: center;
        padding: 1rem 0;
    }
    
    .profile-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        border: 3px solid #e9ecef;
        margin-bottom: 1rem;
    }
    
    .profile-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.25rem;
    }
    
    .profile-email {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .edit-profile-btn {
        background: #e54343;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.75rem 2rem;
        font-weight: 500;
        margin-top: 1rem;
        text-decoration: none;
        display: inline-block;
        transition: background 0.2s;
    }
    
    .edit-profile-btn:hover {
        background: #cc3333;
        color: white;
    }
    
    .section-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 1.5rem 0 0.75rem;
        padding: 0 0.5rem;
    }
    
    .menu-list {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    
    .menu-list-item {
        display: flex;
        align-items: center;
        padding: 1rem 1.25rem;
        text-decoration: none;
        color: #212529;
        border-bottom: 1px solid #f1f3f5;
        transition: background 0.2s;
    }
    
    .menu-list-item:last-child {
        border-bottom: none;
    }
    
    .menu-list-item:hover {
        background: #f8f9fa;
        color: #212529;
    }
    
    .menu-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 1rem;
        font-size: 1.1rem;
    }
    
    .menu-content {
        flex: 1;
    }
    
    .menu-title {
        font-weight: 500;
        font-size: 0.95rem;
        margin: 0;
        color: #212529;
    }
    
    .menu-subtitle {
        font-size: 0.8rem;
        color: #6c757d;
        margin: 0;
    }
    
    .menu-arrow {
        color: #adb5bd;
        font-size: 0.9rem;
    }
    
    .logout-btn {
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1rem;
        font-weight: 500;
        width: 100%;
        margin-top: 1.5rem;
        transition: background 0.2s;
    }
    
    .logout-btn:hover {
        background: #c82333;
    }
    
    .guest-card {
        background: white;
        border-radius: 16px;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    
    .guest-icon {
        width: 80px;
        height: 80px;
        background: #f8f9fa;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #adb5bd;
        margin-bottom: 1.5rem;
    }
    
    .guest-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.5rem;
    }
    
    .guest-text {
        color: #6c757d;
        margin-bottom: 2rem;
    }
    
    .btn-login {
        background: #4361ee;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.875rem;
        font-weight: 500;
        width: 100%;
        margin-bottom: 0.75rem;
        text-decoration: none;
        display: block;
        transition: background 0.2s;
    }
    
    .btn-login:hover {
        background: #3651d4;
        color: white;
    }
    
    .btn-register {
        background: white;
        color: #4361ee;
        border: 2px solid #4361ee;
        border-radius: 12px;
        padding: 0.875rem;
        font-weight: 500;
        width: 100%;
        text-decoration: none;
        display: block;
        transition: all 0.2s;
    }
    
    .btn-register:hover {
        background: #4361ee;
        color: white;
    }
</style>

<div class="profile-page px-2">
    @auth
        <!-- Profile Info Card -->
        <div class="profile-card">
            <div class="profile-avatar-section">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=e54343&color=fff&size=180" 
                     class="profile-avatar" 
                     alt="صورة الملف الشخصي">
                <div class="profile-name">{{ Auth::user()->name }}</div>
                <div class="profile-email">{{ Auth::user()->email }}</div>
                <a href="{{ route('profile.edit') }}" class="edit-profile-btn">
                    <i class="fas fa-edit me-1"></i> تعديل الملف الشخصي
                </a>
            </div>
        </div>

        <!-- القائمة الرئيسية -->
        <div class="menu-list">
            <a href="{{ route('dashboard') }}" class="menu-list-item">
                <div class="menu-icon" style="background: #e3f2fd; color: #2196f3;">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="menu-content">
                    <div class="menu-title">طلباتي</div>
                    <div class="menu-subtitle">عرض وتتبع الطلبات</div>
                </div>
                <i class="fas fa-chevron-left menu-arrow"></i>
            </a>
            
            <a href="{{ route('profile.edit') }}" class="menu-list-item">
                <div class="menu-icon" style="background: #f3e5f5; color: #9c27b0;">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="menu-content">
                    <div class="menu-title">العناوين</div>
                    <div class="menu-subtitle">إدارة عناوين التوصيل</div>
                </div>
                <i class="fas fa-chevron-left menu-arrow"></i>
            </a>
            
            <a href="{{ route('profile.edit') }}" class="menu-list-item">
                <div class="menu-icon" style="background: #fff3e0; color: #ff9800;">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="menu-content">
                    <div class="menu-title">الإشعارات</div>
                    <div class="menu-subtitle">إعدادات التنبيهات</div>
                </div>
                <i class="fas fa-chevron-left menu-arrow"></i>
            </a>
            
            <a href="{{ route('home') }}" class="menu-list-item">
                <div class="menu-icon" style="background: #e0f2f1; color: #009688;">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="menu-content">
                    <div class="menu-title">الدعم الفني</div>
                    <div class="menu-subtitle">تواصل معنا</div>
                </div>
                <i class="fas fa-chevron-left menu-arrow"></i>
            </a>
        </div>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
            </button>
        </form>

    @else
        <!-- Guest View -->
        <div class="guest-card">
            <div class="guest-icon">
                <i class="fas fa-user"></i>
            </div>
            <h2 class="guest-title">مرحباً بك</h2>
            <p class="guest-text">سجل الدخول للوصول إلى حسابك ومتابعة طلباتك</p>
            <a href="{{ route('login') }}" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
            </a>
            <a href="{{ route('register') }}" class="btn-register">
                <i class="fas fa-user-plus me-2"></i>إنشاء حساب جديد
            </a>
        </div>
    @endauth
</div>
@endsection
