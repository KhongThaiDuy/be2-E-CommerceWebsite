@extends('dashboard.homelayouts')
@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8 text-center">Danh sách sản phẩm</h1>

    <!-- Form tìm kiếm -->
    <form action="{{ route('products.home') }}" method="GET" class="mb-8 max-w-md mx-auto">
        <input
            type="text"
            name="search"
            value="{{ old('search', $search ?? '') }}"
            placeholder="Tìm kiếm sản phẩm..."
            class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
    </form>

    <!-- Lưới sản phẩm -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($products as $product)
            <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition duration-300 flex flex-col">
                <a href="#" class="block overflow-hidden rounded-t-lg">
                    <img
                        src="{{ asset($product->image1) }}"
                        alt="{{ $product->product_name }}"
                        class="w-full h-48 object-cover transform hover:scale-105 transition duration-300"
                    />
                </a>
                <div class="p-4 flex flex-col flex-grow">
                    <a href="#" class="text-lg font-semibold mb-2 line-clamp-2 hover:text-blue-600" title="{{ $product->product_name }}">
                        {{ $product->product_name }}
                    </a>
                    <p class="text-red-600 font-bold text-xl mb-2">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                    <p class="text-gray-500 text-sm mb-2">Số lượng còn lại: {{ $product->quantity }}</p>

                    <!-- Rating: hiển thị theo hàng ngang -->
                    <div class="flex items-center space-x-1 mt-auto">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $product->rating)
                                <span class="text-yellow-400 text-lg">★</span>
                            @else
                                <span class="text-gray-300 text-lg">★</span>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-500">Không có sản phẩm nào.</p>
        @endforelse
    </div>
</div>
@endsection
