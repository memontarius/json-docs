<?php

namespace App\Services;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\User;
use App\Services\JsonPatcher\JsonPatcherInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DocumentService
{
    public function index(?User $user, int $perPage): LengthAwarePaginator
    {
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

        return $query->paginate($perPage, ['*']);
    }

    public function update(Document $document): bool
    {
        $jsonPatcher = app()->make(JsonPatcherInterface::class);
        $newPayload = null;
        $userPayload = json_decode(json_encode(
            request()->json('document.payload')
        ));

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
            return false;
        }

        $document->update([
            'payload' => $newPayload
        ]);
        return true;
    }

    public function store(User $user): Model|Builder
    {
        return Document::query()->create([
            'status' => DocumentStatus::Draft,
            'payload' => null,
            'user_id' => (int)$user->getKey()
        ]);
    }

    public function publish(Document $document): void
    {
        $document->update([
            'status' => DocumentStatus::Published
        ]);
    }
}
