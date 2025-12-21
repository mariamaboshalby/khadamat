<section>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-4">
                <div class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-circle"
                    style="width: 50px; height: 50px;">
                    <i class="fas fa-lock fs-4"></i>
                </div>
                <div class="ms-3 me-3">
                    <h5 class="fw-bold mb-1">{{ __('تحديث كلمة المرور') }}</h5>
                    <p class="text-muted small mb-0">
                        {{ __('للحفاظ على أمانك، يرجى استخدام كلمة مرور قوية وطويلة.') }}
                    </p>
                </div>
            </div>

            <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('put')

                <div class="mb-3">
                    <label for="update_password_current_password"
                        class="form-label fw-bold small text-secondary">{{ __('كلمة المرور الحالية') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="fas fa-key small"></i>
                        </span>
                        <input type="password" name="current_password" id="update_password_current_password"
                            class="form-control border-start-0 ps-0 @error('current_password', 'updatePassword') is-invalid @enderror"
                            autocomplete="current-password">
                    </div>
                    @error('current_password', 'updatePassword')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="update_password_password"
                        class="form-label fw-bold small text-secondary">{{ __('كلمة المرور الجديدة') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="fas fa-lock small"></i>
                        </span>
                        <input type="password" name="password" id="update_password_password"
                            class="form-control border-start-0 ps-0 @error('password', 'updatePassword') is-invalid @enderror"
                            autocomplete="new-password">
                    </div>
                    @error('password', 'updatePassword')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="update_password_password_confirmation"
                        class="form-label fw-bold small text-secondary">{{ __('تأكيد كلمة المرور') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="fas fa-check-circle small"></i>
                        </span>
                        <input type="password" name="password_confirmation" id="update_password_password_confirmation"
                            class="form-control border-start-0 ps-0 @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                            autocomplete="new-password">
                    </div>
                    @error('password_confirmation', 'updatePassword')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                        <i class="fas fa-save ms-2"></i>
                        {{ __('حفظ التغييرات') }}
                    </button>

                    @if (session('status') === 'password-updated')
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
