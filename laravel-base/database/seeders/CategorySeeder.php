<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Danh mục cha
        $sachId = DB::table('categories')->insertGetId([
            'name' => 'Sách',
            'slug' => Str::slug('Sách'),
            'description' => 'Tất cả các loại sách',
            'parent_id' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Danh mục con
        DB::table('categories')->insert([
            [
                'name' => 'Sách Giáo Khoa',
                'slug' => Str::slug('Sách Giáo Khoa'),
                'description' => 'Sách giáo khoa các cấp',
                'parent_id' => $sachId,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sách Tham Khảo',
                'slug' => Str::slug('Sách Tham Khảo'),
                'description' => 'Sách tham khảo cho học sinh, sinh viên',
                'parent_id' => $sachId,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Danh mục độc lập
        DB::table('categories')->insert([
            'name' => 'Văn Phòng Phẩm',
            'slug' => Str::slug('Văn Phòng Phẩm'),
            'description' => 'Các loại văn phòng phẩm',
            'parent_id' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
} 