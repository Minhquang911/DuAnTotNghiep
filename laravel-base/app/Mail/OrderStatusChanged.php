<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $oldStatus, string $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        $statusLabels = [
            'pending' => 'Chờ xác nhận',
            'processing' => 'Đang xử lý',
            'delivering' => 'Đang giao hàng',
            'completed' => 'Đã giao thành công',
            'finished' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            'failed' => 'Giao hàng thất bại'
        ];

        $subject = "Cập nhật trạng thái đơn hàng #{$this->order->order_code}";
        
        return $this->subject($subject)
                    ->view('emails.orders.status-changed')
                    ->with([
                        'order' => $this->order,
                        'oldStatusLabel' => $statusLabels[$this->oldStatus] ?? $this->oldStatus,
                        'newStatusLabel' => $statusLabels[$this->newStatus] ?? $this->newStatus,
                    ]);
    }
} 