@extends('layouts.mobile')

@section('title', $service->name)

@section('content')
<div class="app-content fade-in p-3">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="fw-bold m-0">{{ $service->name }}</h4>
        <div class="p-2 bg-white rounded-circle shadow-sm {{ $service->color_class }}" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
            <i class="fas {{ $service->icon }}"></i>
        </div>
    </div>

    <!-- Service Details -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
        <div class="card-body p-4">
            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-3">تفاصيل الخدمة</h5>
                <p class="text-muted">هذه الخدمة تنتمي إلى تخصص {{ $service->specialization->name ?? 'غير محدد' }}.</p>
            </div>

            @if($service->specialization)
            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-3">التخصص</h5>
                <div class="d-flex align-items-center p-3 bg-light rounded">
                    <div class="me-3">
                        <i class="fas fa-tools text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $service->specialization->name }}</h6>
                        <small class="text-muted">{{ $service->specialization->description ?? 'لا يوجد وصف لهذا التخصص' }}</small>
                    </div>
                </div>
            </div>
            @endif

            <div class="d-grid gap-2">
                <a href="{{ route('requests.create', ['service_id' => $service->id]) }}" class="btn btn-primary btn-lg rounded-pill py-3">
                    <i class="fas fa-plus-circle me-2"></i>طلب هذه الخدمة
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Reusing colors from home */
    .bg-light.blue { background: #ffecec !important; }
    .bg-light.yellow { background: #fefce8 !important; }
    .bg-light.cyan { background: #ecfeff !important; }
    .bg-light.orange { background: #fff7ed !important; }
    .bg-light.purple { background: #faf5ff !important; }
    .bg-light.red { background: #fef2f2 !important; }
</style>
@endsection