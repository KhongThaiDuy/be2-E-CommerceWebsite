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
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $product = new Product([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'rating' => $request->rating,
        ]);

        // Upload image1
        if ($request->hasFile('image1')) {
            $file = $request->file('image1');
            $filename = time() . '_1_' . $file->getClientOriginalName();
            $destination = public_path('assets/images');

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $product->image1 = 'assets/images/' . $filename;
        }

        // Upload image2
        if ($request->hasFile('image2')) {
            $file = $request->file('image2');
            $filename = time() . '_2_' . $file->getClientOriginalName();
            $destination = public_path('assets/images');

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $product->image2 = 'assets/images/' . $filename;
        }

        // Upload image3
        if ($request->hasFile('image3')) {
            $file = $request->file('image3');
            $filename = time() . '_3_' . $file->getClientOriginalName();
            $destination = public_path('assets/images');

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $product->image3 = 'assets/images/' . $filename;
        }

        $product->save();

        return redirect()->route('product.index')->with('success', 'Sản phẩm đã được thêm thành công.');
    }


    // Hiển thị form sửa sản phẩm
    public function edit(Product $product)
    {
        $categories = Category::all(); // Lấy tất cả danh mục
        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
{
    $request->validate([
        'product_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'quantity' => 'required|integer',
        'category_id' => 'required|exists:categories,category_id',
        'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'rating' => 'nullable|integer|min:1|max:5',
    ]);

    $product->product_name = $request->product_name;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->quantity = $request->quantity;
    $product->category_id = $request->category_id;
    $product->rating = $request->rating;

    // Xử lý image1
    if ($request->hasFile('image1')) {
        if ($product->image1) {
            \Storage::disk('public')->delete($product->image1);
        }
        $product->image1 = $request->file('image1')->store('images', 'public');
    }

    // Xử lý image2
    if ($request->hasFile('image2')) {
        if ($product->image2) {
            \Storage::disk('public')->delete($product->image2);
        }
        $product->image2 = $request->file('image2')->store('images', 'public');
    }

    // Xử lý image3
    if ($request->hasFile('image3')) {
        if ($product->image3) {
            \Storage::disk('public')->delete($product->image3);
        }
        $product->image3 = $request->file('image3')->store('images', 'public');
    }

    $product->save();

    return redirect()->route('product.index')->with('success', 'Cập nhật sản phẩm thành công!');
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
