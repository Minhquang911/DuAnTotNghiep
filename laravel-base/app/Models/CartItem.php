<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'variant_id',
        'quantity',
    ];

    // Mỗi mục giỏ hàng thuộc về 1 giỏ
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Mỗi mục giỏ hàng chứa thông tin 1 sản phẩm
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // Tổng giá trị của mục này
    public function totalPrice()
    {
        return $this->quantity * $this->product->gia;
    }
}