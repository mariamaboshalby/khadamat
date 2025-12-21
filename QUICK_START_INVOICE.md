# 🚀 دليل البدء السريع - نظام الفواتير

## ⚡ التشغيل السريع (5 دقائق)

### 1. تأكد من تشغيل السيرفر
```bash
php artisan serve
```

### 2. افتح المتصفح
```
http://127.0.0.1:8000
```

### 3. سجل دخول كمسؤول
```
البريد: admin@example.com
كلمة المرور: password
```

### 4. اذهب للطلبات
```
http://127.0.0.1:8000/admin/requests
```

### 5. اضغط على أيقونة الفاتورة 🧾
أو افتح أي طلب واضغط "عرض الفاتورة"

### 6. اطبع الفاتورة
اضغط زر "طباعة الفاتورة" أو `Ctrl+P`

---

## 📍 الروابط المباشرة

### للمسؤولين:
```
قائمة الطلبات:
http://127.0.0.1:8000/admin/requests

فاتورة طلب معين:
http://127.0.0.1:8000/admin/requests/1/invoice
```

### للعملاء:
```
لوحة التحكم:
http://127.0.0.1:8000/dashboard

فاتورة طلب معين:
http://127.0.0.1:8000/requests/1/invoice
```

---

## 🎨 أماكن أزرار الفاتورة

### 1. في قائمة الطلبات (Admin)
```
الموقع: resources/views/admin/requests/index.blade.php
السطر: ~110
الزر: أيقونة خضراء 🧾
```

### 2. في تفاصيل الطلب (Admin)
```
الموقع: resources/views/admin/requests/show.blade.php
السطر: ~8-16
الزر: "عرض الفاتورة" (أخضر)
```

### 3. في تفاصيل الطلب (Customer)
```
الموقع: resources/views/requests/show.blade.php
السطر: ~240-246
الزر: "عرض الفاتورة" (أخضر)
الشرط: الطلب قيد التنفيذ أو مكتمل
```

---

## 🔧 التخصيص السريع

### تغيير اسم الشركة:
**الملف:** `resources/views/admin/requests/invoice.blade.php`  
**السطر:** ~115
```html
<div class="company-name">منصة خدمات</div>
<p class="mb-0">خدمات الصيانة والإصلاح المنزلية</p>
```

### تغيير نسبة الضريبة:
**الملف:** `resources/views/admin/requests/invoice.blade.php`  
**السطر:** ~220
```php
// من 15% إلى 10%
{{ number_format($total * 0.10, 2) }}
```

### تغيير العملة:
**الملف:** `resources/views/admin/requests/invoice.blade.php`  
**البحث عن:** `ريال`
**الاستبدال بـ:** `دولار` أو `جنيه` أو أي عملة

### إضافة شعار:
**الملف:** `resources/views/admin/requests/invoice.blade.php`  
**السطر:** ~113 (قبل company-name)
```html
<img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-width: 150px; margin-bottom: 10px;">
```

---

## 🐛 حل المشاكل السريع

### المشكلة: خطأ 404
```bash
# الحل:
php artisan route:clear
php artisan cache:clear
php artisan config:clear
```

### المشكلة: الفاتورة فارغة
```sql
-- تأكد من وجود سعر في الطلب:
UPDATE requests SET proposed_price = 500 WHERE id = 1;
```

### المشكلة: التصميم مكسور
```
السبب: Bootstrap CDN غير محمل
الحل: تحقق من اتصال الإنترنت
```

### المشكلة: خطأ 403
```
السبب: محاولة الوصول لفاتورة طلب آخر
الحل: سجل دخول بالحساب الصحيح
```

---

## 📱 اختبار سريع

### اختبار 1: فاتورة بسيطة
```bash
1. افتح: http://127.0.0.1:8000/admin/requests/1/invoice
2. تحقق من ظهور البيانات
3. اضغط طباعة
```

### اختبار 2: فاتورة مع قطع غيار
```sql
-- أضف قطع غيار للطلب:
INSERT INTO request_items (request_id, warehouse_item_id, quantity, unit_price, total_price)
VALUES (1, 1, 2, 50, 100);
```

### اختبار 3: الصلاحيات
```bash
1. سجل دخول كعميل
2. حاول فتح: /requests/999/invoice (طلب ليس لك)
3. يجب أن يظهر خطأ 403
```

---

## 💡 نصائح سريعة

### ✅ افعل:
- استخدم `target="_blank"` لفتح الفاتورة في نافذة جديدة
- تأكد من وجود `proposed_price` قبل عرض الفاتورة
- استخدم `eager loading` لتحسين الأداء
- اختبر الطباعة قبل النشر

### ❌ لا تفعل:
- لا تعرض الفاتورة للطلبات بدون أسعار
- لا تسمح بالوصول للفواتير بدون صلاحيات
- لا تنسى حساب الضريبة
- لا تستخدم inline styles كثيراً

---

## 📊 بيانات تجريبية سريعة

### إنشاء طلب تجريبي كامل:
```sql
-- 1. إنشاء طلب
INSERT INTO requests (user_id, service_id, address, status, proposed_price, description)
VALUES (1, 1, 'الرياض - حي النخيل', 'completed', 500, 'إصلاح تكييف');

-- 2. إضافة قطع غيار
INSERT INTO request_items (request_id, warehouse_item_id, quantity, unit_price, total_price)
VALUES 
(LAST_INSERT_ID(), 1, 2, 50, 100),
(LAST_INSERT_ID(), 2, 1, 150, 150);

-- 3. تعيين فني
UPDATE requests 
SET assigned_technician_id = 1 
WHERE id = LAST_INSERT_ID();
```

---

## 🎯 الخطوات التالية

### بعد التشغيل الناجح:
1. ✅ اختبر جميع السيناريوهات
2. ✅ خصص التصميم حسب احتياجك
3. ✅ أضف شعار الشركة
4. ✅ اختبر الطباعة
5. ✅ راجع الصلاحيات

### تطويرات مقترحة:
- [ ] تصدير PDF
- [ ] إرسال بالبريد
- [ ] حفظ الفواتير
- [ ] تقارير مالية
- [ ] ربط بوابات الدفع

---

## 📞 المساعدة

### إذا واجهت مشكلة:
1. راجع ملف `INVOICE_TESTING.md`
2. تحقق من ملف `INVOICE_SYSTEM.md`
3. راجع الـ logs: `storage/logs/laravel.log`
4. اتصل بالدعم الفني

---

## ✨ ملخص الأوامر المهمة

```bash
# تشغيل السيرفر
php artisan serve

# مسح الكاش
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear

# عرض الروابط
php artisan route:list | grep invoice

# فحص قاعدة البيانات
php artisan tinker
>>> App\Models\Request::with('requestItems')->find(1)
```

---

**🎉 مبروك! نظام الفواتير جاهز للاستخدام**

**الوقت المتوقع للإعداد:** 5-10 دقائق  
**مستوى الصعوبة:** سهل ⭐  
**الحالة:** جاهز للإنتاج ✅
