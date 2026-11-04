@extends('layouts.mobile')

@section('title', 'لوحة تحكم العميل | خدمات')

@section('content')

    <div class="dash">

        <!-- Header -->
        <div class="dash-head">
            <div>
                <h1 class="dash-hi">مرحباً، {{ Auth::user()->name }}</h1>
                <p class="dash-tag">نظرة سريعة على طلباتك وأحدث التحديثات.</p>
            </div>
            <a href="{{ route('requests.create') }}" class="dash-cta">
                <i class="fas fa-plus"></i> طلب خدمة جديدة
            </a>
        </div>

        <!-- Price approval alert -->
        @if (($stats['pending_approval'] ?? 0) > 0)
            @php $pendingPriceReq = $requests->firstWhere('price_status', 'pending_customer_approval'); @endphp
            <div class="dash-notice">
                <i class="fas fa-file-invoice-dollar"></i>
                <span class="dash-notice-text">
                    يوجد {{ $stats['pending_approval'] }} عرض سعر بانتظار موافقتك.
                </span>
                @if ($pendingPriceReq)
                    <a href="{{ route('pricing.show', \App\Helpers\EncryptionHelper::encryptId($pendingPriceReq->id)) }}">مراجعة</a>
                @endif
            </div>
        @endif

        <!-- Stats: single segmented bar -->
        <div class="dash-stats">
            <button type="button" class="dash-stat" onclick="filterByStatus('all')">
                <span class="dash-stat-num">{{ $stats['total'] ?? 0 }}</span>
                <span class="dash-stat-label">إجمالي الطلبات</span>
            </button>
            <button type="button" class="dash-stat" onclick="filterByStatus('in_progress')">
                <span class="dash-stat-num">{{ $stats['in_progress'] ?? 0 }}</span>
                <span class="dash-stat-label">جاري التنفيذ</span>
            </button>
            <button type="button" class="dash-stat" onclick="filterByStatus('pending')">
                <span class="dash-stat-num">{{ $stats['pending'] ?? 0 }}</span>
                <span class="dash-stat-label">قيد المراجعة</span>
            </button>
            <button type="button" class="dash-stat" onclick="filterByStatus('completed')">
                <span class="dash-stat-num">{{ $stats['completed'] ?? 0 }}</span>
                <span class="dash-stat-label">المكتملة</span>
            </button>
        </div>

        <!-- Active request tracking -->
        @if ($activeRequest)
            @php
                $step = 1;
                if ($activeRequest->status === 'approved') $step = 2;
                elseif ($activeRequest->status === 'in_progress') $step = 3;
                elseif ($activeRequest->status === 'completed') $step = 4;
                $stMap = [
                    'pending' => 'قيد الانتظار', 'approved' => 'تم قبول الطلب',
                    'in_progress' => 'جاري العمل', 'completed' => 'مكتمل', 'cancelled' => 'ملغي',
                ];
            @endphp
            <div class="dash-panel">
                <div class="dash-panel-head">
                    <span class="dash-panel-title">متابعة الطلب الحالي</span>
                    <span class="dash-status"><i class="dot"></i>{{ $stMap[$activeRequest->status] ?? $activeRequest->status }}</span>
                </div>

                <div class="dash-track">
                    @foreach ([['الاستلام','fa-clipboard-check'],['تعيين الفني','fa-user-check'],['التنفيذ','fa-wrench'],['الإنجاز','fa-flag-checkered']] as $i => $s)
                        <div class="dash-node {{ $step >= $i+1 ? 'on' : '' }}">
                            <span class="dash-node-dot"><i class="fas {{ $s[1] }}"></i></span>
                            <span class="dash-node-label">{{ $s[0] }}</span>
                        </div>
                        @if (!$loop->last)
                            <span class="dash-node-bar {{ $step >= $i+2 ? 'on' : '' }}"></span>
                        @endif
                    @endforeach
                </div>

                <div class="dash-panel-foot">
                    <span class="dash-foot-service">{{ $activeRequest->service->name ?? 'خدمة عامة' }}</span>
                    @if ($activeRequest->assignedTechnician)
                        <span class="dash-foot-tech"><i class="fas fa-user"></i> {{ $activeRequest->assignedTechnician->user->name ?? 'فني معتمد' }}</span>
                    @endif
                    <a href="{{ route('requests.show', \App\Helpers\EncryptionHelper::encryptId($activeRequest->id)) }}" class="dash-tlink">
                        التفاصيل <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        @endif

        <!-- Requests -->
        <div class="dash-list">
            <div class="dash-list-head">
                <h2 class="dash-h2">طلباتي</h2>
                <div class="dash-tabs">
                    <button class="dash-tab active" data-filter="all">الكل <span>{{ $stats['total'] ?? 0 }}</span></button>
                    <button class="dash-tab" data-filter="in_progress">جاري <span>{{ $stats['in_progress'] ?? 0 }}</span></button>
                    <button class="dash-tab" data-filter="pending">معلق <span>{{ $stats['pending'] ?? 0 }}</span></button>
                    <button class="dash-tab" data-filter="completed">مكتمل <span>{{ $stats['completed'] ?? 0 }}</span></button>
                    <button class="dash-tab" data-filter="cancelled">ملغي <span>{{ $stats['cancelled'] ?? 0 }}</span></button>
                </div>
            </div>

            @if ($requests->count() > 0)
                <div id="requestsContainer">
                    @foreach ($requests as $request)
                        @php
                            $statusKey = $request->status;
                            $filterGroup = in_array($statusKey, ['approved', 'in_progress']) ? 'in_progress' : $statusKey;
                            $st = [
                                'pending' => ['c' => '#d97706', 't' => 'قيد الانتظار'],
                                'approved' => ['c' => '#0891b2', 't' => 'تم القبول'],
                                'in_progress' => ['c' => '#0b5f8a', 't' => 'جاري التنفيذ'],
                                'completed' => ['c' => '#059669', 't' => 'مكتمل'],
                                'cancelled' => ['c' => '#dc2626', 't' => 'ملغي'],
                            ][$statusKey] ?? ['c' => '#667085', 't' => $statusKey];
                        @endphp

                        <div class="dash-row" data-status="{{ $filterGroup }}">
                            <div class="dash-row-main">
                                <div class="dash-row-top">
                                    <span class="dash-row-name">{{ $request->service->name ?? 'خدمة صيانة' }}</span>
                                    <span class="dash-status" style="--dot: {{ $st['c'] }}; color: {{ $st['c'] }};"><i class="dot"></i>{{ $st['t'] }}</span>
                                </div>
                                <div class="dash-row-meta">
                                    <span><i class="far fa-calendar"></i> {{ $request->created_at->format('Y/m/d') }}</span>
                                    @if ($request->address)
                                        <span><i class="fas fa-map-marker-alt"></i> {{ Str::limit($request->address, 30) }}</span>
                                    @endif
                                    @if ($request->total_price || $request->proposed_price)
                                        <span><i class="fas fa-coins"></i> {{ number_format($request->total_price ?: $request->proposed_price, 2) }} ج.م</span>
                                    @endif
                                </div>
                            </div>
                            <div class="dash-row-actions">
                                @if ($request->price_status === 'pending_customer_approval')
                                    <a href="{{ route('pricing.show', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" class="dash-mini dash-mini-warn">مراجعة السعر</a>
                                @endif
                                @if ($request->status === 'pending')
                                    <form action="{{ route('requests.destroy', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" method="POST"
                                        onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟');" class="m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dash-mini dash-mini-danger">إلغاء</button>
                                    </form>
                                @endif
                                <a href="{{ route('requests.show', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" class="dash-mini dash-mini-primary">التفاصيل</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="noFilterResults" class="dash-none d-none">لا توجد طلبات في هذا التصنيف.</div>
            @else
                <div class="dash-empty">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>لا توجد طلبات حالياً</h3>
                    <p>ابدأ بطلب خدمة وسنصلك بأفضل الفنيين المعتمدين.</p>
                    <a href="{{ route('requests.create') }}" class="dash-cta">طلب خدمة جديدة</a>
                </div>
            @endif
        </div>

        <!-- Support -->
        <div class="dash-support">
            <span>تحتاج مساعدة؟ فريق الدعم متاح على مدار الساعة.</span>
            <a href="https://wa.me/201000000000" target="_blank"><i class="fab fa-whatsapp"></i> تواصل مع الدعم</a>
        </div>

    </div>

