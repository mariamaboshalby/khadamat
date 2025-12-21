@extends('layouts.admin')

@section('title', 'إضافة مراجعة جديدة')
@section('header_title', 'إضافة مراجعة جديدة')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">إضافة مراجعة جديدة</h1>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>عودة إلى القائمة
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">نموذج إضافة مراجعة</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reviews.store') }}" method="POST">
                @csrf
                
                <div class="row">
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
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>حفظ المراجعة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection