<?php
// Debug script to check requests data
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== تحقق من البيانات ===\n\n";

// Check Services
echo "الخدمات:\n";
$services = \App\Models\Service::with('specialization')->get();
foreach ($services as $service) {
    echo "- {$service->name} (التخصص: " . ($service->specialization->name ?? 'غير محدد') . ")\n";
}

echo "\nالتخصصات:\n";
$specializations = \App\Models\Specialization::get();
foreach ($specializations as $spec) {
    echo "- {$spec->name}\n";
}

echo "\nالفنيين:\n";
$technicians = \App\Models\Technician::with(['user', 'specialization'])->get();
foreach ($technicians as $tech) {
    echo "- {$tech->user->name} (التخصص: {$tech->specialization->name})\n";
}

echo "\nالطلبات:\n";
$requests = \App\Models\Request::with(['user', 'service'])->get();
foreach ($requests as $request) {
    echo "- طلب #{$request->id}: {$request->service->name} - الحالة: {$request->status}\n";
}

echo "\nالطلبات المعتمدة غير المعينة:\n";
$approvedRequests = \App\Models\Request::with(['user', 'service'])
    ->where('status', 'approved')
    ->whereNull('assigned_technician_id')
    ->get();
    
echo "عدد الطلبات المعتمدة: " . $approvedRequests->count() . "\n";
foreach ($approvedRequests as $request) {
    echo "- طلب #{$request->id}: {$request->service->name}\n";
}
?>