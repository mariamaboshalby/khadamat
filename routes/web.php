<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RequestController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\TechnicianController;
use App\Http\Controllers\Admin\SpecializationController;
use App\Http\Controllers\Admin\WarehouseItemController;
use App\Http\Controllers\Admin\RequestController as AdminRequestController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\OfferController;

// Technician Controllers
use App\Http\Controllers\Technician\RequestController as TechnicianRequestController;
use App\Http\Controllers\Technician\ProfileController as TechnicianProfileController;
use App\Http\Controllers\NotificationsController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [HomeController::class, 'services'])->name('services.index');
Route::get('/offers', [HomeController::class, 'offers'])->name('offers.index');
Route::get('/service/{encryptedId}', [HomeController::class, 'serviceShow'])->name('service.show');

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'userDashboard'])
        ->name('dashboard');

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [HomeController::class, 'profile'])->name('profile.index');

        Route::prefix('settings')->group(function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });
    });

    // User Requests
    Route::prefix('requests')->group(function () {
        Route::get('/',        [RequestController::class, 'index'])->name('requests.index');
        Route::get('/create',  [RequestController::class, 'create'])->name('requests.create');
        Route::post('/',       [RequestController::class, 'store'])->name('requests.store');
        Route::get('/{encryptedId}',    [RequestController::class, 'show'])->name('requests.show');
        Route::get('/{encryptedId}/edit', [RequestController::class, 'edit'])->name('requests.edit');
        Route::put('/{encryptedId}',    [RequestController::class, 'update'])->name('requests.update');
        Route::delete('/{encryptedId}', [RequestController::class, 'destroy'])->name('requests.destroy');
        Route::delete('/{encryptedId}/media/{mediaId}', [RequestController::class, 'deleteMedia'])->name('requests.delete-media');
    });
    
    // Customer Pricing
    Route::get('/pricing/{encryptedId}', [\App\Http\Controllers\CustomerPricingController::class, 'show'])->name('pricing.show');
    Route::post('/pricing/{encryptedId}/accept', [\App\Http\Controllers\CustomerPricingController::class, 'accept'])->name('requests.accept-price');
    Route::post('/pricing/{encryptedId}/reject', [\App\Http\Controllers\CustomerPricingController::class, 'reject'])->name('requests.reject-price');
    
    Route::post('/proposals/{encryptedId}/accept', [RequestController::class, 'acceptProposal'])->name('requests.accept-proposal');
    Route::post('/proposals/{encryptedId}/reject', [RequestController::class, 'rejectProposal'])->name('requests.reject-proposal');
    
    Route::post('/requests/{encryptedId}/review', [RequestController::class, 'submitReview'])->name('requests.submit-review');
    
    Route::get('/requests/{encryptedId}/invoice', [RequestController::class, 'invoice'])->name('requests.invoice');
});

Route::get('/technician/{encryptedId}/profile', [HomeController::class, 'technicianProfile'])->name('technician.profile');
Route::post('/technician/{encryptedId}/review', [HomeController::class, 'submitTechnicianReview'])->name('technician.review');

