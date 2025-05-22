@extends('dashboard.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold mb-4">Edit User: {{ $user->full_name }}</h1>
        <form action="{{ route('user.update', ['user' => $user->hash_id]) }}" method="POST">
        <input type="hidden" name="updated_at" value="{{ optional($user->updated_at)->toISOString() }}">
        @csrf
            @method('PUT')
            @if ($errors->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative my-4">
                    <strong>Lỗi:</strong> {{ $errors->first('error') }}
                </div>
            @endif
            <div>
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
                <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('username') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password (leave blank to keep current):</label>
                <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('password') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div>
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('email') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="full_name" class="block text-gray-700 text-sm font-bold mb-2">Full Name:</label>
                <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('full_name') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address:</label>
                <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('address') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone:</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('phone') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role:</label>
                <select id="role" name="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Image:</label>
                <input type="file" id="image" name="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @if ($user->image)
                    <img src="{{ asset('storage/' . $user->image) }}" alt="Current Image" class="mt-2 w-20 h-20 rounded-full">
                @endif
                <small class="text-gray-500">Leave blank to keep the current image.</small>
                @error('image') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update User
            </button>
        </form>
    </div>
@endsection