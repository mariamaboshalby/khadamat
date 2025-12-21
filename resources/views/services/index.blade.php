@extends('layouts.mobile')

@section('title', 'الخدمات')

@section('content')
<div class="app-content fade-in p-3">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="fw-bold m-0">كل الخدمات</h4>
        <div class="p-2 bg-white rounded-circle shadow-sm">
            <i class="fas fa-th-large text-primary"></i>
        </div>
    </div>

    <!-- Search -->
    <div class="search-box mb-4 position-relative">
        <input type="text" id="search-input" value="{{ request('search') }}" class="form-control form-control-lg border-0 shadow-sm ps-5" placeholder="ابحث عن خدمة..." style="border-radius: 15px; height: 55px;">
        <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
    </div>

    <!-- Services Grid -->
    <div id="services-container" class="row g-3">
        @foreach($services as $service)
            <div class="col-6 col-md-4 col-lg-3 service-item" data-service-name="{{ $service->name }}">
                <a href="{{ route('service.show', $service->id) }}" class="service-card-modern h-100 d-block text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 p-3 text-center" style="border-radius: 20px; transition: transform 0.2s;">
                        <div class="icon-wrapper mx-auto mb-3 {{ $service->color_class }}" style="width: 60px; height: 60px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                            <i class="fas {{ $service->icon }}"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">{{ $service->name }}</h6>
                        <p class="text-muted small mb-0">{{ Str::limit($service->description ?? 'وصف الخدمة', 30) }}</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<style>
    .service-card-modern:hover .card {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    /* Reusing colors from home */
    .icon-wrapper.blue { background: #ffecec; color: #cc3333; }
    .icon-wrapper.yellow { background: #fefce8; color: #eab308; }
    .icon-wrapper.cyan { background: #ecfeff; color: #06b6d4; }
    .icon-wrapper.orange { background: #fff7ed; color: #f97316; }
    .icon-wrapper.purple { background: #faf5ff; color: #a855f7; }
    .icon-wrapper.red { background: #fef2f2; color: #ef4444; }
</style>

<script>
    // Client-side search filtering without page reload
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const serviceItems = document.querySelectorAll('.service-item');
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                serviceItems.forEach(function(item) {
                    const serviceName = item.getAttribute('data-service-name').toLowerCase();
                    
                    if (searchTerm === '' || serviceName.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection
