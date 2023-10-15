<?php

namespace App\Utils;

use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Http\Request;

class DateTime
{
    public static function getTimeZone(Request $request): CarbonTimeZone
    {
        $timeZone = 'UTC';
        if ($request->hasHeader('X-Timezone')) {
            $timeZone = $request->header('X-Timezone');
        } else {
            $ip = $request->ip();
            $url = "http://ip-api.com/json/$ip";
            $response = file_get_contents($url);
            $jsonResponse = json_decode($response, true);
            if (isset($jsonResponse['timezone'])) {
                $timeZone = json_decode($response, true)['timezone'];
            }
        }
        return CarbonTimeZone::create($timeZone);
    }

    public static function formatToUtcWithTimeZone(Carbon $time, CarbonTimeZone $timeZone): string
    {
        return $time->isoFormat('YYYY-D-M HH:mm:ss') . $timeZone->toOffsetName();
    }
}
