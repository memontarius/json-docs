<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'payload'];
    
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

}
