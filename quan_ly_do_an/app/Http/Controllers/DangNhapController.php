<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\{
    TaiKhoan,
    SinhVien
};

class DangNhapController extends Controller
{
    public function dangNhap()
    {
        return view('dangNhap');
    }

    public function xacNhanDangNhap(Request $request)
    {
        $tenTaiKhoan = $request->input('ten_tai_khoan');
        $matKhau = $request->input('mat_khau');

        $taiKhoan = TaiKhoan::where('ten_tk', $tenTaiKhoan)->first();
        if (!$taiKhoan || $taiKhoan->mat_khau !== $matKhau) {
            return response()->json([
                'success' => false,
                'error' => 'Tên tài khoản hoặc mật khẩu không đúng!',
            ]);
        }

        Session::put('ma_tai_khoan', $taiKhoan->ma_tk);

        $route = '/';
        if ($taiKhoan->loai_tk === 'sinh_vien') {
            $sinhVien = SinhVien::where('ma_tk', $taiKhoan->ma_tk)->first();
            Session::put('ten_sinh_vien', $sinhVien ? $sinhVien->ho_ten : null);
            $route = route('dang_ky_de_tai.index');
        }

        return response()->json([
            'success' => true,
            'route' => $route,
        ]);
    }

    public function dangXuat(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $request->session()->flush(); 
        $request->session()->invalidate(); 
        $request->session()->regenerateToken();
    
        return response()->json([
            'success' => true,
            'route' => route('dang_nhap') 
        ]);
    }
}
