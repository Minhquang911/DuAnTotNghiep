<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('category')->latest()->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'publisher' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:còn hàng,hết hàng,ẩn',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required'
        ]);

        $data = $request->all();

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Lưu ảnh vào thư mục public/books
            $file->move(public_path('books'), $filename);
            
            // Lưu đường dẫn tương đối vào database
            $data['cover_image'] = 'books/' . $filename;
        }

        Book::create($data);

        return redirect()->route('books.index')
            ->with('success', 'Sách đã được thêm thành công');
    }

    public function show(Book $book)
    {
        return view('admin.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'publisher' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:còn hàng,hết hàng,ẩn',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required'
        ]);

        $data = $request->all();

        if ($request->hasFile('cover_image')) {
            // Xóa ảnh cũ nếu có
            if ($book->cover_image) {
                $oldImagePath = public_path($book->cover_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Lưu ảnh mới vào thư mục public/books
            $file->move(public_path('books'), $filename);
            
            // Lưu đường dẫn tương đối vào database
            $data['cover_image'] = 'books/' . $filename;
        }

        $book->update($data);

        return redirect()->route('books.index')
            ->with('success', 'Sách đã được cập nhật thành công');
    }

    public function destroy(Book $book)
    {
        // Xóa ảnh bìa nếu có
        if ($book->cover_image) {
            $imagePath = public_path($book->cover_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Sách đã được xóa thành công');
    }
} 