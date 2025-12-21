@extends('layouts.admin')

@section('title', 'تعديل المراجعة')
@section('header_title', 'تعديل المراجعة')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">تعديل المراجعة #{{ $review->id }}</h1>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>عودة إلى القائمة
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">نموذج تعديل المراجعة</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reviews.update', $review) }}" method="POST">
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
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-2"></i>إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>تحديث المراجعة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection