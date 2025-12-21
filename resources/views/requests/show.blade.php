@extends('layouts.mobile')

@section('title', 'تفاصيل الطلب')

@section('content')
    <div class="">

        <div class="row justify-content-center">
            <div class="col-lg-12">

                <!-- Main Card -->
                <div class="card shadow-lg border-0 rounded-4 p-4">

                    <!-- Status -->
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'approved' => 'primary',
                            'in_progress' => 'info',
                            'completed' => 'success',
                            'cancelled' => 'secondary',
                            'rejected' => 'danger',
                        ];
                        $status = $requestData->status;
                        $color = $statusColors[$status] ?? 'secondary';
                    @endphp

                    <div class="text-center mb-5">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-{{ $color }}-subtle text-{{ $color }}"
                            style="width: 120px; height: 120px; font-size: 48px;">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h3 class="fw-bold mt-3">{{ ucfirst($status) }}</h3>
                        <p class="text-muted">رقم الطلب: #{{ $requestData->id }}</p>
                    </div>

                    <!-- User Info -->
                    <div class="mb-5">
                        <h4 class="fw-bold text-primary mb-3"><i class="fas fa-user me-2"></i>معلومات العميل</h4>
                        <div class="row">
                            <div class="col-md-6 mb-2"><strong>الاسم:</strong> {{ $requestData->user->name }}</div>
                            <div class="col-md-6 mb-2"><strong>البريد:</strong> {{ $requestData->user->email }}</div>
                            @if (isset($requestData->user->phone))
                                <div class="col-md-6 mb-2"><strong>الهاتف:</strong> {{ $requestData->user->phone }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>

                    <!-- Request Info -->
                    <div class="mb-5">
                        <h4 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-2"></i>معلومات الطلب</h4>
                        <div class="row">
                            <div class="col-md-6 mb-2"><strong>الخدمة:</strong> {{ $requestData->service->name }}</div>
                            <div class="col-md-6 mb-2"><strong>العنوان:</strong> {{ $requestData->address }}</div>
                            <div class="col-md-6 mb-2"><strong>الوصف:</strong>
                                @if ($requestData->description)
                                    {{ $requestData->description }}
                                @else
                                    <span class="text-muted">لا يوجد وصف</span>
                                @endif
                            </div>
                            <div class="col-md-6 mb-2"><strong>الموعد:</strong>
                                @if ($requestData->scheduled_at)
                                    {{ \Carbon\Carbon::parse($requestData->scheduled_at)->format('Y-m-d h:i A') }}
                                @else
                                    <span class="text-muted">لم يتم تحديد موعد</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr>

                    <!-- Proposals Section -->
                    @if($requestData->proposals->count() > 0)
                        <div class="mb-5">
                            <h4 class="fw-bold text-primary mb-3"><i class="fas fa-money-bill-wave me-2"></i>عروض الأسعار المقدمة</h4>
                            <div class="row">
                                @foreach($requestData->proposals as $proposal)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-{{ $proposal->status === 'accepted' ? 'success' : ($proposal->status === 'rejected' ? 'danger' : 'warning') }} shadow-sm">
                                            <div class="card-header bg-{{ $proposal->status === 'accepted' ? 'success' : ($proposal->status === 'rejected' ? 'danger' : 'warning') }} text-white">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-user-cog me-2"></i>
                                                    <a href="{{ route('technician.profile', $proposal->technician_id) }}" class="text-white text-decoration-none" target="_blank">
                                                        {{ $proposal->technician->user->name }}
                                                        <i class="fas fa-external-link-alt ms-1" style="font-size: 12px;"></i>
                                                    </a>
                                                    @if($proposal->status === 'accepted')
                                                        <span class="badge bg-light text-success float-end">مقبول</span>
                                                    @elseif($proposal->status === 'rejected')
                                                        <span class="badge bg-light text-danger float-end">مرفوض</span>
                                                    @else
                                                        <span class="badge bg-light text-warning float-end">قيد الانتظار</span>
                                                    @endif
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <strong>سعر الخدمة:</strong> 
                                                    <span class="fs-5 text-success">{{ number_format($proposal->proposed_price, 2) }} ج.م</span>
                                                </div>
                                                
                                                @if($proposal->items->count() > 0)
                                                    <div class="mb-2">
                                                        <strong>القطع المطلوبة:</strong>
                                                        <ul class="list-unstyled ms-3 mt-2">
                                                            @foreach($proposal->items as $item)
                                                                <li class="mb-1">
                                                                    <i class="fas fa-cog text-muted me-1"></i>
                                                                    {{ $item->warehouseItem->name }} 
                                                                    ({{ $item->quantity }} × {{ number_format($item->unit_price, 2) }} = {{ number_format($item->total_price, 2) }} ج.م)
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="text-end">
                                                            <strong>تكلفة القطع:</strong> 
                                                            <span class="text-info">{{ number_format($proposal->items->sum('total_price'), 2) }} ج.م</span>
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                <div class="border-top pt-2 mt-2">
                                                    <strong>الإجمالي:</strong> 
                                                    <span class="fs-4 fw-bold text-primary">{{ number_format($proposal->proposed_price + $proposal->items->sum('total_price'), 2) }} ج.م</span>
                                                </div>
                                                
                                                @if($proposal->price_notes)
                                                    <div class="mt-2">
                                                        <small class="text-muted"><strong>ملاحظات:</strong> {{ $proposal->price_notes }}</small>
                                                    </div>
                                                @endif
                                                
                                                @if($proposal->status === 'pending' && $requestData->status !== 'in_progress')
                                                    <div class="mt-3">
                                                        <form method="POST" action="{{ route('requests.accept-proposal', $proposal->id) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="fas fa-check me-1"></i> قبول العرض
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('requests.reject-proposal', $proposal->id) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="fas fa-times me-1"></i> رفض
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <hr>
                    @endif

                    <!-- Pricing Section -->
                    @if($requestData->price_status === 'pending' && $requestData->assigned_technician_id)
                        <div class="mb-5">
                            <h4 class="fw-bold text-primary mb-3"><i class="fas fa-money-bill-wave me-2"></i>عرض السعر</h4>
                            <div class="alert alert-warning border-0 shadow-sm">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-exclamation-triangle fa-2x text-warning me-3"></i>
                                    <div>
                                        <h5 class="mb-0">في انتظار موافقتك على السعر</h5>
                                        <small class="text-muted">الفني قدم عرض سعر للخدمة</small>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-2">
                                        <strong>سعر الخدمة:</strong> 
                                        <span class="fs-5 text-success">{{ number_format($requestData->proposed_price, 2) }} ج.م</span>
                                    </div>
                                    @if($requestData->requestItems->count() > 0)
                                        <div class="col-md-6 mb-2">
                                            <strong>تكلفة القطع:</strong> 
                                            <span class="fs-5 text-info">{{ number_format($requestData->total_items_price, 2) }} ج.م</span>
                                        </div>
                                    @endif
                                    <div class="col-12 mt-2">
                                        <strong>الإجمالي:</strong> 
                                        <span class="fs-4 fw-bold text-primary">{{ number_format($requestData->total_price, 2) }} ج.م</span>
                                    </div>
                                </div>

                                @if($requestData->price_notes)
                                    <div class="mb-3">
                                        <strong>ملاحظات الفني:</strong>
                                        <p class="text-muted mb-0">{{ $requestData->price_notes }}</p>
                                    </div>
                                @endif

                                @if($requestData->requestItems->count() > 0)
                                    <div class="mb-3">
                                        <strong>القطع المطلوبة:</strong>
                                        <div class="table-responsive mt-2">
                                            <table class="table table-sm table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>القطعة</th>
                                                        <th>الكمية</th>
                                                        <th>السعر</th>
                                                        <th>الإجمالي</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($requestData->requestItems as $item)
                                                        <tr>
                                                            <td>{{ $item->warehouseItem->name }}</td>
                                                            <td>{{ $item->quantity }} {{ $item->warehouseItem->unit }}</td>
                                                            <td>{{ number_format($item->unit_price, 2) }} ج.م</td>
                                                            <td>{{ number_format($item->total_price, 2) }} ج.م</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                <div class="d-flex gap-2 flex-wrap">
                                    <form method="POST" action="{{ route('requests.accept-price', $requestData->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check me-1"></i> قبول السعر
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                        <i class="fas fa-times me-1"></i> رفض العرض
                                    </button>
                                </div>
                            </div>
                        </div>
                        <hr>
                    @endif

                    <!-- Review Section -->
                    @if($requestData->status === 'completed' && !$requestData->reviews()->where('user_id', auth()->id())->exists())
                        <div class="mb-5">
                            <h4 class="fw-bold text-primary mb-3"><i class="fas fa-star me-2"></i>تقييم الفني</h4>
                            <form method="POST" action="{{ route('requests.submit-review', $requestData->id) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">التقييم</label>
                                    <div class="rating-stars">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                                            <label for="star{{ $i }}">★</label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">التعليق</label>
                                    <textarea name="comment" class="form-control" rows="3" placeholder="اكتب تعليقك..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i> إرسال التقييم
                                </button>
                            </form>
                        </div>
                        <hr>
                    @endif

                    <!-- Images Section -->
                    @if ($requestData->getMedia('requests')->count() > 0)
                        <h5 class="fw-bold text-primary mt-4 mb-3"><i class="fas fa-images me-2"></i>الصور المرفقة</h5>
                        <div class="row g-3 mb-3">
                            @foreach ($requestData->getMedia('requests') as $media)
                                <div class="col-3">
                                    <img src="{{ $media->getUrl() }}" class="img-fluid rounded shadow-sm" alt="صورة الطلب">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="text-center mt-4">
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            @if(in_array($requestData->status, ['in_progress', 'completed']))
                                <a href="{{ route('requests.invoice', $requestData->id) }}" class="btn btn-lg btn-success px-5 py-2 fw-bold" target="_blank">
                                    <i class="fas fa-file-invoice me-2"></i> عرض الفاتورة
                                </a>
                            @endif
                            @if($requestData->status === 'pending')
                                <a href="{{ route('requests.edit', $requestData->id) }}" class="btn btn-lg btn-warning px-5 py-2 fw-bold">
                                    <i class="fas fa-edit me-2"></i> تعديل الطلب
                                </a>
                            @endif
                            <a href="{{ route('dashboard') }}" class="btn btn-lg btn-outline-secondary px-5 py-2 fw-bold">
                                <i class="fas fa-arrow-right me-2"></i> رجوع
                            </a>
                        </div>
                    </div>

                </div>
                <!-- End Card -->

            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">رفض العرض</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('requests.reject-price', $requestData->id) }}">
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                سيتم إعادة الطلب للفنيين الآخرين
                            </div>
                            <div class="mb-3">
                                <label class="form-label">سبب الرفض</label>
                                <textarea name="customer_notes" class="form-control" rows="4" required placeholder="اكتب سبب رفض العرض..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-danger">تأكيد الرفض</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        .bg-warning-subtle {
            background-color: rgba(255, 193, 7, 0.15);
        }

        .bg-primary-subtle {
            background-color: rgba(0, 123, 255, 0.15);
        }

        .bg-info-subtle {
            background-color: rgba(23, 162, 184, 0.15);
        }

        .bg-success-subtle {
            background-color: rgba(40, 167, 69, 0.15);
        }

        .bg-secondary-subtle {
            background-color: rgba(108, 117, 125, 0.15);
        }

        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.15);
        }
        
        .rating-stars {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
        }
        
        .rating-stars input {
            display: none;
        }
        
        .rating-stars label {
            font-size: 40px;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .rating-stars input:checked ~ label,
        .rating-stars label:hover,
        .rating-stars label:hover ~ label {
            color: #ffc107;
        }
    </style>
@endpush
