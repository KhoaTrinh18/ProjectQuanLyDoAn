<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\{
    GiangVien,
    TaiKhoanSV,
    TaiKhoanGV,
    SinhVien,
    ThietLap
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

        $thietLap = ThietLap::where('trang_thai', 1)->first();

        $taiKhoanSV = TaiKhoanSV::where(['ten_tk' => $tenTaiKhoan, 'nam_hoc' => $thietLap->nam_hoc])->first();
        $taiKhoanGV = TaiKhoanGV::where('ten_tk', $tenTaiKhoan)->first();

        if ($taiKhoanSV) {
            if (!$taiKhoanSV || $taiKhoanSV->mat_khau !== $matKhau) {
                return response()->json([
                    'success' => false,
                    'error' => 'Tên tài khoản hoặc mật khẩu không đúng!',
                ]);
            }
            Session::put('ma_tai_khoan', $taiKhoanSV->ma_tk);
        } else {
            if (!$taiKhoanGV || $taiKhoanGV->mat_khau !== $matKhau) {
                return response()->json([
                    'success' => false,
                    'error' => 'Tên tài khoản hoặc mật khẩu không đúng!',
                ]);
            }
            Session::put('ma_tai_khoan', $taiKhoanGV->ma_tk);
        }

        $route = '/';
        $homNay = now()->format('Y-m-d');

        if ($taiKhoanSV) {
            $sinhVien = SinhVien::where('ma_tk', $taiKhoanSV->ma_tk)->first();
            Session::put('ten_sinh_vien', $sinhVien->ho_ten);
            Session::put('role', 'sinhvien');
            if ($homNay > $thietLap->ngay_ket_thuc_dang_ky) {
                $route = route('thong_tin_de_tai.thong_tin');
            } else {
                $route = route('dang_ky_de_tai.danh_sach');
            }
        } else if ($taiKhoanGV->loai_tk == 'giang_vien') {
            $giangVien = GiangVien::where('ma_tk', $taiKhoanGV->ma_tk)->first();
            if ($giangVien->hocVi->ten_hoc_vi == "Thạc sĩ") {
                $hocVi = "ThS. ";
            } else {
                $hocVi = "TS. ";
            }
            Session::put('ten_giang_vien', $hocVi . $giangVien->ho_ten);
            Session::put('role', 'giangvien');
            if ($homNay > $thietLap->ngay_ket_thuc_dang_ky) {
                $route = route('thong_tin_de_tai.danh_sach_huong_dan');
            } else {
                $route = route('thong_tin_de_tai.danh_sach_duyet');
            }
        } else if ($taiKhoanGV->loai_tk == 'admin') {
            Session::put('ten_admin', 'admin');
            Session::put('role', 'admin');
            $route = route('thong_ke');
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
