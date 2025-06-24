<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('client.profiles.profile', compact('user'));
    }

    public function password()
    {
        return view('client.profiles.password');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $messages = [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Địa chỉ email này đã được sử dụng.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'birthday.date' => 'Ngày sinh không hợp lệ.',
            'avatar.image' => 'File phải là hình ảnh.',
            'avatar.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
        ];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:male,female'],
            'birthday' => ['nullable', 'date'],
            'avatar' => ['nullable', 'image', 'max:2048'], // max 2MB
        ], $messages);

        // Xử lý upload avatar
        if ($request->hasFile('avatar')) {
            try {
                // Xóa avatar cũ nếu có
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $path = $request->file('avatar')->store('avatars', 'public');
                $validated['avatar'] = $path;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Không thể tải lên ảnh đại diện: ' . $e->getMessage());
            }
        }

        try {
            $user = User::find($user->id);
            if ($user) {
                $user->fill($validated);
                $user->save();
            }

            return redirect()->route('user.profile')->with('success', 'Thông tin cá nhân đã được cập nhật thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật thông tin: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {

        $messages = [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'current_password.current_password' => 'Mật khẩu hiện tại không chính xác.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
            'password.regex' => 'Mật khẩu phải chứa ít nhất một chữ hoa, một chữ thường, một số và một ký tự đặc biệt.',
        ];

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
        ], $messages);

        try {
            $user = Auth::user();
            $user = User::find($user->id);
            if ($user) {
                $user->fill([
                    'password' => Hash::make($validated['password'])
                ]);
                $user->save();
            }

            return redirect()->route('user.profile.password')->with('success', 'Mật khẩu đã được thay đổi thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thay đổi mật khẩu: ' . $e->getMessage());
        }
    }
}
