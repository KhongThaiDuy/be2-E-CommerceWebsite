<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserSession
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            $currentIp = $request->ip();
            $currentAgent = $request->header('User-Agent');

            // Lấy IP và User Agent đã lưu trong session
            $sessionIp = session('user_ip');
            $sessionAgent = session('user_agent');

            // Nếu chưa lưu, thì lưu lại
            if (!$sessionIp || !$sessionAgent) {
                session(['user_ip' => $currentIp]);
                session(['user_agent' => $currentAgent]);
            } else {
                // Nếu IP hoặc User Agent thay đổi so với lúc login thì logout ngay
                if ($sessionIp !== $currentIp || $sessionAgent !== $currentAgent) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect('/login')->withErrors([
                        'message' => 'Phiên đăng nhập của bạn đã bị thay đổi thiết bị hoặc IP. Vui lòng đăng nhập lại.',
                    ]);
                }
            }
        }

        return $next($request);
    }
}
