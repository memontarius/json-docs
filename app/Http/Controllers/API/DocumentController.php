<?php

namespace App\Http\Controllers\API;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\DocumentsResource;
use App\Models\Document;
use App\Models\User;
use App\Services\ErrorResponder\ErrorResponder;
use App\Services\ErrorResponder\ResponseError;
use App\Services\JsonPatcher\JsonPatcherInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class DocumentController extends Controller
{
    private readonly ErrorResponder $errorResponder;

    public function __construct()
    {
        $this->errorResponder = app()->make(ErrorResponder::class);
    }

    public function store(): JsonResponse
    {
        /** @var User $user */
        $user = request()->user();

        $document = Document::query()->create([
            'status' => DocumentStatus::Draft,
            'payload' => null,
            'user_id' => (int)$user->getKey()
        ]);

        return (new DocumentResource($document))
            ->response()
            ->setStatusCode(200);
        return 'store';
    }

    public function index(): DocumentsResource
    {
        $query = request()->query();
        $perPage = $query['perPage'] ?? 20;
        $user = request()->user();
        $query = null;

        if ($user) {
            $query = Document::query()
                ->where(function ($query) use($user) {
                    $query->where('status', DocumentStatus::Draft);
                    $query->where('user_id', $user->id);
                })
                ->orWhere('status', DocumentStatus::Published);
        } else {
            $query = Document::where('status', DocumentStatus::Published);
        }

        $documents = $query->paginate($perPage, ['*']);

        return new DocumentsResource($documents);
    }

    public function show(Request $request, Document $document): DocumentResource|JsonResponse
    {
        if (!$this->hasAccessToDocument($request->user(), $document)) {
            return $this->errorResponder->makeByError(ResponseError::Forbidden);
        }
        return new DocumentResource($document);
    }

    public function update(Document $document, JsonPatcherInterface $jsonPatcher): DocumentResource|JsonResponse
    {
        if (!$this->isOwner(request()->user(), $document)) {
            return $this->errorResponder->makeByError(ResponseError::Forbidden);
        }
        if ($document->status === DocumentStatus::Published) {
            return $this->errorResponder->makeByError(ResponseError::NotAllowedEditPublishedDocument);
        }

        $newPayload = null;
        $userPayload = request()->json('document.payload');
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
            return $this->errorResponder->makeByError(ResponseError::BadRequest);
        }

        $document->update([
            'payload' => $newPayload
        ]);

        return new DocumentResource($document);
    }

    public function publish(Document $document): DocumentResource|JsonResponse
    {
        if (!$this->isOwner(request()->user(), $document)) {
            return $this->errorResponder->makeByError(ResponseError::Forbidden);
        }

        $document->update([
            'status' => DocumentStatus::Published
        ]);
        return new DocumentResource($document);
    }

    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @param Document $document
     * @return bool
     */
    private function hasAccessToDocument(?\Illuminate\Contracts\Auth\Authenticatable $user, Document $document): bool
    {
        return ($this->isOwner($user, $document) || $document->status === DocumentStatus::Published);
    }

    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @param Document $document
     * @return bool
     */
    private function isOwner(?\Illuminate\Contracts\Auth\Authenticatable $user, Document $document): bool
    {
        return $user && $user->id === $document->user_id;
    }
}
