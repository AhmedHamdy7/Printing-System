<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ProductRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\UserRepository;
use App\Services\InvoiceService;
use App\Services\PdfService;
use App\Services\ReportService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ProductRepository::class, fn() =>
            new ProductRepository(new \App\Models\Product)
        );

        $this->app->singleton(InvoiceRepository::class, fn() =>
            new InvoiceRepository(new \App\Models\Invoice)
        );

        $this->app->singleton(UserRepository::class, fn() =>
            new UserRepository(new \App\Models\User)
        );

        $this->app->singleton(InvoiceService::class);
        $this->app->singleton(PdfService::class);
        $this->app->singleton(ReportService::class);
    }

    public function boot(): void
    {
        //
    }
}
