@extends('layouts.mobile')

@section('title', 'اعدادات الحساب')

@section('content')
    <div class="app-content fade-in p-3">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-bold m-0">اعدادات الحساب</h4>
        </div>

        <div class="profile-edit-wrapper mb-4">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="profile-edit-wrapper mb-4">
            @include('profile.partials.update-password-form')
        </div>

        <div class="profile-edit-wrapper">
            @include('profile.partials.delete-user-form')
        </div>
    </div>

    <style>
        .profile-edit-wrapper {
            border-radius: 20px;
            padding: 0;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .profile-edit-wrapper>section {
            padding: 0;
        }
    </style>
@endsection
