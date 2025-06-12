<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';

    // Các trường cho phép gán hàng loạt
    protected $fillable = [
        'order_code',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'customer_province',
        'customer_district',
        'customer_ward',
        'total_amount',
        'shipping_fee',
        'discount_amount',
        'amount_due',
        'payment_method',
        'payment_status',
        'paid_at',
        'status',
        'cancel_reason',
        'cancelled_at',
        'coupon_code',
        'note',
        'ordered_at'
    ];

    // Kiểu dữ liệu đặc biệt
    protected $casts = [
        'paid_at'    => 'datetime',
        'ordered_at' => 'datetime',
        'total_amount'    => 'decimal:2',
        'shipping_fee'    => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'amount_due'      => 'decimal:2',
        'cancelled_at' => 'datetime'
    ];

    // Các trạng thái đơn hàng
    const STATUS_PENDING = 'pending';           // Chờ xác nhận
    const STATUS_PROCESSING = 'processing';     // Đã xác nhận - đang xử lý
    const STATUS_DELIVERING = 'delivering';     // Đang giao
    const STATUS_COMPLETED = 'completed';       // Đã giao thành công
    const STATUS_FINISHED = 'finished';         // Hoàn thành toàn bộ (kết thúc quy trình)
    const STATUS_CANCELLED = 'cancelled';       // Đã hủy
    const STATUS_FAILED = 'failed';             // Giao hàng thất bại

    // Các trạng thái thanh toán
    const PAYMENT_STATUS_UNPAID = 'unpaid';     // Chưa thanh toán
    const PAYMENT_STATUS_PAID = 'paid';         // Đã thanh toán

    // Các phương thức thanh toán
    const PAYMENT_METHOD_COD = 'cod';           // Thanh toán khi nhận hàng
    const PAYMENT_METHOD_BANK_TRANSFER = 'bank_transfer'; // Chuyển khoản ngân hàng

    // Mối quan hệ: Một đơn hàng có nhiều order item
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // (Nếu muốn liên kết User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Lấy danh sách trạng thái đơn hàng
    public function getStatusOptionsAttribute()
    {
        return [
            self::STATUS_PENDING => 'Chờ xác nhận',
            self::STATUS_PROCESSING => 'Đã xác nhận - đang xử lý',
            self::STATUS_DELIVERING => 'Đang giao',
            self::STATUS_COMPLETED => 'Đã giao thành công',
            self::STATUS_FINISHED => 'Hoàn thành toàn bộ',
            self::STATUS_CANCELLED => 'Đã hủy',
            self::STATUS_FAILED => 'Giao hàng thất bại'
        ];
    }

    // Lấy danh sách trạng thái thanh toán
    public function getPaymentStatusOptionsAttribute()
    {
        return [
            self::PAYMENT_STATUS_UNPAID => 'Chưa thanh toán',
            self::PAYMENT_STATUS_PAID => 'Đã thanh toán'
        ];
    }

    // Lấy danh sách phương thức thanh toán
    public function getPaymentMethodOptionsAttribute()
    {
        return [
            self::PAYMENT_METHOD_COD => 'Thanh toán khi nhận hàng',
            self::PAYMENT_METHOD_BANK_TRANSFER => 'Chuyển khoản ngân hàng'
        ];
    }

    // Lấy text trạng thái đơn hàng
    public function getStatusTextAttribute()
    {
        return $this->status_options[$this->status] ?? 'Không xác định';
    }

    // Lấy text trạng thái thanh toán
    public function getPaymentStatusTextAttribute()
    {
        return $this->payment_status_options[$this->payment_status] ?? 'Không xác định';
    }

    // Lấy text phương thức thanh toán
    public function getPaymentMethodTextAttribute()
    {
        return $this->payment_method_options[$this->payment_method] ?? 'Không xác định';
    }

    // Lấy màu badge cho trạng thái đơn hàng
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_PROCESSING => 'info',
            self::STATUS_DELIVERING => 'primary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_FINISHED => 'success',
            self::STATUS_CANCELLED => 'danger',
            self::STATUS_FAILED => 'secondary',
            default => 'secondary'
        };
    }

    // Lấy màu badge cho trạng thái thanh toán
    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            self::PAYMENT_STATUS_UNPAID => 'warning',
            self::PAYMENT_STATUS_PAID => 'success',
            default => 'secondary'
        };
    }
}