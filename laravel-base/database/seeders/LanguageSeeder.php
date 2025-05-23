<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            [
                'name' => 'Tiếng Việt',
                'code' => 'vi',
                'slug' => 'tieng-viet',
                'is_active' => true,
            ],
            [
                'name' => 'Tiếng Anh',
                'code' => 'en',
                'slug' => 'tieng-anh',
                'is_active' => true,
            ],
            [
                'name' => 'Tiếng Trung',
                'code' => 'zh',
                'slug' => 'tieng-trung',
                'is_active' => true,
            ],
            [
                'name' => 'Tiếng Nhật',
                'code' => 'ja',
                'slug' => 'tieng-nhat',
                'is_active' => true,
            ],
            [
                'name' => 'Tiếng Hàn',
                'code' => 'ko',
                'slug' => 'tieng-han',
                'is_active' => true,
            ],
        ];

        foreach ($languages as $language) {
            DB::table('languages')->insert([
                'name' => $language['name'],
                'code' => $language['code'],
                'slug' => $language['slug'],
                'is_active' => $language['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 