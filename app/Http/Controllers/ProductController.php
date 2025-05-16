<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; // Import model Category
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Hiển thị tất cả sản phẩm
    public function index(Request $request)
{
    $search = $request->input('search');
    $sort = $request->input('sort');

    $products = Product::with('category')
        ->when($search, function ($query, $search) {
            $query->where('product_name', 'like', "%{$search}%")
                ->orWhere('price', 'like', "%{$search}%")
                ->orWhere('quantity', 'like', "%{$search}%");
        })
        ->when($sort, function ($query, $sort) {
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'quantity_asc':
                    $query->orderBy('quantity', 'asc');
                    break;
                case 'quantity_desc':
                    $query->orderBy('quantity', 'desc');
                    break;
            }
            
        })
        ->get();

    return view('admin.product.index', compact('products', 'search', 'sort'));
}
    


    // Hiển thị form thêm mới sản phẩm
    public function create()
    {
        $categories = Category::all(); // Lấy tất cả danh mục
        return view('admin.product.create', compact('categories'));
    }

    // Lưu sản phẩm mới
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,category_id',
            'image1' => 'nullable|image',
            'image2' => 'nullable|image',
            'image3' => 'nullable|image',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $product = new Product([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'image1' => $request->file('image1') ? $request->file('image1')->store('images') : null,
            'image2' => $request->file('image2') ? $request->file('image2')->store('images') : null,
            'image3' => $request->file('image3') ? $request->file('image3')->store('images') : null,
            'rating' => $request->rating,
        ]);
        
        $product->save();

        return redirect()->route('product.index')->with('success', 'Sản phẩm đã được thêm thành công.');
    }

    // Hiển thị form sửa sản phẩm
    public function edit(Product $product)
    {
        $categories = Category::all(); // Lấy tất cả danh mục
        return view('admin.product.edit', compact('product', 'categories'));
    }

    // Cập nhật sản phẩm
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
    
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
    
  public function suggestions(Request $request)
{
    $query = $request->get('q');

    $suggestions = Product::where('product_name', 'like', "%{$query}%")
        ->limit(5)
        ->pluck('product_name');

    return response()->json($suggestions);
}


public function home(Request $request)
{
    $search = $request->input('search');
    $sort = $request->input('sort');

    $products = Product::with('category')
        ->when($search, function ($query, $search) {
            $query->where('product_name', 'like', "%{$search}%")
                ->orWhere('price', 'like', "%{$search}%")
                ->orWhere('quantity', 'like', "%{$search}%");
        })
        ->when($sort, function ($query, $sort) {
            if ($sort === 'asc') {
                $query->orderBy('price', 'asc');
            } elseif ($sort === 'desc') {
                $query->orderBy('price', 'desc');
            }
        })
        ->get();

    return view('products.home', compact('products', 'search', 'sort'));
}



    // Xoá sản phẩm
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Sản phẩm đã được xoá thành công.');
    }
}
