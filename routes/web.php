<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProductController;


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



// Xử lý Đăng ký / Đăng nhập / Đăng xuất
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('dangxuat');

// Form hiển thị Đăng ký / Đăng nhập
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.form');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('user.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');
});



// danh mục
Route::get('/admin/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/admin/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
Route::get('/categories/{id}/products', [CategoryController::class, 'showProducts'])->name('categories.products');


//Bài viết

Route::get('/blogs', [BlogController::class, 'showAll'])->name('blogs.home');
Route::get('/blogs/{id}', [BlogController::class, 'show'])->name('blogs.show');



Route::get('/admin/blogs', [BlogController::class, 'index'])->name('blogs.index'); // Hiển thị danh sách bài viết
Route::get('/admin/blogs/create', [BlogController::class, 'create'])->name('blogs.create'); // Tạo bài viết mới
Route::post('/admin/blogs', [BlogController::class, 'store'])->name('blogs.store'); // Lưu bài viết mới
Route::get('/admin/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
Route::put('/admin/blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update'); // Cập nhật bài viết
Route::delete('/admin/blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');

//Sản phẩm

Route::get('/admin/product', [ProductController::class, 'index'])->name('product.index'); // Hiển thị danh sách sản phẩm
Route::get('/admin/product/create', [ProductController::class, 'create'])->name('product.create'); // Tạo sản phẩm mới
Route::post('/admin/product', [ProductController::class, 'store'])->name('product.store'); // Lưu sản phẩm mới
Route::get('/admin/product/{product}/edit', [ProductController::class, 'edit'])->name('product.edit'); // Chỉnh sửa sản phẩm
Route::put('/admin/product/{product}', [ProductController::class, 'update'])->name('product.update'); // Cập nhật sản phẩm
Route::delete('/admin/product/{product}', [ProductController::class, 'destroy'])->name('product.destroy'); // Xóa sản phẩm
Route::get('/products/home', [ProductController::class, 'home'])->name('products.home');

//Gợi ý tìm kiếm
Route::get('/categories/search/suggestions', [App\Http\Controllers\CategoryController::class, 'suggestions'])->name('categories.suggestions');
Route::get('/products/suggestions', [ProductController::class, 'suggestions'])->name('products.suggestions');
Route::get('/users/suggestions', [UserController::class, 'suggestions'])->name('users.suggestions');



