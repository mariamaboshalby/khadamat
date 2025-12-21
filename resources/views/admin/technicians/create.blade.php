@extends('layouts.admin')

@section('title', 'إضافة فني جديد')

@section('content')

<div style="max-width: 1000px; margin: 0 auto;">
    <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 28px; margin: 0 0 10px; color: #2d3748; font-weight: 700;">إضافة فني جديد</h1>
            <p style="color: #718096; margin: 0;">تسجيل بيانات فني جديد في النظام</p>
        </div>
        <a href="{{ route('admin.techs.index') }}" 
           style="background: white; border: 1px solid #e2e8f0; color: #4a5568; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            عودة للقائمة
        </a>
    </div>
    
    <div class="card" style="padding: 0; overflow: hidden; border: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
        <div style="background: linear-gradient(135deg, #ff8787 0%,#e54343 100%); padding: 24px; color: white;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 20px; font-weight: 700; margin: 0 0 4px;">بيانات الفني</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">يرجى ملء جميع الحقول المطلوبة بدقة</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.techs.store') }}" style="padding: 32px;">
            @csrf
            
            <!-- Personal Info Section -->
            <div style="margin-bottom: 32px;">
                <h4 style="font-size: 16px; color: #4a5568; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f7fafc;">
                    <span style="color: #10b981;">01.</span> المعلومات الشخصية
                </h4>
                
                <div style="display: grid; grid-template-columns: 1fr; gap: 24px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">الاسم الكامل <span style="color: #e53e3e;">*</span></label>
                        <div style="position: relative;">
                            <input type="text" name="name" value="{{ old('name') }}" required
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="أدخل اسم الفني">
                            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        @error('name')
                            <p style="color: #e53e3e; font-size: 13px; margin-top: 6px; display: flex; align-items: center; gap: 4px;">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">البريد الإلكتروني <span style="color: #e53e3e;">*</span></label>
                            <div style="position: relative;">
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                    onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                    placeholder="example@email.com">
                                <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            @error('email')
                                <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">رقم الهاتف <span style="color: #e53e3e;">*</span></label>
                            <div style="position: relative;">
                                <input type="text" name="phone" value="{{ old('phone') }}" required
                                    style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                    onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                    placeholder="05xxxxxxxx">
                                <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            @error('phone')
                                <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Info Section -->
            <div style="margin-bottom: 32px;">
                <h4 style="font-size: 16px; color: #4a5568; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f7fafc;">
                    <span style="color: #10b981;">02.</span> بيانات الحساب
                </h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">كلمة المرور <span style="color: #e53e3e;">*</span></label>
                        <div style="position: relative;">
                            <input type="password" name="password" required
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="••••••••">
                            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        @error('password')
                            <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">تأكيد كلمة المرور <span style="color: #e53e3e;">*</span></label>
                        <div style="position: relative;">
                            <input type="password" name="password_confirmation" required
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="••••••••">
                            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Info Section -->
            <div style="margin-bottom: 32px;">
                <h4 style="font-size: 16px; color: #4a5568; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f7fafc;">
                    <span style="color: #10b981;">03.</span> المعلومات الوظيفية
                </h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">الدور الوظيفي <span style="color: #e53e3e;">*</span></label>
                        <div style="position: relative;">
                            <select name="role" required
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';">
                                <option value="">اختر الدور</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                        {{ $role == 'admin' ? 'أدمن' : 'فني' }}
                                    </option>
                                @endforeach
                            </select>
                            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        @error('role')
                            <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">التخصص <span style="color: #e53e3e;">*</span></label>
                        <div style="position: relative;">
                            <select name="specialization_id" required
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';">
                                <option value="">اختر التخصص</option>
                                @foreach($specializations as $specialization)
                                    <option value="{{ $specialization->id }}" {{ old('specialization_id') == $specialization->id ? 'selected' : '' }}>
                                        {{ $specialization->name }}
                                    </option>
                                @endforeach
                            </select>
                            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        @error('specialization_id')
                            <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">نبذة عن الفني (اختياري)</label>
                    <textarea name="bio" rows="4"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; resize: vertical; background: #f8fafc; transition: all 0.2s;"
                        onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                        placeholder="أدخل أي معلومات إضافية أو خبرات سابقة للفني...">{{ old('bio') }}</textarea>
                    @error('bio')
                        <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Location Section -->
            <div style="margin-bottom: 32px;">
                <h4 style="font-size: 16px; color: #4a5568; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f7fafc;">
                    <span style="color: #10b981;">04.</span> الموقع الجغرافي
                </h4>
                
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                        <button type="button" onclick="getCurrentLocation()" 
                            style="flex: 1; padding: 12px; border: 2px dashed #10b981; background: rgba(16, 185, 129, 0.05); color: #10b981; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px;"
                            onmouseover="this.style.background='rgba(16, 185, 129, 0.1)';"
                            onmouseout="this.style.background='rgba(16, 185, 129, 0.05)';">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            تحديد الموقع تلقائياً
                        </button>
                        <button type="button" onclick="toggleManualLocation()" 
                            style="flex: 1; padding: 12px; border: 2px dashed #cc3333; background: rgba(204, 51, 51, 0.05); color: #cc3333; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px;"
                            onmouseover="this.style.background='rgba(204, 51, 51, 0.1)';"
                            onmouseout="this.style.background='rgba(204, 51, 51, 0.05)';">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            إدخال الموقع يدوياً
                        </button>
                    </div>
                    
                    <div id="locationStatus" style="padding: 12px; border-radius: 8px; font-size: 14px; display: none; margin-bottom: 16px;"></div>
                </div>

                <!-- Manual Location Fields (Hidden by default) -->
                <div id="manualLocationFields" style="display: none;">
                    <!-- Location Search -->
                    <div style="margin-bottom: 24px; position: relative;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">ابحث عن الموقع</label>
                        <div style="position: relative;">
                            <input type="text" id="locationSearch" 
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                onblur="setTimeout(() => { this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'; }, 200);"
                                oninput="searchLocation(this.value)"
                                placeholder="ابحث عن مدينة أو عنوان...">
                            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div id="searchResults" style="display: none; position: absolute; z-index: 1000; left: 0; right: 0; max-height: 300px; overflow-y: auto; background: white; border: 1px solid #e2e8f0; border-radius: 10px; margin-top: 4px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);"></div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">خط العرض (Latitude)</label>
                            <div style="position: relative;">
                                <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude') }}" readonly
                                    style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; background: #f3f4f6; cursor: not-allowed;"
                                    placeholder="24.7136">
                                <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                            </div>
                            @error('latitude')
                                <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">خط الطول (Longitude)</label>
                            <div style="position: relative;">
                                <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude') }}" readonly
                                    style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; background: #f3f4f6; cursor: not-allowed;"
                                    placeholder="46.6753">
                                <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                            </div>
                            @error('longitude')
                                <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address Field -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">العنوان التفصيلي (اختياري)</label>
                    <div style="position: relative;">
                        <textarea name="address" rows="3" id="address"
                            style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; resize: vertical; background: #f8fafc; transition: all 0.2s;"
                            onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                            placeholder="أدخل العنوان التفصيلي للفني...">{{ old('address') }}</textarea>
                        <svg style="position: absolute; left: 12px; top: 12px; color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    @error('address')
                        <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Badges Section -->
            <div style="margin-bottom: 32px;">
                <h4 style="font-size: 16px; color: #4a5568; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f7fafc;">
                    <span style="color: #10b981;">05.</span> الشارات
                </h4>
                
                <div id="badgesContainer"></div>
                
                <button type="button" onclick="addBadge()" 
                    style="width: 100%; padding: 12px; border: 2px dashed #10b981; background: rgba(16, 185, 129, 0.05); color: #10b981; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px;"
                    onmouseover="this.style.background='rgba(16, 185, 129, 0.1)';"
                    onmouseout="this.style.background='rgba(16, 185, 129, 0.05)';">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    إضافة شارة
                </button>
            </div>
            
            <div style="padding-top: 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 16px;">
                <a href="{{ route('admin.techs.index') }}"
                    style="padding: 12px 24px; border-radius: 10px; border: 1px solid #e2e8f0; background: white; color: #4a5568; text-decoration: none; font-weight: 600; transition: all 0.2s;"
                    onmouseover="this.style.background='#f7fafc'; this.style.borderColor='#cbd5e0';"
                    onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0';">
                    إلغاء
                </a>
                <button type="submit" class="btn-primary rounded-3" >
                    حفظ البيانات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let badgeIndex = 0;

