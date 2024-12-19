<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // Kiểm tra nếu user đã đăng nhập và có role phù hợp
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // Nếu không đúng role, chuyển hướng về frontend hoặc dashboard
        return redirect('/'); // Hoặc trang khác nếu cần
    }
}
