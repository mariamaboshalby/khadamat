@extends('layouts.admin')

@section('title', 'إدارة المراجعات')
@section('header_title', 'إدارة المراجعات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">جميع المراجعات</h1>
        <div>
            <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>إضافة مراجعة جديدة
            </a>
            <a href="{{ route('admin.reviews.export') }}" class="btn btn-success">
                <i class="fas fa-download me-2"></i>تصدير البيانات
            </a>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">قائمة المراجعات</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="reviewsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المستخدم</th>
                            <th>الفني</th>
                            <th>التقييم</th>
                            <th>العنوان</th>
                            <th>الحالة</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td>{{ $review->id }}</td>
                            <td>{{ $review->user->name ?? 'N/A' }}</td>
                            <td>{{ $review->technician->user->name ?? 'N/A' }}</td>
                            <td>
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                    <span class="ms-2">({{ $review->rating }}/5)</span>
                                </div>
                            </td>
                            <td>{{ $review->title ?? 'بدون عنوان' }}</td>
                            <td>
                                @switch($review->status)
                                    @case('pending')
                                        <span class="badge bg-warning">قيد الانتظار</span>
                                        @break
                                    @case('approved')
                                        <span class="badge bg-success">موافق عليه</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">مرفوض</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $review->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-info btn-sm" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.reviews.edit', $review) }}" class="btn btn-warning btn-sm text-white" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه المراجعة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">لا توجد مراجعات حالياً</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($reviews->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $reviews->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.rating-stars i {
    font-size: 0.9rem;
}
</style>
@endpush