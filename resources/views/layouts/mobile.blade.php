<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'خدمات')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Google Fonts - Cairo & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary-color: #0b5f8a;
            --primary-dark: #072540;
            --primary-light: #188ec9;
            --accent-orange: #ff8a00;
            --accent-orange-hover: #e57c00;
            --secondary-color: #ff8a00;
            --bg-color: #f8fafc;
            --surface-color: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.07), 0 1px 2px -1px rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 12px -2px rgb(0 0 0 / 0.08), 0 2px 6px -2px rgb(0 0 0 / 0.04);
            --shadow-lg: 0 12px 24px -4px rgb(0 0 0 / 0.1), 0 4px 8px -4px rgb(0 0 0 / 0.04);
            --shadow-xl: 0 20px 35px -5px rgba(11, 95, 138, 0.12);
            --radius-sm: 0.5rem;
            --radius-md: 0.875rem;
            --radius-lg: 1.25rem;
            --radius-xl: 1.75rem;
        }

        * {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background: var(--bg-color);
            color: var(--text-primary);
            padding-bottom: 90px;
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Main Container */
        .app-container {
            width: 100%;
            margin: 0 auto;
            min-height: 100vh;
            position: relative;
        }

        /* Mobile Specifics */
        @media (max-width: 768px) {
            .app-container {
                max-width: 100%;
                background: var(--surface-color);
            }
        }

        /* Desktop Specifics */
        @media (min-width: 769px) {
            body {
                padding-bottom: 0;
            }

            .app-container {
                max-width: 100%;
                padding: 0;
            }
        }

        /* Glassmorphism Utilities */
        .glass {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        /* Header */
        .app-header {
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        /* Desktop Header */
        .desktop-header {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(16px);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.04);
            padding: 12px 0;
            border-bottom: 1px solid #eef2f6;
            z-index: 1000;
        }

        .site-logo {
            height: 48px;
            width: auto;
            object-fit: contain;
            transition: transform 0.2s;
        }

        .site-logo:hover {
            transform: scale(1.02);
        }

        .desktop-nav-link {
            color: var(--text-secondary);
            font-weight: 700;
            margin: 0 14px;
            text-decoration: none;
            transition: all 0.25s ease;
            font-size: 15px;
            position: relative;
            padding: 6px 4px;
        }

        .desktop-nav-link:hover {
            color: var(--primary-color);
        }

        .desktop-nav-link.active {
            color: var(--primary-color);
        }

        .desktop-nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 4px;
        }

        /* Modern CTA Buttons */
        .btn-brand-orange {
            background: linear-gradient(135deg, #ff9500 0%, #ff7a00 100%);
            color: #ffffff !important;
            font-weight: 800;
            padding: 9px 24px;
            border-radius: 30px;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(255, 122, 0, 0.35);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-brand-orange:hover {
            background: linear-gradient(135deg, #ff8500 0%, #e66a00 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 122, 0, 0.45);
        }

        .btn-brand-navy {
            background: var(--primary-color);
            color: #ffffff !important;
            font-weight: 700;
            padding: 9px 22px;
            border-radius: 30px;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-brand-navy:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(11, 95, 138, 0.3);
        }

        .header-icon-btn {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--surface-color);
            border-radius: 12px;
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-sm);
        }

        .header-icon-btn:active {
            transform: scale(0.95);
        }

        .site-logo {
            height: 55px;
            width: 100px;
        }

        /* Bottom Navigation (Mobile Only) */
        .bottom-nav {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 40px);
            max-width: 440px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
            display: flex;
            justify-content: space-between;
            padding: 12px 24px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            padding: 4px;
        }

        .nav-icon {
            font-size: 22px;
            transition: transform 0.3s ease;
        }

        .nav-label {
            font-size: 11px;
            font-weight: 700;
            opacity: 0.8;
        }

        .nav-item.active {
            color: var(--primary-color);
        }

        .nav-item.active .nav-icon {
            transform: translateY(-2px);
        }

        .nav-item.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            width: 4px;
            height: 4px;
            background: var(--primary-color);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--primary-color);
        }

        /* Desktop Navigation Links */
        .desktop-nav-link {
            color: var(--text-secondary);
            font-weight: 700;
            margin: 0 18px;
            text-decoration: none;
            transition: color 0.2s;
            font-size: 15px;
        }

        .desktop-nav-link:hover,
        .desktop-nav-link.active {
            color: var(--primary-color);
        }

        .btn-primary {
            background: #083a56;
            color: white;
            border: #083a56;
            text-decoration: none;
            padding: 8px 15px;
            transition: 0.4s;
        }

        .btn-primary:hover {
            background: #0b5f8a;
            color: white;
            border: #0b5f8a;
            text-decoration: none;
            padding: 8px 15px;
        }

        .btn-outline-primary {
            color: #083a56;
            background: transparent;
            border: 1px solid #083a56;
            text-decoration: none;
            padding: 5px 15px;
            transition: 0.4s;
        }

        .btn-outline-primary:hover {
            color: #fff;
            background: #083a56;
            border: 1px solid #083a56;
            text-decoration: none;
            padding: 5px 15px;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        /* Scrollbar Hide */
        ::-webkit-scrollbar {
            display: none;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Desktop Header -->
    <div class="desktop-header d-none d-md-block sticky-top">
        <div class="container-fluid px-lg-5">
            <div class="d-flex justify-content-between align-items-center">
                <header class="site-header">
                    <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
                        <img src="{{ asset('images/logo.png') }}" alt="خدمتي" class="site-logo">
                    </a>
                </header>

                <div class="d-flex align-items-center">
                    <a href="{{ route('home') }}"
                        class="desktop-nav-link {{ request()->is('/') ? 'active' : '' }}">الرئيسية</a>
                    <a href="{{ route('services.index') }}"
                        class="desktop-nav-link {{ request()->is('services*') ? 'active' : '' }}">الخدمات</a>
                    <a href="{{ url('/#how-it-works') }}"
                        class="desktop-nav-link">كيف نعمل؟</a>
                    <a href="{{ url('/#why-us') }}"
                        class="desktop-nav-link">لماذا نحن</a>
                    <a href="{{ route('technicians.index') }}"
                        class="desktop-nav-link {{ request()->routeIs('technicians*') ? 'active' : '' }}">نخبة الفنيين</a>
                    <a href="{{ route('offers.index') }}"
                        class="desktop-nav-link {{ request()->is('offers*') ? 'active' : '' }}">العروض</a>

                    @auth
                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}"
                                class="desktop-nav-link {{ request()->is('admin*') ? 'active' : '' }}">لوحة الإدارة</a>
                        @elseif (auth()->user()->hasRole('technician'))
                            <a href="{{ route('technician.repair-requests.index') }}"
                                class="desktop-nav-link {{ request()->is('technician*') ? 'active' : '' }}">طلبات الاصلاح</a>
                        @else
                            <a href="{{ route('dashboard') }}"
                                class="desktop-nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">لوحة التحكم</a>
                        @endif
                    @endauth
                </div>

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('requests.create') }}" class="btn-brand-orange">
                        <span>طلب خدمة</span>
                    </a>

                    @auth
                        <a href="{{ route('profile.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2 fw-bold" title="حسابي">
                            <i class="fas fa-user-circle me-1"></i> حسابي
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline m-0">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light rounded-pill px-3 py-2 text-danger fw-bold border" title="تسجيل الخروج">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-2 fw-bold">
                            تسجيل الدخول
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>


    <div class="app-container">
        @yield('content')
        <div class="text-center py-4" style="border-top: 1px solid var(--border-color);">
            <p class="mb-0 text-muted fs-6">
                &copy; تم التطوير بكل ❤️ بواسطة
                <strong class="text-primary">Urca Team</strong>
            </p>
        </div>

        <!-- Bottom Navigation (Mobile Only) -->
        <div class="bottom-nav d-md-none">
            <a href="{{ route('home') }}" class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                <i class="fas fa-home nav-icon"></i>
                <span class="nav-label">الرئيسية</span>
            </a>
            <a href="{{ route('services.index') }}" class="nav-item {{ request()->is('services*') ? 'active' : '' }}">
                <i class="fas fa-layer-group nav-icon"></i>
                <span class="nav-label">خدمات</span>
            </a>
            @auth
                @if (auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-item {{ request()->is('admin*') ? 'active' : '' }}">
                        <i class="fas fa-cogs nav-icon"></i>
                        <span class="nav-label">لوحة التحكم</span>
                    </a>
                @elseif (auth()->user()->hasRole('technician'))
                    <a href="{{ route('technician.repair-requests.index') }}"
                        class="nav-item {{ request()->is('technician*') ? 'active' : '' }}">
                        <i class="fas fa-wrench nav-icon"></i>
                        <span class="nav-label">الاصلاحات</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}"
                        class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-th-large nav-icon"></i>
                        <span class="nav-label">لوحتي</span>
                    </a>
                @endif
            @else
                <a href="{{ route('requests.create') }}"
                    class="nav-item {{ request()->is('requests*') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle nav-icon"></i>
                    <span class="nav-label">طلب</span>
                </a>
            @endauth
            <a href="{{ route('offers.index') }}" class="nav-item {{ request()->is('offers*') ? 'active' : '' }}">
                <i class="fas fa-percent nav-icon"></i>
                <span class="nav-label">عروض</span>
            </a>
            <a href="{{ route('profile.index') }}" class="nav-item {{ request()->is('profile*') ? 'active' : '' }}">
                <i class="fas fa-user nav-icon"></i>
                <span class="nav-label">حسابي</span>
            </a>
        </div>
    </div>

    @yield('footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
