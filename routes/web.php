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
Route::get('/service/{id}', [HomeController::class, 'serviceShow'])->name('service.show');

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
        Route::get('/{id}',    [RequestController::class, 'show'])->name('requests.show');
        Route::get('/{id}/edit', [RequestController::class, 'edit'])->name('requests.edit');
        Route::put('/{id}',    [RequestController::class, 'update'])->name('requests.update');
        Route::delete('/{id}', [RequestController::class, 'destroy'])->name('requests.destroy');
        Route::delete('/{id}/media/{mediaId}', [RequestController::class, 'deleteMedia'])->name('requests.delete-media');
    });
    
    // Customer Pricing
    Route::get('/pricing/{id}', [\App\Http\Controllers\CustomerPricingController::class, 'show'])->name('pricing.show');
    Route::post('/pricing/{id}/accept', [\App\Http\Controllers\CustomerPricingController::class, 'accept'])->name('requests.accept-price');
    Route::post('/pricing/{id}/reject', [\App\Http\Controllers\CustomerPricingController::class, 'reject'])->name('requests.reject-price');
    
    Route::post('/proposals/{id}/accept', [RequestController::class, 'acceptProposal'])->name('requests.accept-proposal');
    Route::post('/proposals/{id}/reject', [RequestController::class, 'rejectProposal'])->name('requests.reject-proposal');
    
    Route::post('/requests/{id}/review', [RequestController::class, 'submitReview'])->name('requests.submit-review');
    
    Route::get('/requests/{id}/invoice', [RequestController::class, 'invoice'])->name('requests.invoice');
});

Route::get('/technician/{id}/profile', [HomeController::class, 'technicianProfile'])->name('technician.profile');
Route::post('/technician/{id}/review', [HomeController::class, 'submitTechnicianReview'])->name('technician.review');

// Notifications
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications/unread-count', [NotificationsController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationsController::class, 'markAsRead'])->name('notifications.mark-as-read');
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
        Route::resource('customers', CustomerController::class);

        // Technicians
        Route::get('techs', [TechnicianController::class, 'index'])->name('techs.index');
        Route::get('techs/create', [TechnicianController::class, 'create'])->name('techs.create');
        Route::post('techs', [TechnicianController::class, 'store'])->name('techs.store');
        Route::get('techs/{technician}', [TechnicianController::class, 'show'])->name('techs.show');
        Route::get('techs/{tech}/edit', [TechnicianController::class, 'edit'])->name('techs.edit');
        Route::put('techs/{tech}', [TechnicianController::class, 'update'])->name('techs.update');
        Route::delete('techs/{tech}', [TechnicianController::class, 'destroy'])->name('techs.destroy');

        // Specializations
        Route::resource('specializations', SpecializationController::class)->except(['show']);

        // Services
        Route::resource('services', ServiceController::class);

        // Offers
        Route::resource('offers', OfferController::class);

        // Warehouse Items
        Route::get('/warehouse-items/low-stock', [WarehouseItemController::class, 'lowStock'])
            ->name('warehouse-items.low-stock');

        Route::resource('warehouse-items', WarehouseItemController::class);

        Route::get('/requests', [AdminRequestController::class, 'index'])->name('requests.index');

        // عرض تفاصيل الطلب للأدمن
        Route::get('/requests/{id}', [AdminRequestController::class, 'show'])->name('requests.show');

        // تحديث حالة الطلب
        Route::patch('/requests/{id}/status', [AdminRequestController::class, 'updateStatus'])
            ->name('requests.update-status');
        
        // الفاتورة
        Route::get('/requests/{id}/invoice', [AdminRequestController::class, 'invoice'])->name('requests.invoice');
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
        Route::get('/repair-requests/{id}', [TechnicianRequestController::class, 'show'])->name('repair-requests.show');
        
        // صفحة التسعير
        Route::get('/repair-requests/{id}/pricing', [TechnicianRequestController::class, 'pricing'])->name('repair-requests.pricing');
        
        // إرسال عرض السعر
        Route::post('/repair-requests/{id}/pricing', [TechnicianRequestController::class, 'submitPricing'])->name('repair-requests.submit-pricing');
        
        // قبول/رفض السعر المعدل من العميل
        Route::post('/repair-requests/{id}/accept-negotiation', [TechnicianRequestController::class, 'acceptNegotiation'])->name('repair-requests.accept-negotiation');
        Route::post('/repair-requests/{id}/reject-negotiation', [TechnicianRequestController::class, 'rejectNegotiation'])->name('repair-requests.reject-negotiation');
        
        // التقديم على طلب إصلاح
        Route::post('/repair-requests/{id}/apply', [TechnicianRequestController::class, 'apply'])->name('repair-requests.apply');
        
        // إنهاء الطلب
        Route::post('/repair-requests/{id}/complete', [TechnicianRequestController::class, 'complete'])->name('repair-requests.complete');

        // Profile
        Route::get('/profile', [TechnicianProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [TechnicianProfileController::class, 'update'])->name('profile.update');
    });


require __DIR__.'/auth.php';
