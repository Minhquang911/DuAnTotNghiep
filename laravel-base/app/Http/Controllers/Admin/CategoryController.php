<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::whereNull('parent_id')->with('children');

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

            // Nếu muốn bật (is_active = 1) và có cha bị khóa thì không cho phép
            if ($isActive && $category->parent && !$category->parent->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể bật danh mục khi danh mục cha đang bị khóa!'
                ], 400);
            }

            // Cập nhật chính nó
            $category->update(['is_active' => $isActive]);

            // Đệ quy cập nhật tất cả con
            $this->updateChildrenStatus($category, $isActive);

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
        $categories = Category::all();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:categories,id',
            ], [
                'name.required' => 'Tên danh mục là bắt buộc',
                'name.string' => 'Tên danh mục phải là chuỗi',
                'name.max' => 'Tên danh mục không được vượt quá 255 ký tự',
                'parent_id.exists' => 'Danh mục cha không tồn tại',
            ]);

            $category = Category::create($request->all());
            return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function edit(Category $category)
    {
        $categories = Category::all();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:categories,id',
            ], [
                'name.required' => 'Tên danh mục là bắt buộc',
                'name.string' => 'Tên danh mục phải là chuỗi',
                'name.max' => 'Tên danh mục không được vượt quá 255 ký tự',
                'parent_id.exists' => 'Danh mục cha không tồn tại',
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

    private function updateChildrenStatus($category, $isActive)
    {
        foreach ($category->children as $child) {
            $child->update(['is_active' => $isActive]);
            $this->updateChildrenStatus($child, $isActive);
        }
    }
}