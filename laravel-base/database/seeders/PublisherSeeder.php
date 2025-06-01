<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublisherSeeder extends Seeder
{
    public function run()
    {
        DB::table('publishers')->insert([
            [
                'name' => 'Nhà xuất bản Giáo Dục',
                'slug' => Str::slug('Nhà xuất bản Giáo Dục'),
                'address' => 'Hà Nội',
                'phone' => '0241234567',
                'email' => 'giaoduc@example.com',
                'description' => 'Chuyên xuất bản sách giáo dục',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhà xuất bản Kim Đồng',
                'slug' => Str::slug('Nhà xuất bản Kim Đồng'),
                'address' => 'Hà Nội',
                'phone' => '0242345678',
                'email' => 'kimdong@example.com',
                'description' => 'Chuyên xuất bản sách thiếu nhi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhà xuất bản Trẻ',
                'slug' => Str::slug('Nhà xuất bản Trẻ'),
                'address' => 'TP. Hồ Chí Minh',
                'phone' => '0283456789',
                'email' => 'tre@example.com',
                'description' => 'Chuyên xuất bản sách cho giới trẻ',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhà xuất bản Lao Động',
                'slug' => Str::slug('Nhà xuất bản Lao Động'),
                'address' => 'Hà Nội',
                'phone' => '0244567890',
                'email' => 'laodong@example.com',
                'description' => 'Chuyên xuất bản sách lao động',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 
