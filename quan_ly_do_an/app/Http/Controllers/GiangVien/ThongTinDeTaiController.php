<?php

namespace App\Http\Controllers\GiangVien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    BangPhanCongSVDK,
    DeTaiGiangVien,
    GiangVien,
    SinhVien,
    GiangVienDeTaiGV
};

class ThongTinDeTaiController extends Controller
{
    public function danhSachDuyet()
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
        $maDeTais = GiangVienDeTaiGV::where('ma_gv', $giangVien->ma_gv)->pluck('ma_de_tai');
        $deTais = DeTaiGiangVien::with('linhVuc')
            ->whereIn('ma_de_tai', $maDeTais)
            ->where(['da_huy' => 0, 'trang_thai' => 2])
            ->orderBy('ma_de_tai', 'desc')
            ->get();
        return view('giangvien.thongtindetai.danhSachDuyet', compact('deTais'));
    }

    public function chiTietDuyet($ma_de_tai)
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
        $deTai = DeTaiGiangVien::with(['linhVuc', 'giangViens', 'sinhViens' => function ($query) use ($giangVien) {
            $query->wherePivot('ma_gvhd', $giangVien->ma_gv);
        }])->where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('giangvien.thongtindetai.chiTiet', compact('deTai'));
    }

    public function huySinhVien(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $ma_sv = $request->input('ma_sv');
        $ma_de_tai = $request->input('ma_de_tai');

        $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->first();
        $deTai->so_luong_sv_dang_ky -= 1;
        $deTai->save();

        BangPhanCongSVDK::where(['ma_de_tai' => $ma_de_tai, 'ma_sv' => $ma_sv])->delete();

        $sinhVien = SinhVien::where('ma_sv', $ma_sv)->first();
        $sinhVien->dang_ky = 0;
        $sinhVien->loai_sv = null;
        $sinhVien->save();

        return response()->json([
            'success' => true,
        ]);
    }
}
