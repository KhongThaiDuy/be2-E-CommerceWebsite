@extends('dashboard.app') 

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold mb-4">Blog Management</h1>
        
       <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Button -->
            <a href="{{ route('blogs.create') }}"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full md:w-auto text-center">
                Add New Blog
            </a>

            <!-- Search Form -->
            <form method="GET" action="{{ route('blogs.index') }}" class="flex items-center gap-2 w-full md:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                    class="px-4 py-2 rounded border text-sm focus:outline-none flex-1 md:w-64" />
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

         @if ($blogs->isEmpty())
            <p class="text-gray-500 italic">No blogs found.</p>
        @endif

        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($blogs as $blog)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $blog->post_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $blog->title }}</td>
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
    </div>
@endsection
