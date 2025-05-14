@extends('dashboard.app')

@section('content')
<div class="container mx-auto mt-4">
  <form action="{{ route('categories.store') }}" method="POST">
    @csrf
    <label for="category_name">Tên danh mục:</label>
    <input type="text" name="category_name" id="category_name" class="border px-2 py-1 block w-full mt-1" required>
    <button type="submit" class="bg-green-500 text-white px-4 py-2 mt-2">Thêm</button>
  </form>
</div>
@endsection
