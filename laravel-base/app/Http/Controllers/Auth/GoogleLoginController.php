<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleLoginController extends Controller
{
    // Điều hướng sang Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Xử lý callback từ Google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            // Kiểm tra email đã có trong hệ thống chưa
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Nếu chưa có thì tạo mới user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt($googleUser->getEmail()), // Password chính là email đăng ký
                    'email_verified_at' => now(),
                    'role_id' => 2,
                ]);
            }

            // Đăng nhập
            Auth::login($user, true);

            // Chuyển về trang trướcc đó hoặc trang chủ
            return redirect()->back() ?: redirect()->route('home');
        } catch (\Exception $e) {
            // Lỗi: có thể show flash hoặc chuyển về trang login với thông báo lỗi
            return redirect()->route('login')->with('error', 'Không thể đăng nhập bằng Google!');
        }
    }
}