
@extends('dashboard.app') 

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Blog Management</h1>
    <div class="mb-4">
        <form action="{{ route('blogs.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" placeholder="🔍 Tìm tiêu đề bài viết..." value="{{ request('search') }}"
                class="form-control w-auto border-primary">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
            <a href="{{ route('blogs.index') }}" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Xoá lọc
            </a>
        </form>
    </div>

    <div class="mb-4">
        <a href="{{ route('blogs.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Add New Blog
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                    <th class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($blogs as $blog)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $blog->post_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $blog->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($blog->image)
                                <img src="{{ asset($blog->image) }}" alt="Blog Image" class="w-16 h-16 object-cover rounded">
                            @else
                                <span class="text-gray-400 italic">No image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $blog->user->full_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $blog->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('blogs.edit', $blog) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('blogs.destroy', $blog) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2" onclick="return confirm('Are you sure you want to delete this blog?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4 d-flex justify-content-center">
            {{ $blogs->links() }}
        </div>
</div>
@endsection
