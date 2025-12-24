@extends('layouts.admin')

@section('title', 'تعديل بيانات الفني')
@section('header_title', 'تعديل بيانات الفني')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 28px; margin: 0 0 10px; color: #2d3748; font-weight: 700;">تعديل بيانات الفني</h1>
            <p style="color: #718096; margin: 0;">تحديث بيانات الفني: {{ $technician->user->name ?? 'غير معروف' }}</p>
        </div>
        <a href="{{ route('admin.techs.index') }}" class="btn btn-outline-secondary" >
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            عودة للقائمة
        </a>
    </div>

    <div class="card" style="padding: 0; overflow: hidden; border: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
        <div style="background: linear-gradient(135deg, #ff7373 0%, #cc3333 100%); padding: 24px; color: white;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 20px; font-weight: 700; margin: 0 0 4px;">نموذج تعديل بيانات الفني</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">يمكنك تعديل بيانات الفني وحفظ التغييرات</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.techs.update', \App\Helpers\EncryptionHelper::encryptId($technician->id)) }}" style="padding: 32px;">
            @csrf
            @method('PUT')

            {{-- Personal Info Section --}}
            <div style="margin-bottom: 32px;">
                <h4 style="font-size: 16px; color: #4a5568; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f7fafc;">
                    <span style="color: #667eea;">01.</span> المعلومات الشخصية
                </h4>

                <div style="display: grid; grid-template-columns: 1fr; gap: 24px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">الاسم الكامل <span style="color: #e53e3e;">*</span></label>
                        <div style="position: relative;">
                            <input type="text" name="name" value="{{ old('name', $technician->user->name) }}" required
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="أدخل اسم الفني">
                            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        @error('name')
                            <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">البريد الإلكتروني <span style="color: #e53e3e;">*</span></label>
                            <div style="position: relative;">
                                <input type="email" name="email" value="{{ old('email', $technician->user->email) }}" required
                                    style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                    onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
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
                                <input type="text" name="phone" value="{{ old('phone', $technician->user->phone) }}" required
                                    style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                    onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
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

            {{-- Job Info Section --}}
            <div style="margin-bottom: 32px;">
                <h4 style="font-size: 16px; color: #4a5568; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f7fafc;">
                    <span style="color: #667eea;">02.</span> المعلومات الوظيفية
                </h4>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">الدور الوظيفي <span style="color: #e53e3e;">*</span></label>
                        <div style="position: relative;">
                            <select name="role" required
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';">
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ old('role', $currentRole) == $role ? 'selected' : '' }}>
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
                                onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';">
                                @foreach($specializations as $specialization)
                                    <option value="{{ $specialization->id }}" {{ old('specialization_id', $technician->specialization_id) == $specialization->id ? 'selected' : '' }}>
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

                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">حالة التوفر <span style="color: #e53e3e;">*</span></label>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="radio" name="availability_status" value="available" {{ old('availability_status', $technician->availability_status) == 'available' ? 'checked' : '' }}>
                            <span>متاح</span>
                        </label>
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="radio" name="availability_status" value="busy" {{ old('availability_status', $technician->availability_status) == 'busy' ? 'checked' : '' }}>
                            <span>مشغول</span>
                        </label>
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="radio" name="availability_status" value="on_leave" {{ old('availability_status', $technician->availability_status) == 'on_leave' ? 'checked' : '' }}>
                            <span>في إجازة</span>
                        </label>
                    </div>
                    @error('availability_status')
                        <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">نبذة عن الفني (اختياري)</label>
                    <textarea name="bio" rows="4"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; resize: vertical; background: #f8fafc; transition: all 0.2s;"
                        onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                        placeholder="أدخل أي معلومات إضافية أو خبرات سابقة للفني...">{{ old('bio', $technician->bio) }}</textarea>
                    @error('bio')
                        <p style="color: #e53e3e; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Location Section --}}
            <div style="margin-bottom: 32px;">
                <h4 style="font-size: 16px; color: #4a5568; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f7fafc;">
                    <span style="color: #667eea;">03.</span> الموقع الجغرافي
                </h4>

                <div style="margin-bottom: 20px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                        <button type="button" onclick="event.preventDefault(); getCurrentLocation();" 
                            class="location-btn"
                            style="padding: 16px 20px; border: 2px solid #667eea; background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(102, 126, 234, 0.05) 100%); color: #667eea; border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 10px; position: relative; overflow: hidden;">
                            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="transition: transform 0.3s ease;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span style="font-size: 15px;">📍 تحديد موقعي الحالي</span>
                        </button>
                        <button type="button" onclick="event.preventDefault(); toggleMapView();" 
                            class="map-btn"
                            style="padding: 16px 20px; border: 2px solid #10b981; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%); color: #10b981; border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 10px; position: relative; overflow: hidden;">
                            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="transition: transform 0.3s ease;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <span style="font-size: 15px;">🗺️ اختيار من الخريطة</span>
                        </button>
                    </div>
                    
                    <div id="locationStatus" style="padding: 14px 18px; border-radius: 10px; font-size: 14px; display: none; margin-bottom: 16px; animation: slideDown 0.3s ease;"></div>
                </div>

                {{-- Map Container --}}
                <div id="mapContainer" style="display: none; margin-bottom: 24px; animation: slideDown 0.4s ease;">
                    <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #4a5568; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            ابحث عن موقع
                        </label>
                        <div style="position: relative;">
                            <input type="text" id="searchMap" 
                                style="width: 100%; padding: 14px 20px; padding-right: 45px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.3s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(102, 126, 234, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                                placeholder="ابحث عن مدينة، حي، أو شارع...">
                            <svg style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #a0aec0; pointer-events: none;" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div id="searchResults" style="background: white; border: 2px solid #e2e8f0; border-radius: 10px; margin-top: 10px; max-height: 250px; overflow-y: auto; display: none; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);"></div>
                    </div>
                    <div id="map" style="width: 100%; height: 450px; border-radius: 12px; border: 3px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);"></div>
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 16px; border-radius: 10px; margin-top: 12px; display: flex; align-items: center; gap: 10px; font-size: 13px;">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span>💡 نصيحة: انقر على الخريطة أو اسحب العلامة لتحديد الموقع بدقة</span>
                    </div>
                </div>

                <div hidden style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">خط العرض (Latitude)</label>
                        <div style="position: relative;">
                            <input type="number" step="any" name="latitude" hidden id="latitude" value="{{ old('latitude', $technician->latitude) }}"
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
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
                            <input type="number" step="any" name="longitude" hidden id="longitude" value="{{ old('longitude', $technician->longitude) }}"
                                style="width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; transition: all 0.2s; background: #f8fafc;"
                                onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
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

                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">العنوان التفصيلي (اختياري)</label>
                    <div style="position: relative;">
                        <textarea name="address" id="address" rows="3"
                            style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; resize: vertical; background: #f8fafc; transition: all 0.2s;"
                            onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
                            placeholder="أدخل العنوان التفصيلي للفني...">{{ old('address', $technician->address) }}</textarea>
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

            {{-- Badges Section --}}
            <div style="margin-bottom: 32px;">
                <h4 style="font-size: 16px; color: #4a5568; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f7fafc;">
                    <span style="color: #667eea;">04.</span> الشارات
                </h4>
                
                <div id="badgesContainer">
                    @foreach($technician->badges as $index => $badge)
                    <div class="badge-item" id="badge-{{ $index }}" style="background: #f8fafc; padding: 20px; border-radius: 10px; margin-bottom: 16px; border: 1px solid #e2e8f0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <h5 style="margin: 0; color: #2d3748; font-size: 15px; font-weight: 600;">شارة #{{ $index + 1 }}</h5>
                            <button type="button" onclick="removeBadge({{ $index }})" 
                                style="background: #fee2e2; color: #dc2626; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;">
                                حذف
                            </button>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px;">مستوى الخبرة</label>
                            <select name="badges[]" required
                                style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 15px; background: white;">
                                <option value="">اختر المستوى</option>
                                <option value="beginner" {{ $badge->level == 'beginner' ? 'selected' : '' }}>مبتدئ</option>
                                <option value="intermediate" {{ $badge->level == 'intermediate' ? 'selected' : '' }}>متوسط</option>
                                <option value="expert" {{ $badge->level == 'expert' ? 'selected' : '' }}>خبير</option>
                                <option value="master" {{ $badge->level == 'master' ? 'selected' : '' }}>متمكن</option>
                            </select>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <button type="button" onclick="addBadge()" 
                    style="width: 100%; padding: 12px; border: 2px dashed #667eea; background: rgba(102, 126, 234, 0.05); color: #667eea; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px;"
                    onmouseover="this.style.background='rgba(102, 126, 234, 0.1)';"
                    onmouseout="this.style.background='rgba(102, 126, 234, 0.05)';">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    إضافة شارة
                </button>
            </div>

            <div style="padding-top: 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 16px;">
                <a href="{{ route('admin.techs.show', \App\Helpers\EncryptionHelper::encryptId($technician->id)) }}" class="btn btn-outline-secondary">
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

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

