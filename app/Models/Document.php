<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    const STATUSES = [
        'draft',
        'published'
    ];
}
