@extends('dashboard.app')

@section('content')
    <div class="container">
        <h1 class="my-4">Chỉnh sửa sản phẩm</h1>

        <form action="{{ route('product.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="product_name">Tên sản phẩm</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}" required>
            </div>

            <div class="form-group">
                <label for="category_id">Danh mục</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->category_id }}" {{ $product->category_id == $category->category_id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="price">Giá</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
            </div>

            <div class="form-group">
                <label for="quantity">Số lượng</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $product->quantity }}" required>
            </div>

            <div class="form-group">
                <label for="image1">Ảnh 1</label>
                <input type="file" class="form-control" id="image1" name="image1">
                <img src="{{ asset($product->image1) }}" class="mt-2" width="100">
            </div>

            <div class="form-group">
                <label for="image2">Ảnh 2</label>
                <input type="file" class="form-control" id="image2" name="image2">
                <img src="{{ asset($product->image2) }}" class="mt-2" width="100">
            </div>

            <div class="form-group">
                <label for="image3">Ảnh 3</label>
                <input type="file" class="form-control" id="image3" name="image3">
                <img src="{{ asset($product->image3) }}" class="mt-2" width="100">
            </div>

            <div class="form-group">
                <label for="rating">Đánh giá</label>
                <input type="number" class="form-control" id="rating" name="rating" value="{{ $product->rating }}" min="1" max="5">
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
@endsection
