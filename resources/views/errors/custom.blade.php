@extends('dashboard.app')

@section('title', 'Lỗi')

@section('content')
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold text-red-600 mb-4">Lỗi: Không tìm thấy dữ liệu</h1>
        <p>{{ $message }}</p>
        <a href="{{ route('user.index') }}" class="text-blue-500 underline mt-6 inline-block">Quay lại danh sách người dùng</a>
    </div>
@endsection
