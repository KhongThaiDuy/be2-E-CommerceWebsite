@extends('dashboard.app')

@section('content')
<div class="container mx-auto mt-4">
  <form action="{{ route('categories.update', $category->category_id) }}" method="POST">
    @csrf
    @method('PUT')
    <label for="category_name">Tên danh mục:</label>
    <input type="text" name="category_name" id="category_name" value="{{ $category->category_name }}" class="border px-2 py-1 block w-full mt-1" required>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-2">Cập nhật</button>
  </form>
</div>
@endsection
