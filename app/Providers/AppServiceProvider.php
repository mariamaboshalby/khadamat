<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Helpers\EncryptionHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        
        // Create a custom Blade directive for encrypting IDs
        \Blade::directive('encryptId', function ($expression) {
            return "<?php echo \\App\\Helpers\\EncryptionHelper::encryptId($expression); ?>";
        });
    }
}
