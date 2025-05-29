<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::with(['user']);

        // Tìm kiếm theo nội dung
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('content', 'like', "%{$search}%");
        }

        // Lọc theo trạng thái duyệt
        if ($request->has('is_read')) {
            $isRead = $request->get('is_read');
            if ($isRead === '1') {
                $query->where('is_read', true);
            } elseif ($isRead === '0') {
                $query->where('is_read', false);
            }
        }

        // Sắp xếp
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'user':
                $query->orderBy('user_id');
                break;
            case 'read':
                $query->orderBy('is_read', 'desc');
                break;
            default:
                $query->latest();
        }

        // Phân trang
        $contacts = $query->paginate(10)->withQueryString();

        // Lấy danh sách người dùng cho bộ lọc
        $users = User::select('id', 'name')->get();

        // Thống kê
        $stats = [
            'total' => Contact::count(),
            'read' => Contact::where('is_read', true)->count(),
            'unread' => Contact::where('is_read', false)->count(),
        ];

        return view('admin.contacts.index', compact(
            'contacts',
            'users',
            'stats'
        ));
    }

    public function approve(Contact $contact)
    {
        try {
            DB::beginTransaction();

            $contact->update(['is_read' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã đọc liên hệ thành công.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đọc liên hệ.'
            ], 500);
        }
    }

    public function reject(Contact $contact)
    {
        try {
            DB::beginTransaction();

            $contact->update(['is_read' => false]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã chưa đọc liên hệ thành công.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi chưa đọc liên hệ.'
            ], 500);
        }
    }

    public function destroy(Contact $contact)
    {
        try {
            DB::beginTransaction();

            // Sau đó xóa liên hệ
            $contact->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa liên hệ thành công.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa liên hệ.'
            ], 500);
        }
    }


}
