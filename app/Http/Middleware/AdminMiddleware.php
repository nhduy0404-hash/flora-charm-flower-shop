<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập tài khoản Quản trị viên để tiếp tục!');
        }

        if (!Auth::user()->isAdmin()) {
            abort(403, 'Truy cập bị từ chối: Bạn không có quyền Quản trị viên để truy cập khu vực này!');
        }

        return $next($request);
    }
}
