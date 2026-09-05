@php
    $encryptedId = \App\Helpers\EncryptionHelper::encryptId($request->id);
@endphp

<div class="request-actions">
    <a href="{{ route('admin.requests.show', $encryptedId) }}" class="btn-request-view btn-sm" title="عرض">
        <i class="fa-solid fa-eye"></i>
        <span class="ms-1">عرض</span>
    </a>

    <a href="{{ route('admin.requests.invoice', $encryptedId) }}"
        class="btn btn-success btn-request-invoice btn-sm" target="_blank" title="الفاتورة">
        <i class="fa-solid fa-file-invoice"></i>
    </a>

    @if (in_array($request->status, ['pending', 'approved', 'in_progress']))
        <div class="dropdown d-inline">
            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-arrows-rotate"></i>
                <span class="ms-1">الحالة</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @if ($request->status == 'pending')
                    <li>
                        <form action="{{ route('admin.requests.update-status', $encryptedId) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button class="dropdown-item text-info">قبول الطلب</button>
                        </form>
                    </li>
                    <li>
                        <form action="{{ route('admin.requests.update-status', $encryptedId) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button class="dropdown-item text-danger">رفض الطلب</button>
                        </form>
                    </li>
                @endif

                @if ($request->status == 'approved')
                    <li>
                        <form action="{{ route('admin.requests.update-status', $encryptedId) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="in_progress">
                            <button class="dropdown-item text-primary">بدء التنفيذ</button>
                        </form>
                    </li>
                @endif

                @if ($request->status == 'in_progress')
                    <li>
                        <form action="{{ route('admin.requests.update-status', $encryptedId) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button class="dropdown-item text-success">اكتمال الطلب</button>
                        </form>
                    </li>
                @endif
            </ul>
        </div>
    @endif
</div>