@keyframes ripple {
    0% {
        transform: scale(0);
        opacity: 1;
    }
    100% {
        transform: scale(4);
        opacity: 0;
    }
}

.location-btn:hover, .map-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.2);
}

.location-btn:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(102, 126, 234, 0.1) 100%) !important;
    border-color: #5a67d8;
}

.map-btn:hover {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(16, 185, 129, 0.1) 100%) !important;
    border-color: #059669;
}

.location-btn:active, .map-btn:active {
    transform: translateY(0);
}

.location-btn svg, .map-btn svg {
    transition: transform 0.3s ease;
}

.location-btn:hover svg {
    transform: scale(1.2) rotate(10deg);
}

.map-btn:hover svg {
    transform: scale(1.2);
}

.location-btn::before, .map-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.location-btn:hover::before, .map-btn:hover::before {
    width: 300px;
    height: 300px;
}

/* Search Results Animation */
#searchResults > div {
    animation: slideDown 0.2s ease;
}

/* Loading Spinner */
.spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Map Marker Bounce */
.leaflet-marker-icon {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

/* Smooth transitions for all inputs */
input, textarea, select {
    transition: all 0.3s ease !important;
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map = null;
let marker = null;
let searchTimeout = null;
let badgeIndex = {{ $technician->badges->count() }};

function getCurrentLocation() {
    const statusDiv = document.getElementById('locationStatus');
    const latitudeField = document.getElementById('latitude');
    const longitudeField = document.getElementById('longitude');
    
    statusDiv.style.display = 'block';
    statusDiv.style.background = 'linear-gradient(135deg, rgba(204, 51, 51, 0.1) 0%, rgba(204, 51, 51, 0.05) 100%)';
    statusDiv.style.border = '2px solid rgba(204, 51, 51, 0.3)';
    statusDiv.style.color = '#cc3333';
    statusDiv.innerHTML = '<div style="display: flex; align-items: center; gap: 10px;"><div class="spinner"></div><span style="font-weight: 600;">🔍 جاري تحديد موقعك الحالي...</span></div>';

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                latitudeField.value = lat.toFixed(6);
                longitudeField.value = lng.toFixed(6);
                
                // Highlight the fields
                latitudeField.style.background = 'rgba(16, 185, 129, 0.1)';
                longitudeField.style.background = 'rgba(16, 185, 129, 0.1)';
                setTimeout(() => {
                    latitudeField.style.background = '#f8fafc';
                    longitudeField.style.background = '#f8fafc';
                }, 2000);
                
                statusDiv.style.background = 'linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%)';
                statusDiv.style.border = '2px solid rgba(16, 185, 129, 0.4)';
                statusDiv.style.color = '#10b981';
                statusDiv.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <svg width="22" height="22" fill="currentColor" viewBox="0 0 20 20" style="flex-shrink: 0;">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <div style="font-weight: 700; margin-bottom: 2px;">✅ تم تحديد الموقع بنجاح!</div>
                            <div style="font-size: 12px; opacity: 0.9;">خط العرض: ${lat.toFixed(6)} | خط الطول: ${lng.toFixed(6)}</div>
                        </div>
                    </div>
                `;
                
                // Update map if open
                if (map) {
                    updateMapMarker(lat, lng);
                }
                
                // Get address
                getAddressFromCoordinates(lat, lng);
            },
            function(error) {
                let errorMessage = 'فشل تحديد الموقع: ';
                let errorDetails = '';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage += 'تم رفض الإذن';
                        errorDetails = 'يرجى السماح للمتصفح بالوصول إلى موقعك';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage += 'معلومات الموقع غير متاحة';
                        errorDetails = 'تأكد من تفعيل خدمات الموقع';
                        break;
                    case error.TIMEOUT:
                        errorMessage += 'انتهت مهلة الطلب';
                        errorDetails = 'حاول مرة أخرى';
                        break;
                    default:
                        errorMessage += 'حدث خطأ غير معروف';
                        errorDetails = 'استخدم الخريطة بدلاً من ذلك';
                        break;
                }
                
                statusDiv.style.background = 'linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(239, 68, 68, 0.05) 100%)';
                statusDiv.style.border = '2px solid rgba(239, 68, 68, 0.4)';
                statusDiv.style.color = '#ef4444';
                statusDiv.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <svg width="22" height="22" fill="currentColor" viewBox="0 0 20 20" style="flex-shrink: 0;">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <div style="font-weight: 700; margin-bottom: 2px;">❌ ${errorMessage}</div>
                            <div style="font-size: 12px; opacity: 0.9;">${errorDetails}</div>
                        </div>
                    </div>
                `;
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        statusDiv.style.background = 'linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(239, 68, 68, 0.05) 100%)';
        statusDiv.style.border = '2px solid rgba(239, 68, 68, 0.4)';
        statusDiv.style.color = '#ef4444';
        statusDiv.innerHTML = `
            <div style="display: flex; align-items: center; gap: 10px;">
                <svg width="22" height="22" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <div style="font-weight: 700;">❌ المتصفح لا يدعم تحديد الموقع</div>
                </div>
            </div>
        `;
    }
}

function toggleMapView() {
    const mapContainer = document.getElementById('mapContainer');
    const statusDiv = document.getElementById('locationStatus');
    
    if (mapContainer.style.display === 'none') {
        mapContainer.style.display = 'block';
        statusDiv.style.display = 'block';
        statusDiv.style.background = 'rgba(16, 185, 129, 0.1)';
        statusDiv.style.border = '1px solid rgba(16, 185, 129, 0.3)';
        statusDiv.style.color = '#10b981';
        statusDiv.innerHTML = '🗺️ الخريطة مفتوحة - انقر على الخريطة لتحديد الموقع';
        
        if (!map) {
            initMap();
        }
    } else {
        mapContainer.style.display = 'none';
        statusDiv.style.display = 'none';
    }
}

function initMap() {
    const lat = document.getElementById('latitude').value || 24.7136;
    const lng = document.getElementById('longitude').value || 46.6753;
    
    map = L.map('map').setView([lat, lng], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Add marker if coordinates exist
    if (document.getElementById('latitude').value && document.getElementById('longitude').value) {
        marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);
        
        marker.on('dragend', function(e) {
            const pos = marker.getLatLng();
            updateCoordinates(pos.lat, pos.lng);
        });
    }
    
    // Click on map to add/move marker
    map.on('click', function(e) {
        updateMapMarker(e.latlng.lat, e.latlng.lng);
    });
}

function updateMapMarker(lat, lng) {
    if (!map) return;
    
    if (marker) {
        marker.setLatLng([lat, lng]);
    } else {
        marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);
        
        marker.on('dragend', function(e) {
            const pos = marker.getLatLng();
            updateCoordinates(pos.lat, pos.lng);
        });
    }
    
    map.setView([lat, lng], 13);
    updateCoordinates(lat, lng);
}

