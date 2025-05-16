<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Book;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(PromotionSeeder::class);
        
        // Tạo dữ liệu mẫu cho categories
        $categories = [
            ['name' => 'Tiểu thuyết'],
            ['name' => 'Kinh tế'],
            ['name' => 'Khoa học'],
            ['name' => 'Tâm lý - Kỹ năng sống'],
            ['name' => 'Văn học']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Tạo dữ liệu mẫu cho books
        $books = [
            [
                'title' => 'Đắc Nhân Tâm',
                'description' => 'Cuốn sách nổi tiếng về nghệ thuật đối nhân xử thế',
                'author' => 'Dale Carnegie',
                'publisher' => 'NXB Tổng hợp TP.HCM',
                'category_id' => 4,
                'price' => 89000,
                'stock' => 100,
                'status' => 'còn hàng',
                'cover_image' => 'dac-nhan-tam.jpg'
            ],
            [
                'title' => 'Nhà Giả Kim',
                'description' => 'Câu chuyện về hành trình tìm kiếm kho báu và ý nghĩa cuộc sống',
                'author' => 'Paulo Coelho',
                'publisher' => 'NXB Văn học',
                'category_id' => 1,
                'price' => 75000,
                'stock' => 50,
                'status' => 'còn hàng',
                'cover_image' => 'nha-gia-kim.jpg'
            ],
            [
                'title' => 'Tư Duy Nhanh và Chậm',
                'description' => 'Phân tích về hai hệ thống tư duy của con người',
                'author' => 'Daniel Kahneman',
                'publisher' => 'NXB Thế giới',
                'category_id' => 3,
                'price' => 195000,
                'stock' => 30,
                'status' => 'còn hàng',
                'cover_image' => 'tu-duy-nhanh-va-cham.jpg'
            ],
            [
                'title' => 'Khéo Ăn Nói Sẽ Có Được Thiên Hạ',
                'description' => 'Nghệ thuật giao tiếp và thuyết phục',
                'author' => 'Trác Nhã',
                'publisher' => 'NXB Văn hóa - Văn nghệ',
                'category_id' => 4,
                'price' => 68000,
                'stock' => 75,
                'status' => 'còn hàng',
                'cover_image' => 'kheo-an-noi.jpg'
            ],
            [
                'title' => 'Tôi Thấy Hoa Vàng Trên Cỏ Xanh',
                'description' => 'Tiểu thuyết về tuổi thơ và tình cảm gia đình',
                'author' => 'Nguyễn Nhật Ánh',
                'publisher' => 'NXB Trẻ',
                'category_id' => 5,
                'price' => 125000,
                'stock' => 60,
                'status' => 'còn hàng',
                'cover_image' => 'toi-thay-hoa-vang.jpg'
            ]
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
