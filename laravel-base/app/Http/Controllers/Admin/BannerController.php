<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $query = Banner::query();

        // Tìm kiếm theo tiêu đề
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('title', 'like', "%{$search}%");
        }

        // Sắp xếp theo vị trí và ngày tạo mới nhất
        $query->orderBy('created_at', 'desc');

        // Phân trang với 10 items mỗi trang
        $banners = $query->paginate(10)->withQueryString();

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'link' => 'nullable|url|max:255',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=800,min_height=200,max_width=1920,max_height=600',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề banner',
            'title.max' => 'Tiêu đề banner không được vượt quá 255 ký tự',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự',
            'link.url' => 'Link không đúng định dạng URL',
            'link.max' => 'Link không được vượt quá 255 ký tự',
            'image.required' => 'Vui lòng chọn hình ảnh banner',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
            'image.dimensions' => 'Kích thước hình ảnh không phù hợp. Yêu cầu: chiều rộng từ 800px đến 1920px, chiều cao từ 200px đến 600px',
            'is_active.required' => 'Vui lòng chọn trạng thái',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Xử lý upload hình ảnh
            $imagePath = $request->file('image')->store('banners', 'public');

            // Tạo banner mới
            $banner = Banner::create([
                'title' => $request->title,
                'description' => $request->description,
                'link' => $request->link,
                'image' => $imagePath,
                'is_active' => $request->boolean('is_active'),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.banners.index')
                ->with('success', 'Thêm banner mới thành công!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Xóa file hình ảnh nếu đã upload nhưng lưu database thất bại
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi thêm banner: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'link' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề banner',
            'title.max' => 'Tiêu đề banner không được vượt quá 255 ký tự',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự',
            'link.url' => 'Link không đúng định dạng URL',
            'link.max' => 'Link không được vượt quá 255 ký tự',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
            'is_active.required' => 'Vui lòng chọn trạng thái',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'link' => $request->link,
                'is_active' => $request->boolean('is_active'),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ];

            // Xử lý upload hình ảnh mới nếu có
            if ($request->hasFile('image')) {
                // Xóa hình ảnh cũ
                if ($banner->image) {
                    Storage::disk('public')->delete($banner->image);
                }
                // Upload hình ảnh mới
                $data['image'] = $request->file('image')->store('banners', 'public');
            }

            $banner->update($data);

            DB::commit();

            return redirect()
                ->route('admin.banners.index')
                ->with('success', 'Cập nhật banner thành công!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi cập nhật banner: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Banner $banner)
    {
        try {
            DB::beginTransaction();

            // Xóa hình ảnh từ storage
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }

            // Xóa banner từ database
            $banner->delete();

            DB::commit();

            return redirect()
                ->route('admin.banners.index')
                ->with('success', 'Xóa banner thành công!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.banners.index')
                ->with('error', 'Có lỗi xảy ra khi xóa banner: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Banner $banner)
    {
        try {
            DB::beginTransaction();

            $banner->update([
                'is_active' => !$banner->is_active
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái banner thành công!'
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