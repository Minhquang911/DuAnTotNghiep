<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Promotion::create([
            'code' => 'SALE50',
            'description' => 'Giảm 50% cho đơn từ 200k',
            'discount_type' => 'percent',
            'discount_value' => 50,
            'max_discount_amount' => 100000,
            'min_order_amount' => 200000,
            'max_usage' => 100,
            'usage_count' => 0,
            'start_date' => now()->subDays(1),
            'end_date' => now()->addDays(30),
            'is_active' => true,
        ]);
    }
}
