@extends('layouts.admin')

@section('title', 'إضافة عميل جديد')

@section('content')
<style>
.form-card { transition: all 0.3s ease; border: none; }
.form-card:hover { transform: translateY(-3px); box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.15) !important; }
.form-input { transition: all 0.3s ease; border: 2px solid #e2e8f0; border-radius: 12px; padding: 14px 18px; font-size: 15px; }
.form-input:focus { border-color: #ff7373; box-shadow: 0 0 0 4px rgba(204, 51, 51, 0.1); outline: none; }
.form-label { font-weight: 600; color: #4a5568; font-size: 14px; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
@keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
.animate-slide { animation: slideUp 0.5s ease forwards; }
</style>

<div class="mb-4 animate-slide">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #ff7373 0%, #cc3333 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 16px -4px rgba(204, 51, 51, 0.4);">
                    <i class="fa-solid fa-user-plus text-white fa-2x"></i>
                </div>
                <div>
                    <h1 class="fw-bold mb-1" style="font-size: 28px; color: #2d3748;"> إضافة عميل جديد</h1>
                    <p class="text-muted mb-0" style="font-size: 15px;">يرجى إدخال بيانات العميل بدقة</p>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.customers.index') }}" 
           class="btn px-4 py-2"
           style="background: #718096; color: white; border: none; border-radius: 12px; font-weight: 600; transition: all 0.3s;"
           onmouseover="this.style.background='#5a6c7d'"
           onmouseout="this.style.background='#718096'">
            <i class="fa-solid fa-arrow-right me-2"></i>عودة
        </a>
    </div>
</div>

<div class="card form-card shadow-sm rounded-4 animate-slide" style="animation-delay: 0.1s;">
    <div class="card-body p-0">
        <div style="background: linear-gradient(135deg, #ff7373 0%, #cc3333 100%); padding: 24px; color: white;">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-user-plus fa-lg"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1" style="font-size: 20px;"> نموذج الإضافة</h3>
                    <p class="mb-0" style="font-size: 14px; opacity: 0.9;">يرجى ملء جميع الحقول المطلوبة</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.customers.store') }}" class="p-4">
            @csrf
            
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fa-solid fa-user" style="color: #ff7373;"></i>
                        الاسم <span style="color: #ff7373;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="form-control form-input"
                        placeholder="أدخل اسم العميل">
                    @error('name')
                        <div class="d-flex align-items-center gap-2 mt-2" style="color: #ff7373; font-size: 13px;">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fa-solid fa-envelope" style="color: #ff7373;"></i>
                        البريد الإلكتروني <span style="color: #ff7373;">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="form-control form-input"
                        placeholder="example@email.com"
                        dir="ltr" style="text-align: right;">
                    @error('email')
                        <div class="d-flex align-items-center gap-2 mt-2" style="color: #ff7373; font-size: 13px;">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fa-solid fa-phone" style="color: #ff7373;"></i>
                        رقم الهاتف <span style="color: #ff7373;">*</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                        class="form-control form-input"
                        placeholder="05xxxxxxxx">
                    @error('phone')
                        <div class="d-flex align-items-center gap-2 mt-2" style="color: #ff7373; font-size: 13px;">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fa-solid fa-lock" style="color: #ff7373;"></i>
                        كلمة المرور <span style="color: #ff7373;">*</span>
                    </label>
                    <input type="password" name="password" required
                        class="form-control form-input"
                        placeholder="أدخل كلمة مرور قوية">
                    @error('password')
                        <div class="d-flex align-items-center gap-2 mt-2" style="color: #ff7373; font-size: 13px;">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fa-solid fa-lock" style="color: #ff7373;"></i>
                        تأكيد كلمة المرور <span style="color: #ff7373;">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required
                        class="form-control form-input"
                        placeholder="أعد إدخال كلمة المرور">
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                <a href="{{ route('admin.customers.index') }}"
                   class="btn px-4 py-2"
                   style="background: #e2e8f0; color: #4a5568; border: none; border-radius: 12px; font-weight: 600; transition: all 0.3s;"
                   onmouseover="this.style.background='#cbd5e0'"
                   onmouseout="this.style.background='#e2e8f0'">
                    <i class="fa-solid fa-xmark me-2"></i>إلغاء
                </a>
                <button type="submit" 
                        class=" btn-primary rounded-3 px-5 py-2">
                    <i class="fa-solid fa-plus me-2"></i>إضافة العميل
                </button>
            </div>
        </form>
    </div>
</div>
@endsection