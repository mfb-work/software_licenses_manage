<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\LicenseRepository;
use App\Repositories\ProductRepository;
use App\Services\EnvatoService;
use App\Services\LicenseService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ProductRepository::class, function () {
            return new ProductRepository();
        });

        $this->app->singleton(LicenseRepository::class, function () {
            return new LicenseRepository();
        });

        $this->app->singleton(EnvatoService::class, function () {
            return new EnvatoService();
        });

        $this->app->singleton(LicenseService::class, function ($app) {
            return new LicenseService(
                $app->make(LicenseRepository::class),
                $app->make(ProductRepository::class),
                $app->make(EnvatoService::class)
            );
        });
    }

    public function boot()
    {
        //
    }
}
