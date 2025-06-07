<?php

namespace App\Http\Controllers\Admin;

use App\Models\Album;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    /**
     * Upload multiple images for a product
     */
    public function upload(Request $request, Product $product)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $uploadedImages = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products/albums', 'public');
                
                $album = Album::create([
                    'product_id' => $product->id,
                    'image' => $path
                ]);

                $uploadedImages[] = $album;
            }

            return response()->json([
                'success' => true,
                'message' => 'Tải lên hình ảnh thành công',
                'images' => $uploadedImages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải lên hình ảnh: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an image from album
     */
    public function destroy(Album $album)
    {
        try {
            // Delete image file from storage
            if (Storage::disk('public')->exists($album->image)) {
                Storage::disk('public')->delete($album->image);
            }

            // Delete album record
            $album->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa hình ảnh thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa hình ảnh: ' . $e->getMessage()
            ], 500);
        }
    }
}