<?php

namespace App\Providers;

use App\Services\ErrorResponder;
use App\Services\JsonPatcher\JsonPatcherInterface;
use App\Services\JsonPatcher\MyJsonPatcher;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        ErrorResponder::class => ErrorResponder::class
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(JsonPatcherInterface::class, MyJsonPatcher::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
