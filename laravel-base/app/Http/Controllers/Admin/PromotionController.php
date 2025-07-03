<?php

namespace App\Http\Controllers\Admin;

use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhere('code', 'like', "%$search%");
            });
        }

        $promotions = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        // Validate các trường bắt buộc và tùy chọn
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'max_discount_value' => 'nullable|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean'
        ], [
            'code.required' => 'Vui lòng nhập mã khuyến mãi.',
            'code.string' => 'Mã khuyến mãi phải là chuỗi.',
            'code.max' => 'Mã khuyến mãi không được vượt quá 50 ký tự.',
            'code.unique' => 'Mã khuyến mãi đã tồn tại.',
            'title.required' => 'Vui lòng nhập tiêu đề mã khuyến mãi.',
            'discount_type.required' => 'Vui lòng chọn loại giảm giá.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm.',
            'discount_value.numeric' => 'Giá trị giảm phải là số.',
            'discount_value.min' => 'Giá trị giảm phải lớn hơn hoặc bằng 0.',
            'max_discount_value.numeric' => 'Giá trị tối đa phải là số.',
            'max_discount_value.min' => 'Giá trị tối đa phải lớn hơn hoặc bằng 0.',
            'min_order_value.numeric' => 'Đơn hàng tối thiểu phải là số.',
            'min_order_value.min' => 'Đơn hàng tối thiểu phải lớn hơn hoặc bằng 0.',
            'start_date.required' => 'Vui lòng nhập ngày bắt đầu.',
            'end_date.required' => 'Vui lòng nhập ngày kết thúc.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'image.image' => 'File phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng jpeg, png hoặc gif.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'usage_limit.integer' => 'Giới hạn sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Giới hạn sử dụng ít nhất là 1.',
        ]);

        // Xử lý upload hình ảnh (nếu có)
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('promotions', 'public');
            $validated['image'] = $imagePath;
        }

        // Chuyển đổi giá trị is_active (nếu không có thì mặc định là 1 (true))
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // Lưu dữ liệu vào DB (sử dụng transaction để đảm bảo tính toàn vẹn)
        DB::beginTransaction();
        try {
            $promotion = Promotion::create($validated);
            DB::commit();
            return redirect()->route('admin.promotions.index')->with('success', 'Thêm mã khuyến mãi thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Có lỗi xảy ra khi thêm mã khuyến mãi: ' . $e->getMessage()]);
        }
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code,' . $promotion->id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'max_discount_value' => 'nullable|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'is_active' => 'boolean'
        ], [
            'code.required' => 'Vui lòng nhập mã khuyến mãi.',
            'code.string' => 'Mã khuyến mãi phải là chuỗi.',
            'code.max' => 'Mã khuyến mãi không được vượt quá 50 ký tự.',
            'code.unique' => 'Mã khuyến mãi đã tồn tại.',
            'title.required' => 'Vui lòng nhập tiêu đề mã khuyến mãi.',
            'discount_type.required' => 'Vui lòng chọn loại giảm giá.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm.',
            'discount_value.numeric' => 'Giá trị giảm phải là số.',
            'discount_value.min' => 'Giá trị giảm phải lớn hơn hoặc bằng 0.',
            'max_discount_value.numeric' => 'Giá trị tối đa phải là số.',
            'max_discount_value.min' => 'Giá trị tối đa phải lớn hơn hoặc bằng 0.',
            'min_order_value.numeric' => 'Đơn hàng tối thiểu phải là số.',
            'min_order_value.min' => 'Đơn hàng tối thiểu phải lớn hơn hoặc bằng 0.',
            'start_date.required' => 'Vui lòng nhập ngày bắt đầu.',
            'end_date.required' => 'Vui lòng nhập ngày kết thúc.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'usage_limit.integer' => 'Giới hạn sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Giới hạn sử dụng ít nhất là 1.',
            'image.image' => 'File phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng jpeg, png hoặc gif.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.'
        ]);

        // Nếu discount_type là 'fixed' thì set max_discount_value và min_order_value về null
        if ($validated['discount_type'] === 'fixed') {
            $validated['max_discount_value'] = null;
            $validated['min_order_value'] = null;
        }

        // Bổ sung kiểm tra usage_limit không nhỏ hơn used_count
        if (
            array_key_exists('usage_limit', $validated) &&
            !is_null($validated['usage_limit']) &&
            $validated['usage_limit'] < $promotion->used_count
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'usage_limit' => 'Giới hạn sử dụng không được nhỏ hơn số lượt đã dùng hiện tại (' . $promotion->used_count . ').'
                ]);
        }

        // Xử lý upload hình ảnh mới (nếu có)
        if ($request->hasFile('image')) {
            // Xóa hình ảnh cũ nếu tồn tại
            if ($promotion->image && Storage::disk('public')->exists($promotion->image)) {
                Storage::disk('public')->delete($promotion->image);
            }
            // Lưu ảnh mới
            $imagePath = $request->file('image')->store('promotions', 'public');
            $validated['image'] = $imagePath;
        }        

        // Chuyển đổi giá trị is_active
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // Cập nhật dữ liệu vào DB (sử dụng transaction để đảm bảo tính toàn vẹn)
        DB::beginTransaction();
        try {
            $promotion->update($validated);
            DB::commit();
            return redirect()->route('admin.promotions.index')
                ->with('success', 'Cập nhật mã khuyến mãi thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật mã khuyến mãi: ' . $e->getMessage()]);
        }
    }

    public function toggleStatus(Promotion $promotion)
    {
        try {
            DB::beginTransaction();

            $promotion->update([
                'is_active' => !$promotion->is_active
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái khuyến mãi thành công!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Promotion $promotion)
    {
        try {
            DB::beginTransaction();

            // Xóa hình ảnh nếu tồn tại
            if ($promotion->image) {
                Storage::delete('public/' . $promotion->image);
            }

            // Xóa khuyến mãi
            $promotion->delete();

            DB::commit();

            return redirect()->route('admin.promotions.index')
                ->with('success', 'Xóa mã khuyến mãi thành công!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.promotions.index')
                ->with('error', 'Có lỗi xảy ra khi xóa mã khuyến mãi: ' . $e->getMessage());
        }
    }
}