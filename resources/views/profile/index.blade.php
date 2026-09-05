@extends('layouts.mobile')

@section('title', 'حسابي')

@push('styles')
<style>
    .profile-page {
        background: #f0f4f8;
        min-height: 100vh;
        padding-bottom: 2rem;
    }

    /* ── Desktop Centering ── */
    @media (min-width: 769px) {
        .profile-page {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 2rem 1rem 3rem;
        }

        .profile-inner {
            width: 100%;
            max-width: 520px;
            background: transparent;
        }

        .profile-hero {
            border-radius: 20px;
        }

        .stats-card {
            margin: -2.2rem 0 0;
        }

        .menu-card {
            margin: 0;
        }

        .section-label {
            padding: 0 0.25rem;
        }

        .btn-logout {
            width: 100%;
            margin: 1.5rem 0 2rem;
        }
    }

    /* ── Hero Banner ── */
    .profile-hero {
        background: linear-gradient(135deg, #083a56 0%, #0b5f8a 60%, #188ec9 100%);
        padding: 2.5rem 1.5rem 4.5rem;
        position: relative;
        overflow: hidden;
    }
    .profile-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .profile-hero-avatar {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.6);
        box-shadow: 0 8px 24px rgba(0,0,0,0.25);
        object-fit: cover;
    }

    .profile-hero-name {
        font-size: 1.3rem;
        font-weight: 800;
        color: #fff;
        margin: 0.75rem 0 0.2rem;
    }

    .profile-hero-email {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.75);
    }

    .profile-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.18);
        backdrop-filter: blur(8px);
        color: #fff;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 4px 14px;
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.25);
        margin-top: 0.5rem;
    }

    /* ── Stats Bar ── */
    .stats-card {
        background: #fff;
        border-radius: 20px;
        margin: -2.2rem 1rem 0;
        padding: 1.2rem 1rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.10);
        position: relative;
        z-index: 10;
        display: flex;
        justify-content: space-around;
        align-items: center;
    }

    .stat-item {
        text-align: center;
        flex: 1;
    }

    .stat-item + .stat-item {
        border-right: 1px solid #f0f0f0;
    }

    .stat-value {
        font-size: 1.35rem;
        font-weight: 800;
        color: #083a56;
    }

    .stat-label {
        font-size: 0.72rem;
        color: #9ca3af;
        font-weight: 600;
        margin-top: 2px;
    }

    /* ── Section Label ── */
    .section-label {
        font-size: 0.72rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0 1.25rem;
        margin: 1.75rem 0 0.6rem;
    }

    /* ── Menu Cards ── */
    .menu-card {
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        margin: 0 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.2rem;
        text-decoration: none;
        color: #1e293b;
        transition: background 0.18s;
        border-bottom: 1px solid #f8fafc;
        position: relative;
    }

    .menu-item:last-child { border-bottom: none; }
    .menu-item:hover { background: #f8fafc; color: #1e293b; }
    .menu-item:active { background: #f1f5f9; }

    .menu-item-icon {
        width: 44px;
        height: 44px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .menu-item-body { flex: 1; }

    .menu-item-title {
        font-weight: 700;
        font-size: 0.95rem;
        margin: 0 0 2px;
        color: #1e293b;
    }

    .menu-item-sub {
        font-size: 0.78rem;
        color: #94a3b8;
        margin: 0;
    }

    .menu-item-arrow {
        color: #cbd5e1;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .menu-item-badge {
        background: #e54343;
        color: #fff;
        font-size: 0.7rem;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 20px;
        margin-left: 6px;
        flex-shrink: 0;
    }

    /* ── Edit Profile Button ── */
    .btn-edit-profile {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(6px);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: 30px;
        padding: 7px 20px;
        font-size: 0.85rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        margin-top: 1rem;
    }
    .btn-edit-profile:hover {
        background: rgba(255,255,255,0.3);
        color: #fff;
    }

    /* ── Logout ── */
    .btn-logout {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: calc(100% - 2rem);
        margin: 1.5rem 1rem 2rem;
        background: #fff;
        color: #e54343;
        border: 1.5px solid #fecaca;
        border-radius: 16px;
        padding: 1rem;
        font-weight: 800;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-logout:hover {
        background: #fef2f2;
        border-color: #f87171;
    }

    /* ── Guest View ── */
    .guest-section {
        margin: 2rem 1rem;
        background: #fff;
        border-radius: 22px;
        padding: 3rem 1.5rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07);
    }
    .guest-avatar-icon {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, #e0f2fe, #bfdbfe);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        color: #0b5f8a;
        margin-bottom: 1.5rem;
    }
    .guest-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    .guest-text {
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 2rem;
    }
    .btn-guest-login {
        display: block;
        background: linear-gradient(135deg, #083a56, #0b5f8a);
        color: #fff;
        border-radius: 14px;
        padding: 0.9rem;
        font-weight: 800;
        text-decoration: none;
        margin-bottom: 0.75rem;
        transition: all 0.2s;
        box-shadow: 0 4px 14px rgba(11,95,138,0.3);
    }
    .btn-guest-login:hover { color: #fff; transform: translateY(-1px); }
    .btn-guest-register {
        display: block;
        background: #fff;
        color: #0b5f8a;
        border: 2px solid #0b5f8a;
        border-radius: 14px;
        padding: 0.875rem;
        font-weight: 800;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-guest-register:hover {
        background: #0b5f8a;
        color: #fff;
    }

    /* ── App Version ── */
    .app-version-tag {
        text-align: center;
        font-size: 0.75rem;
        color: #cbd5e1;
        padding: 0.5rem 0 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="profile-page">
<div class="profile-inner">
    @auth
        @php
            $user = Auth::user();
            $isAdmin = $user->hasRole('admin') || $user->user_type === 'admin';
            $isTech  = $user->hasRole('technician') || $user->user_type === 'technician';
            $requestsCount = 0;
            $notifCount = $user->unreadNotifications()->count();

            if ($isAdmin) {
                $requestsCount = \App\Models\Request::where('status','pending')->count();
            } elseif ($isTech) {
                $technician = $user->technician;
                $requestsCount = $technician
                    ? \App\Models\RequestProposal::where('technician_id', $technician->id)->count()
                    : 0;
                $completedCount = $technician
                    ? \App\Models\Request::where('assigned_technician_id', $technician->id)->where('status','completed')->count()
                    : 0;
            } else {
                $requestsCount = \App\Models\Request::where('user_id', $user->id)->count();
                $completedCount = \App\Models\Request::where('user_id', $user->id)->where('status','completed')->count();
                $pendingCount = \App\Models\Request::where('user_id', $user->id)->whereIn('status',['pending','approved'])->count();
            }
        @endphp

        {{-- ──────────────── HERO ──────────────── --}}
        <div class="profile-hero text-center">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ffffff&color=083a56&size=180&bold=true"
                 class="profile-hero-avatar" alt="صورة الملف">
            <div class="profile-hero-name">{{ $user->name }}</div>
            <div class="profile-hero-email">{{ $user->email }}</div>

            @if($isAdmin)
                <span class="profile-role-badge">
                    <i class="fas fa-shield-alt"></i> مدير النظام
                </span>
            @elseif($isTech)
                <span class="profile-role-badge">
                    <i class="fas fa-hard-hat"></i> فني معتمد
                </span>
            @else
                <span class="profile-role-badge">
                    <i class="fas fa-user-check"></i> عميل موثق
                </span>
            @endif

            <br>
            <a href="{{ route('profile.edit') }}" class="btn-edit-profile">
                <i class="fas fa-pen"></i> تعديل الملف الشخصي
            </a>
        </div>

        {{-- ──────────────── STATS ──────────────── --}}
        <div class="stats-card">
            @if($isAdmin)
                <div class="stat-item">
                    <div class="stat-value">{{ \App\Models\User::count() }}</div>
                    <div class="stat-label">المستخدمون</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ \App\Models\Request::count() }}</div>
                    <div class="stat-label">الطلبات</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $requestsCount }}</div>
                    <div class="stat-label">طلبات جديدة</div>
                </div>
            @elseif($isTech)
                <div class="stat-item">
                    <div class="stat-value">{{ $requestsCount }}</div>
                    <div class="stat-label">عروضي</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $completedCount }}</div>
                    <div class="stat-label">مكتملة</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ number_format($user->technician?->rating ?? 0, 1) }}</div>
                    <div class="stat-label">التقييم</div>
                </div>
            @else
                <div class="stat-item">
                    <div class="stat-value">{{ $requestsCount }}</div>
                    <div class="stat-label">طلباتي</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $completedCount }}</div>
                    <div class="stat-label">مكتملة</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $pendingCount }}</div>
                    <div class="stat-label">قيد التنفيذ</div>
                </div>
            @endif
        </div>

        {{-- ──────────────── MENU ──────────────── --}}
        @if($isAdmin)
            <div class="section-label">لوحة الإدارة</div>
            <div class="menu-card">
                <a href="{{ route('admin.dashboard') }}" class="menu-item">
                    <div class="menu-item-icon" style="background:#e0f2fe; color:#0284c7;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="menu-item-body">
                        <p class="menu-item-title">لوحة التحكم</p>
                        <p class="menu-item-sub">إحصائيات وتقارير شاملة</p>
                    </div>
                    <i class="fas fa-chevron-left menu-item-arrow"></i>
                </a>
                <a href="{{ route('admin.requests.index') }}" class="menu-item">
                    <div class="menu-item-icon" style="background:#fef3c7; color:#d97706;">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="menu-item-body">
                        <p class="menu-item-title">إدارة الطلبات</p>
                        <p class="menu-item-sub">مراجعة ومتابعة جميع الطلبات</p>
                    </div>
                    @if($requestsCount > 0)
                        <span class="menu-item-badge">{{ $requestsCount }}</span>
                    @endif
                    <i class="fas fa-chevron-left menu-item-arrow"></i>
                </a>
                <a href="{{ route('admin.techs.index') }}" class="menu-item">
                    <div class="menu-item-icon" style="background:#d1fae5; color:#059669;">
                        <i class="fas fa-hard-hat"></i>
                    </div>
                    <div class="menu-item-body">
                        <p class="menu-item-title">إدارة الفنيين</p>
                        <p class="menu-item-sub">عرض وتعيين الفنيين</p>
                    </div>
                    <i class="fas fa-chevron-left menu-item-arrow"></i>
                </a>
                <a href="{{ route('admin.customers.index') }}" class="menu-item">
                    <div class="menu-item-icon" style="background:#ede9fe; color:#7c3aed;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="menu-item-body">
                        <p class="menu-item-title">إدارة العملاء</p>
                        <p class="menu-item-sub">عرض وإدارة حسابات العملاء</p>
                    </div>
                    <i class="fas fa-chevron-left menu-item-arrow"></i>
                </a>
            </div>

            <div class="section-label">المخزون والخدمات</div>
            <div class="menu-card">
                <a href="{{ route('admin.warehouse-items.index') }}" class="menu-item">
                    <div class="menu-item-icon" style="background:#fce7f3; color:#db2777;">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <div class="menu-item-body">
                        <p class="menu-item-title">المخزون</p>
                        <p class="menu-item-sub">إدارة القطع والمواد</p>
                    </div>
                    <i class="fas fa-chevron-left menu-item-arrow"></i>
                </a>
                <a href="{{ route('admin.services.index') }}" class="menu-item">
                    <div class="menu-item-icon" style="background:#ecfdf5; color:#10b981;">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="menu-item-body">
                        <p class="menu-item-title">الخدمات</p>
                        <p class="menu-item-sub">إدارة قائمة الخدمات</p>
                    </div>
                    <i class="fas fa-chevron-left menu-item-arrow"></i>
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="menu-item">
                    <div class="menu-item-icon" style="background:#fff7ed; color:#f97316;">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="menu-item-body">
                        <p class="menu-item-title">التقييمات</p>
                        <p class="menu-item-sub">مراجعة وإدارة التقييمات</p>
                    </div>
                    <i class="fas fa-chevron-left menu-item-arrow"></i>
                </a>
            </div>

        @elseif($isTech)
            <div class="section-label">لوحة الفني</div>
            <div class="menu-card">
                <a href="{{ route('technician.repair-requests.index') }}" class="menu-item">
                    <div class="menu-item-icon" style="background:#dbeafe; color:#2563eb;">
                        <i class="fas fa-wrench"></i>
                    </div>
                    <div class="menu-item-body">
                        <p class="menu-item-title">طلبات الإصلاح</p>
                        <p class="menu-item-sub">الطلبات المتاحة لتخصصك</p>
                    </div>
                    <i class="fas fa-chevron-left menu-item-arrow"></i>
                </a>
                <a href="{{ route('technician.my-requests') }}" class="menu-item">
                    <div class="menu-item-icon" style="background:#d1fae5; color:#059669;">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="menu-item-body">
                        <p class="menu-item-title">طلباتي</p>
                        <p class="menu-item-sub">الطلبات التي قدّمت عليها</p>
                    </div>
                    <i class="fas fa-chevron-left menu-item-arrow"></i>
                </a>
                <a href="{{ route('technician.profile.edit') }}" class="menu-item">
                    <div class="menu-item-icon" style="background:#ede9fe; color:#7c3aed;">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div class="menu-item-body">
                        <p class="menu-item-title">ملف الفني</p>
                        <p class="menu-item-sub">تحديث بيانات وموقعك</p>
                    </div>
                    <i class="fas fa-chevron-left menu-item-arrow"></i>
                </a>
            </div>

        @else
            <div class="section-label">طلباتي</div>
            <div class="menu-card">
                <a href="{{ route('dashboard') }}" class="menu-item">
                    <div class="menu-item-icon" style="background:#dbeafe; color:#2563eb;">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <div class="menu-item-body">
                        <p class="menu-item-title">لوحة التحكم</p>
                        <p class="menu-item-sub">ملخص طلباتك وأنشطتك</p>
                    </div>
                    <i class="fas fa-chevron-left menu-item-arrow"></i>
                </a>
                <a href="{{ route('requests.index') }}" class="menu-item">
                    <div class="menu-item-icon" style="background:#fef3c7; color:#d97706;">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="menu-item-body">
                        <p class="menu-item-title">طلباتي</p>
                        <p class="menu-item-sub">عرض وتتبع جميع طلباتك</p>
                    </div>
                    @if($pendingCount > 0)
                        <span class="menu-item-badge">{{ $pendingCount }}</span>
                    @endif
                    <i class="fas fa-chevron-left menu-item-arrow"></i>
                </a>
                <a href="{{ route('requests.create') }}" class="menu-item">
                    <div class="menu-item-icon" style="background:#dcfce7; color:#16a34a;">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="menu-item-body">
                        <p class="menu-item-title">طلب خدمة جديدة</p>
                        <p class="menu-item-sub">أضف طلب صيانة أو خدمة</p>
                    </div>
                    <i class="fas fa-chevron-left menu-item-arrow"></i>
                </a>
            </div>
        @endif

        {{-- ──────────────── NOTIFICATIONS & SETTINGS ──────────────── --}}
        <div class="section-label">الإعدادات</div>
        <div class="menu-card">
            <a href="{{ route('notifications.index') }}" class="menu-item">
                <div class="menu-item-icon" style="background:#fff7ed; color:#f97316;">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="menu-item-body">
                    <p class="menu-item-title">الإشعارات</p>
                    <p class="menu-item-sub">مراجعة كل التنبيهات</p>
                </div>
                @if($notifCount > 0)
                    <span class="menu-item-badge">{{ $notifCount }}</span>
                @endif
                <i class="fas fa-chevron-left menu-item-arrow"></i>
            </a>
            <a href="{{ route('profile.edit') }}" class="menu-item">
                <div class="menu-item-icon" style="background:#f1f5f9; color:#475569;">
                    <i class="fas fa-gear"></i>
                </div>
                <div class="menu-item-body">
                    <p class="menu-item-title">إعدادات الحساب</p>
                    <p class="menu-item-sub">تغيير البيانات وكلمة المرور</p>
                </div>
                <i class="fas fa-chevron-left menu-item-arrow"></i>
            </a>
        </div>

        {{-- ──────────────── LOGOUT ──────────────── --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                تسجيل الخروج
            </button>
        </form>

        <div class="app-version-tag">v1.0.0 · خدمتي</div>

    @else
        {{-- ──────────────── GUEST ──────────────── --}}
        <div class="guest-section">
            <div class="guest-avatar-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <h2 class="guest-title">مرحباً بك</h2>
            <p class="guest-text">سجّل الدخول للوصول إلى حسابك ومتابعة طلباتك والاستفادة من خدماتنا</p>
            <a href="{{ route('login') }}" class="btn-guest-login">
                <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
            </a>
            <a href="{{ route('register') }}" class="btn-guest-register">
                <i class="fas fa-user-plus me-2"></i>إنشاء حساب جديد
            </a>
        </div>
    @endauth
</div>{{-- profile-inner --}}
</div>{{-- profile-page --}}
@endsection
