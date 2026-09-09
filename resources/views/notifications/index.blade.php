@extends('layouts.mobile')

@section('title', 'الإشعارات')

@section('content')
    <div class="app-content py-5 px-2">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
            <div>
                <h4 class="fw-bold m-0" style="color: var(--text-primary);">الإشعارات</h4>
                <p class="text-muted small m-0">تابع آخر التحديثات والطلبات</p>
            </div>
            @if (auth()->user()->unreadNotifications()->count() > 0)
                <button id="mark-all-read" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold shadow-sm">
                    <i class="fas fa-check-double me-1"></i> تعليم الكل كمقروء
                </button>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                <i class="fas fa-check-circle me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if ($notifications->isEmpty())
            <div class="text-center py-5">
                <div class="empty-state-icon mb-3">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <h5 class="fw-bold text-muted">لا توجد إشعارات</h5>
                <p class="text-muted small">ليس لديك أي إشعارات جديدة في الوقت الحالي.</p>
                <a href="{{ route('home') }}" class="btn-primary rounded-pill px-4 mt-2">العودة للرئيسية</a>
            </div>
        @else
            <div class="notifications-list pb-5">
                @foreach ($notifications as $notification)
                    <div class="notification-card {{ $notification->read_at ? 'read' : 'unread' }} mb-3 fade-in"
                        @if(isset($notification->data['action_url'])) 
                            data-url="{{ $notification->data['action_url'] }}" 
                            style="cursor: pointer;"
                        @endif>
                        <div class="d-flex align-items-start p-3">
                            <div class="flex-shrink-0 ms-3">
                                <div
                                    class="icon-circle {{ $notification->read_at ? 'bg-light text-muted' : 'bg-primary-subtle text-primary' }}">
                                    <i
                                        class="fas {{ isset($notification->data['icon']) ? $notification->data['icon'] : 'fa-bell' }}"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="fw-bold mb-0 text-dark">{{ $notification->data['message'] ?? 'إشعار جديد' }}
                                    </h6>
                                    <span class="time-badge">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>

                                <div class="notification-details mb-2">
                                    @if (isset($notification->data['service_name']))
                                        <span class="badge bg-light text-dark border me-1">
                                            <i class="fas fa-tools me-1 text-muted"></i>
                                            {{ $notification->data['service_name'] }}
                                        </span>
                                    @endif
                                    @if (isset($notification->data['request_id']))
                                        <span class="badge bg-light text-dark border">
                                            <i class="fas fa-hashtag me-1 text-muted"></i>
                                            {{ $notification->data['request_id'] }}
                                        </span>
                                    @endif
                                </div>

                                @if (!$notification->read_at)
                                    <div class="d-flex justify-content-end mt-2">
                                        <button data-id="{{ $notification->id }}"
                                            class="mark-as-read btn btn-sm btn-link text-decoration-none p-0 text-primary fw-bold"
                                            style="font-size: 0.85rem;">
                                            تعليم كمقروء <i class="fas fa-check ms-1"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4 mb-5">
                {{ $notifications->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            .bg-primary-subtle {
                background-color: rgba(204, 51, 51, 0.1) !important;
            }

            .empty-state-icon {
                width: 80px;
                height: 80px;
                background: var(--bg-color);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 32px;
                color: var(--text-muted);
                margin: 0 auto;
            }

            .notification-card {
                background: var(--surface-color);
                border: 1px solid var(--border-color);
                border-radius: 16px;
                transition: all 0.2s ease;
                position: relative;
                overflow: hidden;
            }

            .notification-card.unread {
                border-left: 4px solid var(--primary-color);
                background: #fffafa;
                /* Very light tint */
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            }

            .notification-card.unread::before {
                content: '';
                position: absolute;
                top: 15px;
                right: 15px;
                width: 8px;
                height: 8px;
                background-color: var(--primary-color);
                border-radius: 50%;
            }

            .notification-card.read {
                opacity: 0.85;
            }

            .icon-circle {
                width: 45px;
                height: 45px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
            }

            .time-badge {
                font-size: 0.75rem;
                color: var(--text-muted);
                background: var(--bg-color);
                padding: 2px 8px;
                border-radius: 12px;
                white-space: nowrap;
                margin-right: 8px;
            }

            .notification-details .badge {
                font-weight: 500;
                padding: 5px 10px;
                border-radius: 8px;
            }

            /* Animation on read */
            .notification-card.marking-read {
                transform: scale(0.98);
                opacity: 0.7;
            }

            /* Clickable card hover effect */
            .notification-card[data-url]:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Helper: Update Navbar Badge locally
                function decrementNavbarBadge(count = 1) {
                    const badge = document.getElementById('notification-badge');
                    if (badge) {
                        let current = parseInt(badge.innerText) || 0;
                        let newCount = Math.max(0, current - count);
                        badge.innerText = newCount;
                        if (newCount === 0) badge.style.display = 'none';
                    }
                }

                // Click on card → navigate to action_url (mark as read first if unread)
                document.querySelectorAll('.notification-card[data-url]').forEach(card => {
                    card.addEventListener('click', function(e) {
                        // Don't trigger if clicking the mark-as-read button
                        if (e.target.closest('.mark-as-read')) return;

                        const url = this.getAttribute('data-url');
                        const notificationId = this.querySelector('.mark-as-read')?.getAttribute('data-id');

                        // If unread, mark as read silently then navigate
                        if (this.classList.contains('unread') && notificationId) {
                            fetch(`/notifications/${notificationId}/read`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                },
                            }).finally(() => {
                                window.location.href = url;
                            });
                        } else {
                            window.location.href = url;
                        }
                    });
                });

                // Mark single notification as read (Optimistic UI)
                document.querySelectorAll('.mark-as-read').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const btn = this;
                        const card = btn.closest('.notification-card');
                        const notificationId = btn.getAttribute('data-id');

                        // 1. Visual Feedback Immediately
                        card.classList.add('marking-read'); // Start animation
                        btn.style.pointerEvents = 'none'; // Prevent double clicks

                        // 2. Scheduled UI cleanup (matches CSS transition)
                        setTimeout(() => {
                            card.classList.remove('unread', 'marking-read');
                            card.classList.add('read');

                            // Remove button area
                            const actionArea = btn.closest('.d-flex');
                            if (actionArea) actionArea.remove();

                            // Update icon style
                            const iconBox = card.querySelector('.icon-circle');
                            if (iconBox) {
                                iconBox.classList.remove('bg-primary-subtle', 'text-primary');
                                iconBox.classList.add('bg-light', 'text-muted');
                            }

                            // 3. Update Badge Instantly
                            decrementNavbarBadge(1);
                        }, 300); // Wait for the 0.3s CSS transition

                        // 4. Send Request in Background
                        fetch(`/notifications/${notificationId}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                        }).catch(error => {
                            console.error('Network Error:', error);
                            // Optionally revert UI here if needed
                        });
                    });
                });

                // Mark All as Read (Optimistic UI)
                const markAllReadButton = document.getElementById('mark-all-read');
                if (markAllReadButton) {
                    markAllReadButton.addEventListener('click', function() {
                        const btn = this;

                        // 1. Visual Feedback
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري التحديث...';
                        btn.disabled = true;

                        // 2. Update all unread cards visually immediately
                        const unreadCards = document.querySelectorAll('.notification-card.unread');
                        unreadCards.forEach(card => {
                            card.classList.remove('unread');
                            card.classList.add('read');

                            // Remove buttons inside them
                            const actionBtn = card.querySelector('.mark-as-read');
                            if (actionBtn) {
                                const actionArea = actionBtn.closest('.d-flex');
                                if (actionArea) actionArea.remove();
                            }

                            // Update icons
                            const iconBox = card.querySelector('.icon-circle');
                            if (iconBox) {
                                iconBox.classList.remove('bg-primary-subtle', 'text-primary');
                                iconBox.classList.add('bg-light', 'text-muted');
                            }
                        });

                        // 3. Clear Badge
                        const badge = document.getElementById('notification-badge');
                        if (badge) badge.style.display = 'none';

                        // 4. Send Server Request
                        fetch('/notifications/mark-all-read', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    btn.remove(); // Remove the "Mark all" button
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                // If failed, we might want to reload to sync state
                                // location.reload(); 
                            });
                    });
                }
            });
        </script>
    @endpush
@endsection
