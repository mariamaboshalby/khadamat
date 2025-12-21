@extends('layouts.admin')

@section('title', 'إدارة الطلبات')

@section('content')
    <style>
        .btn-primary {
            background: #e54343;
            color: white;
            border: #e54343;
            text-decoration: none;
            padding: 3px 15px;
            margin: 5px;
        }

        .btn-primary:hover {
            background: #cc3333;
            color: white;
            border: #cc3333;
            text-decoration: none;
            padding: 3px 15px;
        }
    </style>
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0 text-gray-800">إدارة الطلبات</h2>

            <!-- Filters -->
            <div class="btn-group">
                <a href="{{ route('admin.requests.index') }}"
                    class="btn btn-outline-primary {{ !request('status') ? 'active' : '' }}">الكل</a>
                <a href="{{ route('admin.requests.index', ['status' => 'pending']) }}"
                    class="btn btn-outline-warning {{ request('status') == 'pending' ? 'active' : '' }}">قيد الانتظار</a>
                <a href="{{ route('admin.requests.index', ['status' => 'approved']) }}"
                    class="btn btn-outline-info {{ request('status') == 'approved' ? 'active' : '' }}">مقبولة</a>
                <a href="{{ route('admin.requests.index', ['status' => 'completed']) }}"
                    class="btn btn-outline-success {{ request('status') == 'completed' ? 'active' : '' }}">مكتملة</a>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الطلبات</h6>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>العميل</th>
                                <th>الخدمة</th>
                                <th>العنوان</th>
                                <th>الموعد</th>
                                <th>الحالة</th>
                                <th>تاريخ الطلب</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($requests as $request)
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td>{{ $request->user->name ?? 'غير معروف' }}</td>
                                    <td>{{ $request->service->name ?? 'خدمة عامة' }}</td>
                                    <td>{{ Str::limit($request->address, 30) }}</td>

                                    <td>
                                        @if ($request->scheduled_at)
                                            {{ $request->scheduled_at->format('Y-m-d H:i') }}
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>

                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'approved' => 'info',
                                                'in_progress' => 'primary',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                'rejected' => 'secondary',
                                            ];

                                            $statusLabels = [
                                                'pending' => 'قيد الانتظار',
                                                'approved' => 'تم القبول',
                                                'in_progress' => 'جاري التنفيذ',
                                                'completed' => 'مكتمل',
                                                'cancelled' => 'ملغي',
                                                'rejected' => 'مرفوض',
                                            ];
                                        @endphp

                                        <span
                                            class="badge bg-{{ $statusColors[$request->status] ?? 'secondary' }} px-3 py-2">
                                            {{ $statusLabels[$request->status] ?? $request->status }}
                                        </span>
                                    </td>

                                    <td>{{ $request->created_at->format('Y-m-d') }}</td>

                                    <td class="text-center">

                                        <!-- View Button -->
                                        <a href="{{ route('admin.requests.show', $request->id) }}"
                                            class="btn-primary rounded-3 btn-sm">
                                            عرض التفاصيل
                                        </a>

                                        <!-- Invoice Button -->
                                        <a href="{{ route('admin.requests.invoice', $request->id) }}"
                                            class="btn btn-success btn-sm" target="_blank" title="عرض الفاتورة">
                                            <i class="fa-solid fa-file-invoice"></i>
                                        </a>

                                        <!-- Dropdown Actions -->
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                تغيير الحالة
                                            </button>

                                            <ul class="dropdown-menu">

                                                @if ($request->status == 'pending')
                                                    <li>
                                                        <form
                                                            action="{{ route('admin.requests.update-status', $request->id) }}"
                                                            method="POST">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="status" value="approved">
                                                            <button class="dropdown-item text-info">قبول الطلب</button>
                                                        </form>
                                                    </li>

                                                    <li>
                                                        <form
                                                            action="{{ route('admin.requests.update-status', $request->id) }}"
                                                            method="POST">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button class="dropdown-item text-danger">رفض الطلب</button>
                                                        </form>
                                                    </li>
                                                @endif

                                                @if ($request->status == 'approved')
                                                    <li>
                                                        <form
                                                            action="{{ route('admin.requests.update-status', $request->id) }}"
                                                            method="POST">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="status" value="in_progress">
                                                            <button class="dropdown-item text-primary">بدء التنفيذ</button>
                                                        </form>
                                                    </li>
                                                @endif

                                                @if ($request->status == 'in_progress')
                                                    <li>
                                                        <form
                                                            action="{{ route('admin.requests.update-status', $request->id) }}"
                                                            method="POST">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="status" value="completed">
                                                            <button class="dropdown-item text-success">اكتمال الطلب</button>
                                                        </form>
                                                    </li>
                                                @endif

                                            </ul>
                                        </div>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">لا توجد طلبات حالياً</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $requests->links() }}
                </div>

            </div>
        </div>

    </div>
@endsection
