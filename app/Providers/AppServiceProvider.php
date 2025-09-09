<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\BoothMemberHelper;
use App\Helpers\CurrencyHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('booth-member-helper', function () {
            return new BoothMemberHelper();
        });
        
        $this->app->bind('currency-helper', function () {
            return new CurrencyHelper();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Blade directives for currency formatting
        \Blade::directive('currency', function ($expression) {
            return "<?php echo App\Helpers\CurrencyHelper::getEventCurrencySymbol(\$event); ?>";
        });
        
        \Blade::directive('formatAmount', function ($expression) {
            return "<?php echo App\Helpers\CurrencyHelper::formatEventAmount($expression, \$event); ?>";
        });
    }
}
