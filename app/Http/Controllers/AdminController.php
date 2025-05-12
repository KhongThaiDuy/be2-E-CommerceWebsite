<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('dashboard.admin'); // Hoặc bất kỳ view nào mà em muốn hiển thị cho admin dashboard
    }
}
