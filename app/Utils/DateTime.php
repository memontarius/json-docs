<?php

namespace App\Utils;

use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Http\Request;


class DateTime
{
    /**
     * Get timezone of client by the request. Check header with name 'X-Timezone', if no one, gets time zone by IP.
     *
     * @param Request $request
     * @return CarbonTimeZone
     */
    public static function getTimeZone(Request $request): CarbonTimeZone
    {
        $timeZone = 'UTC';
        $ip = $request->ip();

        if ($request->hasHeader('X-Timezone')) {
            $timeZone = $request->header('X-Timezone');
        } if ($ip !== null) {
            $url = "http://ip-api.com/json/$ip";
            $response = file_get_contents($url);
            $jsonResponse = json_decode($response, true);
            if (isset($jsonResponse['timezone'])) {
                $timeZone = json_decode($response, true)['timezone'];
            }
        }

        return CarbonTimeZone::create($timeZone);
    }

    /**
     * Format time by iso-8601 with time zone
     *
     * @param Carbon $time
     * @param CarbonTimeZone $timeZone
     * @return string
     */
    public static function formatToUtcWithTimeZone(Carbon $time, CarbonTimeZone $timeZone): string
    {
        return $time->isoFormat('YYYY-D-M HH:mm:ss') . $timeZone->toOffsetName();
    }
}
