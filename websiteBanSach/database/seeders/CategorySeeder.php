<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Điện thoại'],
            ['name' => 'Máy tính'],
            ['name' => 'Đồng hồ'],
            ['name' => 'Thời trang'],
            ['name' => 'Gia dụng'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}