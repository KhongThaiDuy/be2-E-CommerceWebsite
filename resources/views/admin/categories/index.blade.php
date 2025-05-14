@extends('dashboard.app') 

@section('content')
<div class="container mx-auto mt-4">
  <a href="{{ route('categories.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Thêm danh mục</a>
  @if (session('success'))
    <div class="mt-2 text-green-600">{{ session('success') }}</div>
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
    </tbody>
  </table>
</div>
@endsection