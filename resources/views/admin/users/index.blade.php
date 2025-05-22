@extends('dashboard.app')

@section('content')

@if ($errors->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $errors->first('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container py-4">
    <h1 class="text-2xl font-semibold mb-4">User Management</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <form action="{{ route('user.index') }}" method="GET" class="row g-2">
        <div class="col position-relative">
            <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Tìm tên hoặc email" value="{{ request('keyword') }}" autocomplete="off">
            <ul id="suggestions-list" class="list-group position-absolute" style="z-index: 999; width: 100%; max-height: 200px; overflow-y: auto;"></ul>
        </div>
            <div class="col">
                <select name="role" class="form-select">
                    <option value="">Tất cả vai trò</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
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
                        <form action="{{ route('user.destroy', $user->hash_id) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
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

@push('scripts')
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!confirm('Bạn có chắc muốn xoá người dùng này không?')) {
                e.preventDefault();
            }
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#keyword').on('input', function() {
        let query = $(this).val().trim();

        if(query.length < 2) {
            $('#suggestions-list').empty();
            return;
        }

        $.ajax({
            url: "{{ route('user.suggestions') }}",
            method: 'GET',
            data: { keyword: query },
            success: function(data) {
                let html = '';
                if(data.length > 0) {
                    data.forEach(item => {
                        // Lưu luôn value dưới dạng data-value để dùng khi click
                        html += `<li class="list-group-item suggestion-item" style="cursor:pointer;" data-value="${item.value}">${item.label}</li>`;
                    });
                } else {
                    html = '<li class="list-group-item text-muted">Không tìm thấy kết quả</li>';
                }
                $('#suggestions-list').html(html);
            },
            error: function() {
                $('#suggestions-list').empty();
            }
        });
    });

    // Khi click chọn gợi ý
    $(document).on('click', '.suggestion-item', function() {
    // Lấy value đã lưu trong data-value
    let val = $(this).data('value');
    $('#keyword').val(val);
    $('#suggestions-list').empty();
    });

    // Click ngoài ẩn gợi ý
    $(document).click(function(e) {
        if(!$(e.target).closest('#keyword, #suggestions-list').length) {
            $('#suggestions-list').empty();
        }
    });
    
});
</script>

@endpush


