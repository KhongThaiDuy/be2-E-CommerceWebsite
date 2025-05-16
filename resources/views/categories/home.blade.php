@extends('dashboard.homelayouts')

@section('content')
<div class="container py-4">

  <ul class="category-list mb-4">
    @foreach ($categories as $cat)
      <li class="category-item d-inline-block mr-2">
        <a href="{{ route('categories.products', $cat->category_id) }}" class="btn btn-secondary">
          {{ $cat->category_name }}
        </a>
      </li>
    @endforeach
  </ul>

  <h2 class="text-2xl font-bold mb-4">Danh mục: {{ $category->category_name }}</h2>

@if ($category->products->isEmpty())
  <p>Không có sản phẩm nào trong danh mục này.</p>
@else
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @foreach ($category->products as $product)
      <div class="border p-2 rounded">
        @if ($product->image1)
          <img src="{{ asset('storage/' . $product->image1) }}" alt="{{ $product->product_name }}" class="w-full mb-2">
        @endif

        <h3 class="font-semibold">{{ $product->product_name }}</h3>
        <p>{{ number_format($product->price, 0, ',', '.') }}₫</p>
      </div>
    @endforeach
  </div>
@endif
@endsection
