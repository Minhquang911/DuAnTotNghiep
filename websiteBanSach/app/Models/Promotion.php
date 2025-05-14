<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'code', 'description', 'discount_type', 'discount_value',
        'max_discount_amount', 'min_order_amount', 'max_usage',
        'usage_count', 'start_date', 'end_date', 'is_active'
    ];
}
