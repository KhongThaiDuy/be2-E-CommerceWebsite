<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    /**
     * Handle user registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'full_name' => $request->full_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'role' => 'customer', // Mặc định là customer
            'image' => $request->image,
        ]);

        // Bạn có thể đăng nhập ngay sau khi đăng ký nếu muốn
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registration successful!'); // Chuyển hướng đến trang chủ
    }

    /**
     * Handle user login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // Xác thực thành công
            $request->session()->regenerate();

            // Kiểm tra vai trò và chuyển hướng
            if (Auth::user()->role == 'admin') {
                return redirect()->route('dashboard')->with('success', 'Login successful!'); // Chuyển hướng đến trang dashboard
            } else {
                return redirect()->route('dashboard')->with('success', 'Login successful!'); // Chuyển hướng đến trang chủ
            }
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // Thực hiện logout
        Auth::logout();

        // Xóa session và token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Chuyển hướng người dùng về trang chủ sau khi logout
        return redirect('/')->with('success', 'Logged out successfully!');
    }

     public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }
}
