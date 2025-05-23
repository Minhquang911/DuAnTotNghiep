<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $variants = [
            // Đắc Nhân Tâm - Bìa mềm Tiếng Việt
            [
                'product_id' => 1,
                'format_id' => 1, // Bìa mềm
                'language_id' => 1, // Tiếng Việt
                'sku' => 'DNT-BM-TV-001',
                'price' => 89000,
                'promotion_price' => 75000,
                'stock' => 100,
                'is_active' => true,
            ],
            // Đắc Nhân Tâm - Bìa cứng Tiếng Việt
            [
                'product_id' => 1,
                'format_id' => 2, // Bìa cứng
                'language_id' => 1, // Tiếng Việt
                'sku' => 'DNT-BC-TV-001',
                'price' => 129000,
                'promotion_price' => 99000,
                'stock' => 50,
                'is_active' => true,
            ],
            // Đắc Nhân Tâm - Ebook Tiếng Việt
            [
                'product_id' => 1,
                'format_id' => 3, // Ebook
                'language_id' => 1, // Tiếng Việt
                'sku' => 'DNT-EB-TV-001',
                'price' => 59000,
                'promotion_price' => 45000,
                'stock' => 999,
                'is_active' => true,
            ],
            // Nhà Giả Kim - Bìa mềm Tiếng Việt
            [
                'product_id' => 2,
                'format_id' => 1, // Bìa mềm
                'language_id' => 1, // Tiếng Việt
                'sku' => 'NGK-BM-TV-001',
                'price' => 79000,
                'promotion_price' => 65000,
                'stock' => 150,
                'is_active' => true,
            ],
            // Nhà Giả Kim - Bìa mềm Tiếng Anh
            [
                'product_id' => 2,
                'format_id' => 1, // Bìa mềm
                'language_id' => 2, // Tiếng Anh
                'sku' => 'NGK-BM-TA-001',
                'price' => 99000,
                'promotion_price' => 85000,
                'stock' => 80,
                'is_active' => true,
            ],
            // Tôi Thấy Hoa Vàng Trên Cỏ Xanh - Bìa mềm Tiếng Việt
            [
                'product_id' => 3,
                'format_id' => 1, // Bìa mềm
                'language_id' => 1, // Tiếng Việt
                'sku' => 'THV-BM-TV-001',
                'price' => 99000,
                'promotion_price' => 79000,
                'stock' => 200,
                'is_active' => true,
            ],
        ];

        foreach ($variants as $variant) {
            DB::table('product_variants')->insert([
                'product_id' => $variant['product_id'],
                'format_id' => $variant['format_id'],
                'language_id' => $variant['language_id'],
                'sku' => $variant['sku'],
                'price' => $variant['price'],
                'promotion_price' => $variant['promotion_price'],
                'stock' => $variant['stock'],
                'is_active' => $variant['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 