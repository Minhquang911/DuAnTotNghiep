<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Format;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FormatController extends Controller
{
    public function index(Request $request)
    {
        $query = Format::query();

        // Search by name or description
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
        }

        $formats = $query->paginate(10)->withQueryString();

        return view('admin.formats.index', compact('formats'));
    }

    public function create()
    {
        return view('admin.formats.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:formats',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Tên định dạng là bắt buộc.',
            'name.string' => 'Tên định dạng phải là chuỗi.',
            'name.max' => 'Tên định dạng không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên định dạng đã tồn tại.',
        ]);

        try {
            DB::beginTransaction();

            // Tạo slug từ tên
            $validated['slug'] = Str::slug($validated['name']);

            // Kiểm tra slug trùng lặp
            $count = 1;
            $originalSlug = $validated['slug'];
            while (Format::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $count;
                $count++;
            }

            // Set mặc định is_active là true nếu không được truyền vào
            $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

            Format::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.formats.index')
                ->with('success', 'Định dạng sách đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo định dạng sách. Vui lòng thử lại sau.');
        }
    }

    public function edit(Format $format)
    {
        return view('admin.formats.edit', compact('format'));
    }

    public function update(Request $request, Format $format)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:formats,name,' . $format->id,
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Tên định dạng là bắt buộc.',
            'name.string' => 'Tên định dạng phải là chuỗi.',
            'name.max' => 'Tên định dạng không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên định dạng đã tồn tại.',
        ]);

        try {
            DB::beginTransaction();

            // Tạo slug từ tên
            $validated['slug'] = Str::slug($validated['name']);

            // Kiểm tra slug trùng lặp
            $count = 1;
            $originalSlug = $validated['slug'];
            while (Format::where('slug', $validated['slug'])->where('id', '!=', $format->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $count;
                $count++;
            }

            // Xử lý trạng thái is_active
            $validated['is_active'] = $request->has('is_active') ? true : false;

            $format->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.formats.index')
                ->with('success', 'Định dạng sách đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật định dạng sách. Vui lòng thử lại sau.');
        }
    }

    public function destroy(Format $format)
    {
        try {
            DB::beginTransaction();

            // Kiểm tra xem có sách nào đang sử dụng định dạng này không
            $productCount = $format->productVariants()->count();
            if ($productCount > 0) {
                DB::rollBack();
                return back()->with('error', "Không thể xóa định dạng '{$format->name}' vì đang có {$productCount} sách đang sử dụng định dạng này.");
            }

            $format->delete();

            DB::commit();

            return redirect()
                ->route('admin.formats.index')
                ->with('success', 'Định dạng sách đã được xóa thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi xóa định dạng sách. Vui lòng thử lại sau.');
        }
    }

    public function toggleStatus(Format $format)
    {
        try {
            DB::beginTransaction();

            $format->update(['is_active' => !$format->is_active]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trạng thái đã được cập nhật thành công.',
                'is_active' => $format->is_active
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái. Vui lòng thử lại sau.'
            ], 500);
        }
    }
}