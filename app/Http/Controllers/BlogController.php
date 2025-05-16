<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with('user');
    
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
    
        $blogs = $query->latest()->paginate(6); // Áp dụng phân trang ở đây
    
        return view('admin.blogs.index', compact('blogs'));
    }
    


    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $blog = new Blog;
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->user_id = auth()->user()->id;
        $blog->rating = $request->rating ?? 1;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = public_path('assets/blogs');

            // Tạo thư mục nếu chưa có
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $blog->image = 'assets/blogs/' . $filename;
        }

        $blog->save();
        return redirect()->route('blogs.index')->with('success', 'Thêm bài viết thành công!');
    }

    public function edit($post_id)
    {
        $blog = Blog::findOrFail($post_id);
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, $post_id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $blog = Blog::findOrFail($post_id);
        $blog->title = $request->title;
        $blog->content = $request->content;

        if ($request->hasFile('image')) {
            // Xoá ảnh cũ nếu có
            if ($blog->image && file_exists(public_path($blog->image))) {
                unlink(public_path($blog->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = public_path('assets/blogs');

            // Tạo thư mục nếu chưa có
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $blog->image = 'assets/blogs/' . $filename;
        }

        $blog->save();
        return redirect()->route('blogs.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    public function destroy(Blog $blog)
    {
        // Xoá ảnh nếu có
        if ($blog->image && file_exists(public_path($blog->image))) {
            unlink(public_path($blog->image));
        }

        $blog->delete();
        return redirect()->route('blogs.index')->with('success', 'Xoá bài viết thành công!');
    }
    public function showAll()
    {
        
        $blogs = Blog::latest()->paginate(6); // Hoặc paginate nếu cần
        return view('blogs.home', compact('blogs'));
    }
    public function show($id)
    {
        $blog = Blog::with('user')->findOrFail($id);
        return view('blogs.show', compact('blog'));
    }

}

