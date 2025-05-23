@@ -0,0 +1,200 @@
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = User::where('role_id', '!=', 1); // Loại bỏ tài khoản admin

            // Tìm kiếm theo tên, email hoặc số điện thoại
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            // Lọc theo giới tính
            if ($request->filled('gender')) {
                $query->where('gender', $request->gender);
            }

            // Lọc theo trạng thái
            if ($request->filled('status')) {
                $query->where('is_active', $request->status);
            }

            $users = $query->latest()->paginate(10)->withQueryString();
            return view('admin.users.index', compact('users'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        try {
            // Validate dữ liệu
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'nullable|string|max:10',
                'gender' => 'nullable|in:male,female',
                'birthday' => 'nullable|date|before:today',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'required|boolean',
            ], [
                'name.required' => 'Vui lòng nhập họ và tên',
                'name.max' => 'Họ và tên không được vượt quá 255 ký tự',
                'email.required' => 'Vui lòng nhập email',
                'email.email' => 'Email không hợp lệ',
                'email.unique' => 'Email này đã được sử dụng',
                'password.required' => 'Vui lòng nhập mật khẩu',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                'password.confirmed' => 'Mật khẩu xác nhận không khớp',
                'phone.max' => 'Số điện thoại không được vượt quá 10 ký tự',
                'gender.in' => 'Vui lòng chọn giới tính hợp lệ',
                'birthday.date' => 'Ngày sinh không hợp lệ',
                'birthday.before' => 'Ngày sinh phải nhỏ hơn ngày hiện tại',
                'avatar.image' => 'File phải là hình ảnh',
                'avatar.mimes' => 'File phải có định dạng: jpeg, png, jpg, gif',
                'avatar.max' => 'Kích thước file không được vượt quá 2MB',
                'is_active.required' => 'Vui lòng chọn trạng thái',
                'is_active.boolean' => 'Trạng thái không hợp lệ',
            ]);

            // Xử lý upload ảnh
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $avatarPath = $avatar->store('avatars', 'public');
                $validated['avatar'] = $avatarPath;
            }

            // Mã hóa mật khẩu
            $validated['password'] = Hash::make($validated['password']);

            $validated['role_id'] = 2;

            // Tạo user mới
            User::create($validated);

            return redirect()->route('admin.users.index')
                ->with('success', 'Thêm người dùng thành công');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        try {
            // Validate dữ liệu
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'phone' => 'nullable|string|max:10',
                'gender' => 'nullable|in:male,female',
                'birthday' => 'nullable|date|before:today',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'required|boolean',
            ], [
                'name.required' => 'Vui lòng nhập họ và tên',
                'name.max' => 'Họ và tên không được vượt quá 255 ký tự',
                'email.required' => 'Vui lòng nhập email',
                'email.email' => 'Email không hợp lệ',
                'email.unique' => 'Email này đã được sử dụng',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                'password.confirmed' => 'Mật khẩu xác nhận không khớp',
                'phone.max' => 'Số điện thoại không được vượt quá 10 ký tự',
                'gender.in' => 'Vui lòng chọn giới tính hợp lệ',
                'birthday.date' => 'Ngày sinh không hợp lệ',
                'birthday.before' => 'Ngày sinh phải nhỏ hơn ngày hiện tại',
                'avatar.image' => 'File phải là hình ảnh',
                'avatar.mimes' => 'File phải có định dạng: jpeg, png, jpg, gif',
                'avatar.max' => 'Kích thước file không được vượt quá 2MB',
                'is_active.required' => 'Vui lòng chọn trạng thái',
                'is_active.boolean' => 'Trạng thái không hợp lệ',
            ]);

            // Xử lý upload ảnh
            if ($request->hasFile('avatar')) {
                // Xóa ảnh cũ nếu có
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $avatar = $request->file('avatar');
                $avatarPath = $avatar->store('avatars', 'public');
                $validated['avatar'] = $avatarPath;
            }

            // Mã hóa mật khẩu nếu có
            if ($request->filled('password')) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            // Cập nhật user
            $user->update($validated);

            return redirect()->route('admin.users.index')
                ->with('success', 'Cập nhật người dùng thành công');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Request $request, User $user)
    {
        try {
            $user->update([
                'is_active' => $request->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}