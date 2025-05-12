<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

// Trang chủ
Route::get('/', function () {
    return view('home');
})->name('home');

// Dashboard (admin và user đều vào đây)
Route::get('/dashboard', function () {
    return view('dashboard.homecrub');  // Cho user bình thường
})->middleware('auth')->name('dashboard');

// Dashboard cho admin
Route::get('/admin-dashboard', [AdminController::class, 'index'])->name('admin-dashboard');

// Trang homecrub cho user bình thường
Route::get('/dashboard/homecrub', function () {
    return view('dashboard.homecrub');
})->name('homecrub');

// Hiển thị form đăng nhập
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login.form');

// Xử lý Đăng ký / Đăng nhập / Đăng xuất
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('dangxuat');

// Form hiển thị Đăng ký / Đăng nhập
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.form');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');

Route::middleware(['auth'])->group(function () { // Assuming you have authentication set up
    Route::get('/admin/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('user.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');
});