<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'payload', 'user_id'];
    protected $hidden = ['user_id'];

    protected $casts = [
        'status' => DocumentStatus::class,
        'payload' => 'object'
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($document) {
            $document->{$document->getKeyName()} = (string)Str::uuid();
        });
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    /**
     * @param Authenticatable|null $user
     * @param Document $document
     * @return bool
     */
    public function hasAccess(?Authenticatable $user): bool
    {
        return ($this->isOwner($user, $this) || $this->status === DocumentStatus::Published);
    }

    /**
     * @param Authenticatable|null $user
     * @param Document $document
     * @return bool
     */
    public function isOwner(?Authenticatable $user): bool
    {
        return $user && $user->id === $this->user_id;
    }
}
