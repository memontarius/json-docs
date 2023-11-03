<?php

namespace App\Providers;

use App\Services\DocumentService;
use App\Services\ErrorResponder\ErrorResponder;
use App\Services\JsonPatcher\JsonPatcherInterface;
use App\Services\JsonPatcher\MyJsonPatcher;
use App\Services\TimeZoneRecognizer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private string $timeZonesKey = 'timezones';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TimeZoneRecognizer::class, function () {
            $cachedTimeZones = Cache::get($this->timeZonesKey, []);
            return new TimeZoneRecognizer('http://ip-api.com/json/', $cachedTimeZones);
        });

        $this->app->singleton(ErrorResponder::class, ErrorResponder::class);
        $this->app->singleton(DocumentService::class, DocumentService::class);
        $this->app->singleton(JsonPatcherInterface::class, MyJsonPatcher::class);

        App::terminating(function () {
            $timeZoneRecognizer = App::make(TimeZoneRecognizer::class);
            Cache::put($this->timeZonesKey, $timeZoneRecognizer->getTimeZones(), now()->addDay());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
