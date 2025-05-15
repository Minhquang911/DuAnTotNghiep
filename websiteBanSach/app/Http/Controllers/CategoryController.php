<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Áp dụng bộ lọc theo tên nếu có
        $query = Category::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Lấy danh sách các danh mục
        $categories = $query->latest()->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories',
        ]);

        Category::create($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được thêm thành công');
    }

    public function edit($category_id)
    {
        $category = Category::findOrFail($category_id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $id . ',category_id',
        ]);

        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name]);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($category_id)
    {
        Category::destroy($category_id);
        return back()->with('success', 'Danh mục đã được xóa');
    }
}