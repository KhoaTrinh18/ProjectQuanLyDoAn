<?php

namespace App\Http\Middleware;

use App\Models\TaiKhoanGV;
use App\Models\TaiKhoanSV;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class KiemTraDoiMatKhau
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $requiredRole = null): Response
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $taiKhoan = TaiKhoanSV::where('ma_tk', $maTaiKhoan)->first();
        if($taiKhoan->da_dang_nhap == 0) {
            return redirect('/dang-ky-de-tai')->with('error', 'Bạn phải đổi mật khẩu để tiếp tục.');
        }

        return $next($request);
    }
}
