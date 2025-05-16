<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    protected $fillable = [
        'title',
        'description',
        'author',
        'publisher',
        'category_id',
        'price',
        'stock',
        'status',
        'cover_image'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
} 