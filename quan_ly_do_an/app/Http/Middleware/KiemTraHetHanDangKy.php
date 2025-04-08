<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use App\Models\{
    TaiKhoanSV,
    ThietLap
};

class KiemTraHetHanDangKy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $taikhoan = TaiKhoanSV::where('ma_tk', $maTaiKhoan)->first();
        $thietLap = ThietLap::where('nam_hoc', $taikhoan->nam_hoc)->first();
        $ngayHetHan = Carbon::create(2024, 5, 1)->toDateString();
        if(Carbon::parse($thietLap->ngay_ket_thuc_dang_ky)->lt($ngayHetHan)) {
            return redirect('/dang-ky-de-tai')->with('error', 'Bạn cần đăng nhập để tiếp tục.');
        }
        return $next($request);
    }
}
