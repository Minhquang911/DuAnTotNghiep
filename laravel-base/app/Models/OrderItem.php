<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'sku',
        'product_name',
        'product_variant_name',
        'product_image',
        'price',
        'quantity',
        'total',
    ];

    protected $casts = [
        'price'  => 'decimal:2',
        'total'  => 'decimal:2',
    ];

    // Mối quan hệ: OrderItem thuộc về một Order
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Mối quan hệ: OrderItem thuộc về một Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Mối quan hệ: Nếu có biến thể, OrderItem thuộc về một ProductVariant
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }
}