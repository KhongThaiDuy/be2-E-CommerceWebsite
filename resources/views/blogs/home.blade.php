@extends('dashboard.homelayouts')

@section('content')
<br>
<div class="container mt-5">
    <h2 class="mb-4 text-center text-primary">📰 Tất cả Bài Viết</h2>

    <div class="row g-4">
        @forelse($blogs as $blog)
            <div class="col-md-4 d-flex align-items-stretch">
                <div class="card h-100 shadow-sm border-0">
                    @if($blog->image)
                        <img src="{{ asset($blog->image) }}" class="card-img-top" alt="{{ $blog->title }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary">{{ $blog->title }}</h5>
                        <p class="card-text text-muted mb-2">{{ Str::limit(strip_tags($blog->content), 100) }}</p>
                        <p class="small text-secondary mb-1">
                            👤 {{ $blog->user->full_name ?? 'Không rõ' }} |
                            ⭐ {{ $blog->rating }} |
                            📅 {{ $blog->created_at->format('d/m/Y') }}
                        </p>
                        <a href="{{ route('blogs.show', ['id' => $blog->post_id]) }}" class="btn btn-outline-primary btn-sm mt-auto">Đọc tiếp</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p class="text-muted">Không có bài viết nào.</p>
            </div>
        @endforelse
        <div class="mt-4 d-flex justify-content-center">
            {{ $blogs->links() }}
        </div>
    </div>
</div>
@endsection
