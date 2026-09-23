<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
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
public function boot()
{
    Blade::if('access', function ($module, $action) {
        $admin = auth('admin')->user();
        return $admin && $admin->hasAccess($module, $action);
    });
}

    
}
