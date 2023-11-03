<?php

namespace App\Http\Controllers\API;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\DocumentsResource;
use App\Models\Document;
use App\Services\DocumentService;
use App\Services\ErrorResponder\ErrorResponder;
use App\Services\ErrorResponder\ResponseError;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;


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

    public function update(string $documentId): DocumentResource|JsonResponse
    {
        try {
            DB::beginTransaction();

            $document = Document::lockForUpdate()->find($documentId);

            if (!$document->isOwner(request()->user())) {
                return $this->errorResponder->makeByError(ResponseError::Forbidden);
            }

            if ($document->status === DocumentStatus::Published) {
                return $this->errorResponder->makeByError(ResponseError::NotAllowedEditPublishedDocument);
            }

            if (!$this->documentService->update($document)) {
                return $this->errorResponder->makeByError(ResponseError::BadRequest);
            }

            DB::commit();

            return new DocumentResource($document);
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->errorResponder->make('Error update document', 500);
        }
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
