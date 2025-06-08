<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class LogoutIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Auth::logout();
        // hủy phiên làm việc của người dùng và tạo lại token CSRF để bảo mật
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $next($request);
    }
}
