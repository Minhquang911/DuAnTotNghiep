<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 20; $i++) {
            DB::table('orders')->insert([
                'user_id' => null, // hoặc random user_id nếu có bảng users
                'user_name' => $faker->name,
                'user_email' => $faker->unique()->email,
                'user_phone' => $faker->phoneNumber,
                'shipping_address' => $faker->address,
                'total_amount' => $total = $faker->randomFloat(2, 500000, 5000000),
                'status_id' => rand(1, 3), // Giả sử status_id 1, 2, 3 tồn tại
                'payment_method' => $faker->randomElement(['COD', 'Online']),
                'promotion_code' => $faker->randomElement([null, 'SALE20', 'DISCOUNT50']),
                'promotion_discount' => $discount = rand(0, 100000),
                'shipping_fee' => $ship = rand(15000, 40000),
                'final_amount' => $total - $discount + $ship,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

