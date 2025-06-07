<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $users = User::inRandomOrder()->take(10)->get();
        $products = \App\Models\Product::all();
        $productVariants = \App\Models\ProductVariant::all();

        for ($i = 0; $i < 20; $i++) {
            // Sinh 1 order
            $user = $users->random();

            $statusArr = [
                'pending',
                'processing',
                'delivering',
                'completed',
                'finished',
                'cancelled',
                'failed'
            ];
            $status = $statusArr[array_rand($statusArr)];

            $discount = rand(0, 1) ? rand(ceil(5000 / 1000), floor(15000 / 1000)) * 1000 : 0;

            $order = Order::create([
                'order_code' => 'OD' . strtoupper(Str::random(8)),
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '09' . rand(10000000, 99999999),
                'customer_address' => fake()->address(),
                'customer_province' => 'Tỉnh ' . rand(1, 20),
                'customer_district' => 'Huyện ' . rand(1, 30),
                'customer_ward' => 'Phường ' . rand(1, 40),
                'total_amount' => 0, // sẽ cập nhật lại bên dưới
                'shipping_fee' => 30000,
                'discount_amount' => $discount,
                'amount_due' => 0, // sẽ cập nhật lại bên dưới
                'payment_method' => ['cod', 'bank_transfer'][rand(0, 1)],
                'payment_status' => ['unpaid', 'paid'][rand(0, 1)],
                'paid_at' => rand(0, 1) ? Carbon::now() : null,
                'status' => $status,
                'coupon_code' => rand(0, 1) ? 'SALE' . rand(100, 999) : null,
                'note' => rand(0, 1) ? 'Khách yêu cầu giao giờ hành chính.' : null,
                'ordered_at' => Carbon::now(),
            ]);

            // Tạo order_items cho order này
            $numItems = rand(1, 5); // mỗi order có 1-5 dòng sản phẩm
            $usedProducts = $products->random(min($numItems, $products->count()));

            $orderTotal = 0;

            foreach ($usedProducts as $product) {
                if (empty($product->title)) {
                    continue;
                }
                $variants = $productVariants->where('product_id', $product->id);
                $variant = $variants->count() ? $variants->random() : null;

                $price = rand(ceil(45000 / 1000), floor(99000 / 1000)) * 1000;
                $quantity = rand(1, 5);
                $total = $price * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $variant ? $variant->id : null,
                    'sku' => $variant ? $variant->sku : 'SKU' . rand(10000, 99999),
                    'product_name' => $product->title,
                    'product_variant_name' => $variant ? $variant->name : null,
                    'product_image' => $product->cover_image ?? null,
                    'price' => $price,
                    'quantity' => $quantity,
                    'total' => $total,
                ]);
                $orderTotal += $total;
            }

            // Cập nhật lại tổng tiền cho order
            $order->update([
                'total_amount' => $orderTotal,
                'amount_due'   => $orderTotal + $order->shipping_fee - $order->discount_amount
            ]);
        }
    }
}