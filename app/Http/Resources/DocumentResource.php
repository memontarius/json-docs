<?php

namespace App\Http\Resources;

use App\Utils;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $timezone = Utils\DateTime::getTimeZone($request);
        return [
            'id' => $this->id,
            'status' => $this->status,
            'payload' => (object)$this->payload,
            'createAt' => Utils\DateTime::formatToUtcWithTimeZone($this->created_at, $timezone),
            'modifyAt' => Utils\DateTime::formatToUtcWithTimeZone($this->updated_at, $timezone),
        ];
    }
}
