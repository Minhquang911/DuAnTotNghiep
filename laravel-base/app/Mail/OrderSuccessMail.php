<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order->load('orderItems');
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        $subject = "Đặt hàng thành công - Mã đơn #{$this->order->order_code}";

        return $this->subject($subject)
                    ->view('emails.orders.success')
                    ->with([
                        'order' => $this->order
                    ]);
    }
} 