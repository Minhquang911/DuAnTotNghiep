<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::insert([
            [
                'user_id' => 1,
                'title' => 'Khai mạc Ngày hội Công nghệ thông tin 2025',
                'slug' => 'khai-mac-ngay-hoi-cong-nghe-thong-tin-2025',
                'content' => 'Sáng ngày 20/5, ngày hội Công nghệ thông tin 2025 đã diễn ra với sự tham gia của hơn 500 sinh viên và nhiều doanh nghiệp lớn trong ngành.',
                'image' => 'uploads/posts/it-day-2025.jpg',
                'is_published' => true,
                'created_at' => Carbon::parse('2025-05-20 08:00:00'),
                'updated_at' => Carbon::parse('2025-05-20 08:00:00'),
            ],
            [
                'user_id' => 2,
                'title' => '7 xu hướng phát triển phần mềm năm 2025',
                'slug' => '7-xu-huong-phat-trien-phan-mem-nam-2025',
                'content' => 'Năm 2025, AI, điện toán đám mây và phát triển bền vững là các xu hướng nổi bật mà lập trình viên cần quan tâm.',
                'image' => 'uploads/posts/software-trends-2025.png',
                'is_published' => true,
                'created_at' => Carbon::parse('2025-05-19 10:15:00'),
                'updated_at' => Carbon::parse('2025-05-19 10:15:00'),
            ],
            [
                'user_id' => 3,
                'title' => 'Kinh nghiệm phỏng vấn vị trí Backend Developer',
                'slug' => 'kinh-nghiem-phong-van-vi-tri-backend-developer',
                'content' => 'Chia sẻ kinh nghiệm chuẩn bị, các câu hỏi thường gặp và lưu ý khi phỏng vấn vị trí Backend Developer cho sinh viên mới ra trường.',
                'image' => 'uploads/posts/backend-interview-tips.jpg',
                'is_published' => false,
                'created_at' => Carbon::parse('2025-05-18 14:30:00'),
                'updated_at' => Carbon::parse('2025-05-18 14:30:00'),
            ],
            [
                'user_id' => 1,
                'title' => 'Thư mời tham dự Workshop: Lập trình Web hiện đại',
                'slug' => 'thu-moi-tham-du-workshop-lap-trinh-web-hien-dai',
                'content' => 'Trung tâm tổ chức workshop về lập trình web hiện đại dành cho tất cả sinh viên công nghệ. Đăng ký trước ngày 25/5 để giữ chỗ.',
                'image' => 'uploads/posts/workshop-web-modern.jpg',
                'is_published' => true,
                'created_at' => Carbon::parse('2025-05-17 09:00:00'),
                'updated_at' => Carbon::parse('2025-05-17 09:00:00'),
            ],
            [
                'user_id' => 2,
                'title' => 'Cách tối ưu hiệu suất website với Laravel',
                'slug' => 'cach-toi-uu-hieu-suat-website-voi-laravel',
                'content' => 'Bài viết hướng dẫn chi tiết các kỹ thuật tối ưu hiệu suất cho website sử dụng framework Laravel: cache, queue, tối ưu truy vấn SQL...',
                'image' => null, // Bài này không có ảnh
                'is_published' => false,
                'created_at' => Carbon::parse('2025-05-16 16:10:00'),
                'updated_at' => Carbon::parse('2025-05-16 16:10:00'),
            ],
        ]);
    }
}