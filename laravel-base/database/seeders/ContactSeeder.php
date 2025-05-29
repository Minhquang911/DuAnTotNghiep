<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contact::insert([
            [
                'user_id' => 2,
                'email' => 'nguyenvana@gmail.com',
                'content' => 'Xin chào, tôi muốn hỏi về thời gian xử lý yêu cầu của tôi đã gửi tuần trước.',
                'is_read' => false,
            ],
            [
                'user_id' => 2,
                'email' => 'tranthibich@yahoo.com',
                'content' => 'Tôi gặp lỗi khi đăng nhập vào hệ thống, mong được hỗ trợ sớm.',
                'is_read' => false,
            ],
            [
                'user_id' => 2,
                'email' => 'lequocminh@gmail.com',
                'content' => 'Cảm ơn bộ phận hỗ trợ đã giúp tôi thay đổi thông tin cá nhân thành công!',
                'is_read' => true,
            ],
            [
                'user_id' => 2,
                'email' => 'phamhoanglinh@hotmail.com',
                'content' => 'Tôi muốn góp ý về giao diện mới, hy vọng có thêm chế độ tối để dễ sử dụng buổi tối.',
                'is_read' => false,
            ],
            [
                'user_id' => 2,
                'email' => 'vuquanghuy@gmail.com',
                'content' => 'Tài khoản của tôi bị khóa mà không rõ lý do. Nhờ kiểm tra giúp tôi.',
                'is_read' => false,
            ]
        ]);
    }
}
