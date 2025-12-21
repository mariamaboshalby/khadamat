<section>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-4">
                <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle"
                    style="width: 50px; height: 50px;">
                    <i class="fas fa-user fs-4"></i>
                </div>
                <div class="ms-3 me-3">
                    <h5 class="fw-bold mb-1">{{ __('معلومات الملف الشخصي') }}</h5>
                    <p class="text-muted small mb-0">
                        {{ __('قم بتحديث معلومات ملفك الشخصي وعنوان البريد الإلكتروني.') }}
                    </p>
                </div>
            </div>

            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="mb-3">
                    <label for="name" class="form-label fw-bold small text-secondary">{{ __('الاسم') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="fas fa-user small"></i>
                        </span>
                        <input type="text" name="name" id="name"
                            class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                    </div>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email"
                        class="form-label fw-bold small text-secondary">{{ __('البريد الإلكتروني') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="fas fa-envelope small"></i>
                        </span>
                        <input type="email" name="email" id="email"
                            class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required autocomplete="username">
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div class="alert alert-warning mt-3 d-flex align-items-start" role="alert">
                            <i class="fas fa-exclamation-triangle mt-1 ms-2"></i>
                            <div>
                                <p class="mb-1 text-dark small">{{ __('عنوان بريدك الإلكتروني غير مؤكد.') }}</p>
                                <button form="send-verification"
                                    class="btn btn-link p-0 small text-decoration-none fw-bold">
                                    {{ __('انقر هنا لإعادة إرسال رسالة التحقق.') }}
                                </button>
                                @if (session('status') === 'verification-link-sent')
                                    <div class="text-success small mt-1 fw-bold">
                                        {{ __('تم إرسال رابط تحقق جديد إلى عنوان بريدك الإلكتروني.') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                        <i class="fas fa-save ms-2"></i>
                        {{ __('حفظ التغييرات') }}
                    </button>

                    @if (session('status') === 'profile-updated')
                        <span x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                            class="text-success small fw-bold fade-in">
                            <i class="fas fa-check-circle ms-1"></i> {{ __('تم الحفظ.') }}
                        </span>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>
