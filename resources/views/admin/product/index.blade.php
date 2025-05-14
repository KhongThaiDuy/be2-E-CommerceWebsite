@extends('dashboard.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold mb-4">Product Management</h1>

        <div class="mb-4">
            <a href="{{ route('product.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Add New Product
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-200 text-green-800 py-2 px-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên sản phẩm</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ảnh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                        <th class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->product_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->category->category_name ?? 'Không có' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($product->price) }} VND</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($product->image1)
                                    <img src="{{ asset('storage/' . $product->image1) }}" alt="Ảnh sản phẩm" class="w-10 h-10 rounded object-cover">
                                @else
                                    <span class="text-gray-400 italic">No image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('product.edit', $product->product_id) }}" class="text-indigo-600 hover:text-indigo-900">Sửa</a>
                                <form action="{{ route('product.destroy', $product->product_id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-2" onclick="return confirm('Bạn có chắc chắn muốn xoá sản phẩm này không?')">Xoá</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
