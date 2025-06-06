<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            PublisherSeeder::class,
            BannerSeeder::class,
            PromotionSeeder::class,
            FormatSeeder::class,
            LanguageSeeder::class,
            ProductSeeder::class,
            ProductVariantSeeder::class,
            CommentSeeder::class,
            ContactSeeder::class,
            PostSeeder::class,
            AlbumSeeder::class,
            OrderSeeder::class,
            RatingSeeder::class,
        ]);
    }
}