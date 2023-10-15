<?php

namespace App\Http\Controllers\API;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Models\Document;

class DocumentController extends Controller
{
    public function store(): DocumentResource
    {
        $document = Document::create([
            'status' => DocumentStatus::Draft->value,
            'payload' => '{}',
        ]);
        return new DocumentResource($document);
    }

    public function index()
    {
        return 'index';
    }

    public function show(Document $document): DocumentResource
    {
        return new DocumentResource($document);
    }
}
