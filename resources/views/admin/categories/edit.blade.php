@extends('dashboard.app')

@section('content')
<div class="container mx-auto mt-4 max-w-md">
  <h2 class="text-xl font-bold mb-4">Chỉnh sửa danh mục</h2>

  @if ($errors->any())
    <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('categories.update', $category->category_id) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
      <label for="category_name" class="block font-semibold">Tên danh mục:</label>
      <input type="text" name="category_name" id="category_name" 
        value="{{ old('category_name', $category->category_name) }}"
        class="border px-3 py-2 w-full rounded focus:outline-none focus:ring"
        required>
    </div>

    <!-- Hidden updated_at -->
    <input type="hidden" name="updated_at" value="{{ $category->updated_at }}">

    <div class="flex gap-2">
      <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
        Cập nhật
      </button>
      <a href="{{ route('categories.index') }}" class="text-gray-600 hover:underline px-4 py-2">Huỷ</a>
    </div>
  </form>
</div>
@endsection
