<section>
    <div class="card border-0 shadow-sm border-danger border-end border-3">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-4">
                <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle"
                    style="width: 50px; height: 50px;">
                    <i class="fas fa-exclamation-triangle fs-4"></i>
                </div>
                <div class="ms-3 me-3">
                    <h5 class="fw-bold mb-1">{{ __('حذف الحساب') }}</h5>
                    <p class="text-muted small mb-0">
                        {{ __('بمجرد حذف حسابك، سيتم حذف جميع مواردك وبياناتك نهائيًا.') }}
                    </p>
                </div>
            </div>

            <p class="text-muted small mb-4">
                {{ __('قبل حذف حسابك، يرجى تنزيل أي بيانات أو معلومات ترغب في الاحتفاظ بها.') }}
            </p>

            <button type="button" class="btn btn-danger px-4 fw-bold shadow-sm" data-bs-toggle="modal"
                data-bs-target="#confirmUserDeletionModal">
                <i class="fas fa-trash-alt ms-2"></i>
                {{ __('حذف الحساب') }}
            </button>

            <!-- Modal -->
            <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1"
                aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <form method="post" action="{{ route('profile.destroy') }}" class="p-2">
                            @csrf
                            @method('delete')

                            <div class="modal-header border-bottom-0 pb-0">
                                <h5 class="modal-title fw-bold text-danger" id="confirmUserDeletionModalLabel">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ __('هل أنت متأكد من أنك تريد حذف حسابك؟') }}
                                </h5>
                                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <p class="text-muted small mb-4">
                                    {{ __('بمجرد حذف حسابك، سيتم حذف جميع مواردك وبياناتك نهائيًا. يرجى إدخال كلمة المرور الخاصة بك لتأكيد رغبتك في حذف حسابك بشكل دائم.') }}
                                </p>

                                <div class="mb-3">
                                    <label for="password"
                                        class="form-label fw-bold small text-secondary">{{ __('كلمة المرور') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted">
                                            <i class="fas fa-lock small"></i>
                                        </span>
                                        <input type="password" id="password" name="password"
                                            class="form-control border-start-0 ps-0 @error('password', 'userDeletion') is-invalid @enderror"
                                            placeholder="{{ __('كلمة المرور') }}" required>
                                    </div>
                                    @error('password', 'userDeletion')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="modal-footer border-top-0 pt-0">
                                <button type="button" class="btn btn-light fw-bold"
                                    data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                                <button type="submit" class="btn btn-danger fw-bold shadow-sm">
                                    {{ __('حذف الحساب') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if ($errors->userDeletion->isNotEmpty())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var myModal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
                        myModal.show();
                    });
                </script>
            @endif
        </div>
    </div>
</section>
