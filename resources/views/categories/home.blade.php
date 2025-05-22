@extends('dashboard.homelayouts')

@section('content')
<div class="container py-4">

 
  <h2 class="text-2xl font-bold mb-4">Danh mục: {{ $category->category_name }}</h2>

@if ($category->products->isEmpty())
  <p>Không có sản phẩm nào trong danh mục này.</p>
@else
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @foreach ($category->products as $product)
      <div class="border p-2 rounded">
        @if ($product->image1)
          <img src="{{ asset($product->image1) }}" alt="{{ $product->product_name }}" class="w-full mb-2">
        @endif

        <h3 class="font-semibold">{{ $product->product_name }}</h3>
        <p>{{ number_format($product->price, 0, ',', '.') }}₫</p>
      </div>
    @endforeach
  </div>
@endif
@endsection
