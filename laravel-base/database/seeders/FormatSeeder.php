<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FormatSeeder extends Seeder
{
    public function run(): void
    {
        $formats = [
            [
                'name' => 'Bìa mềm',
                'slug' => 'bia-mem',
                'is_active' => true,
            ],
            [
                'name' => 'Bìa cứng',
                'slug' => 'bia-cung',
                'is_active' => true,
            ],
            [
                'name' => 'Ebook',
                'slug' => 'ebook',
                'is_active' => true,
            ],
            [
                'name' => 'Audio Book',
                'slug' => 'audio-book',
                'is_active' => true,
            ],
        ];

        foreach ($formats as $format) {
            DB::table('formats')->insert([
                'name' => $format['name'],
                'slug' => $format['slug'],
                'is_active' => $format['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 