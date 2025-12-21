# 📊 مصدر بيانات الفاتورة

## 🎯 من أين تأتي الأسعار؟

### ✅ الطريقة الجديدة (بعد التحديث):
الفاتورة تعرض الأسعار من **عرض الفني المقبول** (Accepted Proposal)

```php
// يتم جلب العرض المقبول
$acceptedProposal = $request->proposals()
    ->where('status', 'accepted')
    ->with('items.warehouseItem')
    ->first();
```

---

## 📋 مكونات الفاتورة:

### 1. سعر الخدمة (Labor Cost)
```php
المصدر: $acceptedProposal->proposed_price
مثال: 500 جنيه
```

### 2. قطع الغيار (Parts)
```php
المصدر: $acceptedProposal->items
كل قطعة تحتوي على:
- الاسم: $item->warehouseItem->name
- الكمية: $item->quantity
- السعر: $item->unit_price
- الإجمالي: $item->total_price
```

### 3. الإجمالي (Total)
```php
الحساب: سعر الخدمة + مجموع قطع الغيار
$total = $acceptedProposal->proposed_price + $acceptedProposal->items->sum('total_price')
```

---

## 🔄 دورة البيانات:

```
1. الفني يقدم عرض سعر (Proposal)
   ↓
2. العميل يقبل العرض
   ↓
3. حالة العرض تصبح: accepted
   ↓
4. الفاتورة تعرض بيانات هذا العرض
```

---

## 📊 جداول قاعدة البيانات:

### request_proposals
```sql
- id
- request_id
- technician_id
- proposed_price (سعر الخدمة)
- price_notes
- status (pending/accepted/rejected)
```

### proposal_items
```sql
- id
- proposal_id
- warehouse_item_id
- quantity (الكمية)
- unit_price (سعر الوحدة)
- total_price (الإجمالي)
```

---

## 💡 مثال عملي:

### عرض الفني:
```
سعر الخدمة: 500 جنيه
قطع الغيار:
  - خلاط مياه: 2 × 50 = 100 جنيه
  - محبس زاوية: 1 × 150 = 150 جنيه
الإجمالي: 750 جنيه
```

### في الفاتورة:
```
الخدمة: 500 جنيه
Parts: 250 جنيه
Transport: 0 جنيه
Total: 750 جنيه
```

---

## ⚠️ حالات خاصة:

### إذا لم يكن هناك عرض مقبول:
```php
// تستخدم البيانات القديمة من جدول requests
$laborCost = $request->proposed_price ?? 0;
```

### إذا لم تكن هناك قطع غيار:
```php
// تظهر فقط سعر الخدمة
Parts: 0 جنيه
```

---

## 🔍 التحقق من البيانات:

### في Controller:
```php
$acceptedProposal = $request->proposals()
    ->where('status', 'accepted')
    ->first();

if ($acceptedProposal) {
    // استخدم بيانات العرض المقبول
    $price = $acceptedProposal->proposed_price;
    $items = $acceptedProposal->items;
} else {
    // استخدم البيانات القديمة
    $price = $request->proposed_price;
}
```

---

## ✅ الفوائد:

1. **دقة البيانات**: الأسعار من العرض الذي وافق عليه العميل
2. **الشفافية**: العميل يرى نفس الأسعار التي وافق عليها
3. **التتبع**: يمكن معرفة أي فني قدم العرض
4. **التفاصيل**: عرض كل قطعة غيار بسعرها

---

**تم التحديث:** 2025  
**الحالة:** ✅ نشط
