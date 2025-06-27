<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'title',
        'slug',
        'author',
        'category_id',
        'publisher_id',
        'description',
        'cover_image',
        'published_at',
        'isbn',
        'view_count',
        'order_count',
        'is_active',
        'is_hot',
        'is_new',
        'is_best_seller',
        'is_recommended',
        'is_featured',
        'is_promotion',
        'length_cm',
        'width_cm',
        'weight_g',
        'page_count',
    ];

    protected $casts = [
        'published_at'    => 'date',
        'is_active'       => 'boolean',
        'is_hot'          => 'boolean',
        'is_new'          => 'boolean',
        'is_best_seller'  => 'boolean',
        'is_recommended'  => 'boolean',
        'is_featured'     => 'boolean',
        'is_promotion'    => 'boolean',
        'view_count'      => 'integer',
        'order_count'     => 'integer',
        'length_cm'       => 'float',
        'width_cm'        => 'float',
        'weight_g'        => 'integer',
        'page_count'      => 'integer',
    ];

    // ================= Relationships =================

    /**
     * Danh mục sách
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Nhà xuất bản
     */
    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    /**
     * Bình luận
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Đánh giá
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Ảnh sách
     */
    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    /**
     * Các biến thể sản phẩm (format, ngôn ngữ, ...)
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ================= Scopes =================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeHot($query)
    {
        return $query->where('is_hot', true);
    }

    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    public function scopeBestSeller($query)
    {
        return $query->where('is_best_seller', true);
    }

    public function scopeRecommended($query)
    {
        return $query->where('is_recommended', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePromotion($query)
    {
        return $query->where('is_promotion', true);
    }

    // ================= Accessors & Mutators =================

    /**
     * Đường dẫn ảnh bìa
     */
    public function getCoverImageUrlAttribute()
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    }

    /**
     * Điểm đánh giá trung bình
     */
    public function getAverageRatingAttribute()
    {
        $rating = $this->ratings()->avg('rating');
        return $rating ? round($rating, 1) : null;
    }

    /**
     * Số lượng đánh giá
     */
    public function getRatingCountAttribute()
    {
        return $this->ratings()->count();
    }

    /**
     * Giá thấp nhất của các biến thể
     */
    public function getMinPriceAttribute()
    {
        return $this->variants()->min('price');
    }

    /**
     * Giá cao nhất của các biến thể
     */
    public function getMaxPriceAttribute()
    {
        return $this->variants()->max('price');
    }

    /**
     * Giá khuyến mãi thấp nhất (nếu có)
     */
    public function getMinPromotionPriceAttribute()
    {
        return $this->variants()->whereNotNull('promotion_price')->min('promotion_price');
    }
} 