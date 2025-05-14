@extends('dashboard.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold mb-4">Thêm sản phẩm</h1>

        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="product_name" class="block text-gray-700 text-sm font-bold mb-2">Tên sản phẩm:</label>
                <input type="text" id="product_name" name="product_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div>
                <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Danh mục:</label>
                <select id="category_id" name="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Giá:</label>
                <input type="number" id="price" name="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div>
                <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Số lượng:</label>
                <input type="number" id="quantity" name="quantity" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div>
                <label for="image1" class="block text-gray-700 text-sm font-bold mb-2">Ảnh 1:</label>
                <input type="file" id="image1" name="image1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label for="image2" class="block text-gray-700 text-sm font-bold mb-2">Ảnh 2:</label>
                <input type="file" id="image2" name="image2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label for="image3" class="block text-gray-700 text-sm font-bold mb-2">Ảnh 3:</label>
                <input type="file" id="image3" name="image3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label for="rating" class="block text-gray-700 text-sm font-bold mb-2">Đánh giá:</label>
                <input type="number" id="rating" name="rating" min="1" max="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Lưu
            </button>
        </form>
    </div>
@endsection
