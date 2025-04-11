<?php

namespace App\Http\Controllers\SinhVien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    BangPhanCongSVDK,
    SinhVien,
    DeTaiGiangVien,
    DeTaiSinhVien,
    SinhVienDeTaiSV
};

class ThongTinDeTaiController extends Controller
{
    public function thongTin()
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        $daDangKy = $sinhVien->dang_ky;

        if ($daDangKy) {
            if ($sinhVien->loai_sv == 1) {
                $sinhVienDTSV = SinhVienDeTaiSV::where('ma_sv', $sinhVien->ma_sv)->first();
                $deTai = DeTaiSinhVien::with('sinhViens')->where('ma_de_tai', $sinhVienDTSV->ma_de_tai)->first();
                $loaiDeTai = 'de_tai_sv';
            } else {
                $phanCongSVDK = BangPhanCongSVDK::where('ma_sv', $sinhVien->ma_sv)->first();
                $deTai = DeTaiGiangVien::with(['sinhViens' => function ($query) use ($phanCongSVDK) {
                    $query->wherePivot('ma_gvhd', $phanCongSVDK->ma_gvhd);
                }])->where('ma_de_tai', $phanCongSVDK->ma_de_tai)->first();
                $loaiDeTai = 'de_tai_gv';
            }
            return view('sinhvien.thongtindetai.thongTin', compact('deTai', 'loaiDeTai', 'daDangKy'));
        } else {
            return view('sinhvien.thongtindetai.thongTin', compact('daDangKy'));
        }
    }

    public function chiTiet()
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        if ($sinhVien->loai_sv == 1) {
            $sinhVienDTSV = SinhVienDeTaiSV::where('ma_sv', $sinhVien->ma_sv)->first();
            $deTai = DeTaiSinhVien::with('linhVuc')->where('ma_de_tai', $sinhVienDTSV->ma_de_tai)->first();
        } else {
            $phanCongSVDK = BangPhanCongSVDK::where('ma_sv', $sinhVien->ma_sv)->first();
            $deTai = DeTaiGiangVien::with(['linhVuc', 'giangViens'])->where('ma_de_tai', $phanCongSVDK->ma_de_tai)->first();
        }
        return view('sinhvien.thongtindetai.chiTiet', compact('deTai'));
    }

    public function huy(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $ma_de_tai = $request->input('ma_de_tai');

        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        if ($sinhVien->loai_sv == 1) {
            $deTai = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->first();
            $sinhViens = SinhVien::Where('ma_de_tai_sv', $ma_de_tai)->get();
            if ($sinhViens->count() <= 1) {
                $deTai->da_huy = 1;
                $deTai->trang_thai = null;
            }
            $deTai->save();

            $sinhVien->ma_de_tai_sv = null;
            $sinhVien->loai_sv = null;
            $sinhVien->ngay = null;
            $sinhVien->save();
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function danhSachDeTaiHuy()
    {
        $deTais = DeTaiSinhVien::with('linhVuc')->where('da_huy', 1)->orderBy('ma_de_tai', 'desc')->get();
        return view('sinhvien.thongtindetai.deTaiHuy', compact('deTais'));
    }

    public function chiTietDeTaiHuy($ma_de_tai)
    {
        $deTai = DeTaiSinhVien::with('linhVuc')->where('ma_de_tai', $ma_de_tai)->first();
        return view('sinhvien.thongtindetai.chiTietDeTaiHuy', compact('deTai'));
    }

    public function xacNhandeXuat(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $ma_de_tai = $request->input('ma_de_tai');

        $deTai = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->first();
        $deTai->da_huy = 0;
        $deTai->save();

        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        $mssvList[] = $sinhVien->mssv;
        SinhVien::whereIn('mssv', $mssvList)->update([
            'ma_de_tai_sv' => $ma_de_tai,
            'loai_sv' => 1,
            'ngay' => Carbon::now()
        ]);

        session(['co_de_tai' => 1]);

        return response()->json([
            'success' => true,
        ]);
    }
}
