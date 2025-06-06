<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Format;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'publisher']);

        // Search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Filter by publisher
        if ($request->filled('publisher_id')) {
            $query->where('publisher_id', $request->get('publisher_id'));
        }

        // Filter by status
        if ($request->has('status')) {
            $status = $request->get('status');
            switch ($status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'hot':
                    $query->where('is_hot', true);
                    break;
                case 'new':
                    $query->where('is_new', true);
                    break;
                case 'best_seller':
                    $query->where('is_best_seller', true);
                    break;
                case 'recommended':
                    $query->where('is_recommended', true);
                    break;
                case 'featured':
                    $query->where('is_featured', true);
                    break;
                case 'promotion':
                    $query->where('is_promotion', true);
                    break;
            }
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '>=', $request->get('min_price'));
            });
        }
        if ($request->filled('max_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '<=', $request->get('max_price'));
            });
        }

        // Filter by format
        if ($request->filled('format_id')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('format_id', $request->get('format_id'));
            });
        }

        // Filter by language
        if ($request->filled('language_id')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('language_id', $request->get('language_id'));
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'author_asc':
                $query->orderBy('author', 'asc');
                break;
            case 'author_desc':
                $query->orderBy('author', 'desc');
                break;
            case 'price_asc':
                $query->orderByRaw('(SELECT MIN(price) FROM product_variants WHERE product_id = products.id) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('(SELECT MAX(price) FROM product_variants WHERE product_id = products.id) DESC');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(10)->withQueryString();

        // Get filter options
        $categories = Category::active()->get();
        $publishers = Publisher::active()->get();
        $formats = Format::active()->get();
        $languages = Language::active()->get();

        return view('admin.products.index', compact(
            'products',
            'categories',
            'publishers',
            'formats',
            'languages'
        ));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'publisher', 'variants.format', 'variants.language', 'albums']);
        return view('admin.products.show', compact('product'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        $publishers = Publisher::active()->get();
        $formats = Format::active()->get();
        $languages = Language::active()->get();

        return view('admin.products.create', compact('categories', 'publishers', 'formats', 'languages'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'publisher_id' => 'required|exists:publishers,id',
                'description' => 'required|string',
                'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'published_at' => 'required|date',
                'isbn' => 'required|string|max:20|unique:products',
                'length_cm' => 'nullable|numeric|min:0',
                'width_cm' => 'nullable|numeric|min:0',
                'weight_g' => 'nullable|integer|min:0',
                'page_count' => 'nullable|integer|min:1',
                'is_active' => 'boolean',
                'is_hot' => 'boolean',
                'is_new' => 'boolean',
                'is_best_seller' => 'boolean',
                'is_recommended' => 'boolean',
                'is_featured' => 'boolean',
                'is_promotion' => 'boolean',
                // Variants
                'variants' => 'required|array|min:1',
                'variants.*.format_id' => 'required|exists:formats,id',
                'variants.*.language_id' => 'required|exists:languages,id',
                'variants.*.sku' => [
                    'required',
                    'string',
                    'max:50',
                    function ($attribute, $value, $fail) use ($request) {
                        $index = explode('.', $attribute)[1];
                        $variantId = $request->variants[$index]['id'] ?? null;

                        $exists = DB::table('product_variants')
                            ->where('sku', $value)
                            ->where('id', '!=', $variantId)
                            ->exists();

                        if ($exists) {
                            $fail('Mã SKU đã tồn tại.');
                        }
                    }
                ],
                'variants.*.price' => 'required|numeric|min:0',
                'variants.*.promotion_price' => 'nullable|numeric|min:0|lt:variants.*.price',
                'variants.*.stock' => 'required|integer|min:0',
                'variants.*.is_active' => 'boolean',
            ], [
                'title.required' => 'Tiêu đề là bắt buộc.',
                'title.string' => 'Tiêu đề phải là chuỗi.',
                'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
                'author.required' => 'Tác giả là bắt buộc.',
                'author.string' => 'Tác giả phải là chuỗi.',
                'author.max' => 'Tác giả không được vượt quá 255 ký tự.',
                'category_id.required' => 'Danh mục là bắt buộc.',
                'category_id.exists' => 'Danh mục không hợp lệ.',
                'publisher_id.required' => 'Nhà xuất bản là bắt buộc.',
                'publisher_id.exists' => 'Nhà xuất bản không hợp lệ.',
                'description.required' => 'Mô tả là bắt buộc.',
                'description.string' => 'Mô tả phải là chuỗi.',
                'cover_image.required' => 'Ảnh bìa là bắt buộc.',
                'cover_image.image' => 'Ảnh bìa phải là hình ảnh.',
                'cover_image.mimes' => 'Ảnh bìa phải có định dạng jpeg, png, jpg, gif.',
                'cover_image.max' => 'Ảnh bìa không được vượt quá 2MB.',
                'published_at.required' => 'Ngày xuất bản là bắt buộc.',
                'published_at.date' => 'Ngày xuất bản phải là ngày.',
                'isbn.required' => 'ISBN là bắt buộc.',
                'isbn.string' => 'ISBN phải là chuỗi.',
                'isbn.max' => 'ISBN không được vượt quá 20 ký tự.',
                'isbn.unique' => 'ISBN đã tồn tại.',
                'length_cm.numeric' => 'Chiều dài phải là số.',
                'length_cm.min' => 'Chiều dài không được nhỏ hơn 0.',
                'width_cm.numeric' => 'Chiều rộng phải là số.',
                'width_cm.min' => 'Chiều rộng không được nhỏ hơn 0.',
                'weight_g.integer' => 'Trọng lượng phải là số nguyên.',
                'weight_g.min' => 'Trọng lượng không được nhỏ hơn 0.',
                'page_count.integer' => 'Số trang phải là số nguyên.',
                'page_count.min' => 'Số trang phải lớn hơn 0.',
                'variants.required' => 'Ít nhất một phiên bản là bắt buộc.',
                'variants.array' => 'Phiên bản phải là mảng.',
                'variants.min' => 'Ít nhất một phiên bản là bắt buộc.',
                'variants.*.format_id.required' => 'Định dạng là bắt buộc.',
                'variants.*.format_id.exists' => 'Định dạng không hợp lệ.',
                'variants.*.language_id.required' => 'Ngôn ngữ là bắt buộc.',
                'variants.*.language_id.exists' => 'Ngôn ngữ không hợp lệ.',
                'variants.*.sku.required' => 'Mã SKU là bắt buộc.',
                'variants.*.sku.string' => 'Mã SKU phải là chuỗi.',
                'variants.*.sku.max' => 'Mã SKU không được vượt quá 50 ký tự.',
                'variants.*.price.required' => 'Giá là bắt buộc.',
                'variants.*.price.numeric' => 'Giá phải là số.',
                'variants.*.price.min' => 'Giá không được nhỏ hơn 0.',
                'variants.*.promotion_price.numeric' => 'Giá khuyến mãi phải là số.',
                'variants.*.promotion_price.min' => 'Giá khuyến mãi không được nhỏ hơn 0.',
                'variants.*.promotion_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
                'variants.*.stock.required' => 'Số lượng tồn kho là bắt buộc.',
                'variants.*.stock.integer' => 'Số lượng tồn kho phải là số nguyên.',
                'variants.*.stock.min' => 'Số lượng tồn kho không được nhỏ hơn 0.',
            ]);

            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                try {
                    $path = $request->file('cover_image')->store('products', 'public');
                    $validated['cover_image'] = $path;
                } catch (\Exception $e) {
                    throw new \Exception('Không thể tải lên ảnh bìa: ' . $e->getMessage());
                }
            }

            // Generate slug
            $validated['slug'] = Str::slug($validated['title']);

            // Create product
            $product = Product::create($validated);

            // Create variants
            foreach ($request->variants as $variant) {
                $product->variants()->create($variant);
            }

            // Xử lý lưu album ảnh mới
            if ($request->hasFile('album_images')) {
                foreach ($request->file('album_images') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store('products/albums', 'public');
                        $product->albums()->create([
                            'image' => $path,
                        ]);
                    }
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sản phẩm đã được tạo thành công.'
                ]);
            }

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được tạo thành công.');
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error while creating product: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Database error while creating product: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi lưu dữ liệu. Vui lòng thử lại sau.')
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error while creating product: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Product $product)
    {
        $product->load(['variants.format', 'variants.language']);
        $categories = Category::active()->get();
        $publishers = Publisher::active()->get();
        $formats = Format::active()->get();
        $languages = Language::active()->get();

        return view('admin.products.edit', compact('product', 'categories', 'publishers', 'formats', 'languages'));
    }

    public function update(Request $request, Product $product)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'publisher_id' => 'required|exists:publishers,id',
                'description' => 'required|string',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'published_at' => 'required|date',
                'isbn' => 'required|string|max:20|unique:products,isbn,' . $product->id,
                'length_cm' => 'nullable|numeric|min:0',
                'width_cm' => 'nullable|numeric|min:0',
                'weight_g' => 'nullable|integer|min:0',
                'page_count' => 'nullable|integer|min:1',
                'is_active' => 'boolean',
                'is_hot' => 'boolean',
                'is_new' => 'boolean',
                'is_best_seller' => 'boolean',
                'is_recommended' => 'boolean',
                'is_featured' => 'boolean',
                'is_promotion' => 'boolean',
                // Variants
                'variants' => 'required|array|min:1',
                'variants.*.id' => 'nullable|exists:product_variants,id',
                'variants.*.format_id' => 'required|exists:formats,id',
                'variants.*.language_id' => 'required|exists:languages,id',
                'variants.*.sku' => [
                    'required',
                    'string',
                    'max:50',
                    function ($attribute, $value, $fail) use ($request) {
                        $index = explode('.', $attribute)[1];
                        $variantId = $request->variants[$index]['id'] ?? null;
                        
                        $exists = DB::table('product_variants')
                            ->where('sku', $value)
                            ->where('id', '!=', $variantId)
                            ->exists();
                            
                        if ($exists) {
                            $fail('Mã SKU đã tồn tại.');
                        }
                    }
                ],
                'variants.*.price' => 'required|numeric|min:0',
                'variants.*.promotion_price' => 'nullable|numeric|min:0|lt:variants.*.price',
                'variants.*.stock' => 'required|integer|min:0',
                'variants.*.is_active' => 'boolean',
            ], [
                'title.required' => 'Tiêu đề là bắt buộc.',
                'title.string' => 'Tiêu đề phải là chuỗi.',
                'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
                'author.required' => 'Tác giả là bắt buộc.',
                'author.string' => 'Tác giả phải là chuỗi.',
                'author.max' => 'Tác giả không được vượt quá 255 ký tự.',
                'category_id.required' => 'Danh mục là bắt buộc.',
                'category_id.exists' => 'Danh mục không hợp lệ.',
                'publisher_id.required' => 'Nhà xuất bản là bắt buộc.',
                'publisher_id.exists' => 'Nhà xuất bản không hợp lệ.',
                'description.required' => 'Mô tả là bắt buộc.',
                'description.string' => 'Mô tả phải là chuỗi.',
                'cover_image.image' => 'Ảnh bìa phải là hình ảnh.',
                'cover_image.mimes' => 'Ảnh bìa phải có định dạng jpeg, png, jpg, gif.',
                'cover_image.max' => 'Ảnh bìa không được vượt quá 2MB.',
                'published_at.required' => 'Ngày xuất bản là bắt buộc.',
                'published_at.date' => 'Ngày xuất bản phải là ngày.',
                'isbn.required' => 'ISBN là bắt buộc.',
                'isbn.string' => 'ISBN phải là chuỗi.',
                'isbn.max' => 'ISBN không được vượt quá 20 ký tự.',
                'isbn.unique' => 'ISBN đã tồn tại.',
                'length_cm.numeric' => 'Chiều dài phải là số.',
                'length_cm.min' => 'Chiều dài không được nhỏ hơn 0.',
                'width_cm.numeric' => 'Chiều rộng phải là số.',
                'width_cm.min' => 'Chiều rộng không được nhỏ hơn 0.',
                'weight_g.integer' => 'Trọng lượng phải là số nguyên.',
                'weight_g.min' => 'Trọng lượng không được nhỏ hơn 0.',
                'page_count.integer' => 'Số trang phải là số nguyên.',
                'page_count.min' => 'Số trang phải lớn hơn 0.',
                'variants.required' => 'Ít nhất một phiên bản là bắt buộc.',
                'variants.array' => 'Phiên bản phải là mảng.',
                'variants.min' => 'Ít nhất một phiên bản là bắt buộc.',
                'variants.*.format_id.required' => 'Định dạng là bắt buộc.',
                'variants.*.format_id.exists' => 'Định dạng không hợp lệ.',
                'variants.*.language_id.required' => 'Ngôn ngữ là bắt buộc.',
                'variants.*.language_id.exists' => 'Ngôn ngữ không hợp lệ.',
                'variants.*.sku.required' => 'Mã SKU là bắt buộc.',
                'variants.*.sku.string' => 'Mã SKU phải là chuỗi.',
                'variants.*.sku.max' => 'Mã SKU không được vượt quá 50 ký tự.',
                'variants.*.price.required' => 'Giá là bắt buộc.',
                'variants.*.price.numeric' => 'Giá phải là số.',
                'variants.*.price.min' => 'Giá không được nhỏ hơn 0.',
                'variants.*.promotion_price.numeric' => 'Giá khuyến mãi phải là số.',
                'variants.*.promotion_price.min' => 'Giá khuyến mãi không được nhỏ hơn 0.',
                'variants.*.promotion_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
                'variants.*.stock.required' => 'Số lượng tồn kho là bắt buộc.',
                'variants.*.stock.integer' => 'Số lượng tồn kho phải là số nguyên.',
                'variants.*.stock.min' => 'Số lượng tồn kho không được nhỏ hơn 0.',
            ]);

            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                // Delete old image
                if ($product->cover_image) {
                    Storage::disk('public')->delete($product->cover_image);
                }
                $path = $request->file('cover_image')->store('products', 'public');
                $validated['cover_image'] = $path;
            }

            // Generate slug if title changed
            if ($product->title !== $validated['title']) {
                $validated['slug'] = Str::slug($validated['title']);
            }

            // Update product
            $product->update($validated);

            // Update variants
            $existingVariantIds = $product->variants->pluck('id')->toArray();
            $updatedVariantIds = [];

            foreach ($request->variants as $variant) {
                if (isset($variant['id'])) {
                    // Update existing variant
                    $product->variants()->where('id', $variant['id'])->update($variant);
                    $updatedVariantIds[] = $variant['id'];
                } else {
                    // Create new variant
                    $newVariant = $product->variants()->create($variant);
                    $updatedVariantIds[] = $newVariant->id;
                }
            }

            // Delete variants that were not updated
            $variantsToDelete = array_diff($existingVariantIds, $updatedVariantIds);
            if (!empty($variantsToDelete)) {
                $product->variants()->whereIn('id', $variantsToDelete)->delete();
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được cập nhật thành công.');
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error while updating product: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Database error while updating product: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi lưu dữ liệu. Vui lòng thử lại sau.')
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error while updating product: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            // Delete cover image
            if ($product->cover_image) {
                Storage::disk('public')->delete($product->cover_image);
            }

            $product->delete();

            DB::commit();
            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được xóa thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Có lỗi xảy ra khi xóa sản phẩm. Vui lòng thử lại sau.');
        }
    }

    public function toggleStatus(Product $product)
    {
        try {
            DB::beginTransaction();

            $product->update(['is_active' => !$product->is_active]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trạng thái đã được cập nhật thành công.',
                'is_active' => $product->is_active
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