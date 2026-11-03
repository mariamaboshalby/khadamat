@extends('layouts.mobile')

@section('title', 'الإشعارات')

@section('content')
    <div class="notif-page fade-in">

        <!-- Header -->
        <div class="notif-header">
            <div class="notif-header-text">
                <h1 class="notif-h1">الإشعارات</h1>
                <p class="notif-sub">تابع آخر التحديثات والطلبات</p>
            </div>
            @if (auth()->user()->unreadNotifications()->count() > 0)
                <button id="mark-all-read" class="btn-markall">
                    <i class="fas fa-check-double me-1"></i> تعليم الكل كمقروء
                </button>
            @endif
        </div>

        @if (session('success'))
            <div class="notif-flash"><i class="fas fa-circle-check"></i> {{ session('success') }}</div>
        @endif

        @if ($notifications->isEmpty())
            <div class="notif-empty-state">
                <div class="empty-ico"><i class="fas fa-bell-slash"></i></div>
                <h5 class="empty-title">لا توجد إشعارات</h5>
                <p class="empty-text">ليس لديك أي إشعارات جديدة في الوقت الحالي.</p>
                <a href="{{ route('home') }}" class="btn-home">العودة للرئيسية</a>
            </div>
        @else
            <div class="notif-list">
                @foreach ($notifications as $notification)
                    <div class="notif-card {{ $notification->read_at ? 'read' : 'unread' }}"
                        @if (isset($notification->data['action_url']))
                            data-url="{{ $notification->data['action_url'] }}" style="cursor: pointer;"
                        @endif>
                        <div class="notif-card-inner">
                            <div class="notif-ico">
                                <i class="fas {{ $notification->data['icon'] ?? 'fa-bell' }}"></i>
                            </div>

                            <div class="notif-main">
                                <div class="notif-top">
                                    <h6 class="notif-msg">{{ $notification->data['message'] ?? 'إشعار جديد' }}</h6>
                                    <span class="notif-time">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>

                                @if (isset($notification->data['service_name']) || isset($notification->data['request_id']))
                                    <div class="notif-tags">
                                        @if (isset($notification->data['service_name']))
                                            <span class="notif-tag"><i class="fas fa-wrench"></i>{{ $notification->data['service_name'] }}</span>
                                        @endif
                                        @if (isset($notification->data['request_id']))
                                            <span class="notif-tag"><i class="fas fa-hashtag"></i>{{ $notification->data['request_id'] }}</span>
                                        @endif
                                    </div>
                                @endif

                                @if (!$notification->read_at)
                                    <div class="notif-actions">
                                        <button data-id="{{ $notification->id }}"
                                            class="mark-as-read">
                                            تعليم كمقروء <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="notif-pagination">
                {{ $notifications->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            .notif-page {
                max-width: 760px;
                margin: 0 auto;
                padding: 24px 16px 48px;
            }

            .notif-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                flex-wrap: wrap;
                margin-bottom: 20px;
            }
            .notif-h1 { font-size: 22px; font-weight: 800; color: var(--text-primary); margin: 0; }
            .notif-sub { color: var(--text-muted); font-size: 13px; margin: 2px 0 0; }

            .btn-markall {
                background: #eef5f9;
                color: var(--primary-color);
                border: 1px solid #d7e8f1;
                border-radius: 30px;
                padding: 8px 16px;
                font-weight: 700;
                font-size: 13px;
                transition: all .2s;
            }
            .btn-markall:hover { background: var(--primary-color); color: #fff; border-color: var(--primary-color); }

            .notif-flash {
                display: flex; align-items: center; gap: 10px;
                background: #e9f7f0; color: #067647;
                border: 1px solid #b7e4c7; border-radius: 12px;
                padding: 12px 16px; margin-bottom: 16px; font-weight: 600; font-size: 14px;
            }

            .notif-list { display: flex; flex-direction: column; gap: 12px; }

            .notif-card {
                background: var(--surface-color);
                border: 1px solid var(--border-color);
                border-radius: 16px;
                transition: all .2s ease;
                position: relative;
                overflow: hidden;
            }
            .notif-card.unread {
                border-color: #cfe4ef;
                box-shadow: 0 6px 18px -8px rgba(11, 95, 138, .18);
            }
            .notif-card.unread::before {
                content: '';
                position: absolute;
                inset-inline-start: 0;
                top: 0; bottom: 0;
                width: 4px;
                background: var(--primary-color);
            }
            .notif-card.read { opacity: .72; }
            .notif-card[data-url]:hover { transform: translateY(-2px); box-shadow: 0 10px 24px -10px rgba(11,95,138,.28); }

            .notif-card-inner { display: flex; align-items: flex-start; gap: 14px; padding: 16px 18px; }

            .notif-ico {
                width: 44px; height: 44px; flex-shrink: 0;
                display: flex; align-items: center; justify-content: center;
                border-radius: 12px; font-size: 18px;
                background: #eef5f9; color: var(--primary-color);
            }
            .notif-card.read .notif-ico { background: #f1f5f9; color: var(--text-muted); }

            .notif-main { flex: 1; min-width: 0; }

            .notif-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
            .notif-msg { font-weight: 700; color: var(--text-primary); margin: 0; font-size: 15px; line-height: 1.5; }
            .notif-card.read .notif-msg { font-weight: 600; color: var(--text-secondary); }

            .notif-time {
                font-size: 12px; color: var(--text-muted);
                background: #f1f5f9; padding: 3px 10px; border-radius: 20px;
                white-space: nowrap; flex-shrink: 0;
            }

            .notif-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
            .notif-tag {
                display: inline-flex; align-items: center; gap: 6px;
                background: #f8fafc; border: 1px solid var(--border-color);
                color: var(--text-secondary); border-radius: 8px;
                padding: 4px 10px; font-size: 12px; font-weight: 600;
            }
            .notif-tag i { color: var(--text-muted); font-size: 11px; }

            .notif-actions { display: flex; justify-content: flex-end; margin-top: 10px; }
            .mark-as-read {
                background: none; border: none; padding: 0;
                color: var(--primary-color); font-weight: 700; font-size: 13px;
                display: inline-flex; align-items: center; gap: 6px; cursor: pointer;
            }
            .mark-as-read:hover { text-decoration: underline; }

            .notif-card.marking-read { transform: scale(.985); opacity: .6; }

            /* Empty state */
            .notif-empty-state { text-align: center; padding: 48px 20px; }
            .empty-ico {
                width: 84px; height: 84px; margin: 0 auto 16px;
                background: #f1f5f9; border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                font-size: 34px; color: var(--text-muted);
            }
            .empty-title { font-weight: 800; color: var(--text-secondary); }
            .empty-text { color: var(--text-muted); font-size: 14px; margin-bottom: 18px; }
            .btn-home {
                display: inline-block; background: var(--primary-color); color: #fff;
                padding: 10px 24px; border-radius: 30px; font-weight: 700; text-decoration: none;
                transition: all .2s;
            }
            .btn-home:hover { background: var(--primary-dark); color: #fff; }

            .notif-pagination { display: flex; justify-content: center; margin-top: 24px; }

            @media (max-width: 480px) {
                .notif-top { flex-direction: column-reverse; align-items: flex-start; }
                .notif-time { margin-bottom: 4px; }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                function updateBadges(count) {
                    document.querySelectorAll('.js-notif-count').forEach(function(b) {
                        if (count > 0) { b.style.display = ''; b.textContent = count > 99 ? '99+' : count; }
                        else { b.style.display = 'none'; }
                    });
                }

                function currentBadgeCount() {
                    const b = document.querySelector('.js-notif-count');
                    return b ? (parseInt((b.textContent || '0').replace('+', '')) || 0) : 0;
                }

                // Click card → navigate (mark read first if unread)
                document.querySelectorAll('.notif-card[data-url]').forEach(card => {
                    card.addEventListener('click', function(e) {
                        if (e.target.closest('.mark-as-read')) return;
                        const url = this.getAttribute('data-url');
                        const readBtn = this.querySelector('.mark-as-read');
                        if (this.classList.contains('unread') && readBtn) {
                            fetch(`/notifications/${readBtn.getAttribute('data-id')}/read`, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' }
                            }).finally(() => { window.location.href = url; });
                        } else {
                            window.location.href = url;
                        }
                    });
                });

                // Mark single as read (optimistic)
                document.querySelectorAll('.mark-as-read').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const btn = this;
                        const card = btn.closest('.notif-card');
                        const notificationId = btn.getAttribute('data-id');

                        card.classList.add('marking-read');
                        btn.style.pointerEvents = 'none';

                        setTimeout(() => {
                            card.classList.remove('unread', 'marking-read');
                            card.classList.add('read');
                            const actionArea = btn.closest('.notif-actions');
                            if (actionArea) actionArea.remove();
                            updateBadges(Math.max(0, currentBadgeCount() - 1));
                        }, 300);

                        fetch(`/notifications/${notificationId}/read`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' }
                        }).catch(err => console.error('Network Error:', err));
                    });
                });

                // Mark all as read
                const markAllReadButton = document.getElementById('mark-all-read');
                if (markAllReadButton) {
                    markAllReadButton.addEventListener('click', function() {
                        const btn = this;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري التحديث...';
                        btn.disabled = true;

                        document.querySelectorAll('.notif-card.unread').forEach(card => {
                            card.classList.remove('unread');
                            card.classList.add('read');
                            const actionArea = card.querySelector('.notif-actions');
                            if (actionArea) actionArea.remove();
                        });

                        updateBadges(0);

                        fetch('/notifications/mark-all-read', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' }
                        }).then(r => r.json()).then(data => {
                            if (data.success) btn.remove();
                        }).catch(err => console.error('Error:', err));
                    });
                }
            });
        </script>
    @endpush
@endsection
