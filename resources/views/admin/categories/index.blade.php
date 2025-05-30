@extends('dashboard.app')

@section('content')
  <div class="container mx-auto mt-4">
    <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <!-- Button -->
    <a href="{{ route('categories.create') }}"
      class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full md:w-auto text-center">
      Add New Category
    </a>

    <!-- Search Form -->
    <form method="GET" action="{{ route('categories.index') }}" class="flex items-center gap-2 w-full md:w-auto">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
      class="px-4 py-2 rounded border text-sm focus:outline-none flex-1 md:w-64" />
      
      <div id="suggestions"
     class="absolute bg-white border border-gray-300 rounded mt-1 w-full md:w-64 hidden z-10 shadow-md">
</div>
    

      <button type="submit" class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600">
      <i class="fa fa-search"></i>
      </button>
    </form>
    </div>
    @if (session('success'))
    <div class="mt-2 text-green-600">{{ session('success') }}</div>
    @endif

      @if ($categories->isEmpty())
            <p class="text-gray-500 italic">No categories found.</p>
        @endif

    <table class="mt-4 w-full border">
    <thead>
      <tr>
      <th class="border px-4 py-2">ID</th>
      <th class="border px-4 py-2">Tên danh mục</th>
      <th class="border px-4 py-2">Hành động</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($categories as $category)
      <tr>
      <td class="border px-4 py-2">{{ $category->category_id }}</td>
      <td class="border px-4 py-2">{{ $category->category_name }}</td>
      <td class="border px-4 py-2">
      <a href="{{ route('categories.edit', $category->category_id) }}" class="text-blue-500">Sửa</a>
      <form action="{{ route('categories.destroy', $category->category_id) }}" method="POST" class="inline">
      @csrf
      @method('DELETE')
      <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Xoá danh mục này?')">Xoá</button>
      </form>
      </td>
      </tr>
    @endforeach
    {{ $categories->links('pagination::bootstrap-5') }}
    </tbody>
    </table>
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
          url: '{{ route("categories.suggestions") }}',
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
