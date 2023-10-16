<?php

namespace App\Providers;

use App\Services\ErrorResponder;
use App\Services\JsonPatcher;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        JsonPatcher::class => JsonPatcher::class,
        ErrorResponder::class => ErrorResponder::class
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
