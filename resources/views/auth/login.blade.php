@extends('layouts.mobile')

@section('title', 'تسجيل الدخول')

@section('content')

<div class="app-content fade-in d-flex justify-content-center" style="min-height: 80vh;">
    <div class="w-100" style="max-width: 400px;">
        
        <!-- Logo -->
        <div class="text-center mt-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="" style="height: 100px; width: 200px;">
            <p class="text-muted">مرحباً بك مجدداً</p>
        </div>

        <!-- Login Form -->
        <div class="card border-0 shadow-lg" style="border-radius: 25px;">
            <div class="card-body p-4">
                <h5 class="fw-bold text-center mb-4">تسجيل الدخول</h5>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Phone Number -->
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-bold small text-muted">رقم الهاتف</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-end-3 ps-3"><i class="fas fa-phone text-muted"></i></span>
                            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required autofocus class="form-control bg-light border-0 rounded-start-3 py-2" placeholder="أدخل رقم هاتفك" style="direction: rtl;">
                        </div>
                        <x-input-error :messages="$errors->get('phone')" class="mt-2 text-danger small" />
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold small text-muted">كلمة المرور</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-end-3 ps-3"><i class="fas fa-lock text-muted"></i></span>
                            <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control bg-light border-0 rounded-start-3 py-2" placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                            <label for="remember_me" class="form-check-label small text-muted">تذكرني</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="small text-decoration-none fw-bold"  style="color: #083a56;">نسيت كلمة المرور؟</a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class=" btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm mb-3">
                        تسجيل الدخول <i class="fas fa-arrow-left ms-2"></i>
                    </button>

                    <!-- Register Link -->
                    <div class="text-center">
                        <span class="text-muted small">ليس لديك حساب؟</span>
                        <a href="{{ route('register') }}" class=" fw-bold small text-decoration-none ms-1" style="color: #083a56;">إنشاء حساب جديد</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
