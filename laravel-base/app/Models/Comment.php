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
        'is_approved',
        'parent_id',
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

    // Quan hệ với Comment cha
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Quan hệ với các Comment con
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
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

    // Scope để lấy các bình luận cha (không phải reply)
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }
}