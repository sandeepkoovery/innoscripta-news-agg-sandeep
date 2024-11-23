<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content', 'category', 'source', 'url', 'api_source', 'published_at',
    ];

    // Optionally, cast the 'published_at' field to a date
    protected $casts = [
        'published_at' => 'datetime',
    ];
}
