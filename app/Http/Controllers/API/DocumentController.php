<?php

namespace App\Http\Controllers\API;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function store(): DocumentResource
    {
        $document = Document::create([
            'status' => DocumentStatus::Draft->value,
            'payload' => null,
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

    public function update(Request $request, Document $document): DocumentResource|JsonResponse
    {
        $newPayload = null;

        if (!$document->payload) {
            $userDocument = $request->post();
            $userPayload = $userDocument['document']['payload'] ?? null;
            if ($userPayload) {
                $newPayload = $userPayload;
            }
        } else {

        }

        if ($newPayload === null) {
            return response()->json(['error' => 'Bad Request'], 400);
        }

        $document->update([
            'payload' => $newPayload
        ]);

        return new DocumentResource($document);
    }
}
