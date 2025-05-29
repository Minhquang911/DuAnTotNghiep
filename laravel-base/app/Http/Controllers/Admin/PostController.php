<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user']);

        // Tìm kiếm theo nội dung
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        }

        // Lọc theo trạng thái duyệt
        if ($request->has('is_published')) {
            $isPublished = $request->get('is_published');
            if ($isPublished === '1') {
                $query->where('is_published', true);
            } elseif ($isPublished === '0') {
                $query->where('is_published', false);
            }
        }

        // Sắp xếp
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'post':
                $query->orderBy('title');
                break;
            case 'published':
                $query->orderBy('is_published', 'desc');
                break;
            default:
                $query->latest();
        }

        // Phân trang
        $posts = $query->paginate(10)->withQueryString();

        // Lấy danh sách người dùng cho bộ lọc
        $users = User::select('id', 'name')->get();

        // Thống kê
        $stats = [
            'total' => Post::count(),
            'published' => Post::where('is_published', true)->count(),
            'unpublished' => Post::where('is_published', false)->count(),
        ];

        return view('admin.posts.index', compact(
            'posts',
            'users',
            'stats'
        ));
    }

    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'required|boolean',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề bài viết',
            'title.max' => 'Tiêu đề bài viết không được vượt quá 255 ký tự',
            'content.required' => 'Vui lòng nhập nội dung bài viết',
            'content.max' => 'Nội dung bài viết không được vượt quá 1000 ký tự',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
            'is_published.required' => 'Vui lòng chọn trạng thái'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $user = User::find(auth()->user()->id);

            $imagePath = null;

            // Xử lý upload hình ảnh
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('posts', 'public');
            }

            // Tạo bài viết mới
            $post = Post::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'image' => $imagePath,
                'is_published' => $request->boolean('is_published'),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.posts.index')
                ->with('success', 'Thêm bài viết mới thành công!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Xóa file hình ảnh nếu đã upload nhưng lưu database thất bại
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi thêm bài viết: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'required|boolean',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề bài viết',
            'title.max' => 'Tiêu đề bài viết không được vượt quá 255 ký tự',
            'content.required' => 'Vui lòng nhập nội dung bài viết',
            'content.max' => 'Nội dung bài viết không được vượt quá 1000 ký tự',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
            'is_published.required' => 'Vui lòng chọn trạng thái'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $user = User::find(auth()->user()->id);

            $imagePath = null;

            // Xử lý upload hình ảnh
            if ($request->hasFile('image')) {
                // Xóa hình ảnh cũ
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }
                // Upload hình ảnh mới
                $imagePath = $request->file('image')->store('posts', 'public');
            }

            // Cập nhật bài viết
            $post->update([
                'user_id' => $user->id,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'image' => $imagePath,
                'is_published' => $request->boolean('is_published'),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.posts.index')
                ->with('success', 'Thêm bài viết mới thành công!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Xóa file hình ảnh nếu đã upload nhưng lưu database thất bại
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi thêm bài viết: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Post $post)
    {
        try {
            DB::beginTransaction();

            // Xóa hình ảnh từ storage
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            // Xóa bài viết từ database
            $post->delete();

            DB::commit();

            return redirect()
                ->route('admin.posts.index')
                ->with('success', 'Xóa bài viết thành công!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.posts.index')
                ->with('error', 'Có lỗi xảy ra khi xóa bài viết: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Post $post)
    {
        try {
            DB::beginTransaction();

            $post->update([
                'is_published' => !$post->is_published
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái bài viết thành công!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }
}