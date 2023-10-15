<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class DocumentsResource extends JsonResource
{
    public function __construct(LengthAwarePaginator $resource)
    {
        parent::__construct($resource);
    }

    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $documents = DocumentResource::collection($this->resource->items());
        return [
            'document' => $documents,
            'pagination' => [
                "page" => $this->resource->currentPage(),
                "perPage" => $this->resource->perPage(),
                "total" => $this->resource->total()
            ]
        ];
    }
}