function updateCoordinates(lat, lng) {
    document.getElementById('latitude').value = lat.toFixed(6);
    document.getElementById('longitude').value = lng.toFixed(6);
    getAddressFromCoordinates(lat, lng);
}

function getAddressFromCoordinates(lat, lng) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=ar`)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                document.getElementById('address').value = data.display_name;
            }
        })
        .catch(error => {
            console.log('Error getting address:', error);
        });
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchMap');
    const searchResults = document.getElementById('searchResults');
    
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value;
            
            if (query.length < 3) {
                searchResults.style.display = 'none';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                searchLocation(query);
            }, 500);
        });
    }
});

function searchLocation(query) {
    const searchResults = document.getElementById('searchResults');
    searchResults.innerHTML = `
        <div style="padding: 16px; text-align: center; color: #667eea;">
            <div class="spinner" style="margin: 0 auto 8px;"></div>
            <div style="font-weight: 600;">جاري البحث...</div>
        </div>
    `;
    searchResults.style.display = 'block';
    
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&accept-language=ar&countrycodes=sa&limit=5`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                searchResults.innerHTML = `
                    <div style="padding: 20px; text-align: center; color: #718096;">
                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 10px; opacity: 0.5;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div style="font-weight: 600; margin-bottom: 4px;">لا توجد نتائج</div>
                        <div style="font-size: 13px;">جرب البحث بكلمات أخرى</div>
                    </div>
                `;
                return;
            }
            
            let html = '';
            data.forEach((item, index) => {
                html += `
                    <div onclick="selectLocation(${item.lat}, ${item.lon}, '${item.display_name.replace(/'/g, "\\'")}')"
                         style="padding: 14px 16px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: all 0.2s; display: flex; align-items: start; gap: 12px;"
                         onmouseover="this.style.background='linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(102, 126, 234, 0.02) 100%)'; this.style.transform='translateX(4px)'"
                         onmouseout="this.style.background='white'; this.style.transform='translateX(0)'">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style="flex-shrink: 0; margin-top: 2px; color: #667eea;">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: 600; color: #2d3748; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${item.display_name.split(',')[0]}</div>
                            <div style="font-size: 12px; color: #718096; line-height: 1.4;">${item.display_name}</div>
                        </div>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0; margin-top: 4px; color: #a0aec0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </div>
                `;
            });
            
            searchResults.innerHTML = html;
        })
        .catch(error => {
            searchResults.innerHTML = `
                <div style="padding: 20px; text-align: center; color: #ef4444;">
                    <svg width="48" height="48" fill="currentColor" viewBox="0 0 20 20" style="margin: 0 auto 10px; opacity: 0.5;">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div style="font-weight: 600;">حدث خطأ في البحث</div>
                </div>
            `;
        });
}

