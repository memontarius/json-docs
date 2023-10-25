<?php

namespace App\Providers;

use App\Services\DocumentService;
use App\Services\ErrorResponder\ErrorResponder;
use App\Services\JsonPatcher\JsonPatcherInterface;
use App\Services\JsonPatcher\MyJsonPatcher;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ErrorResponder::class, ErrorResponder::class);
        $this->app->singleton(DocumentService::class, DocumentService::class);
        $this->app->bind(JsonPatcherInterface::class, MyJsonPatcher::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
