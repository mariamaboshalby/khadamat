@extends('layouts.mobile')

@section('title', 'إنشاء حساب')

@section('content')
<div class="app-content fade-in d-flex justify-content-center" style="min-height: 80vh;">
    <div class="w-100" style="max-width: 500px;">
        
        <!-- Logo -->
        <div class="text-center ">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="" style="height: 100px; width: 200px;">
            <h4 class="fw-bold " style="color: #e54343;">إنشاء حساب جديد</h4>
        </div>

        <!-- Register Form -->
        <div class="card border-0 shadow-lg" style="border-radius: 25px;">
            <div class="card-body p-4">

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold small text-muted">الاسم الكامل</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-end-3 ps-3"><i class="fas fa-user text-muted"></i></span>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="form-control bg-light border-0 rounded-start-3 py-2" placeholder="الاسم الكامل">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger small" />
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold small text-muted">البريد الإلكتروني</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-end-3 ps-3"><i class="fas fa-envelope text-muted"></i></span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-control bg-light border-0 rounded-start-3 py-2" placeholder="example@mail.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger small" />
                    </div>

                    <!-- Phone -->
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-bold small text-muted">رقم الهاتف</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-end-3 ps-3"><i class="fas fa-phone text-muted"></i></span>
                            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required class="form-control bg-light border-0 rounded-start-3 py-2" placeholder="01xxxxxxxxx">
                        </div>
                        <x-input-error :messages="$errors->get('phone')" class="mt-2 text-danger small" />
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold small text-muted">كلمة المرور</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-end-3 ps-3"><i class="fas fa-lock text-muted"></i></span>
                            <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control bg-light border-0 rounded-start-3 py-2" placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-bold small text-muted">تأكيد كلمة المرور</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-end-3 ps-3"><i class="fas fa-lock text-muted"></i></span>
                            <input id="password_confirmation" type="password" name="password_confirmation" required class="form-control bg-light border-0 rounded-start-3 py-2" placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger small" />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class=" btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm mb-3">
                        إنشاء الحساب <i class="fas fa-check ms-2"></i>
                    </button>

                    <!-- Login Link -->
                    <div class="text-center">
                        <span class="text-muted small">لديك حساب بالفعل؟</span>
                        <a href="{{ route('login') }}" class=" fw-bold small text-decoration-none ms-1"  style="color: #e54343;">تسجيل الدخول</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
