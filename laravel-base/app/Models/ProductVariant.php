<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'format_id',
        'language_id',
        'sku',
        'price',
        'promotion_price',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'promotion_price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function format()
    {
        return $this->belongsTo(Format::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeHasPromotion($query)
    {
        return $query->whereNotNull('promotion_price')
                    ->where('promotion_price', '<', DB::raw('price'));
    }

    // Accessors & Mutators
    public function getDiscountPercentageAttribute()
    {
        if (!$this->promotion_price || $this->promotion_price >= $this->price) {
            return 0;
        }
        return round((($this->price - $this->promotion_price) / $this->price) * 100);
    }

    public function getFinalPriceAttribute()
    {
        return $this->promotion_price ?? $this->price;
    }

    public function getFullNameAttribute()
    {
        return ($this->product->title ?? '') . ' - ' .
               ($this->format->name ?? '') . ' - ' .
               ($this->language->name ?? '');
    }
} 