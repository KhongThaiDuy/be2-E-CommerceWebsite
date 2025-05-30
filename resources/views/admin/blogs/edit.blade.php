@extends('dashboard.app')

@section('content')
<div class="container py-4">
    <h1 class="text-2xl font-semibold mb-4 text-warning">
        <i class="fas fa-edit me-2"></i>Chỉnh sửa bài viết: {{ $blog->title }}
    </h1>

    @if ($errors->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errors->first('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('blogs.update', $blog->post_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="updated_at" value="{{ optional($blog->updated_at)->format('Y-m-d H:i:s') }}">

        <div class="mb-3">
            <label for="title" class="form-label fw-bold text-dark">📝 Tiêu đề</label>
            <input type="text" class="form-control border-primary" id="title" name="title"
                value="{{ old('title', $blog->title) }}" required>
            @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="content" class="form-label fw-bold text-dark">📄 Nội dung</label>
            <textarea class="form-control border-success" id="content" name="content" rows="6" required>{{ old('content', $blog->content) }}</textarea>
            @error('content') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label fw-bold text-dark">🖼️ Hình ảnh</label>
            <input type="file" class="form-control border-info" id="image" name="image">
            @if ($blog->image && file_exists(public_path('storage/' . $blog->image)))
                <img src="{{ asset('storage/' . $blog->image) }}" class="img-thumbnail mt-2" style="max-width: 200px;">
            @else
                <img src="{{ asset('assets/images/default-image.png') }}" class="img-thumbnail mt-2" style="max-width: 200px;">
            @endif
            <small class="text-muted d-block mt-1">Để trống nếu không muốn thay đổi ảnh.</small>
            @error('image') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="rating" class="form-label fw-bold text-dark">⭐ Đánh giá</label>
            <input type="number" min="1" max="5" step="1" class="form-control border-warning" id="rating" name="rating"
                value="{{ old('rating', $blog->rating) }}" required>
            @error('rating') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-save me-1"></i> Cập nhật bài viết
            </button>
            <a href="{{ route('blogs.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection
