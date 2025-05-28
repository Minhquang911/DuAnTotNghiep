<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ProductVariant;
use App\Models\Rating;
use Carbon\Carbon;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách user và product variant để tạo đánh giá
        $users = User::take(10)->get();
        $productVariants = ProductVariant::take(5)->get();

        // Tạo các đánh giá mẫu
        foreach ($productVariants as $variant) {
            // Tạo 2-3 đánh giá cho mỗi biến thể sản phẩm
            $numRatings = min(rand(2, 3), $users->count());
            $selectedUsers = $users->random($numRatings);
            
            foreach ($selectedUsers as $user) {
                $rating = rand(3, 5); // Điểm đánh giá từ 3-5
                $isApproved = rand(0, 1);
                $hasReply = rand(0, 1) && $isApproved;

                $ratingData = [
                    'user_id' => $user->id,
                    'product_variant_id' => $variant->id,
                    'product_id' => $variant->product_id,
                    'rating' => $rating,
                    'comment' => $this->getRandomComment($rating),
                    'is_approved' => $isApproved,
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                ];

                // Thêm phản hồi nếu có
                if ($hasReply) {
                    $ratingData['reply'] = $this->getRandomReply($rating);
                    $ratingData['reply_at'] = Carbon::parse($ratingData['created_at'])->addDays(rand(1, 3));
                }

                Rating::create($ratingData);
            }
        }
    }

    private function getRandomComment(int $rating): string
    {
        $comments = [
            5 => [
                'Sách rất hay, nội dung hấp dẫn và chất lượng giấy tốt!',
                'Đóng gói cẩn thận, sách mới nguyên vẹn. Rất hài lòng!',
                'Giao hàng nhanh, sách đẹp và giá cả hợp lý.',
            ],
            4 => [
                'Sách hay nhưng giá hơi cao một chút.',
                'Nội dung tốt, nhưng giấy có thể cải thiện thêm.',
                'Đáng để mua, nhưng giao hàng hơi chậm.',
            ],
            3 => [
                'Sách tạm được, nhưng có một số lỗi in ấn nhỏ.',
                'Chất lượng sách bình thường, giá cả phù hợp.',
                'Nội dung khá hay nhưng giấy hơi mỏng.',
            ],
        ];

        return $comments[$rating][array_rand($comments[$rating])];
    }

    private function getRandomReply(int $rating): string
    {
        $replies = [
            5 => [
                'Cảm ơn bạn đã đánh giá tích cực! Chúng tôi rất vui khi bạn hài lòng với sản phẩm.',
                'Rất vui khi nhận được phản hồi tốt từ bạn. Mong bạn tiếp tục ủng hộ shop!',
            ],
            4 => [
                'Cảm ơn phản hồi của bạn. Chúng tôi sẽ cố gắng cải thiện dịch vụ tốt hơn nữa.',
                'Cảm ơn đánh giá của bạn. Shop sẽ nỗ lực để mang đến trải nghiệm tốt hơn.',
            ],
            3 => [
                'Cảm ơn phản hồi của bạn. Chúng tôi sẽ khắc phục các vấn đề bạn đề cập.',
                'Xin lỗi vì những trải nghiệm chưa tốt. Shop sẽ cải thiện để phục vụ bạn tốt hơn.',
            ],
        ];

        return $replies[$rating][array_rand($replies[$rating])];
    }
}