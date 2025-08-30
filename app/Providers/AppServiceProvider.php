<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\BoothMemberHelper;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
