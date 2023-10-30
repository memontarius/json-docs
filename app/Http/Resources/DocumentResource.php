<?php

namespace App\Http\Resources;

use App\Services\DateTime;
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
        $dateTime = App::make(DateTime::class);
        $timezone = $dateTime->getTimeZone($request);

        return [
            'id' => $this->id,
            'status' => $this->status,
            'payload' => (object)$this->payload,
            'createAt' => $dateTime->formatToUtcWithTimeZone($this->created_at, $timezone),
            'modifyAt' => $dateTime->formatToUtcWithTimeZone($this->updated_at, $timezone),
        ];
    }
}