function addBadge() {
    const container = document.getElementById('badgesContainer');
    const badgeHtml = `
        <div class="badge-item" id="badge-${badgeIndex}" style="background: #f8fafc; padding: 20px; border-radius: 10px; margin-bottom: 16px; border: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h5 style="margin: 0; color: #2d3748; font-size: 15px; font-weight: 600;">شارة #${badgeIndex + 1}</h5>
                <button type="button" onclick="removeBadge(${badgeIndex})" 
                    style="background: #fee2e2; color: #dc2626; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;">
                    حذف
                </button>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">مستوى الخبرة</label>
                <select name="badges[]" required
                    style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 15px; background: white;">
                    <option value="">اختر المستوى</option>
                    <option value="beginner">مبتدئ</option>
                    <option value="intermediate">متوسط</option>
                    <option value="expert">خبير</option>
                    <option value="master">متمكن</option>
                </select>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', badgeHtml);
    badgeIndex++;
}

function removeBadge(index) {
    document.getElementById(`badge-${index}`).remove();
}

function getCurrentLocation() {
    const statusDiv = document.getElementById('locationStatus');
    const latitudeField = document.getElementById('latitude');
    const longitudeField = document.getElementById('longitude');
    
    statusDiv.style.display = 'block';
    statusDiv.style.background = 'rgba(204, 51, 51, 0.1)';
    statusDiv.style.border = '1px solid rgba(204, 51, 51, 0.3)';
    statusDiv.style.color = '#cc3333';
    statusDiv.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>جاري تحديد الموقع...';

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                latitudeField.value = lat.toFixed(6);
                longitudeField.value = lng.toFixed(6);
                
                statusDiv.style.background = 'rgba(16, 185, 129, 0.1)';
                statusDiv.style.border = '1px solid rgba(16, 185, 129, 0.3)';
                statusDiv.style.color = '#10b981';
                statusDiv.innerHTML = '<i class="fa-solid fa-check-circle me-2"></i>تم تحديد الموقع بنجاح! خط العرض: ' + lat.toFixed(6) + '، خط الطول: ' + lng.toFixed(6);
                
                // Hide manual fields since we got automatic location
                document.getElementById('manualLocationFields').style.display = 'none';
                
                // Try to get address using reverse geocoding (optional)
                getAddressFromCoordinates(lat, lng);
            },
            function(error) {
                let errorMessage = 'فشل تحديد الموقع: ';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage += 'تم رفض الإذن للوصول إلى الموقع';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage += 'معلومات الموقع غير متاحة';
                        break;
                    case error.TIMEOUT:
                        errorMessage += 'انتهت مهلة تحديد الموقع';
                        break;
                    default:
                        errorMessage += 'حدث خطأ غير معروف';
                        break;
                }
                
                statusDiv.style.background = 'rgba(239, 68, 68, 0.1)';
                statusDiv.style.border = '1px solid rgba(239, 68, 68, 0.3)';
                statusDiv.style.color = '#ef4444';
                statusDiv.innerHTML = '<i class="fa-solid fa-exclamation-triangle me-2"></i>' + errorMessage;
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        statusDiv.style.background = 'rgba(239, 68, 68, 0.1)';
        statusDiv.style.border = '1px solid rgba(239, 68, 68, 0.3)';
        statusDiv.style.color = '#ef4444';
        statusDiv.innerHTML = '<i class="fa-solid fa-exclamation-triangle me-2"></i>المتصفح لا يدعم تحديد الموقع الجغرافي';
    }
}

function toggleManualLocation() {
    const manualFields = document.getElementById('manualLocationFields');
    const statusDiv = document.getElementById('locationStatus');
    
    if (manualFields.style.display === 'none') {
        manualFields.style.display = 'block';
        statusDiv.style.display = 'block';
        statusDiv.style.background = 'rgba(204, 51, 51, 0.1)';
        statusDiv.style.border = '1px solid rgba(204, 51, 51, 0.3)';
        statusDiv.style.color = '#cc3333';
        statusDiv.innerHTML = '<i class="fa-solid fa-info-circle me-2"></i>يرجى إدخال إحداثيات الموقع يدوياً';
    } else {
        manualFields.style.display = 'none';
        statusDiv.style.display = 'none';
    }
}

function getAddressFromCoordinates(lat, lng) {
    // Using Nominatim (OpenStreetMap) for reverse geocoding
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=ar`)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                document.getElementById('address').value = data.display_name;
            }
        })
        .catch(error => {
            console.log('Error getting address:', error);
            // Silently fail - address is optional
        });
}

