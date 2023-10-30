<?php

namespace App\Services;

use Carbon\CarbonTimeZone;
use Illuminate\Http\Request;


class TimeZoneRecognizer
{
    private \Closure $funcGetTimeZone;

    public function __construct(
        private readonly string $apiUrl,
        private array           $timeZones = [],
        \Closure                $funcGetTimeZone = null
    )
    {
        if ($funcGetTimeZone) {
            $this->funcGetTimeZone = $funcGetTimeZone;
        } else {
            $this->funcGetTimeZone = fn(?array $response) => $response['timezone'] ?? null;
        }
    }

    /**
     * Get timezone of client by the request. Check header with name 'X-Timezone', if no one, gets time zone by IP.
     *
     * @param Request $request
     * @return CarbonTimeZone
     */
    public function getTimeZone(Request $request): CarbonTimeZone
    {
        $timeZone = 'UTC';
        $ip = $request->ip();

        if ($request->hasHeader('X-Timezone')) {
            $timeZone = $request->header('X-Timezone');
        } else if (array_key_exists($ip, $this->timeZones)) {
            $timeZone = $this->timeZones[$ip];
        } else if ($ip !== null) {
            $url = "{$this->apiUrl}/{$ip}";
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            if ($foundTimeZone = $this->funcGetTimeZone->call($this, $data)) {
                $timeZone = $foundTimeZone;
            }
            $this->timeZones[$ip] = $timeZone;
        }

        return CarbonTimeZone::create($timeZone);
    }

    /**
     * @return array
     */
    public function getTimeZones(): array
    {
        return $this->timeZones;
    }
}
