<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderStatus extends Model
{
    use HasFactory;

    protected $table = 'order_statuses'; // Đảm bảo đúng tên bảng

    protected $fillable = [
        'status_name',
        'display_order',
    ];

    public $timestamps = true;

    /**
     * Danh sách đơn hàng liên quan đến trạng thái này
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'status_id');
    }
}
