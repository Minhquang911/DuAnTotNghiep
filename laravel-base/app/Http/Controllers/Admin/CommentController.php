<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'product', 'parent']);

        // Tìm kiếm theo nội dung
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('content', 'like', "%{$search}%");
        }

        // Lọc theo sản phẩm
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->get('product_id'));
        }

        // Lọc theo người dùng
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        // Lọc theo trạng thái duyệt
        if ($request->has('is_approved')) {
            $isApproved = $request->get('is_approved');
            if ($isApproved === '1') {
                $query->where('is_approved', true);
            } elseif ($isApproved === '0') {
                $query->where('is_approved', false);
            }
        }

        // Lọc theo loại bình luận (cha/con)
        if ($request->filled('type')) {
            switch ($request->get('type')) {
                case 'parent':
                    $query->whereNull('parent_id');
                    break;
                case 'reply':
                    $query->whereNotNull('parent_id');
                    break;
            }
        }

        // Sắp xếp
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'product':
                $query->orderBy('product_id');
                break;
            case 'user':
                $query->orderBy('user_id');
                break;
            case 'approved':
                $query->orderBy('is_approved', 'desc');
                break;
            default:
                $query->latest();
        }

        // Phân trang
        $comments = $query->paginate(10)->withQueryString();

        // Lấy danh sách sản phẩm và người dùng cho bộ lọc
        $products = Product::select('id', 'title')->get();
        $users = User::select('id', 'name')->get();

        // Thống kê
        $stats = [
            'total' => Comment::count(),
            'approved' => Comment::where('is_approved', true)->count(),
            'pending' => Comment::where('is_approved', false)->count(),
            'parents' => Comment::whereNull('parent_id')->count(),
            'replies' => Comment::whereNotNull('parent_id')->count(),
        ];

        return view('admin.comments.index', compact(
            'comments',
            'products',
            'users',
            'stats'
        ));
    }

    public function approve(Comment $comment)
    {
        try {
            DB::beginTransaction();

            $comment->update(['is_approved' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cho phép hiển thị bình luận thành công.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi duyệt bình luận.'
            ], 500);
        }
    }

    public function reject(Comment $comment)
    {
        try {
            DB::beginTransaction();

            $comment->update(['is_approved' => false]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã từ chối bình luận thành công.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi từ chối bình luận.'
            ], 500);
        }
    }

    public function destroy(Comment $comment)
    {
        try {
            DB::beginTransaction();

            // Xóa các bình luận con trước
            $comment->replies()->delete();
            // Sau đó xóa bình luận cha
            $comment->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa bình luận thành công.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa bình luận.'
            ], 500);
        }
    }

    public function trashed(Request $request)
    {
        $query = Comment::onlyTrashed()->with('user', 'replies');

        // Tìm kiếm theo nội dung
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('content', 'like', "%{$search}%");
        }

        // Lọc theo sản phẩm
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->get('product_id'));
        }

        // Lọc theo người dùng
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        // Lọc theo trạng thái duyệt
        if ($request->has('is_approved')) {
            $isApproved = $request->get('is_approved');
            if ($isApproved === '1') {
                $query->where('is_approved', true);
            } elseif ($isApproved === '0') {
                $query->where('is_approved', false);
            }
        }

        // Lọc theo loại bình luận (cha/con)
        if ($request->filled('type')) {
            switch ($request->get('type')) {
                case 'parent':
                    $query->whereNull('parent_id');
                    break;
                case 'reply':
                    $query->whereNotNull('parent_id');
                    break;
            }
        }

        // Sắp xếp
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'product':
                $query->orderBy('product_id');
                break;
            case 'user':
                $query->orderBy('user_id');
                break;
            case 'approved':
                $query->orderBy('is_approved', 'desc');
                break;
            default:
                $query->latest();
        }

        $trashedComments = $query->paginate(10)->withQueryString();

        // Lấy danh sách sản phẩm và người dùng cho bộ lọc
        $products = Product::select('id', 'title')->get();
        $users = User::select('id', 'name')->get();

        return view('admin.comments.trashed', compact('trashedComments', 'products', 'users'));
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();
            $comment = Comment::onlyTrashed()->findOrFail($id);
            // Nếu muốn, khôi phục luôn các replies bị xóa
            $comment->replies()->onlyTrashed()->restore();
            $comment->restore();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Khôi phục bình luận thành công.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi khôi phục bình luận.'
            ], 500);
        }
    }
}
