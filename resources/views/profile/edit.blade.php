@extends('layouts.mobile')

@section('title', 'اعدادات الحساب')

@section('content')
    <div class="app-content fade-in">
        <div class="profile-settings">

            <div class="ps-head">
                <h1>اعدادات الحساب</h1>
                <p>إدارة بياناتك الشخصية وكلمة المرور</p>
            </div>

            <div class="ps-card">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="ps-card">
                @include('profile.partials.update-password-form')
            </div>

            <div class="ps-card ps-danger">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>

    <style>
        .profile-settings {
            --bs-primary-rgb: 11, 95, 138;
            max-width: 720px;
            margin: 0 auto;
            padding: 8px 14px 40px;
        }
        .ps-head { margin-bottom: 14px; }
        .ps-head h1 { font-size: 21px; font-weight: 800; color: #072540; margin: 0; }
        .ps-head p { font-size: 13px; color: #64748b; margin: 3px 0 0; }

        .ps-card { margin-bottom: 14px; }
        .ps-card > section { padding: 0; }
        .ps-card .card {
            border: 1px solid #e6ecf2 !important;
            border-radius: 16px !important;
            box-shadow: none !important;
            background: #fff;
            overflow: hidden;
        }
        .ps-card .card-body { padding: 16px 18px !important; }
        .ps-card .card-body.p-4 { padding: 16px 18px !important; }
        .ps-card .mb-4 { margin-bottom: 14px !important; }

        /* Brand-teal primary buttons */
        .profile-settings .btn-primary {
            --bs-btn-bg: #0b5f8a;
            --bs-btn-border-color: #0b5f8a;
            --bs-btn-hover-bg: #094d70;
            --bs-btn-hover-border-color: #094d70;
            --bs-btn-active-bg: #073f5c;
            --bs-btn-active-border-color: #073f5c;
            --bs-btn-disabled-bg: #0b5f8a;
            --bs-btn-disabled-border-color: #0b5f8a;
            font-weight: 700;
        }

        /* Danger (delete) card */
        .ps-danger .card { border-color: #fecaca !important; border-inline-start: 4px solid #e03131 !important; }
        .ps-danger .btn-danger { font-weight: 700; }

        @media (max-width: 520px) {
            .ps-card .card-body { padding: 14px !important; }
        }
    </style>
@endsection
