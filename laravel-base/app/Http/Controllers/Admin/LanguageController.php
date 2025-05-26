<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LanguageController extends Controller
{
    public function index(Request $request)
    {
        $query = Language::query();

        // Search by name or code
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
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
            case 'code_asc':
                $query->orderBy('code', 'asc');
                break;
            case 'code_desc':
                $query->orderBy('code', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
        }

        $languages = $query->paginate(10)->withQueryString();

        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:languages',
            'code' => 'required|string|max:10|unique:languages',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Tên ngôn ngữ là bắt buộc.',
            'name.string' => 'Tên ngôn ngữ phải là chuỗi.',
            'name.max' => 'Tên ngôn ngữ không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên ngôn ngữ đã tồn tại.',
            'code.required' => 'Mã ngôn ngữ là bắt buộc.',
        ]);

        DB::beginTransaction();
        try {
            // Sinh slug từ name
            $slug = Str::slug($request->input('name'));
            // Đảm bảo slug là duy nhất
            $count = Language::where('slug', $slug)->count();
            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }
            $validated['slug'] = $slug;
            $validated['is_active'] = $request->has('is_active') ? true : false;

            Language::create($validated);

            DB::commit();
            return redirect()
                ->route('admin.languages.index')
                ->with('success', 'Ngôn ngữ đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo ngôn ngữ. Vui lòng thử lại sau.');
        }
    }

    public function edit(Language $language)
    {
        return view('admin.languages.edit', compact('language'));
    }

    public function update(Request $request, Language $language)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:languages,name,' . $language->id,
            'code' => 'required|string|max:10|unique:languages,code,' . $language->id,
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Tên ngôn ngữ là bắt buộc.',
            'name.string' => 'Tên ngôn ngữ phải là chuỗi.',
            'name.max' => 'Tên ngôn ngữ không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên ngôn ngữ đã tồn tại.',
            'code.required' => 'Mã ngôn ngữ là bắt buộc.',
        ]);

        DB::beginTransaction();
        try {
            // Nếu tên thay đổi thì cập nhật lại slug
            if ($language->name !== $validated['name']) {
                $slug = Str::slug($validated['name']);
                $count = Language::where('slug', $slug)->where('id', '!=', $language->id)->count();
                if ($count > 0) {
                    $slug .= '-' . ($count + 1);
                }
                $validated['slug'] = $slug;
            }
            $validated['is_active'] = $request->has('is_active') ? true : false;
            $language->update($validated);
            DB::commit();
            return redirect()
                ->route('admin.languages.index')
                ->with('success', 'Ngôn ngữ đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật ngôn ngữ. Vui lòng thử lại sau.');
        }
    }

    public function destroy(Language $language)
    {
        try {
            DB::beginTransaction();

            // Kiểm tra xem có sách nào đang sử dụng ngôn ngữ này không
            $productCount = $language->productVariants()->count();
            if ($productCount > 0) {
                DB::rollBack();
                return back()->with('error', "Không thể xóa ngôn ngữ '{$language->name}' vì đang có {$productCount} sách đang sử dụng ngôn ngữ này.");
            }

            $language->delete();

            DB::commit();

            return redirect()
                ->route('admin.languages.index')
                ->with('success', 'Ngôn ngữ đã được xóa thành công.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi xóa ngôn ngữ. Vui lòng thử lại sau.');
        }
    }

    public function toggleStatus(Language $language)
    {
        try {
            DB::beginTransaction();

            $language->update(['is_active' => !$language->is_active]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trạng thái đã được cập nhật thành công.',
                'is_active' => $language->is_active
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