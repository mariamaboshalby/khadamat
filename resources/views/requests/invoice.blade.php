@extends('layouts.mobile')

@section('title', 'تعديل الطلب')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-6 col-lg-4">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                <!-- Header -->
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-0">
                    <h6 class="mb-0 fw-bold text-dark">إيصال خدمة</h6>
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" style="max-height:50px">
                </div>

                <!-- Body -->
                <div class="card-body bg-light">

                    <!-- Service -->
                    <div class="card mb-3 border-0 shadow-sm rounded-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-secondary-subtle rounded-3 d-flex align-items-center justify-content-center me-3" style="width:40px;height:40px">
                                    <i class="fas fa-wrench text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $request->service->name }}</div>
                                    <small class="text-muted">صيانة احترافية</small>
                                </div>
                            </div>

                            @php
                                $labor = isset($acceptedProposal) ? ($acceptedProposal->proposed_price ?? 0) : ($request->proposed_price ?? 0);
                                $parts = isset($acceptedProposal) && $acceptedProposal->items ? $acceptedProposal->items->sum('total_price') : 0;
                                $total = $labor + $parts;
                            @endphp

                            @if($labor)
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted">قيمة الخدمة</span>
                                <span class="fw-bold">{{ number_format($labor) }} جنيه</span>
                            </div>
                            @endif

                            @if($parts)
                            <div class="d-flex justify-content-between py-2">
                                <span class="text-muted">قطع الغيار</span>
                                <span class="fw-bold">{{ number_format($parts) }} جنيه</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="card mb-3 border-0 shadow-sm rounded-3">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <span class="fw-bold">الإجمالي</span>
                            <span class="fw-bold text-success fs-5">{{ number_format($total) }} جنيه</span>
                        </div>
                    </div>

                    <!-- Guarantee -->
                    <div class="alert alert-warning d-flex align-items-center rounded-3 mb-3">
                        <i class="fas fa-shield-alt me-2"></i>
                        <strong>ضمان لمدة عام</strong>
                    </div>

                    <!-- Technician -->
                    @if($request->assignedTechnician)
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width:45px;height:45px">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $request->assignedTechnician->user->name }}</div>
                                <small class="text-muted">فني معتمد</small>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                <!-- Actions -->
                <div class="card-footer bg-white d-flex gap-2 no-print">
                    <button onclick="window.print()" class="btn btn-success w-50 fw-bold">
                        <i class="fas fa-print me-1"></i> طباعة
                    </button>
                    <a href="{{ route('requests.show', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" class="btn btn-outline-secondary w-50 fw-bold">
                        رجوع
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print { display:none !important }
    body { background:white }
}
</style>
@endsection
