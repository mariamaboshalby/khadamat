# نظام الفواتير - منصة خدمات

## 📋 نظرة عامة

تم إضافة نظام فواتير احترافي كامل لمنصة خدمات يتيح:
- عرض فواتير تفصيلية للطلبات
- طباعة الفواتير بتصميم احترافي
- حساب تلقائي للضرائب (15% ضريبة القيمة المضافة)
- عرض تفاصيل العميل والفني والخدمة
- قائمة بالقطع المستخدمة وتكلفة العمالة

---

## 🎯 المميزات

### ✅ للمسؤولين (Admin)
- عرض فاتورة لأي طلب من لوحة التحكم
- زر مباشر في صفحة تفاصيل الطلب
- زر سريع في قائمة الطلبات

### ✅ للعملاء (Customers)
- عرض فاتورة الطلب الخاص بهم
- متاح فقط للطلبات قيد التنفيذ أو المكتملة
- إمكانية الطباعة المباشرة

### ✅ التصميم
- تصميم احترافي وأنيق
- دعم كامل للغة العربية (RTL)
- جاهز للطباعة (إخفاء الأزرار عند الطباعة)
- متجاوب مع جميع الأجهزة

---

## 📁 الملفات المضافة/المعدلة

### ملفات جديدة:
```
resources/views/admin/requests/invoice.blade.php
resources/views/requests/invoice.blade.php
INVOICE_SYSTEM.md
```

### ملفات معدلة:
```
app/Http/Controllers/Admin/RequestController.php
app/Http/Controllers/RequestController.php
routes/web.php
resources/views/admin/requests/show.blade.php
resources/views/admin/requests/index.blade.php
resources/views/requests/show.blade.php
```

---

## 🔗 الروابط (Routes)

### للمسؤولين:
```php
GET /admin/requests/{id}/invoice
Route: admin.requests.invoice
```

### للعملاء:
```php
GET /requests/{id}/invoice
Route: requests.invoice
```

---

## 📊 محتويات الفاتورة

### 1. معلومات الفاتورة
- رقم الفاتورة (مع padding)
- تاريخ الإصدار
- حالة الطلب

### 2. بيانات العميل
- الاسم
- رقم الهاتف
- العنوان

### 3. تفاصيل الخدمة
- نوع الخدمة
- الفني المسؤول (إن وجد)
- وصف المشكلة

### 4. جدول البنود
- تكلفة العمالة والخدمة
- قطع الغيار المستخدمة (الاسم، الكمية، السعر)
- الإجمالي لكل بند

### 5. الحسابات المالية
- المجموع الفرعي
- ضريبة القيمة المضافة (15%)
- الإجمالي النهائي

### 6. ملاحظات إضافية
- ملاحظات الفني (إن وجدت)

---

## 💻 كيفية الاستخدام

### للمسؤول:

#### من صفحة تفاصيل الطلب:
```blade
<a href="{{ route('admin.requests.invoice', $requestModel->id) }}" 
   class="btn btn-success" 
   target="_blank">
    عرض الفاتورة
</a>
```

#### من قائمة الطلبات:
```blade
<a href="{{ route('admin.requests.invoice', $request->id) }}" 
   class="btn btn-success btn-sm" 
   target="_blank">
    <i class="fa-solid fa-file-invoice"></i>
</a>
```

### للعميل:

```blade
@if(in_array($requestData->status, ['in_progress', 'completed']))
    <a href="{{ route('requests.invoice', $requestData->id) }}" 
       class="btn btn-success" 
       target="_blank">
        عرض الفاتورة
    </a>
@endif
```

---

## 🎨 التخصيص

### تغيير اسم الشركة:
```html
<div class="company-name">منصة خدمات</div>
<p class="mb-0">خدمات الصيانة والإصلاح المنزلية</p>
```

### تغيير نسبة الضريبة:
```php
// من 15% إلى نسبة أخرى
{{ number_format($total * 0.15, 2) }}  // غير 0.15 للنسبة المطلوبة
```

### تغيير العملة:
```php
{{ number_format($price, 2) }} ريال  // غير "ريال" للعملة المطلوبة
```

### إضافة شعار الشركة:
```html
<div class="company-info">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-width: 150px;">
    <div class="company-name">منصة خدمات</div>
</div>
```

---

## 🖨️ الطباعة

### الطباعة التلقائية:
```javascript
<button onclick="window.print()" class="btn btn-primary">
    طباعة الفاتورة
</button>
```

### إخفاء العناصر عند الطباعة:
```css
@media print {
    .no-print { display: none !important; }
    body { background: white; }
}
```

---

## 🔒 الأمان والصلاحيات

### للمسؤولين:
- يمكنهم عرض فواتير جميع الطلبات
- محمي بـ middleware: `auth`, `role:admin`

### للعملاء:
- يمكنهم عرض فواتير طلباتهم فقط
- التحقق من الملكية:
```php
if ($request->user_id !== Auth::id()) {
    abort(403);
}
```

---

## 📱 التوافق

- ✅ متصفحات الويب (Chrome, Firefox, Safari, Edge)
- ✅ الأجهزة المحمولة (iOS, Android)
- ✅ الطباعة (PDF, طابعات فعلية)
- ✅ دعم RTL كامل للغة العربية

---

## 🚀 التطويرات المستقبلية المقترحة

1. **تصدير PDF**
   - استخدام مكتبة مثل `dompdf` أو `snappy`
   - حفظ الفواتير كملفات PDF

2. **إرسال بالبريد الإلكتروني**
   - إرسال الفاتورة تلقائياً للعميل
   - إشعارات عند إصدار فاتورة جديدة

3. **أرشفة الفواتير**
   - حفظ نسخة من كل فاتورة في قاعدة البيانات
   - سجل تاريخي للفواتير

4. **تقارير مالية**
   - تقارير شهرية/سنوية
   - إحصائيات الإيرادات

5. **طرق الدفع**
   - ربط مع بوابات الدفع
   - تتبع حالة الدفع

---

## 📞 الدعم

للمساعدة أو الاستفسارات:
- البريد الإلكتروني: info@khadamat.com
- الهاتف: 920000000

---

## 📝 ملاحظات

- الفواتير متاحة فقط للطلبات التي لها سعر محدد
- يتم حساب الضريبة تلقائياً (15%)
- التصميم قابل للتخصيص بالكامل
- جميع الأسعار بالريال السعودي

---

**تم التطوير بواسطة:** Amazon Q Developer  
**التاريخ:** 2025  
**الإصدار:** 1.0
