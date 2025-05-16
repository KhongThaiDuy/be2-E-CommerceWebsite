@extends('dashboard.app')

@section('content')
<div class="container py-4">
    <h1 class="text-2xl font-semibold mb-4">User Management</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <form action="{{ route('user.index') }}" method="GET" class="row g-2">
            <div class="col">
                <input type="text" name="keyword" class="form-control" placeholder="Tìm tên hoặc email" value="{{ request('keyword') }}">
            </div>
            <div class="col">
                <select name="sort" class="form-select">
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>ID tăng dần</option>
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>ID giảm dần</option>
                </select>
            </div>

            <div class="col">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>

        <a href="{{ route('user.create') }}" class="btn btn-success">Add New User</a>
    </div>

    <div class="table-responsive shadow-sm">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Image</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>
                            @if ($user->image)
                                <img src="{{ asset($user->image) }}" alt="{{ $user->username }}" class="rounded-circle" width="40" height="40">
                            @else
                                <span class="text-muted fst-italic">No image</span>
                            @endif
                        </td>
                        <td class="text-end">
                        <a href="{{ route('user.edit', $user->hash_id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('user.destroy', $user->hash_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Không có người dùng nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
