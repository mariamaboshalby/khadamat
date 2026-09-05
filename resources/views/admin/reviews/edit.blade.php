@extends('layouts.admin')

@section('title', 'تعديل المراجعة')
@section('header_title', 'تعديل المراجعة')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 24px;">
    <div style="margin-bottom: 36px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 32px; margin: 0 0 8px; color: #2d3748; font-weight: 700;">تعديل المراجعة #{{ $review->id }}</h1>
            <p style="color: #718096; margin: 0; font-size: 16px;">تحديث بيانات المراجعة</p>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 24px; font-weight: 700; margin: 0 0 6px;">بيانات المراجعة</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 15px;">يمكنك تعديل بيانات المراجعة وحفظ التغييرات</p>
                </div>
            </div>
        </div>

        <div class="card-body" style="padding: 40px;">
            <form action="{{ route('admin.reviews.update', \App\Helpers\EncryptionHelper::encryptId($review->id)) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="user_id" class="form-label">المستخدم <span class="text-danger">*</span></label>
                        <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                            <option value="">اختر المستخدم</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ (old('user_id', $review->user_id) == $user->id) ? 'selected' : '' }}>
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
                                <option value="{{ $technician->id }}" {{ (old('technician_id', $review->technician_id) == $technician->id) ? 'selected' : '' }}>
                                    {{ $technician->user->name }} - {{ $technician->specialization->name ?? 'بدون تخصص' }}
                                </option>
                            @endforeach
                        </select>
                        @error('technician_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="rating" class="form-label">التقييم <span class="text-danger">*</span></label>
                        <select name="rating" id="rating" class="form-control @error('rating') is-invalid @enderror" required>
                            <option value="">اختر التقييم</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ (old('rating', $review->rating) == $i) ? 'selected' : '' }}>
                                    {{ $i }} نجمة{{ $i > 1 ? 'ات' : 'ة' }}
                                </option>
                            @endfor
                        </select>
                        @error('rating')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="">اختر الحالة</option>
                            <option value="pending" {{ (old('status', $review->status) == 'pending') ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="approved" {{ (old('status', $review->status) == 'approved') ? 'selected' : '' }}>موافق عليه</option>
                            <option value="rejected" {{ (old('status', $review->status) == 'rejected') ? 'selected' : '' }}>مرفوض</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">العنوان</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $review->title) }}" placeholder="عنوان المراجعة">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="comment" class="form-label">التعليق</label>
                    <textarea name="comment" id="comment" rows="5" class="form-control @error('comment') is-invalid @enderror" placeholder="نص المراجعة">{{ old('comment', $review->comment) }}</textarea>
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
                        تحديث المراجعة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection