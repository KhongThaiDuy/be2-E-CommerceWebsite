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
            ->paginate(10)
            ->appends([
                'search' => $search,
                'sort' => $sort,
            ]);

        return view('admin.product.index', compact('products', 'search', 'sort'));
    }



    // Hiển thị form thêm mới sản phẩm
    public function create()
    {
        $categories = Category::all(); // Lấy tất cả danh mục
        return view('admin.product.create', compact('categories'));
    }

    private function trimUnicodeSpace($value)
    {
        if ($value === null) {
            return $value;
        }
        // Xóa khoảng trắng thường và khoảng trắng Unicode 2 bytes (U+3000)
        return preg_replace('/^[\s\x{3000}]+|[\s\x{3000}]+$/u', '', $value);
    }

    // Lưu sản phẩm mới
    public function store(Request $request)
    {
        $request->merge([
            'product_name' => $this->trimUnicodeSpace($request->input('product_name')),
        ]);
        $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|max:999999999',
            'quantity' => 'required|integer|max:999999999',
            'category_id' => 'required|exists:categories,category_id',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rating' => 'required|integer|min:1|max:5',
        ], [
            'product_name.required' => 'Tên sản phẩm là bắt buộc và không được để trống hoặc toàn khoảng trắng.',
            'product_name.string' => 'Tên sản phẩm không hợp lệ.',
            'price.required' => 'Giá là trường bắt buộc.',
            'price.numeric' => 'Giá không hợp lệ và bắt buộc phải là số nguyên.',
            'quantity.required' => 'Số lượng là trường bắt buộc.',
            'quantity.integer' => 'Số lượng không hợp lệ và bắt buộc phải là số nguyên.',
            'category_id.required' => 'Danh mục là bắt buộc.',
            'category_id.exists' => 'Danh mục không tồn tại.',
            'image1.image' => 'Ảnh 1 phải là file ảnh.',
            'image1.mimes' => 'Ảnh 1 phải có định dạng jpeg, png, jpg, gif.',
            'image1.max' => 'Ảnh 1 không được lớn hơn 2MB.',
            'image2.image' => 'Ảnh 2 phải là file ảnh.',
            'image2.mimes' => 'Ảnh 2 phải có định dạng jpeg, png, jpg, gif.',
            'image2.max' => 'Ảnh 2 không được lớn hơn 2MB.',
            'image3.image' => 'Ảnh 3 phải là file ảnh.',
            'image3.mimes' => 'Ảnh 3 phải có định dạng jpeg, png, jpg, gif.',
            'image3.max' => 'Ảnh 3 không được lớn hơn 2MB.',
            'rating.integer' => 'Đánh giá phải là số nguyên.',
            'rating.min' => 'Đánh giá phải lớn hơn hoặc bằng 1.',
            'rating.max' => 'Đánh giá phải nhỏ hơn hoặc bằng 5.',
        ]);

        $product = new Product([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'rating' => $request->rating,
        ]);

        // Trong store()
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
    public function edit($id)
    {
        if (!is_numeric($id)) {
            return redirect()->route('product.index')->with('error', 'ID sản phẩm không hợp lệ.');
        }

        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('product.index')->with('error', 'Sản phẩm không tồn tại.');
        }

        $categories = Category::all();
        return view('admin.product.edit', compact('product', 'categories'));
    }


    public function update(Request $request, $id)
    {
        if (!is_numeric($id)) {
            return redirect()->route('product.index')->with('error', 'ID sản phẩm không hợp lệ.');
        }

        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('product.index')->with('error', 'Sản phẩm không tồn tại.');
        }
        $request->merge([
            'product_name' => $this->trimUnicodeSpace($request->input('product_name')),
        ]);

        $request->validate(
            [
                'product_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'quantity' => 'required|integer',
                'category_id' => 'required|exists:categories,category_id',
                'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'rating' => 'required|integer|min:1|max:5',
            ],
            [
                'product_name.required' => 'Tên sản phẩm là bắt buộc và không được để trống hoặc toàn khoảng trắng.',
                'product_name.string' => 'Tên sản phẩm không hợp lệ.',
                'price.required' => 'Giá là trường bắt buộc.',
                'price.numeric' => 'Giá không hợp lệ và bắt buộc phải là số nguyên.',
                'quantity.required' => 'Số lượng là trường bắt buộc.',
                'quantity.integer' => 'Số lượng không hợp lệ và phải là số nguyên.',
                'image1.image' => 'Ảnh 1 phải là file ảnh.',
                'image1.mimes' => 'Ảnh 1 phải có định dạng jpeg, png, jpg, gif.',
                'image1.max' => 'Ảnh 1 không được lớn hơn 2MB.',
                'image2.image' => 'Ảnh 2 phải là file ảnh.',
                'image2.mimes' => 'Ảnh 2 phải có định dạng jpeg, png, jpg, gif.',
                'image2.max' => 'Ảnh 2 không được lớn hơn 2MB.',
                'image3.image' => 'Ảnh 3 phải là file ảnh.',
                'image3.mimes' => 'Ảnh 3 phải có định dạng jpeg, png, jpg, gif.',
                'image3.max' => 'Ảnh 3 không được lớn hơn 2MB.',
                'rating.required' => 'Đánh giá là trường bắt buộc.',
                'rating.integer' => 'Đánh giá phải là số nguyên.',
                'rating.min' => 'Đánh giá phải lớn hơn hoặc bằng 1.',
                'rating.max' => 'Đánh giá phải nhỏ hơn hoặc bằng 5.',
            ]
        );

        if ($request->input('updated_at') !== $product->updated_at->toDateTimeString()) {
            return redirect()->back()->withInput()->withErrors([
                'general' => 'Dữ liệu sản phẩm đã bị thay đổi bởi người khác. Vui lòng tải lại trang trước khi cập nhật.',
            ]);
        }

        $product->product_name = $request->product_name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->rating = $request->rating;

        // Trong update()
        if ($request->hasFile('image1')) {
            if ($product->image1 && file_exists(public_path($product->image1))) {
                unlink(public_path($product->image1));
            }

            $file = $request->file('image1');
            $filename = time() . '_1_' . $file->getClientOriginalName();
            $destination = public_path('assets/images');

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $product->image1 = 'assets/images/' . $filename;
        }

        if ($request->hasFile('image2')) {
            if ($product->image2 && file_exists(public_path($product->image2))) {
                unlink(public_path($product->image2));
            }

            $file = $request->file('image2');
            $filename = time() . '_2_' . $file->getClientOriginalName();
            $destination = public_path('assets/images');

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $product->image2 = 'assets/images/' . $filename;
        }

        if ($request->hasFile('image3')) {
            if ($product->image3 && file_exists(public_path($product->image3))) {
                unlink(public_path($product->image3));
            }

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
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('product.index')->with('error', 'Sản phẩm không tồn tại hoặc đã bị xoá.');
        }

        $product->delete();

        return redirect()->route('product.index')->with('success', 'Sản phẩm đã được xoá thành công.');
    }

}
