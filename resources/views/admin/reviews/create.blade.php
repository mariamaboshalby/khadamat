@extends('layouts.admin')

@section('title', 'إضافة مراجعة جديدة')
@section('header_title', 'إضافة مراجعة جديدة')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 24px;">
    <div style="margin-bottom: 36px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 32px; margin: 0 0 8px; color: #2d3748; font-weight: 700;">إضافة مراجعة جديدة</h1>
            <p style="color: #718096; margin: 0; font-size: 16px;">إضافة مراجعة جديدة للنظام</p>
        </div>
        <a href="{{ route('admin.reviews.index') }}" 
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 24px; font-weight: 700; margin: 0 0 6px;">بيانات المراجعة</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 15px;">يرجى ملء جميع الحقول المطلوبة بدقة</p>
                </div>
            </div>
        </div>

        <div class="card-body" style="padding: 40px;">
            <form action="{{ route('admin.reviews.store') }}" method="POST">
                @csrf
                <div class="col-md-6 mb-3">
                    <label for="user_id" class="form-label">المستخدم <span class="text-danger">*</span></label>
                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                        <option value="">اختر المستخدم</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->phone }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="technician_id" class="form-label">الفني <span class="text-danger">*</span></label>
                    <select name="technician_id" id="technician_id" class="form-control @error('technician_id') is-invalid @enderror" required>
                        <option value="">اختر الفني</option>
                        @foreach($technicians as $technician)
                            <option value="{{ $technician->id }}" {{ old('technician_id') == $technician->id ? 'selected' : '' }}>
                                {{ $technician->user->name }} - {{ $technician->specialization->name ?? 'بدون تخصص' }}
                            </option>
                        @endforeach
                    </select>
                    @error('technician_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="rating" class="form-label">التقييم <span class="text-danger">*</span></label>
                        <select name="rating" id="rating" class="form-control @error('rating') is-invalid @enderror" required>
                            <option value="">اختر التقييم</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} نجمة{{ $i > 1 ? 'ات' : 'ة' }}
                                </option>
                            @endfor
                        </select>
                        @error('rating')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">العنوان</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="عنوان المراجعة">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="comment" class="form-label">التعليق</label>
                    <textarea name="comment" id="comment" rows="5" class="form-control @error('comment') is-invalid @enderror" placeholder="نص المراجعة">{{ old('comment') }}</textarea>
                    @error('comment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div style="padding-top: 32px; border-top: 2px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 16px;">
                    <a href="{{ route('admin.reviews.index') }}"
                        style="padding: 14px 32px; border-radius: 10px; border: 2px solid #e2e8f0; background: white; color: #4a5568; text-decoration: none; font-weight: 700; transition: all 0.3s ease; font-size: 16px;"
                        onmouseover="this.style.background='#f7fafc'; this.style.borderColor='#cbd5e0'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';"
                        onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        إلغاء
                    </a>
                    <button type="submit"
                        style="padding: 14px 32px; border-radius: 10px; border: none; background: linear-gradient(135deg, #2a6592 0%, #3498db 50%, #f39c12 100%); color: white; font-weight: 700; transition: all 0.3s ease; font-size: 16px; cursor: pointer; box-shadow: 0 4px 15px rgba(42, 101, 146, 0.3);"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(42, 101, 146, 0.4)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(42, 101, 146, 0.3)';">
                        حفظ المراجعة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection