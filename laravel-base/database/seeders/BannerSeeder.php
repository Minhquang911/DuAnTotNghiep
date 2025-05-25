<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Khuyến mãi sách mới',
                'image' => 'banners/promotion-1.jpg',
                'link' => '/books/new',
                'description' => 'Khám phá những cuốn sách mới nhất với giá ưu đãi',
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
            ],
            [
                'title' => 'Sách bán chạy',
                'image' => 'banners/bestseller.jpg',
                'link' => '/books/bestseller',
                'description' => 'Top những cuốn sách bán chạy nhất tháng',
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addDays(15),
            ],
            [
                'title' => 'Sách giảm giá',
                'image' => 'banners/sale.jpg',
                'link' => '/books/sale',
                'description' => 'Giảm giá lên đến 50% cho các đầu sách được chọn',
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addDays(7),
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}