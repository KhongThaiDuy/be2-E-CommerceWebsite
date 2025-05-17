@extends('dashboard.app')

@section('content')
  <div class="container mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Product Management</h1>

    <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <!-- Button -->
    <a href="{{ route('product.create') }}"
      class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full md:w-auto text-center">
      Add New Product
    </a>

    <!-- Search Form -->
    <form method="GET" action="{{ route('product.index') }}" class="flex items-center gap-2 w-full md:w-auto">
      <!-- Dropdown Sort -->
      <select name="sort" onchange="this.form.submit()"
      class="px-4 py-2 rounded border text-sm focus:outline-none w-full md:w-64">
      <option value="">-- Sắp xếp theo --</option>
      <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
      <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
      <option value="quantity_asc" {{ request('sort') === 'quantity_asc' ? 'selected' : '' }}>Số lượng tăng dần</option>
      <option value="quantity_desc" {{ request('sort') === 'quantity_desc' ? 'selected' : '' }}>Số lượng giảm dần
      </option>
      </select>

      <!-- Input Search -->
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
      class="px-4 py-2 rounded border text-sm focus:outline-none w-full md:w-64" />



      <div id="suggestions"
      class="absolute bg-white border border-gray-300 rounded mt-1 w-full md:w-64 hidden z-10 shadow-md">
      </div>

      <button type="submit" class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600">
      <i class="fa fa-search"></i>
      </button>
    </form>
    </div>

    @if (session('success'))
    <div class="bg-green-200 text-green-800 py-2 px-4 rounded mb-4">
    {{ session('success') }}
    </div>
    @endif
    @if ($products->isEmpty())
    <p class="text-gray-500 italic">No products found.</p>
    @endif

    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
      <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên sản
        phẩm</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục
        </th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng
        </th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ảnh</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo
        </th>
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
      <img src="{{ asset('storage/' . $product->image1) }}" alt="Ảnh sản phẩm"
        class="w-10 h-10 rounded object-cover">
      @else
      <span class="text-gray-400 italic">No image</span>
      @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap">{{ $product->created_at->format('d/m/Y H:i') }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <a href="{{ route('product.edit', $product->product_id) }}"
        class="text-indigo-600 hover:text-indigo-900">Sửa</a>
        <form action="{{ route('product.destroy', $product->product_id) }}" method="POST" class="inline-block">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-900 ml-2"
        onclick="return confirm('Bạn có chắc chắn muốn xoá sản phẩm này không?')">Xoá</button>
        </form>
        </td>
      </tr>
    @endforeach
      </tbody>
    </table>
    </div>
  </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    const input = $('input[name="search"]');
    const suggestionBox = $('#suggestions');

    input.on('keyup', function () {
      let query = $(this).val();

      if (query.length >= 2) {
        $.ajax({
          url: '{{ route("products.suggestions") }}',
          data: { q: query },
          success: function (data) {
            suggestionBox.empty();
            if (data.length) {
              data.forEach(item => {
                suggestionBox.append(`<div class="px-4 py-2 hover:bg-gray-200 cursor-pointer">${item}</div>`);
              });
              suggestionBox.show();
            } else {
              suggestionBox.hide();
            }
          }
        });
      } else {
        suggestionBox.hide();
      }
    });

    // Khi chọn gợi ý
    $(document).on('click', '#suggestions div', function () {
      input.val($(this).text());
      suggestionBox.hide();
    });

    // Ẩn gợi ý khi click ra ngoài
    $(document).on('click', function (e) {
      if (!$(e.target).closest('#suggestions, input[name="search"]').length) {
        suggestionBox.hide();
      }
    });
  });
</script>