function selectLocation(lat, lng, address) {
    const searchResults = document.getElementById('searchResults');
    const searchInput = document.getElementById('searchMap');
    
    searchResults.style.display = 'none';
    searchInput.value = address.split(',')[0];
    
    // Animate selection
    searchInput.style.background = 'rgba(16, 185, 129, 0.1)';
    searchInput.style.borderColor = '#10b981';
    setTimeout(() => {
        searchInput.style.background = '#f8fafc';
        searchInput.style.borderColor = '#e2e8f0';
    }, 1500);
    
    updateMapMarker(lat, lng);
    
    const statusDiv = document.getElementById('locationStatus');
    statusDiv.style.display = 'block';
    statusDiv.style.background = 'linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%)';
    statusDiv.style.border = '2px solid rgba(16, 185, 129, 0.4)';
    statusDiv.style.color = '#10b981';
    statusDiv.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px;">
            <svg width="22" height="22" fill="currentColor" viewBox="0 0 20 20" style="flex-shrink: 0;">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>
                <div style="font-weight: 700; margin-bottom: 2px;">✅ تم اختيار الموقع من البحث</div>
                <div style="font-size: 12px; opacity: 0.9;">${address.split(',').slice(0, 2).join(', ')}</div>
            </div>
        </div>
    `;
}

// Badges functionality
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
</script>
@endpush
