@extends('dashboard.app')

@section('content')
    <div class="container">
        <h1 class="my-4">Chỉnh sửa sản phẩm</h1>
        @if ($errors->has('general'))
            <div class="alert alert-danger">
                {{ $errors->first('general') }}
            </div>
        @endif
        <form action="{{ route('product.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="product_name">Tên sản phẩm</label>
                <input type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror"
                    value="{{ old('product_name', $product->product_name ?? '') }}">
                    <small class="text-muted">Tối đa 255 ký tự</small>
                @error('product_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="category_id">Danh mục</label>
                <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                    @foreach ($categories as $category)
                        <option value="{{ $category->category_id }}" {{ old('category_id', $product->category_id) == $category->category_id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">Giá sản phẩm</label>
                <input type="text" name="price" id="price" value="{{ old('price', $product->price ?? '') }}"
                    class="form-control @error('price') is-invalid @enderror" maxlength="9">
                    <small class="text-muted">Giá nhỏ hơn hoặc bằng 999999999</small>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="quantity">Số lượng</label>
                <input type="text" class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                    name="quantity" value="{{ old('quantity', $product->quantity) }}" maxlength="9">
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
                @if ($product->image1)
                    <img src="{{ asset( $product->image1) }}" alt="Ảnh 1" class="mt-2" width="100">
                @endif
            </div>

            <div class="form-group">
                <label for="image2">Ảnh 2</label>
                <input type="file" class="form-control @error('image2') is-invalid @enderror" id="image2" name="image2">
                @error('image2')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if ($product->image2)
                    <img src="{{ asset(  $product->image2) }}" alt="Ảnh 2" class="mt-2" width="100">
                @endif
            </div>

            <div class="form-group">
                <label for="image3">Ảnh 3</label>
                <input type="file" class="form-control @error('image3') is-invalid @enderror" id="image3" name="image3">
                @error('image3')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if ($product->image3)
                    <img src="{{ asset(  $product->image3) }}" alt="Ảnh 3" class="mt-2" width="100">
                @endif
            </div>

            <div class="form-group">
                <label for="rating">Đánh giá</label>
                <input type="text" class="form-control @error('rating') is-invalid @enderror" id="rating" name="rating"
                    value="{{ old('rating', $product->rating) }}" min="1" max="5" maxlength="1">
                @error('rating')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <input type="hidden" name="updated_at" value="{{ $product->updated_at }}">
            <button type="submit" class="btn btn-primary">Cập nhật</button>

        </form>


    </div>
@endsection

<script>
    document.querySelectorAll('input[type=number]').forEach(input => {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>