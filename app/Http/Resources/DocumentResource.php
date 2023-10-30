<?php

namespace App\Http\Resources;

use App\Services\TimeZoneRecognizer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class DocumentResource extends JsonResource
{
    public static $wrap = 'document';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $timeZoneRecognizer = App::make(TimeZoneRecognizer::class);
        $timezone = $timeZoneRecognizer->getTimeZone($request);

        return [
            'id' => $this->id,
            'status' => $this->status,
            'payload' => (object)$this->payload,
            'createAt' => $this->formatTime($this->created_at, $timezone),
            'modifyAt' => $this->formatTime($this->updated_at, $timezone),
        ];
    }

    private function formatTime(string $dateTime, mixed $timeZone = null): string
    {
        return str_replace('T', ' ', Carbon::parse($dateTime, $timeZone)->toIso8601String());
    }
}
