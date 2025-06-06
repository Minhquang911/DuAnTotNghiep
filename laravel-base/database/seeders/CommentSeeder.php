<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách user và product để tạo bình luận
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->info('Không có dữ liệu users hoặc products để tạo bình luận!');
            return;
        }

        // Mảng các nội dung bình luận mẫu
        $comments = [
            'Sách rất hay, nội dung hấp dẫn và bổ ích. Đóng gói cẩn thận, giao hàng nhanh chóng.',
            'Chất lượng sách tốt, giấy đẹp, mực in rõ ràng. Đáng để mua và đọc.',
            'Nội dung sách rất thú vị, phù hợp với lứa tuổi. Con tôi rất thích đọc.',
            'Sách mới, đẹp, giá cả hợp lý. Giao hàng đúng hẹn, nhân viên thân thiện.',
            'Sách hay nhưng giá hơi cao so với mặt bằng chung. Nội dung thì không chê vào đâu được.',
            'Đóng gói cẩn thận, sách không bị quăn góc. Nội dung sách rất bổ ích.',
            'Sách đẹp, chất lượng tốt. Tuy nhiên giao hàng hơi chậm một chút.',
            'Nội dung sách rất hay, phù hợp với mục đích tìm hiểu của tôi.',
            'Sách mới tinh, không bị lỗi in ấn. Đáng để sưu tầm.',
            'Chất lượng sách tốt, giá cả phải chăng. Sẽ ủng hộ shop dài dài.',
        ];

        // Tạo bình luận
        foreach ($products as $product) {
            // Mỗi sản phẩm tạo 3-5 bình luận
            $numberOfComments = rand(3, 5);
            
            for ($i = 0; $i < $numberOfComments; $i++) {
                Comment::create([
                    'user_id' => $users->random()->id,
                    'product_id' => $product->id,
                    'content' => $comments[array_rand($comments)],
                    'is_approved' => rand(0, 1), // 50% bình luận được duyệt
                ]);
            }
        }

        $this->command->info('Đã tạo dữ liệu mẫu cho bảng comments thành công!');
    }
}