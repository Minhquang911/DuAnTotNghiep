<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|max:20',
            'address' => 'nullable|max:255',
            'role' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Tạo người dùng thành công');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'phone' => 'nullable|max:20',
            'address' => 'nullable|max:255',
            'role' => 'required',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Cập nhật người dùng thành công');
    }
public function show($id)
{
    $user = User::findOrFail($id);
    return view('admin.users.show', compact('user'));
}

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('users.index')->with('success', 'Xóa người dùng thành công');
    }

    public function toggleBlock($id)
{
    $user = User::findOrFail($id);
    $user->is_blocked = !$user->is_blocked;
    $user->save();

    return redirect()->back()->with('success', 'Trạng thái tài khoản đã được cập nhật.');
}

public function resetPassword($id)
{
    $user = User::findOrFail($id);
    $user->password = Hash::make('12345678');
    $user->save();

    return redirect()->back()->with('success', 'Đã reset mật khẩu về 12345678.');
}

}
