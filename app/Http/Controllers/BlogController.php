<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    private function validateTextField($attribute, $value, $fail)
    {
        $trimmed = preg_replace('/[\s\x{3000}]+/u', '', $value);
        if ($trimmed === '') {
            $fail("Trường {$attribute} không được phép chỉ chứa khoảng trắng.");
        }
        if ($value !== strip_tags($value)) {
            $fail("Trường {$attribute} không được chứa thẻ HTML.");
        }
    }

    private function handleImageUpload(Request $request, $folder = 'assets/blogs')
    {
        $file = $request->file('image');
        $ext = strtolower($file->getClientOriginalExtension());

        if ($ext === 'pdf') {
            return ['error' => 'File PDF không được phép tải lên.'];
        }

        $filename = time() . '_' . $file->getClientOriginalName();
        $destination = public_path($folder);

        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        $file->move($destination, $filename);
        return ['path' => $folder . '/' . $filename];
    }

    public function index(Request $request)
    {
        $query = Blog::with('user');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $blogs = $query->orderBy('post_id', 'asc')->paginate(6);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => [
                'required', 'string', 'max:255',
                function ($attribute, $value, $fail) {
                    $this->validateTextField($attribute, $value, $fail);
                }
            ],
            'content' => [
                'required', 'string',
                function ($attribute, $value, $fail) {
                    $this->validateTextField($attribute, $value, $fail);
                }
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->user_id = Auth::id();
        $blog->rating = $request->rating ?? 1;

        if ($request->hasFile('image')) {
            $upload = $this->handleImageUpload($request);
            if (isset($upload['error'])) {
                return back()->withErrors(['image' => $upload['error']])->withInput();
            }
            $blog->image = $upload['path'];
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
    $blog = Blog::findOrFail($post_id);

    // Kiểm tra xung đột cập nhật
    if ($request->filled('updated_at')) {
        $clientTime = \Carbon\Carbon::parse($request->updated_at);
        if ($clientTime->ne($blog->updated_at)) {
            return back()
                ->withErrors(['error' => 'Dữ liệu đã được chỉnh sửa bởi người khác. Vui lòng tải lại trang.'])
                ->withInput();
        }
    }

    // Validation
    $validated = $request->validate([
        'title' => [
            'required', 'string', 'max:255',
            function ($attribute, $value, $fail) {
                $this->validateTextField($attribute, $value, $fail);
            }
        ],
        'content' => [
            'required', 'string',
            function ($attribute, $value, $fail) {
                $this->validateTextField($attribute, $value, $fail);
            }
        ],
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        'rating' => 'nullable|integer|min:1|max:5',
    ]);

    // Cập nhật dữ liệu
    $blog->title = $validated['title'];
    $blog->content = $validated['content'];
    $blog->rating = $validated['rating'] ?? $blog->rating;

    if ($request->hasFile('image')) {
        if ($blog->image && file_exists(public_path($blog->image))) {
            unlink(public_path($blog->image));
        }

        $upload = $this->handleImageUpload($request);
        if (isset($upload['error'])) {
            return back()->withErrors(['image' => $upload['error']])->withInput();
        }

        $blog->image = $upload['path'];
    }

    $blog->save();
    return redirect()->route('blogs.index')->with('success', 'Cập nhật bài viết thành công!');
}


public function destroy(Blog $blog)
{
    try {
        // Xoá ảnh nếu tồn tại và hợp lệ
        if ($blog->image) {
            $imagePath = public_path($blog->image);
            if (file_exists($imagePath) && is_file($imagePath)) {
                unlink($imagePath);
            }
        }

        // Xoá bài viết
        $blog->delete();

        return redirect()->route('blogs.index')->with('success', 'Xoá bài viết thành công!');
    } catch (\Exception $e) {
        return redirect()->route('blogs.index')->withErrors(['error' => 'Không thể xoá bài viết: ' . $e->getMessage()]);
    }
}


    public function showAll()
    {
        $blogs = Blog::latest()->paginate(6);
        return view('blogs.home', compact('blogs'));
    }

    public function show($id)
    {
        $blog = Blog::with('user')->findOrFail($id);
        return view('blogs.show', compact('blog'));
    }
}
