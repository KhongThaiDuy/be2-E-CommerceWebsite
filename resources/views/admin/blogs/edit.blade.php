@extends('dashboard.app')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4 text-warning"><i class="fas fa-edit me-2"></i>Chỉnh sửa bài viết</h1>

        <form action="{{ route('blogs.update', $blog->post_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label fw-bold text-dark">📝 Tiêu đề</label>
                <input type="text" class="form-control border-primary" id="title" name="title" value="{{ old('title', $blog->title) }}" required>
                @error('title')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="content" class="form-label fw-bold text-dark">📄 Nội dung</label>
                <textarea class="form-control border-success" id="content" name="content" rows="5" required>{{ old('content', $blog->content) }}</textarea>
                @error('content')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label fw-bold text-dark">🖼️ Hình ảnh</label>
                <input type="file" class="form-control border-info" id="image" name="image">
                @if ($blog->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $blog->image) }}" alt="Image" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                @endif
                @error('image')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="rating" class="form-label fw-bold text-dark">⭐ Đánh giá (1-5)</label>
                <input type="number" class="form-control border-warning" id="rating" name="rating" min="1" max="5" value="{{ old('rating', $blog->rating) }}" required>
                @error('rating')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
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
