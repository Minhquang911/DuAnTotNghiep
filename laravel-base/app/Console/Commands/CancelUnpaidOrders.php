<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\OrderController;

class CancelUnpaidOrders extends Command
{
    protected $signature = 'orders:cancel-unpaid';
    protected $description = 'Tự động hủy các đơn hàng online chưa thanh toán sau 24h';

    public function handle()
    {
        $controller = new OrderController();
        $response = $controller->cancelUnpaidOrders();
        
        if ($response->getData()->success) {
            $this->info($response->getData()->message);
        } else {
            $this->error($response->getData()->message);
        }
    }
} 