@extends('layouts.admin')

@section('title', 'تعديل العرض')
@section('header_title', 'تعديل العرض')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 28px; margin: 0 0 10px; color: #2d3748; font-weight: 700;">تعديل العرض</h1>
            <p style="color: #718096; margin: 0;">تحديث بيانات العرض: {{ $offer->title }}</p>
        </div>
        <a href="{{ route('admin.offers.index') }}" 
           style="background: white; border: 1px solid #e2e8f0; color: #4a5568; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            عودة للقائمة
        </a>
    </div>
    
    <div class="card" style="padding: 0; overflow: hidden; border: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
        <div style="background: linear-gradient(135deg, #ff7373 0%, #e54343 100%); padding: 24px; color: white;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 20px; font-weight: 700; margin: 0 0 4px;">نموذج تعديل العرض</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">يمكنك تعديل بيانات العرض وحفظ التغييرات</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.offers.update', $encryptedId) }}" style="padding: 32px;">
            @csrf
            @method('PUT')
            
            <!-- Offer Details Section -->
            <div style="margin-bottom: 32px;">
                <h4 style="font-size: 16px; color: #4a5568; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f7fafc;">
                    <span style="color: #667eea;">01.</span> تفاصيل العرض
                </h4>
                
                <div style="display: grid; grid-template-columns: 1fr; gap: 24px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">عنوان العرض <span style="color: #e53e3e;">*</span></label>
                        <div style="position: relative;">
                            <input type="text" name="title" value="{{ old('title', $offer->title) }}" required
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="مثال: خصم 20% على جميع الخدمات">
                            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        @error('title')
                            <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">العنوان الفرعي 1</label>
                            <div style="position: relative;">
                                <input type="text" name="subtitle_1" value="{{ old('subtitle_1', $offer->subtitle_1) }}"
                                    style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                    onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                    placeholder="مثال: عرض لفترة محدودة">
                                <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                            </div>
                            @error('subtitle_1')
                                <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">العنوان الفرعي 2</label>
                            <div style="position: relative;">
                                <input type="text" name="subtitle_2" value="{{ old('subtitle_2', $offer->subtitle_2) }}"
                                    style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                    onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                    placeholder="مثال: حتى نهاية الشهر">
                                <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                            </div>
                            @error('subtitle_2')
                                <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Discount Details Section -->
            <div style="margin-bottom: 32px;">
                <h4 style="font-size: 16px; color: #4a5568; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f7fafc;">
                    <span style="color: #667eea;">02.</span> تفاصيل الخصم
                </h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">نص الشارة</label>
                        <div style="position: relative;">
                            <input type="text" name="badge_text" value="{{ old('badge_text', $offer->badge_text) }}"
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="مثال: عرض خاص">
                            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </div>
                        @error('badge_text')
                            <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">قيمة الخصم</label>
                        <div style="position: relative;">
                            <input type="text" name="discount_value" value="{{ old('discount_value', $offer->discount_value) }}"
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="مثال: 20% أو 50 ريال">
                            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        @error('discount_value')
                            <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">تسمية الخصم</label>
                        <div style="position: relative;">
                            <input type="text" name="discount_label" value="{{ old('discount_label', $offer->discount_label) }}"
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="مثال: خصم">
                            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        @error('discount_label')
                            <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">فئة لون الخصم</label>
                        <div style="position: relative;">
                            <input type="text" name="discount_color_class" value="{{ old('discount_color_class', $offer->discount_color_class) }}"
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="مثال: text-green-500">
                            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                        </div>
                        @error('discount_color_class')
                            <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Icon Section -->
            <div style="margin-bottom: 32px;">
                <h4 style="font-size: 16px; color: #4a5568; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f7fafc;">
                    <span style="color: #667eea;">03.</span> الأيقونة
                </h4>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">رمز الأيقونة (Font Awesome)</label>
                    <div style="position: relative;">
                        <input type="text" name="icon" value="{{ old('icon', $offer->icon) }}"
                            style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                            onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                            placeholder="مثال: fa-percent">
                        <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5"/>
                        </svg>
                    </div>
                    @error('icon')
                        <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                    @enderror
                    <p style="color: #718096; font-size: 13px; margin-top: 6px;">أدخل اسم كلاس أيقونة Font Awesome (مثلاً: fa-percent, fa-gift, fa-tag)</p>
                </div>
            </div>
            
            <div style="padding-top: 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 16px;">
                <a href="{{ route('admin.offers.index') }}"
                    style="padding: 12px 24px; border-radius: 10px; border: 1px solid #e2e8f0; background: white; color: #4a5568; text-decoration: none; font-weight: 600; transition: all 0.2s;"
                    onmouseover="this.style.background='#f7fafc'; this.style.borderColor='#cbd5e0';"
                    onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0';">
                    إلغاء
                </a>
                <button type="submit" class="btn-primary rounded-3" >
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection