<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login.login'); // hoặc view phù hợp với em
})->name('login');

// Route đăng ký
Route::get('/register', function() {
    return view('login.register'); // hoặc view đăng ký của em
})->name('register');