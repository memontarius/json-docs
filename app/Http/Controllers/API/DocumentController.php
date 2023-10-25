<?php

namespace App\Http\Controllers\API;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\DocumentsResource;
use App\Models\Document;
use App\Models\User;
use App\Services\DocumentService;
use App\Services\ErrorResponder\ErrorResponder;
use App\Services\ErrorResponder\ResponseError;
use App\Services\JsonPatcher\JsonPatcherInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class DocumentController extends Controller
{
    private readonly ErrorResponder $errorResponder;
    private readonly DocumentService $documentService;

    public function __construct()
    {
        $this->errorResponder = app()->make(ErrorResponder::class);
        $this->documentService = app()->make(DocumentService::class);
    }

    public function store(): JsonResponse
    {
        $document = $this->documentService->store(request()->user());

        return (new DocumentResource($document))
            ->response()
            ->setStatusCode(200);
    }

    public function index(): DocumentsResource
    {
        $query = request()->query();
        $perPage = $query['perPage'] ?? 20;
        $documents = $this->documentService->index(request()->user(), $perPage);

        return new DocumentsResource($documents);
    }

    public function show(Document $document): DocumentResource|JsonResponse
    {
        if (!$document->hasAccess(request()->user())) {
            return $this->errorResponder->makeByError(ResponseError::Forbidden);
        }

        return new DocumentResource($document);
    }

    public function update(Document $document, JsonPatcherInterface $jsonPatcher): DocumentResource|JsonResponse
    {
        if (!$document->isOwner(request()->user())) {
            return $this->errorResponder->makeByError(ResponseError::Forbidden);
        }

        if ($document->status === DocumentStatus::Published) {
            return $this->errorResponder->makeByError(ResponseError::NotAllowedEditPublishedDocument);
        }

        if (!$this->documentService->update($document)) {
            return $this->errorResponder->makeByError(ResponseError::BadRequest);
        }

        return new DocumentResource($document);
    }

    public function publish(Document $document): DocumentResource|JsonResponse
    {
        if (!$document->isOwner(request()->user())) {
            return $this->errorResponder->makeByError(ResponseError::Forbidden);
        }

        $this->documentService->publish($document);

        return new DocumentResource($document);
    }
}
