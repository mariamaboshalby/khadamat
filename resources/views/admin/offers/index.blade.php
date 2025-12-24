@extends('layouts.admin')

@section('title', 'إدارة العروض')
@section('header_title', 'إدارة العروض')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">جميع العروض</h1>
        <a href="{{ route('admin.offers.create') }}" class="btn-primary rounded-3">
            <i class="fas fa-plus-circle me-2"></i>إضافة عرض جديد
        </a>
    </div>

    <!-- Offers Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">قائمة العروض</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="offersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>الشارة</th>
                            <th>القيمة</th>
                            <th>الأيقونة</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offers as $offer)
                        <tr>
                            <td>{{ $offer->id }}</td>
                            <td>{{ $offer->title }}</td>
                            <td>{{ $offer->badge_text ?? 'بدون شارة' }}</td>
                            <td>{{ $offer->discount_value ?? 'بدون قيمة' }}</td>
                            <td>
                                @if($offer->icon)
                                    <i class="fas {{ $offer->icon }}"></i>
                                @else
                                    بدون أيقونة
                                @endif
                            </td>
                            <td>{{ $offer->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.offers.edit', \App\Helpers\EncryptionHelper::encryptId($offer->id)) }}" class="btn btn-warning btn-sm text-white" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.offers.destroy', \App\Helpers\EncryptionHelper::encryptId($offer->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العرض؟')">
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
                            <td colspan="7" class="text-center">لا توجد عروض حالياً</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection