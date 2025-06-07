<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy tất cả id sản phẩm đã có trong bảng products
        $productIds = DB::table('products')->pluck('id');
        
        $formats = [
            ['id' => 1, 'name' => 'Bìa mềm'],
            ['id' => 2, 'name' => 'Bìa cứng'],
            ['id' => 3, 'name' => 'Ebook'],
        ];
        $languages = [
            ['id' => 1, 'name' => 'Tiếng Việt'],
            ['id' => 2, 'name' => 'Tiếng Anh'],
        ];

        foreach ($productIds as $productId) {
            foreach ($formats as $format) {
                foreach ($languages as $lang) {
                    $sku = "P{$productId}-F{$format['id']}-L{$lang['id']}";
                    DB::table('product_variants')->insert([
                        'product_id' => $productId,
                        'format_id' => $format['id'],
                        'language_id' => $lang['id'],
                        'sku' => $sku,
                        'price' => rand(ceil(59000 / 1000), floor(159000 / 1000)) * 1000,
                        'promotion_price' => rand(ceil(45000 / 1000), floor(99000 / 1000)) * 1000,
                        'stock' => rand(30, 200),
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}