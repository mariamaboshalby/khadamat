@echo off
echo تشغيل الـ seeders لإصلاح مشكلة الطلبات...

php artisan db:seed --class=UpdateServiceSpecializationSeeder
php artisan db:seed --class=RequestSeeder

echo تم الانتهاء!
pause