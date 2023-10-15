<?php

namespace App\Http\Controllers\API;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use App\Services\JsonPatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class DocumentController extends Controller
{
    public function store(): DocumentResource
    {
        $document = Document::create([
            'status' => DocumentStatus::Draft,
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
        $userDocument = $request->post();
        $userPayload = $userDocument['document']['payload'] ?? null;
        $userPayload = json_decode(json_encode($userPayload));

        if ($userPayload !== null) {
            if (!$document->payload) {
                $newPayload = $userPayload;
            } else {
                $newPayload = $document->payload;
                if (!JsonPatcher::create()->apply($newPayload, $userPayload)) {
                    $newPayload = null;
                }
            }
        }

        if ($newPayload === null) {
            $errors = ['error' => 'Bad Request',];
            if ($userPayload === null) {
                $errors['details'] = 'Invalid input data';
            }
            return response()->json($errors, 400);
        }

        $document->update([
            'payload' => $newPayload
        ]);

        return new DocumentResource($document);
    }
}
