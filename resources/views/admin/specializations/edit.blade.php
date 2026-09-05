@extends('layouts.admin')

@section('title', 'تعديل تخصص')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 24px;">
    <div style="margin-bottom: 36px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 32px; margin: 0 0 8px; color: #2d3748; font-weight: 700;">تعديل تخصص</h1>
            <p style="color: #718096; margin: 0; font-size: 16px;">قم بتعديل بيانات التخصص ثم احفظ التغييرات</p>
        </div>
        <a href="{{ route('admin.specializations.index') }}" 
           style="background: white; border: 1px solid #e2e8f0; color: #4a5568; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.05);"
           onmouseover="this.style.background='#f7fafc'; this.style.transform='translateY(-1px)';"
           onmouseout="this.style.background='white'; this.style.transform='translateY(0)';">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7"/>
            </svg>
            عودة للقائمة
        </a>
    </div>
    
    <div class="card" style="padding: 0; overflow: hidden; border: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border-radius: 16px;">
        <div style="background: linear-gradient(135deg, #2a6592 0%, #3498db 50%, #f39c12 100%); padding: 32px; color: white;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 24px; font-weight: 700; margin: 0 0 6px;">بيانات التخصص</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 15px;">يمكنك تعديل بيانات التخصص وحفظ التغييرات</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.specializations.update', $specialization->id) }}" style="padding: 40px;">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 40px; background: #f8fafc; border-radius: 12px; padding: 28px;">
                <h4 style="font-size: 18px; color: #4a5568; font-weight: 700; margin-bottom: 24px; padding-bottom: 12px; border-bottom: 2px solid #e2e8f0; display: flex; align-items: center; gap: 12px;">
                    <span style="background: #10b981; color: white; width: 28px; height: 28px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700;">01</span>
                    تفاصيل التخصص
                </h4>
                
                <div style="display: grid; grid-template-columns: 1fr; gap: 24px;">
                    <div>
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #4a5568; font-size: 15px;">اسم التخصص <span style="color: #e53e3e;">*</span></label>
                        <div style="position: relative;">
                            <input type="text" name="name" value="{{ old('name', $specialization->name) }}" required
                                style="width: 100%; padding: 14px 18px; padding-left: 48px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: all 0.2s; background: white;"
                                onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='white'; this.style.boxShadow='none';"
                                placeholder="مثال: كهرباء – سباكة – نجارة">
                            <svg style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        @error('name')
                            <p style="color: #e53e3e; font-size: 14px; margin-top: 8px; background: #fef2f2; padding: 8px 12px; border-radius: 6px; border-right: 3px solid #e53e3e;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #4a5568; font-size: 15px;">الوصف (اختياري)</label>
                        <div style="position: relative;">
                            <textarea name="description" rows="4"
                                style="width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: all 0.2s; background: white; resize: vertical;"
                                onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='white'; this.style.boxShadow='none';"
                                placeholder="وصف مختصر للتخصص...">{{ old('description', $specialization->description) }}</textarea>
                        </div>
                        @error('description')
                            <p style="color: #e53e3e; font-size: 14px; margin-top: 8px; background: #fef2f2; padding: 8px 12px; border-radius: 6px; border-right: 3px solid #e53e3e;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="display: flex; align-items: center; gap: 12px;">
                        <input type="checkbox" name="is_active" value="1" id="isActive" 
                            style="width: 20px; height: 20px; accent-color: #10b981;"
                            {{ old('is_active', $specialization->is_active) ? 'checked' : '' }}>
                        <label for="isActive" style="font-weight: 600; color: #4a5568; font-size: 15px; cursor: pointer;">تفعيل التخصص</label>
                    </div>
                </div>
            </div>
            
            <div style="padding-top: 32px; border-top: 2px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 16px;">
                <a href="{{ route('admin.specializations.index') }}"
                    style="padding: 14px 32px; border-radius: 10px; border: 2px solid #e2e8f0; background: white; color: #4a5568; text-decoration: none; font-weight: 700; transition: all 0.3s ease; font-size: 16px;"
                    onmouseover="this.style.background='#f7fafc'; this.style.borderColor='#cbd5e0'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';"
                    onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    إلغاء
                </a>
                <button type="submit" 
                    style="padding: 14px 32px; border-radius: 10px; border: none; background: linear-gradient(135deg, #2a6592 0%, #3498db 50%, #f39c12 100%); color: white; font-weight: 700; transition: all 0.3s ease; font-size: 16px; cursor: pointer; box-shadow: 0 4px 15px rgba(42, 101, 146, 0.3);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(42, 101, 146, 0.4)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(42, 101, 146, 0.3)';">
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
