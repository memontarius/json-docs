<?php

namespace App\Enums;

enum DocumentStatus: string
{
    case Draft = 'draft';
    case Published = 'published';

    public static function toArray(): array
    {
        return array_column(DocumentStatus::cases(), 'value');
    }
}
