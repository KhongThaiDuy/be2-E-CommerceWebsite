<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all(); // Lấy tất cả danh mục
        return view('home', compact('categories')); // hoặc 'welcome' tùy file bạn dùng
    }
}
