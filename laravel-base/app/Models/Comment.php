<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'content',
        'is_approved'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    // Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ với Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scope để lấy các bình luận đã được duyệt
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // Scope để lấy các bình luận chưa được duyệt
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }
}