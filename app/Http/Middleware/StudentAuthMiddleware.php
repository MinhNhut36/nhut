<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Log;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class StudentAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {  

        if (Auth::guard('student')->check())
        {
            Log::info('thành công');
            return $next($request);
        } else {
            Log::info('thất bại');
            return redirect()->route('login');
        }
    }
}
    