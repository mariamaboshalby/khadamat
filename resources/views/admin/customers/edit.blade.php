@extends('layouts.admin')

@section('title', 'تعديل بيانات العميل')

@section('content')
<style>


.form-input { transition: all 0.3s ease; border: 2px solid #e2e8f0; border-radius: 12px; padding: 14px 18px; font-size: 15px; }
.form-input:focus { border-color: #ff7373; box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1); outline: none; }
.form-label { font-weight: 600; color: #4a5568; font-size: 14px; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
@keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
.animate-slide { animation: slideUp 0.5s ease forwards; }
</style>

<div class="mb-4 animate-slide ">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #ff7373 0%, #0b5f8a 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 16px -4px rgba(245, 158, 11, 0.4);">
                    <i class="fa-solid fa-user-pen text-white fa-2x"></i>
                </div>
                <div>
                    <h1 class="fw-bold mb-1" style="font-size: 28px; color: #2d3748;"> تعديل بيانات العميل</h1>
                    <p class="text-muted mb-0" style="font-size: 15px;">{{ $customer->name }}</p>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.customers.index') }}" 
           class="btn btn-secondary px-4 py-2"
           onmouseover="this.style.background='#5a6c7d'"
           onmouseout="this.style.background='#718096'">
            <i class="fa-solid fa-arrow-right me-2"></i>عودة
        </a>
    </div>
</div>

<div class="card shadow-sm rounded-4 animate-slide" style="animation-delay: 0.1s;">
    <div class="card-body p-0">
        <div style="background: linear-gradient(135deg,#ff7373 0%,#0b5f8a 100%); padding: 24px; color: white;">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-clipboard-list fa-lg"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1" style="font-size: 20px;"> نموذج التعديل</h3>
                    <p class="mb-0" style="font-size: 14px; opacity: 0.9;">يرجى تعديل البيانات بدقة</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.customers.update', \App\Helpers\EncryptionHelper::encryptId($customer->id)) }}" class="p-4">
            @csrf
            @method('PUT')
            
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fa-solid fa-user" style="color: #ff7373;"></i>
                        الاسم <span style="color: #e53e3e;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required
                        class="form-control form-input"
                        placeholder="أدخل اسم العميل">
                    @error('name')
                        <div class="d-flex align-items-center gap-2 mt-2" style="color: #e53e3e; font-size: 13px;">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fa-solid fa-envelope" style="color: #ff7373;"></i>
                        البريد الإلكتروني <span style="color: #e53e3e;">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" required
                        class="form-control form-input"
                        placeholder="example@email.com"
                        dir="ltr" style="text-align: right;">
                    @error('email')
                        <div class="d-flex align-items-center gap-2 mt-2" style="color: #e53e3e; font-size: 13px;">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fa-solid fa-phone" style="color: #ff7373;"></i>
                        رقم الهاتف <span style="color: #e53e3e;">*</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" required
                        class="form-control form-input"
                        placeholder="05xxxxxxxx">
                    @error('phone')
                        <div class="d-flex align-items-center gap-2 mt-2" style="color: #e53e3e; font-size: 13px;">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fa-solid fa-signal" style="color: #ff7373;"></i>
                        الحالة <span style="color: #e53e3e;">*</span>
                    </label>
                    <select name="status" class="form-select form-input">
                        <option value="active" {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>✅ نشط</option>
                        <option value="inactive" {{ old('status', $customer->status) == 'inactive' ? 'selected' : '' }}>⚠️ غير نشط</option>
                        <option value="suspended" {{ old('status', $customer->status) == 'suspended' ? 'selected' : '' }}>❌ موقوف</option>
                    </select>
                    @error('status')
                        <div class="d-flex align-items-center gap-2 mt-2" style="color: #e53e3e; font-size: 13px;">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                <a href="{{ route('admin.customers.show', \App\Helpers\EncryptionHelper::encryptId($customer->id)) }}"
                   class="btn px-4 py-2"
                   style="background: #e2e8f0; color: #4a5568; border: none; border-radius: 12px; font-weight: 600; transition: all 0.3s;"
                   onmouseover="this.style.background='#cbd5e0'"
                   onmouseout="this.style.background='#e2e8f0'">
                    <i class="fa-solid fa-xmark me-2"></i>إلغاء
                </a>
                <button type="submit" 
                        class="btn-primary rounded-3"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px -4px rgba(16, 185, 129, 0.5)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 8px -2px rgba(16, 185, 129, 0.4)'">
                    <i class="fa-solid fa-floppy-disk me-2"></i>حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
