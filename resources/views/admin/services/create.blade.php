@extends('layouts.admin')

@section('title', 'إضافة خدمة جديدة')
@section('header_title', 'إضافة خدمة جديدة')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 28px; margin: 0 0 10px; color: #2d3748; font-weight: 700;">إضافة خدمة جديدة</h1>
            <p style="color: #718096; margin: 0;">إنشاء خدمة جديدة مرتبطة بتخصص</p>
        </div>
        <a href="{{ route('admin.services.index') }}" 
           style="background: white; border: 1px solid #e2e8f0; color: #4a5568; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            عودة للقائمة
        </a>
    </div>
    
    <div class="card" style="padding: 0; overflow: hidden; border: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
        <div style="background: linear-gradient(135deg, #e53e3e 0%, #cc3333 100%); padding: 24px; color: white;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 20px; font-weight: 700; margin: 0 0 4px;">بيانات الخدمة</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">يرجى ملء جميع الحقول المطلوبة بدقة</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.services.store') }}" style="padding: 32px;">
            @csrf
            
            <!-- Service Details Section -->
            <div style="margin-bottom: 32px;">
                <h4 style="font-size: 16px; color: #4a5568; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f7fafc;">
                    <span style="color: #10b981;">01.</span> تفاصيل الخدمة
                </h4>
                
                <div style="display: grid; grid-template-columns: 1fr; gap: 24px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">اسم الخدمة <span style="color: #e53e3e;">*</span></label>
                        <div style="position: relative;">
                            <input type="text" name="name" value="{{ old('name') }}" required
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="مثال: صيانة التكييف">
                            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        @error('name')
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
            </div>

            <!-- Styling Section -->
            <div style="margin-bottom: 32px;">
                <h4 style="font-size: 16px; color: #4a5568; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f7fafc;">
                    <span style="color: #10b981;">02.</span> التصميم والتنسيق
                </h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">رمز الأيقونة (Font Awesome)</label>
                        <div style="position: relative;">
                            <input type="text" name="icon" value="{{ old('icon') }}"
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="مثال: fa-fan">
                            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5"/>
                            </svg>
                        </div>
                        @error('icon')
                            <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                        @enderror
                        <p style="color: #718096; font-size: 13px; margin-top: 6px;">أدخل اسم كلاس أيقونة Font Awesome (مثلاً: fa-fan, fa-plug, fa-wrench)</p>
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">فئة لون الخدمة</label>
                        <div style="position: relative;">
                            <input type="text" name="color_class" value="{{ old('color_class') }}"
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="مثال: blue">
                            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                        </div>
                        @error('color_class')
                            <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                        @enderror
                        <p style="color: #718096; font-size: 13px; margin-top: 6px;">أدخل اسم الفئة المعرفة مسبقاً (مثلاً: blue, yellow, cyan)</p>
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">اسم الرابط (Route)</label>
                        <div style="position: relative;">
                            <input type="text" name="route_name" value="{{ old('route_name') }}"
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="مثال: service.show">
                            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </div>
                        @error('route_name')
                            <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div style="padding-top: 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 16px;">
                <a href="{{ route('admin.services.index') }}"
                    style="padding: 12px 24px; border-radius: 10px; border: 1px solid #e2e8f0; background: white; color: #4a5568; text-decoration: none; font-weight: 600; transition: all 0.2s;"
                    onmouseover="this.style.background='#f7fafc'; this.style.borderColor='#cbd5e0';"
                    onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0';">
                    إلغاء
                </a>
                <button type="submit" class="btn-primary rounded-3" >
                    حفظ الخدمة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection