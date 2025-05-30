<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class KiemTraDangNhap
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $requiredRole = null): Response
    {
        if (!Session::has('ma_tai_khoan')) {
            return redirect('/dang-nhap')->with('error', 'Bạn cần đăng nhập để tiếp tục.');
        }

        if ($requiredRole && Session::get('role') !== $requiredRole) {
            return abort(403, 'Bạn không có quyền truy cập.');
        }

        return $next($request);
    }
}
