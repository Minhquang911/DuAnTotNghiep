<?php

namespace App\Jobs;

use App\Mail\OrderStatusChanged;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderStatusEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order, string $oldStatus, string $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Gửi email cho khách hàng
        if ($this->order->customer_email) {
            Mail::to($this->order->customer_email)
                ->send(new OrderStatusChanged($this->order, $this->oldStatus, $this->newStatus));
        }

        // Nếu đơn hàng có user_id, gửi thêm email cho user
        if ($this->order->user && $this->order->user->email !== $this->order->customer_email) {
            Mail::to($this->order->user->email)
                ->send(new OrderStatusChanged($this->order, $this->oldStatus, $this->newStatus));
        }
    }
} 