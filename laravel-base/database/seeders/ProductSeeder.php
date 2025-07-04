<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'title' => 'Đắc Nhân Tâm',
                'slug' => 'dac-nhan-tam',
                'author' => 'Dale Carnegie',
                'category_id' => 1, // Cần đảm bảo category này tồn tại
                'publisher_id' => 1, // Cần đảm bảo publisher này tồn tại
                'description' => 'Đắc nhân tâm của Dale Carnegie là quyển sách nổi tiếng nhất, bán chạy nhất và có tầm ảnh hưởng nhất của mọi thời đại.',
                'cover_image' => null,
                'published_at' => '2019-01-01',
                'isbn' => '9786048862545',
                'is_active' => true,
                'is_hot' => true,
                'is_best_seller' => true,
                'length_cm' => 20.5,
                'width_cm' => 14.5,
                'weight_g' => 350,
                'page_count' => 320,
            ],
            [
                'title' => 'Nhà Giả Kim',
                'slug' => 'nha-gia-kim',
                'author' => 'Paulo Coelho',
                'category_id' => 1,
                'publisher_id' => 1,
                'description' => 'Nhà giả kim của Paulo Coelho như một câu chuyện cổ tích giản dị, nhân ái, giàu chất thơ, thấm đẫm những minh triết huyền bí của phương Đông.',
                'cover_image' => null,
                'published_at' => '2018-01-01',
                'isbn' => '9786048862546',
                'is_active' => true,
                'is_new' => true,
                'is_recommended' => true,
                'length_cm' => 19.0,
                'width_cm' => 13.0,
                'weight_g' => 300,
                'page_count' => 250,
            ],
            [
                'title' => 'Tôi Thấy Hoa Vàng Trên Cỏ Xanh',
                'slug' => 'toi-thay-hoa-vang-tren-co-xanh',
                'author' => 'Nguyễn Nhật Ánh',
                'category_id' => 2,
                'publisher_id' => 2,
                'description' => 'Tôi thấy hoa vàng trên cỏ xanh là một tác phẩm đặc sắc của nhà văn Nguyễn Nhật Ánh.',
                'cover_image' => null,
                'published_at' => '2020-01-01',
                'isbn' => '9786048862547',
                'is_active' => true,
                'is_featured' => true,
                'length_cm' => 21.0,
                'width_cm' => 15.0,
                'weight_g' => 370,
                'page_count' => 340,
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->insert([
                'title' => $product['title'],
                'slug' => $product['slug'],
                'author' => $product['author'],
                'category_id' => $product['category_id'],
                'publisher_id' => $product['publisher_id'],
                'description' => $product['description'],
                'cover_image' => $product['cover_image'],
                'published_at' => $product['published_at'],
                'isbn' => $product['isbn'],
                'view_count' => rand(100, 1000),
                'order_count' => rand(10, 100),
                'is_active' => $product['is_active'],
                'is_hot' => $product['is_hot'] ?? false,
                'is_new' => $product['is_new'] ?? false,
                'is_best_seller' => $product['is_best_seller'] ?? false,
                'is_recommended' => $product['is_recommended'] ?? false,
                'is_featured' => $product['is_featured'] ?? false,
                'is_promotion' => $product['is_promotion'] ?? false,
                'length_cm' => $product['length_cm'],
                'width_cm' => $product['width_cm'],
                'weight_g' => $product['weight_g'],
                'page_count' => $product['page_count'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            DB::table('products')->insert([
                'title' => 'Sản phẩm số ' . $i,
                'slug' => 'san-pham-so-' . $i,
                'author' => fake()->name(),
                'category_id' => 1,
                'publisher_id' => 1,
                'description' => fake()->paragraph(),
                'cover_image' => null,
                'published_at' => now()->subYears(rand(1, 5)),
                'isbn' => '97860488625' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'view_count' => rand(100, 1000),
                'order_count' => rand(10, 100),
                'is_active' => true,
                'is_hot' => true,
                'is_new' => true,
                'is_best_seller' => true,
                'is_recommended' => true,
                'is_featured' => true,
                'is_promotion' => true,
                'length_cm' => rand(15, 22),
                'width_cm' => rand(11, 16),
                'weight_g' => rand(300, 400),
                'page_count' => rand(200, 400),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}