<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promotion;
use Carbon\Carbon;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promotions = [
            [
                'title' => 'Giảm 10% cho đơn hàng từ 500k',
                'description' => 'Áp dụng cho tất cả khách hàng mới.',
                'discount_type' => 'percent',
                'discount_value' => 10,
                'max_discount_value' => 100000,
                'min_order_value' => 500000,
                'usage_limit' => 100,
                'used_count' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(30),
                'is_active' => true,
                'image' => null,
            ],
            [
                'title' => 'Giảm 50k cho đơn hàng từ 300k',
                'description' => 'Chỉ áp dụng cho sách giáo khoa.',
                'discount_type' => 'fixed',
                'discount_value' => 50000,
                'max_discount_value' => 50000,
                'min_order_value' => 300000, 
                'usage_limit' => 100,
                'used_count' => 0,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(10),
                'is_active' => true,
                'image' => null,
            ],
            [
                'title' => 'Flash Sale cuối tuần',
                'description' => 'Giảm 20% tối đa 30k cho mọi đơn hàng.',
                'discount_type' => 'percent',
                'discount_value' => 20,
                'max_discount_value' => 30000,
                'min_order_value' => 0,
                'usage_limit' => 100,
                'used_count' => 0,
                'start_date' => Carbon::now()->addDays(2),
                'end_date' => Carbon::now()->addDays(4),
                'is_active' => false,
                'image' => null,
            ],
        ];

        foreach ($promotions as $promotion) {
            Promotion::create($promotion);
        }
    }
}