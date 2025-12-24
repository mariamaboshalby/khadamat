@extends('layouts.admin')

@section('title', 'تنبيهات المخزن')

@section('content')
<style>
     .btn-primary {
            background: #e54343;
            color: white;
            border: #e54343;
        }
</style>
<div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 style="font-size: 28px; margin: 0 0 10px; color: #e53e3e;">تنبيهات المخزن</h1>
        <p style="color: #718096; margin: 0;">الأصناف التي وصلت للحد الأدنى أو نفذت</p>
    </div>
    <a href="{{ route('admin.warehouse-items.index') }}" class="btn-primary" >
        عودة للمخزن
    </a>
</div>

<div class="stat-card">
    @if($items->count() > 0)
        <div class="alert alert-error" style="margin-bottom: 20px;">
            <svg style="width: 24px; height: 24px;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>يوجد {{ $items->count() }} صنف يحتاج إلى إعادة تعبئة</span>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>اسم الصنف</th>
                    <th>الفئة</th>
                    <th>الكمية الحالية</th>
                    <th>الحد الأدنى</th>
                    <th>النقص</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr style="background: #fff5f5;">
                        <td style="font-weight: 600;">{{ $item->name }}</td>
                        <td>{{ $item->category ?? '-' }}</td>
                        <td style="color: #e53e3e; font-weight: 700;">{{ $item->quantity }} {{ $item->unit }}</td>
                        <td>{{ $item->min_quantity }} {{ $item->unit }}</td>
                        <td style="color: #e53e3e;">
                            {{ $item->min_quantity - $item->quantity }} {{ $item->unit }}
                        </td>
                        <td>
                            @if($item->quantity == 0)
                                <span class="badge badge-danger">نفذ</span>
                            @else
                                <span class="badge badge-warning">منخفض</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('admin.warehouse-items.edit', \App\Helpers\EncryptionHelper::encryptId($item->id)) }}" class="btn btn-success btn-sm" title="تحديث الكمية">
                                    <i class="fa-solid fa-pen-to-square"></i> تحديث
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 48px 20px;">
            <svg width="64" height="64" fill="#48bb78" viewBox="0 0 20 20" style="margin: 0 auto 16px;">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <h3 style="color: #2f855a; margin-bottom: 8px;">ممتاز!</h3>
            <p style="color: #718096;">جميع الأصناف متوفرة بكميات كافية.</p>
        </div>
    @endif
</div>
@endsection
