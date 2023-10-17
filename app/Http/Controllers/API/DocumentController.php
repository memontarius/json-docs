<?php

namespace App\Http\Controllers\API;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\DocumentsResource;
use App\Models\Document;
use App\Services\ErrorResponder;
use App\Services\JsonPatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class DocumentController extends Controller
{
    public function store(): JsonResponse
    {
        $document = Document::create([
            'status' => DocumentStatus::Draft,
            'payload' => null,
        ]);

        return (new DocumentResource($document))
            ->response()
            ->setStatusCode(200);
    }

    public function index(Request $request): DocumentsResource
    {
        $query = $request->query();
        $perPage = $query['perPage'] ?? 20;
        $documents = Document::paginate($perPage, ['*']);
        return new DocumentsResource($documents);
    }

    public function show(Document $document): DocumentResource
    {
        return new DocumentResource($document);
    }

    public function update(Request $request, Document $document, JsonPatcher $jsonPatcher, ErrorResponder $errorResponder): DocumentResource|JsonResponse
    {
        if ($document->status === DocumentStatus::Published) {
            return $errorResponder->make('Not allowed to edit a published document', 400);
        }

        $newPayload = null;
        $userPayload = $request->json('document.payload');
        $userPayload = json_decode(json_encode($userPayload));

        if ($userPayload !== null) {
            if (!$document->payload) {
                $newPayload = $userPayload;
            } else {
                $newPayload = $document->payload;
                if (!$jsonPatcher->apply($newPayload, $userPayload)) {
                    $newPayload = null;
                }
            }
        }

        if ($newPayload === null) {
            $details = $userPayload === null ? 'Invalid input data' : '';
            return $errorResponder->make('Bad request', 400, $details);
        }

        $document->update([
            'payload' => $newPayload
        ]);

        return new DocumentResource($document);
    }

    public function publish(Document $document): DocumentResource|JsonResponse
    {
        $document->update([
            'status' => DocumentStatus::Published
        ]);
        return new DocumentResource($document);
    }
}