// Notifications
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications/unread-count', [NotificationsController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{encryptedId}/read', [NotificationsController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-read', [NotificationsController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Reviews
        Route::resource('reviews', ReviewController::class);
        Route::get('/reviews/export', [ReviewController::class, 'export'])->name('reviews.export');

        // Customers
        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('customers/{encryptedId}', [CustomerController::class, 'show'])->name('customers.show');
        Route::get('customers/{encryptedId}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('customers/{encryptedId}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('customers/{encryptedId}', [CustomerController::class, 'destroy'])->name('customers.destroy');

        // Technicians
        Route::get('techs', [TechnicianController::class, 'index'])->name('techs.index');
        Route::get('techs/create', [TechnicianController::class, 'create'])->name('techs.create');
        Route::post('techs', [TechnicianController::class, 'store'])->name('techs.store');
        Route::get('techs/{encryptedId}', [TechnicianController::class, 'show'])->name('techs.show');
        Route::get('techs/{encryptedId}/edit', [TechnicianController::class, 'edit'])->name('techs.edit');
        Route::put('techs/{encryptedId}', [TechnicianController::class, 'update'])->name('techs.update');
        Route::delete('techs/{encryptedId}', [TechnicianController::class, 'destroy'])->name('techs.destroy');

        // Specializations
        Route::get('specializations', [SpecializationController::class, 'index'])->name('specializations.index');
        Route::get('specializations/create', [SpecializationController::class, 'create'])->name('specializations.create');
        Route::post('specializations', [SpecializationController::class, 'store'])->name('specializations.store');
        Route::get('specializations/{encryptedId}/edit', [SpecializationController::class, 'edit'])->name('specializations.edit');
        Route::put('specializations/{encryptedId}', [SpecializationController::class, 'update'])->name('specializations.update');
        Route::delete('specializations/{encryptedId}', [SpecializationController::class, 'destroy'])->name('specializations.destroy');

        // Services
        Route::get('services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('services/create', [ServiceController::class, 'create'])->name('services.create');
        Route::post('services', [ServiceController::class, 'store'])->name('services.store');
        Route::get('services/{encryptedId}', [ServiceController::class, 'show'])->name('services.show');
        Route::get('services/{encryptedId}/edit', [ServiceController::class, 'edit'])->name('services.edit');
        Route::put('services/{encryptedId}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('services/{encryptedId}', [ServiceController::class, 'destroy'])->name('services.destroy');

        // Offers
        Route::get('offers', [OfferController::class, 'index'])->name('offers.index');
        Route::get('offers/create', [OfferController::class, 'create'])->name('offers.create');
        Route::post('offers', [OfferController::class, 'store'])->name('offers.store');
        Route::get('offers/{encryptedId}/edit', [OfferController::class, 'edit'])->name('offers.edit');
        Route::put('offers/{encryptedId}', [OfferController::class, 'update'])->name('offers.update');
        Route::delete('offers/{encryptedId}', [OfferController::class, 'destroy'])->name('offers.destroy');

        // Warehouse Items
        Route::get('/warehouse-items/low-stock', [WarehouseItemController::class, 'lowStock'])
            ->name('warehouse-items.low-stock');

        Route::get('warehouse-items', [WarehouseItemController::class, 'index'])->name('warehouse-items.index');
        Route::get('warehouse-items/create', [WarehouseItemController::class, 'create'])->name('warehouse-items.create');
        Route::post('warehouse-items', [WarehouseItemController::class, 'store'])->name('warehouse-items.store');
        Route::get('warehouse-items/{encryptedId}/edit', [WarehouseItemController::class, 'edit'])->name('warehouse-items.edit');
        Route::put('warehouse-items/{encryptedId}', [WarehouseItemController::class, 'update'])->name('warehouse-items.update');
        Route::delete('warehouse-items/{encryptedId}', [WarehouseItemController::class, 'destroy'])->name('warehouse-items.destroy');

        Route::get('/requests', [AdminRequestController::class, 'index'])->name('requests.index');

        // عرض تفاصيل الطلب للأدمن
        Route::get('/requests/{encryptedId}', [AdminRequestController::class, 'show'])->name('requests.show');

        // تحديث حالة الطلب
        Route::patch('/requests/{encryptedId}/status', [AdminRequestController::class, 'updateStatus'])
            ->name('requests.update-status');
        
        // الفاتورة
        Route::get('/requests/{encryptedId}/invoice', [AdminRequestController::class, 'invoice'])->name('requests.invoice');
    });

Route::prefix('technician')
    ->name('technician.')
    ->middleware(['auth'])
    ->group(function () {
        // عرض طلبات الإصلاح المتاحة
        Route::get('/repair-requests', [TechnicianRequestController::class, 'index'])->name('repair-requests.index');
        
        // عرض طلبات الفني
        Route::get('/my-requests', [TechnicianRequestController::class, 'myRequests'])->name('my-requests');
        
        // عرض تفاصيل طلب إصلاح
        Route::get('/repair-requests/{encryptedId}', [TechnicianRequestController::class, 'show'])->name('repair-requests.show');
        
        // صفحة التسعير
        Route::get('/repair-requests/{encryptedId}/pricing', [TechnicianRequestController::class, 'pricing'])->name('repair-requests.pricing');
        
        // إرسال عرض السعر
        Route::post('/repair-requests/{encryptedId}/pricing', [TechnicianRequestController::class, 'submitPricing'])->name('repair-requests.submit-pricing');
        
        // قبول/رفض السعر المعدل من العميل
        Route::post('/repair-requests/{encryptedId}/accept-negotiation', [TechnicianRequestController::class, 'acceptNegotiation'])->name('repair-requests.accept-negotiation');
        Route::post('/repair-requests/{encryptedId}/reject-negotiation', [TechnicianRequestController::class, 'rejectNegotiation'])->name('repair-requests.reject-negotiation');
        
        // التقديم على طلب إصلاح
        Route::post('/repair-requests/{encryptedId}/apply', [TechnicianRequestController::class, 'apply'])->name('repair-requests.apply');
        
        // إنهاء الطلب
        Route::post('/repair-requests/{encryptedId}/complete', [TechnicianRequestController::class, 'complete'])->name('repair-requests.complete');

        // Profile
        Route::get('/profile', [TechnicianProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [TechnicianProfileController::class, 'update'])->name('profile.update');
    });


require __DIR__.'/auth.php';
