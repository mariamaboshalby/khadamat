@extends('layouts.admin')

@section('title', 'إدارة الخدمات')
@section('header_title', 'إدارة الخدمات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">جميع الخدمات</h1>
        <a href="{{ route('admin.services.create') }}" class="btn-primary rounded-3">
            <i class="fas fa-plus-circle me-2"></i>إضافة خدمة جديدة
        </a>
    </div>

    <!-- Services Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">قائمة الخدمات</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="servicesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>التخصص</th>
                            <th>الأيقونة</th>
                            <th>فئة اللون</th>
                            <th>الرابط</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->specialization->name ?? 'غير محدد' }}</td>
                            <td>
                                @if($service->icon)
                                    <i class="fas {{ $service->icon }}"></i>
                                @else
                                    بدون أيقونة
                                @endif
                            </td>
                            <td>{{ $service->color_class ?? 'غير محدد' }}</td>
                            <td>{{ $service->route_name ?? 'غير محدد' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.services.edit', \App\Helpers\EncryptionHelper::encryptId($service->id)) }}" class="btn btn-warning btn-sm text-white" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.services.destroy', \App\Helpers\EncryptionHelper::encryptId($service->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الخدمة؟')">
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
                            <td colspan="7" class="text-center">لا توجد خدمات حالياً</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection