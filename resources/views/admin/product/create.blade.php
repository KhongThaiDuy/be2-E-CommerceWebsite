@extends('dashboard.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold mb-4">Thêm sản phẩm</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($errors->has('general'))
            <div class="alert alert-danger">
                {{ $errors->first('general') }}
            </div>
        @endif

        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

           <div class="form-group">
                <label for="product_name">Tên sản phẩm</label>
                <input type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror"
                    value="{{ old('product_name') }}">
                @error('product_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="category_id">Danh mục</label>
                <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                    @foreach ($categories as $category)
                        <option value="{{ $category->category_id }}" {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">Giá</label>
                <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price"
                    value="{{ old('price') }}" maxlength="9">
                    <small class="text-muted">Giá nhỏ hơn hoặc bằng 999999999</small>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="quantity">Số lượng</label>
                <input type="text" class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                    name="quantity" value="{{ old('quantity') }}"maxlength="9">
                    <small class="text-muted">Số lượng nhỏ hơn hoặc bằng 999999999</small>
                @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="image1">Ảnh 1</label>
                <input type="file" class="form-control @error('image1') is-invalid @enderror" id="image1" name="image1">
                @error('image1')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

             <div class="form-group">
                <label for="image2">Ảnh 2</label>
                <input type="file" class="form-control @error('image2') is-invalid @enderror" id="image2" name="image2">
                @error('image2')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="image3">Ảnh 3</label>
                <input type="file" class="form-control @error('image3') is-invalid @enderror" id="image3" name="image3">
                @error('image3')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="rating">Đánh giá</label>
                <input type="text" class="form-control @error('rating') is-invalid @enderror" id="rating"
                    name="rating" value="{{ old('rating') }}">
                @error('rating')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Lưu
            </button>
        </form>
    </div>
@endsection