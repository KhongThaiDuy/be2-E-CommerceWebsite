@extends('dashboard.homelayouts')

@section('content')
<br>
<div class="container mt-5">
    <h1 class="mb-3">{{ $blog->title }}</h1>
    <p class="text-secondary mb-3">👤 {{ $blog->user->full_name ?? 'Không rõ' }} | 📅 {{ $blog->created_at->format('d/m/Y') }}</p>
    @if($blog->image)
        <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" class="img-fluid mb-4" style="max-height: 400px; object-fit: cover;">
    @endif
    <div>{!! $blog->content !!}</div>
    <a href="{{ route('blogs.home') }}" class="btn btn-outline-secondary mt-4">Quay lại danh sách</a>
</div>
@endsection
