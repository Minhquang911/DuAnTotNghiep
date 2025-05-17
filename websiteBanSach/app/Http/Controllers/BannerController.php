<?php
	
	namespace App\Http\Controllers;
	use App\Models\Banner;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Http\Request;
	
	class BannerController extends Controller
	{
	    /**
	     * Display a listing of the resource.
	     */
	    public function index()
	    {
	        $banners = Banner::orderBy('created_at', 'desc')->get();
	        return view('admin.banners.index', compact('banners'));
	    }
	
	    /**
	     * Show the form for creating a new resource.
     */
	    public function create()
	    {
	        return view('admin.banners.create');
	    }
	
	    /**
	     * Store a newly created resource in storage.
	     */
	    public function store(Request $request)
	    {
	        $request->validate([
	            'title' => 'nullable|string|max:255',
	            'image_path' => 'required|image|mimes:jpg,jpeg,png,webp',
	            'link_url' => 'nullable|url',
	            'position' => 'nullable|string|max:50',
	        ]);
	
	        $data = $request->all();
	        $data = $request->except('_token');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
	
	        if ($request->hasFile('image_path')) {
	            $data['image_path'] = $request->file('image_path')->store('banners', 'public');
	        }

	        Banner::create($data);
	
	        return redirect()->route('banners.index')->with('success', 'Thêm banner thành công!');
	    }
	
	    /**
	     * Display the specified resource.
	     */
	    public function show(string $id)
	    {
	        $banner = Banner::findOrFail($id);
	        return view('admin.banners.show', compact('banner'));
	    }
	
	    /**
	     * Show the form for editing the specified resource.
	     */
	    public function edit(string $id)
	    {
	        $banner = Banner::findOrFail($id);
	        return view('admin.banners.edit', compact('banner'));
	    }
	
	    /**
	     * Update the specified resource in storage.
	     */
	    public function update(Request $request, string $id)
	    {
	        $banner = Banner::findOrFail($id);
	        $request->validate([
	            'title' => 'nullable|string|max:255',
	            'image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp',
	            'link_url' => 'nullable|url',
	            'position' => 'nullable|string|max:50',
	        ]);
	
	        $data = $request->all();
	        $data = $request->except('_token', '_method');
	        $data['is_active'] = $request->has('is_active') ? 1 : 0;
	

	        if ($request->hasFile('image_path')) {
	            // Xoá ảnh cũ nếu có
	            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
	                Storage::disk('public')->delete($banner->image_path);
	            }
	
	            $data['image_path'] = $request->file('image_path')->store('banners', 'public');
	        }
	
	        $banner->update($data);
	
	        return redirect()->route('banners.index')->with('success', 'Cập nhật banner thành công!');
	    }
	
	    /**
	     * Remove the specified resource from storage.
	     */
	    public function destroy(string $id)
	    {
	        $banner = Banner::findOrFail($id);
	
	        // Xoá ảnh
	        if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
	            Storage::disk('public')->delete($banner->image_path);
	        }
	
	        $banner->delete();
	
	        return redirect()->route('banners.index')->with('success', 'Xóa banner thành công!');
	    }
	}
