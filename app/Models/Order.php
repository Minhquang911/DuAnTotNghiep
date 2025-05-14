<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



use Illuminate\Database\Eloquent\Factories\HasFactory;


class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'user_name', 'user_email', 'user_phone', 'shipping_address', 'total_amount',
        'status_id', 'payment_method', 'promotion_code', 'promotion_discount', 'shipping_fee', 'final_amount'
    ];
    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }


}