let searchTimeout;
function searchLocation(query) {
    clearTimeout(searchTimeout);
    
    if (query.length < 3) {
        document.getElementById('searchResults').style.display = 'none';
        return;
    }
    
    searchTimeout = setTimeout(() => {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&accept-language=ar&limit=5`)
            .then(response => response.json())
            .then(data => {
                const resultsDiv = document.getElementById('searchResults');
                
                if (data.length === 0) {
                    resultsDiv.innerHTML = '<div style="padding: 12px; color: #6b7280;">لم يتم العثور على نتائج</div>';
                    resultsDiv.style.display = 'block';
                    return;
                }
                
                let html = '';
                data.forEach((place, index) => {
                    html += `
                        <div onclick="selectLocation(${place.lat}, ${place.lon}, '${place.display_name.replace(/'/g, "\\'")}')"
                             style="padding: 12px; cursor: pointer; border-bottom: 1px solid #f3f4f6; transition: background 0.2s;"
                             onmouseover="this.style.background='#f9fafb'"
                             onmouseout="this.style.background='white'">
                            <div style="font-weight: 600; color: #1f2937; margin-bottom: 4px;">
                                <i class="fa-solid fa-location-dot" style="color: #10b981; margin-left: 8px;"></i>
                                ${place.display_name.split(',')[0]}
                            </div>
                            <div style="font-size: 13px; color: #6b7280;">${place.display_name}</div>
                        </div>
                    `;
                });
                
                resultsDiv.innerHTML = html;
                resultsDiv.style.display = 'block';
            })
            .catch(error => {
                console.log('Error searching location:', error);
            });
    }, 500);
}

function selectLocation(lat, lon, displayName) {
    document.getElementById('latitude').value = parseFloat(lat).toFixed(6);
    document.getElementById('longitude').value = parseFloat(lon).toFixed(6);
    document.getElementById('address').value = displayName;
    document.getElementById('locationSearch').value = displayName.split(',')[0];
    document.getElementById('searchResults').style.display = 'none';
    
    const statusDiv = document.getElementById('locationStatus');
    statusDiv.style.display = 'block';
    statusDiv.style.background = 'rgba(16, 185, 129, 0.1)';
    statusDiv.style.border = '1px solid rgba(16, 185, 129, 0.3)';
    statusDiv.style.color = '#10b981';
    statusDiv.innerHTML = '<i class="fa-solid fa-check-circle me-2"></i>تم تحديد الموقع بنجاح!';
}
</script>
@endpush
