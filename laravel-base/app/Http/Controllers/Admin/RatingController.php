<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Rating;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RatingController extends Controller
{
    public function index(Request $request)
    {
        $query = Rating::with(['user', 'productVariant', 'product']);

        // Tìm kiếm theo tên user
        if ($request->filled('user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%');
            });
        }

        // Tìm kiếm theo tên sản phẩm
        if ($request->filled('product')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('id', $request->product);
            });
        }

        // Tìm kiếm theo nội dung đánh giá
        if ($request->filled('comment')) {
            $query->where('comment', 'like', '%' . $request->comment . '%');
        }

        // Bộ lọc trạng thái duyệt
        if ($request->has('is_approved')) {
            $isApproved = $request->get('is_approved');
            if ($isApproved === '1') {
                $query->where('is_approved', true);
            } elseif ($isApproved === '0') {
                $query->where('is_approved', false);
            }
        }

        // Bộ lọc số sao
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Sắp xếp
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'product':
                $query->orderBy('product_variant_id');
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
        $ratings = $query->paginate(10)->withQueryString();

        // Lấy danh sách biến thể sản phẩm và người dùng cho bộ lọc
        $productVariants = ProductVariant::with('product')->get();
        $users = User::select('id', 'name')->get();
        $products = Product::all();
        
        // Thống kê
        $stats = [
            'total' => Rating::count(),
            'approved' => Rating::where('is_approved', true)->count(),
            'pending' => Rating::where('is_approved', false)->count(),
            'replied' => Rating::whereNotNull('reply')->count(),
        ];

        return view('admin.ratings.index', compact('ratings', 'productVariants', 'users', 'stats', 'products'));
    }

    public function approve(Rating $rating)
    {
        try {
            DB::beginTransaction();
            $rating->update(['is_approved' => true]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Cho phép hiển thị đánh giá thành công.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi duyệt đánh giá.'
            ], 500);
        }
    }

    public function reject(Rating $rating)
    {
        try {
            DB::beginTransaction();
            $rating->update(['is_approved' => false]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Đã từ chối đánh giá thành công.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi từ chối đánh giá.'
            ], 500);
        }
    }

    public function destroy(Rating $rating)
    {
        try {
            DB::beginTransaction();
            $rating->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa đánh giá thành công.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa đánh giá.'
            ], 500);
        }
    }

    public function reply(Request $request, Rating $rating)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);
        if ($rating->reply) {
            return response()->json([
                'success' => false,
                'message' => 'Đánh giá này đã được trả lời!',
            ], 400);
        }
        $rating->reply = $request->reply;
        $rating->reply_at = now();
        $rating->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã trả lời đánh giá thành công!',
        ]);
    }

    // Hiển thị danh sách đánh giá đã xóa (thùng rác)
    public function trashed(Request $request)
    {
        $query = Rating::onlyTrashed()->with(['user', 'productVariant', 'product']);

        // Tìm kiếm theo tên user
        if ($request->filled('user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%');
            });
        }

        // Tìm kiếm theo tên sản phẩm
        if ($request->filled('product')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('id', $request->product);
            });
        }

        // Tìm kiếm theo nội dung đánh giá
        if ($request->filled('comment')) {
            $query->where('comment', 'like', '%' . $request->comment . '%');
        }

        // Bộ lọc trạng thái duyệt
        if ($request->has('is_approved')) {
            $isApproved = $request->get('is_approved');
            if ($isApproved === '1') {
                $query->where('is_approved', true);
            } elseif ($isApproved === '0') {
                $query->where('is_approved', false);
            }
        }

        // Bộ lọc số sao
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Sắp xếp
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'product':
                $query->orderBy('product_variant_id');
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

        // Phân trang + giữ tham số truy vấn
        $trashedRatings = $query->paginate(10)->withQueryString();

        $products = Product::all();
        return view('admin.ratings.trashed', compact('trashedRatings', 'products'));
    }

    // Khôi phục đánh giá
    public function restore($id)
    {
        try {
            DB::beginTransaction();
            $rating = Rating::onlyTrashed()->findOrFail($id);
            $rating->restore();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Khôi phục đánh giá thành công.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy hoặc lỗi khi khôi phục đánh giá.'
            ], 500);
        }
    }
}