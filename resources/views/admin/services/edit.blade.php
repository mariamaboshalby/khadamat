@extends('layouts.admin')

@section('title', 'تعديل الخدمة')
@section('header_title', 'تعديل الخدمة')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 28px; margin: 0 0 10px; color: #2d3748; font-weight: 700;">تعديل الخدمة</h1>
            <p style="color: #718096; margin: 0;">تحديث بيانات الخدمة: {{ $service->name }}</p>
        </div>
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            عودة للقائمة
        </a>
    </div>
    
    <div class="card" style="padding: 0; overflow: hidden; border: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
        <div style="background: linear-gradient(135deg, #ff7373 0%, #0b5f8a 100%); padding: 24px; color: white;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 20px; font-weight: 700; margin: 0 0 4px;">نموذج تعديل الخدمة</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">يمكنك تعديل بيانات الخدمة وحفظ التغييرات</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.services.update', $encryptedId) }}" style="padding: 32px;">
            @csrf
            @method('PUT')
            
            <!-- Service Details Section -->
            <div style="margin-bottom: 32px;">
                <h4 style="font-size: 16px; color: #4a5568; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f7fafc;">
                    <span style="color: #ff7373;">01.</span> تفاصيل الخدمة
                </h4>
                
                <div style="display: grid; grid-template-columns: 1fr; gap: 24px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">اسم الخدمة <span style="color: #e53e3e;">*</span></label>
                        <div style="position: relative;">
                            <input type="text" name="name" value="{{ old('name', $service->name) }}" required
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
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
                                onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';">
                                <option value="">اختر التخصص</option>
                                @foreach($specializations as $specialization)
                                    <option value="{{ $specialization->id }}" {{ old('specialization_id', $service->specialization_id) == $specialization->id ? 'selected' : '' }}>
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
                    <span style="color: #ff7373;">02.</span> التصميم والتنسيق
                </h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">رمز الأيقونة (Font Awesome)</label>
                        <div style="position: relative;">
                            <input type="text" name="icon" value="{{ old('icon', $service->icon) }}"
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="مثال: fa-fan">
                        </div>
                        @error('icon')
                            <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                        @enderror
                        <p style="color: #718096; font-size: 13px; margin-top: 6px;">أدخل اسم كلاس أيقونة Font Awesome (مثلاً: fa-fan, fa-plug, fa-wrench)</p>
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">فئة لون الخدمة</label>
                        <div style="position: relative;">
                            <input type="text" name="color_class" value="{{ old('color_class', $service->color_class) }}"
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="مثال: blue">
                        </div>
                        @error('color_class')
                            <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                        @enderror
                        <p style="color: #718096; font-size: 13px; margin-top: 6px;">أدخل اسم الفئة المعرفة مسبقاً (مثلاً: blue, yellow, cyan)</p>
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">اسم الرابط (Route)</label>
                        <div style="position: relative;">
                            <input type="text" name="route_name" value="{{ old('route_name', $service->route_name) }}"
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="مثال: service.show">
                        </div>
                        @error('route_name')
                            <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div style="padding-top: 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 16px;">
                <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                    إلغاء
                </a>
                <button type="submit" class="btn-primary rounded-3">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
