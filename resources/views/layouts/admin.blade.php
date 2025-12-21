<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم')</title>

    <!-- Bootstrap 5 RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    @stack('styles')

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #f5f7fa;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            height: 100%;
            position: fixed;
            right: 0;
            top: 0;
            background: #111827;
            background: linear-gradient(180deg, #1f2937 0%, #111827 100%);
            color: white;
            transition: all 0.3s ease;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            box-shadow: -4px 0 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 20px;
        }

        .sidebar-brand {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .site-logo {
            height: 35px;
            width: auto;
        }

        .sidebar .menu-item {
            padding: 12px 25px;
            color: #9ca3af;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
            margin: 4px 15px;
            border-radius: 8px;
        }

        .sidebar .menu-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            transform: translateX(-3px);
        }

        .sidebar .menu-item.active {
            background: linear-gradient(90deg, #cc3333 0%, #992626 100%);
            color: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .sidebar .menu-item i {
            width: 24px;
            text-align: center;
            font-size: 18px;
            transition: 0.2s;
        }

        .sidebar .menu-item:hover i {
            color: #ff6666;
        }

        .sidebar .menu-item.active i {
            color: #fff;
        }

        /* Navbar */
        .main-navbar {
            height: 70px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e5e7eb;
            position: fixed;
            top: 0;
            right: 280px;
            left: 0;
            display: flex;
            align-items: center;
            padding: 0 30px;
            z-index: 900;
            transition: 0.3s;
        }

        /* Content */
        .main-content {
            margin-right: 280px;
            padding-top: 100px;
            padding-inline: 30px;
            transition: 0.3s;
            min-height: 100vh;
        }

        .dropdown-menu {
            text-align: right;
            border: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        #sidebarToggle {
            display: none;
        }

        @media (max-width: 992px) {
            .sidebar {
                right: -280px;
            }

            .sidebar.active {
                right: 0;
            }

            .main-content {
                margin-right: 0;
            }

            .main-navbar {
                right: 0;
            }

            #sidebarToggle {
                display: inline-block;
                margin-left: 15px;
            }
        }

        .btn-primary {
            background: #e54343;
            color: white;
            border: #e54343;
            text-decoration: none;
            padding: 10px 15px;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: #cc3333;
            color: white;
            border: #cc3333;
            text-decoration: none;
            padding: 10px 15px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="main-navbar d-flex justify-content-between">
        <div class="d-flex align-items-center">
            <button class="btn btn-primary d-md-none" id="sidebarToggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Damat Logo" class="site-logo"
                    style="height: 50px; width: 90px;">
            </a>
        </div>

        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('notifications.index') }}" class="header-icon-btn position-relative text-decoration-none" style="color: #e54343">
                <i class="fas fa-bell fs-5"></i>
                <span id="notification-badge"
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    style="font-size: 0.6rem; display: none;">
                    0
                    <span class="visually-hidden">unread messages</span>
                </span>
            </a>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-dark dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name=Admin" width="35" class="rounded-circle">
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('profile.index') }}">ملفي</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="dropdown-item text-danger border-0 bg-transparent w-100 text-end">
                                تسجيل الخروج
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('home') }}" class="sidebar-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Damat Logo" class="site-logo"
                    style="height: 60px; width: 100px;">
            </a>
        </div>

        <div class="d-flex flex-column gap-1">
            <!-- Common Links -->
            @if (Auth::check() && Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}"
                    class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-home"></i> <span>الرئيسية</span>
                </a>

                <div class="px-2 py-2 mt-2">
                    <small class="text-uppercase text-muted fw-bold"
                        style="font-size: 11px; letter-spacing: 1px;">الإدارة</small>
                </div>

                <a href="{{ route('admin.customers.index') }}"
                    class="menu-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> <span>العملاء</span>
                </a>
                <a href="{{ route('admin.techs.index') }}"
                    class="menu-item {{ request()->routeIs('admin.techs.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-gear"></i> <span>الفنيين</span>
                </a>
                <a href="{{ route('admin.specializations.index') }}"
                    class="menu-item {{ request()->routeIs('admin.specializations.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-briefcase"></i> <span>التخصصات</span>
                </a>
                <a href="{{ route('admin.services.index') }}"
                    class="menu-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-concierge-bell"></i> <span>الخدمات</span>
                </a>
                <a href="{{ route('admin.offers.index') }}"
                    class="menu-item {{ request()->routeIs('admin.offers.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags"></i> <span>العروض</span>
                </a>
                <a href="{{ route('admin.warehouse-items.index') }}"
                    class="menu-item {{ request()->routeIs('admin.warehouse-items.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-boxes-stacked"></i> <span>المخزن</span>
                </a>
                <a href="{{ route('admin.requests.index') }}"
                    class="menu-item {{ request()->routeIs('admin.requests.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clipboard-list"></i> <span>الطلبات</span>
                </a>
            @endif

            @if (Auth::check() && Auth::user()->isTechnician())
                <a href="{{ route('dashboard') }}"
                    class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-home"></i> <span>الرئيسية</span>
                </a>

                <div class="px-4 py-2 mt-2">
                    <small class="text-uppercase text-muted fw-bold"
                        style="font-size: 11px; letter-spacing: 1px;">الفني</small>
                </div>

                <a href="{{ route('technician.repair-requests.index') }}"
                    class="menu-item {{ request()->routeIs('technician.repair-requests.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-search"></i> <span>طلبات متاحة</span>
                </a>
                <a href="{{ route('technician.my-requests') }}"
                    class="menu-item {{ request()->routeIs('technician.my-requests') ? 'active' : '' }}">
                    <i class="fa-solid fa-clipboard-check"></i> <span>الطلبات المقدم عليها</span>
                </a>
                <a href="{{ route('technician.profile.edit') }}"
                    class="menu-item {{ request()->routeIs('technician.profile.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-circle"></i> <span>الملف الشخصي</span>
                </a>
            @endif
        </div>

        <div class="mt-auto mb-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="menu-item text-danger w-100 border-0 bg-transparent text-start">
                    <i class="fa-solid fa-right-from-bracket"></i> <span>تسجيل الخروج</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Content -->
    <div class="main-content">
        @yield('content')
        <div class="text-center py-4" style="border-top: 1px solid var(--border-color);">
            <p class="mb-0 text-muted fs-6">
                &copy; تم التطوير بكل ❤️ بواسطة
                <strong class="text-primary">Core-House Team</strong>
            </p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle -->
    <script>
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        toggleBtn.addEventListener('click', () => sidebar.classList.toggle('active'));


        document.addEventListener('DOMContentLoaded', function() {
            fetchNotificationsCount();
        });

        function fetchNotificationsCount() {
            fetch("{{ route('notifications.unread-count') }}")
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    if (data.count > 0) {
                        badge.innerText = data.count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching notifications:', error));
        }
    </script>

    @stack('scripts')
</body>

</html>
