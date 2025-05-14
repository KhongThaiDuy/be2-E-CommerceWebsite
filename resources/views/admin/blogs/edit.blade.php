@extends('dashboard.app')

@section('content')
    <div class="container">
        <h1 class="my-4">Chỉnh sửa bài viết</h1>

        <form action="{{ route('blogs.update', $blog->post_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Tiêu đề</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $blog->title) }}" required>
                @error('title')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="content">Nội dung</label>
                <textarea class="form-control" id="content" name="content" rows="5" required>{{ old('content', $blog->content) }}</textarea>
                @error('content')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="image">Hình ảnh</label>
                <input type="file" class="form-control" id="image" name="image">
                @if ($blog->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $blog->image) }}" alt="Image" style="max-width: 200px;">
                    </div>
                @endif
                @error('image')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-warning">Cập nhật bài viết</button>
        </form>
    </div>
@endsection
