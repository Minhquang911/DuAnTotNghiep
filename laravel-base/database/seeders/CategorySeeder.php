<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Danh mục
        DB::table('categories')->insert([
            [
                'name' => 'Sách',
                'slug' => Str::slug('Sách'),
                'description' => 'Tất cả các loại sách',
                'is_active' => true,
                'created_at' => now()
            ],
            [
                'name' => 'Sách Giáo Khoa',
                'slug' => Str::slug('Sách Giáo Khoa'),
                'description' => 'Sách giáo khoa các cấp',
                'is_active' => true,
                'created_at' => now()
            ],
            [
                'name' => 'Sách Tham Khảo',
                'slug' => Str::slug('Sách Tham Khảo'),
                'description' => 'Sách tham khảo cho học sinh, sinh viên',
                'is_active' => true,
                'created_at' => now()
            ],
            [
                'name' => 'Văn Phòng Phẩm',
                'slug' => Str::slug('Văn Phòng Phẩm'),
                'description' => 'Các loại văn phòng phẩm',
                'is_active' => true,
                'created_at' => now()
            ]
        ]);
    }
} 