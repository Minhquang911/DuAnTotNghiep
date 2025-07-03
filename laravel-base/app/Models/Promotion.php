<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'code',
        'title',
        'description',
        'discount_type',
        'discount_value',
        'max_discount_value',
        'min_order_value',
        'is_active',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'image'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}