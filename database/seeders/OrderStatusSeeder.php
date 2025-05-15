<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ✅ bạn đang thiếu dòng này!

class OrderStatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('order_statuses')->insert([
            ['status_name' => 'Chờ xác nhận', 'display_order' => 1],
            ['status_name' => 'Đang xử lý', 'display_order' => 2],
            ['status_name' => 'Đang giao hàng', 'display_order' => 3],
            ['status_name' => 'Hoàn tất', 'display_order' => 4],
            ['status_name' => 'Đã huỷ', 'display_order' => 5],
        ]);
    }
}
