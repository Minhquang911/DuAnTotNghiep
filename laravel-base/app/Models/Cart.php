<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    // Mỗi giỏ hàng thuộc về 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mỗi giỏ hàng có nhiều sản phẩm (cart_items)
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Tổng số lượng sản phẩm trong giỏ
    public function totalQuantity()
    {
        return $this->items->sum('quantity');
    }

    // Tổng tiền giỏ hàng (nếu muốn lưu thêm trường price tại thời điểm thêm vào)
    public function totalPrice()
    {
        return $this->items->sum(function ($item) {
            return $item->totalPrice();
        });
    }
}