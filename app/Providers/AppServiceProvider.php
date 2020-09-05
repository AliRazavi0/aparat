<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setDatabaseStringLength();
    }

    /**
     * تعیین طول کاراکتر های مورد استفاده در دیتابیس برای رفع مشکل ایجاد دیتابیس در مای اس کیو ال
     */
    private function setDatabaseStringLength(): void
    {
        Schema::defaultStringLength(191);
    }
}
