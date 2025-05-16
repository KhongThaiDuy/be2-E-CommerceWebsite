@extends('dashboard.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold mb-4">User Management</h1>
        <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Button -->
            <a href="{{ route('user.create') }}"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full md:w-auto text-center">
                Add New User
            </a>

            <!-- Search Form -->
            <form method="GET" action="{{ route('user.index') }}" class="flex items-center gap-2 w-full md:w-auto">
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
            <div class="bg-green-200 text-green-800 py-2 px-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if ($users->isEmpty())
            <p class="text-gray-500 italic">No users found.</p>
        @endif

        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Images
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->full_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($user->role) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($user->image)
                                    <img src="{{ asset($user->image) }}" alt="{{ $user->username }}"
                                        class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <span class="text-gray-400 italic">No image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('user.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('user.destroy', $user) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-2"
                                        onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
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
          url: '{{ route("users.suggestions") }}',
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