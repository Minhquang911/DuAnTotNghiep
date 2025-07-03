<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $categories = $query->paginate(10)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function toggleStatus(Request $request, Category $category)
    {
        try {
            $isActive = $request->is_active;

            // Cập nhật trạng thái
            $category->update(['is_active' => $isActive]);

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

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ], [
                'name.required' => 'Tên danh mục là bắt buộc',
                'name.string' => 'Tên danh mục phải là chuỗi',
                'name.max' => 'Tên danh mục không được vượt quá 255 ký tự',
            ]);

            $category = Category::create($request->all());
            return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ], [
                'name.required' => 'Tên danh mục là bắt buộc',
                'name.string' => 'Tên danh mục phải là chuỗi',
                'name.max' => 'Tên danh mục không được vượt quá 255 ký tự',
            ]);

            $category->update($request->all());
            return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật thành công');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        try {
            // Kiểm tra nếu danh mục có sản phẩm thì không cho xóa
            $productCount = $category->products()->count();
            if ($productCount > 0) {
                return redirect()->back()->with('error', "Không thể xóa danh mục '{$category->name}' vì đang có {$productCount} sản phẩm thuộc danh mục này.");
            }
            $category->delete();
            return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa: ' . $e->getMessage());
        }
    }
}