@endsection

@push('styles')
    <style>
        .dash {
            --ink: #101828;
            --muted: #667085;
            --faint: #98a2b3;
            --line: #eaecf0;
            --accent: #0b5f8a;
            --accent-soft: #eef4f8;
            max-width: 940px;
            margin: 0 auto;
            padding: 28px 18px 64px;
        }

        /* Header */
        .dash-head {
            display: flex; align-items: flex-end; justify-content: space-between;
            gap: 16px; flex-wrap: wrap; padding-bottom: 22px; margin-bottom: 22px;
            border-bottom: 1px solid var(--line);
        }
        .dash-hi { font-size: 1.5rem; font-weight: 700; color: var(--ink); margin: 0; letter-spacing: -.2px; }
        .dash-tag { color: var(--muted); font-size: .92rem; margin: 4px 0 0; }
        .dash-cta {
            display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
            background: var(--accent); color: #fff; font-weight: 700; font-size: 14px;
            padding: 11px 20px; border-radius: 10px; transition: background .2s ease;
        }
        .dash-cta:hover { background: #094d70; color: #fff; }

        /* Notice */
        .dash-notice {
            display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
            background: var(--accent-soft); border-radius: 12px; padding: 14px 18px; margin-bottom: 22px;
            font-size: .9rem; color: var(--ink);
        }
        .dash-notice > i { color: var(--accent); }
        .dash-notice-text { margin-inline-end: auto; }
        .dash-notice a { color: var(--accent); font-weight: 700; text-decoration: none; }
        .dash-notice a:hover { text-decoration: underline; }

        /* Stats — one segmented bar */
        .dash-stats {
            display: grid; grid-template-columns: repeat(4, 1fr);
            background: #fff; border: 1px solid var(--line); border-radius: 14px;
            overflow: hidden; margin-bottom: 22px;
        }
        .dash-stat {
            background: none; border: none; cursor: pointer; padding: 20px 12px;
            display: flex; flex-direction: column; align-items: center; gap: 4px;
            border-inline-start: 1px solid var(--line); transition: background .18s ease;
        }
        .dash-stat:first-child { border-inline-start: none; }
        .dash-stat:hover { background: #f9fafb; }
        .dash-stat-num { font-size: 1.6rem; font-weight: 700; color: var(--ink); line-height: 1; }
        .dash-stat-label { font-size: .78rem; color: var(--muted); font-weight: 500; }
        @media (max-width: 575px) {
            .dash-stats { grid-template-columns: repeat(2, 1fr); }
            .dash-stat:nth-child(3) { border-inline-start: none; }
            .dash-stat:nth-child(n+3) { border-top: 1px solid var(--line); }
        }

        /* Panels (tracking) */
        .dash-panel {
            background: #fff; border: 1px solid var(--line); border-radius: 14px;
            padding: 22px; margin-bottom: 30px;
        }
        .dash-panel-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; }
        .dash-panel-title { font-weight: 700; color: var(--ink); font-size: 1rem; }

        .dash-status {
            display: inline-flex; align-items: center; gap: 7px; font-size: .82rem;
            font-weight: 600; color: var(--accent);
        }
        .dash-status .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--dot, #10b981); display: inline-block; }

        /* Tracker */
        .dash-track { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 22px; }
        .dash-node { display: flex; flex-direction: column; align-items: center; gap: 8px; color: var(--faint); }
        .dash-node-dot {
            width: 36px; height: 36px; border-radius: 50%; background: #f2f4f7; color: var(--faint);
            display: flex; align-items: center; justify-content: center; font-size: 14px;
            transition: all .3s ease;
        }
        .dash-node-label { font-size: .76rem; font-weight: 600; }
        .dash-node.on { color: var(--accent); }
        .dash-node.on .dash-node-dot { background: var(--accent); color: #fff; }
        .dash-node-bar { flex: 1; height: 2px; background: #e4e7ec; margin: 17px 6px 0; }
        .dash-node-bar.on { background: var(--accent); }

        .dash-panel-foot { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; padding-top: 16px; border-top: 1px solid var(--line); }
        .dash-foot-service { font-weight: 700; color: var(--ink); }
        .dash-foot-tech { color: var(--muted); font-size: .85rem; display: inline-flex; align-items: center; gap: 6px; }
        .dash-tlink { margin-inline-start: auto; color: var(--accent); font-weight: 700; font-size: .88rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
        .dash-tlink:hover { text-decoration: underline; }

        /* List */
        .dash-list-head { display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; margin-bottom: 16px; }
        .dash-h2 { font-size: 1.15rem; font-weight: 700; color: var(--ink); margin: 0; }
        .dash-tabs { display: flex; gap: 4px; overflow-x: auto; padding-bottom: 2px; scrollbar-width: none; }
        .dash-tabs::-webkit-scrollbar { display: none; }
        .dash-tab {
            background: none; border: none; color: var(--muted); padding: 8px 14px;
            border-radius: 8px; font-size: .86rem; font-weight: 600; white-space: nowrap;
            cursor: pointer; transition: all .16s ease; display: inline-flex; align-items: center; gap: 6px;
        }
        .dash-tab span { font-size: .76rem; color: var(--faint); }
        .dash-tab:hover { background: #f5f6f8; color: var(--ink); }
        .dash-tab.active { background: var(--accent-soft); color: var(--accent); }
        .dash-tab.active span { color: var(--accent); }

        .dash-row {
            background: #fff; border: 1px solid var(--line); border-radius: 12px;
            padding: 18px 20px; margin-bottom: 10px; transition: border-color .18s ease;
        }
        .dash-row:hover { border-color: #dfe3e8; }
        .dash-row-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .dash-row-name { font-weight: 700; color: var(--ink); font-size: 1rem; }
        .dash-row-meta { display: flex; flex-wrap: wrap; gap: 6px 20px; margin-top: 8px; }
        .dash-row-meta span { color: var(--muted); font-size: .82rem; display: inline-flex; align-items: center; gap: 7px; }
        .dash-row-meta i { color: var(--faint); }
        .dash-row-actions { display: flex; align-items: center; gap: 8px; margin-top: 16px; padding-top: 14px; border-top: 1px solid var(--line); }

        .dash-mini {
            display: inline-flex; align-items: center; text-decoration: none; cursor: pointer;
            font-size: .84rem; font-weight: 600; padding: 8px 16px; border-radius: 8px; border: 1px solid transparent;
            transition: all .16s ease;
        }
        .dash-mini-primary { background: var(--accent); color: #fff; margin-inline-start: auto; }
        .dash-mini-primary:hover { background: #094d70; color: #fff; }
        .dash-mini-warn { background: #fff; border-color: #f2c94c; color: #b45309; }
        .dash-mini-warn:hover { background: #fff8e6; }
        .dash-mini-danger { background: #fff; border-color: var(--line); color: #dc2626; }
        .dash-mini-danger:hover { background: #fef2f2; border-color: #fecaca; }

        .dash-none { text-align: center; color: var(--muted); padding: 36px; font-size: .92rem; }
        .dash-empty { text-align: center; padding: 44px 20px; color: var(--muted); }
        .dash-empty i { font-size: 38px; color: #dfe3e8; margin-bottom: 14px; }
        .dash-empty h3 { font-size: 1.1rem; font-weight: 700; color: var(--ink); margin: 0 0 6px; }
        .dash-empty p { font-size: .9rem; margin: 0 0 20px; }

        /* Support */
        .dash-support {
            display: flex; align-items: center; justify-content: center; gap: 16px; flex-wrap: wrap;
            margin-top: 30px; padding-top: 22px; border-top: 1px solid var(--line);
            color: var(--muted); font-size: .88rem;
        }
        .dash-support a { color: #15803d; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 7px; }
        .dash-support a:hover { text-decoration: underline; }

        @media (max-width: 575px) {
            .dash { padding: 20px 14px 56px; }
            .dash-head { align-items: flex-start; }
            .dash-cta { width: 100%; justify-content: center; }
            .dash-node-label { font-size: .68rem; }
            .dash-row-actions { flex-wrap: wrap; }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function filterByStatus(status) {
            document.querySelectorAll('.dash-tab').forEach(tab => {
                tab.classList.toggle('active', tab.getAttribute('data-filter') === status);
            });

            const items = document.querySelectorAll('.dash-row');
            let visible = 0;
            items.forEach(item => {
                const show = status === 'all' || item.getAttribute('data-status') === status;
                item.style.display = show ? 'block' : 'none';
                if (show) visible++;
            });

            const noResults = document.getElementById('noFilterResults');
            if (noResults) noResults.classList.toggle('d-none', !(visible === 0 && items.length > 0));
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.dash-tab').forEach(btn => {
                btn.addEventListener('click', () => filterByStatus(btn.getAttribute('data-filter')));
            });
        });
    </script>
@endpush
