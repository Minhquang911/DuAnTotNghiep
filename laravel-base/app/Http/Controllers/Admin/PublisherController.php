<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // app/Http/Controllers/Admin/PublisherController.php
    public function index(Request $request)
    {
        $query = \App\Models\Publisher::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        }

        $publishers = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.publishers.index', compact('publishers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.publishers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:publishers,slug',
                'address' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:10',
                'email' => 'nullable|email|max:255',
                'description' => 'nullable|string',
                'is_active' => 'required|in:0,1',
            ], [
                'name.required' => 'Tên nhà xuất bản là bắt buộc',
                'name.max' => 'Tên nhà xuất bản không được vượt quá 255 ký tự',
                'slug.unique' => 'Slug đã tồn tại',
                'email.email' => 'Email không hợp lệ',
                'is_active.in' => 'Trạng thái không hợp lệ',
            ]);

            $slug = $request->slug ?: Str::slug($request->name);

            Publisher::create([
                'name' => $request->name,
                'slug' => $slug,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'description' => $request->description,
                'is_active' => $request->input('is_active', 1),
            ]);

            return redirect()->route('admin.publishers.index')->with('success', 'Thêm nhà xuất bản thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Publisher $publisher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publisher $publisher)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:publishers,slug,' . $publisher->id,
                'address' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:10',
                'email' => 'nullable|email|max:255',
                'description' => 'nullable|string',
                'is_active' => 'required|in:0,1',
            ], [
                'name.required' => 'Tên nhà xuất bản là bắt buộc',
                'name.max' => 'Tên nhà xuất bản không được vượt quá 255 ký tự',
                'slug.unique' => 'Slug đã tồn tại',
                'email.email' => 'Email không hợp lệ',
                'is_active.in' => 'Trạng thái không hợp lệ',
            ]);

            $slug = $request->slug ?: \Illuminate\Support\Str::slug($request->name);

            $publisher->update([
                'name' => $request->name,
                'slug' => $slug,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'description' => $request->description,
                'is_active' => $request->input('is_active', 1),
            ]);

            return redirect()->route('admin.publishers.index')->with('success', 'Cập nhật nhà xuất bản thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publisher $publisher)
    {
        try {
            // Kiểm tra nếu nhà xuất bản có sản phẩm thì không cho xóa
            $productCount = $publisher->products()->count();
            if ($productCount > 0) {
                return redirect()->back()->with('error', "Không thể xóa nhà xuất bản '{$publisher->name}' vì đang có {$productCount} sản phẩm thuộc nhà xuất bản này.");
            }
            $publisher->delete();
            return redirect()->route('admin.publishers.index')->with('success', 'Xóa nhà xuất bản thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Request $request, Publisher $publisher)
    {
        try {
            $publisher->update(['is_active' => $request->is_active]);
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
}