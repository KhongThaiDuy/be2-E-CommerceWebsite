<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::when($search, function ($query, $search) {
            $query->where('category_name', 'like', "%{$search}%")
                ->orWhere('category_id', 'like', "%{$search}%");
        })->paginate(3); // Phân trang 10 mục mỗi trang

        // Giữ tham số search khi phân trang
        $categories->appends(['search' => $search]);

        return view('admin.categories.index', compact('categories', 'search'));
    }


    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|max:191',
        ]);

        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Thêm danh mục thành công');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => 'required|max:191',
        ]);

        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Cập nhật thành công');
    }

    public function suggestions(Request $request)
{
    $query = $request->get('q');

    $suggestions = Category::where('category_name', 'like', "%{$query}%")
        ->limit(5)
        ->pluck('category_name');

    return response()->json($suggestions);
}


    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Xoá thành công');
    }
    public function showProducts($id)
    {
        $category = Category::with('products')->findOrFail($id);
        $categories = Category::all(); // ← để hiển thị tất cả danh mục

        return view('categories.home', compact('category', 'categories'));
    }

}
