<?php

namespace App\Http\Controllers\API;

use App\Enums\DocumentStatus;
use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\DocumentsResource;
use App\Models\Document;
use App\Services\DocumentService;
use App\Services\ErrorResponder\ErrorResponder;
use App\Services\ErrorResponder\ResponseError;
use Exception;
use Illuminate\Http\JsonResponse;


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

    public function show(string $documentId): DocumentResource|JsonResponse
    {
        $document = Document::findOrFail($documentId);

        if (!$document->hasAccess(request()->user())) {
            return $this->errorResponder->makeByError(ResponseError::Forbidden);
        }

        return new DocumentResource($document);
    }

    /**
     * @throws Exception
     */
    public function update(string $documentId): DocumentResource|JsonResponse
    {
        return $this->documentService->updateByTransaction(
            $documentId,
            function (Document $document): ?ResponseError {
                return match (true) {
                    !$document->isOwner(request()->user()) => ResponseError::Forbidden,
                    $document->status === DocumentStatus::Published => ResponseError::NotAllowedEditPublishedDocument,
                    !$this->documentService->update($document) => ResponseError::BadRequest,
                    default => null
                };
            },
            function (Document $document): ?ResponseError {
                $isSuccessful = $this->documentService->update($document);
                return $isSuccessful ? null : ResponseError::BadRequest;
            }
        );
    }

    /**
     * @throws Exception
     */
    public function publish(string $documentId): DocumentResource|JsonResponse
    {
        return $this->documentService->updateByTransaction(
            $documentId,
            fn (Document $document) =>
                $document->isOwner(request()->user())
                    ? null
                    : ResponseError::Forbidden,
            function (Document $document): ?ResponseError {
                $this->documentService->publish($document);
                return null;
            }
        );
    }
}
