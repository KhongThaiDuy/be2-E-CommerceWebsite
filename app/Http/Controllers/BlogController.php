<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $blogs = Blog::with('user')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            })
            ->get();

        return view('admin.blogs.index', compact('blogs', 'search'));
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
        $blog->rating = $request->rating ?? 1; // sửa lại đúng biến $blog

        if ($request->hasFile('image')) {
            $blog->image = $request->file('image')->store('images', 'public');
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
            if ($blog->image) {
                \Storage::delete('public/' . $blog->image);
            }
            $blog->image = $request->file('image')->store('images', 'public');
        }

        $blog->save();
        return redirect()->route('blogs.index')->with('success', 'Cập nhật bài viết thành công!');
    }


    public function destroy(Blog $blog)
    {
        // Xoá ảnh nếu có
        if ($blog->image) {
            \Storage::delete('public/' . $blog->image);
        }

        $blog->delete();
        return redirect()->route('blogs.index')->with('success', 'Xoá bài viết thành công!');
    }

}
