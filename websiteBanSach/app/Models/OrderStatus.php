<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    public $timestamps = false;

    protected $fillable = ['status_name', 'display_order', 'created_at'];

//    public function orders()
//    {
//        return $this->hasMany(Order::class);
//    }
}
