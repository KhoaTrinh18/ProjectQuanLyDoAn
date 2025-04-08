<?php

namespace App\Http\Controllers\GiangVien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
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
        $deTai = DeTaiGiangVien::with(['linhVuc', 'giangViens'])->where('ma_de_tai', $ma_de_tai)->firstOrFail();
        $sinhViens = SinhVien::where('ma_de_tai_gv', $ma_de_tai)->get();
        return view('giangvien.thongtindetai.chiTiet', compact('deTai', 'sinhViens'));
    }

    public function huySinhVien(Request $request) {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $ma_sv = $request->input('ma_sv');
        $ma_de_tai = $request->input('ma_de_tai');
        Log::info("Mã sinh viên", [$ma_sv]);
        Log::info("Mã sinh viên", [$ma_de_tai]);

        $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->first();
        $deTai->so_luong_sv_dang_ky -= 1;
        $deTai->save();

        $sinhVien = SinhVien::where('ma_sv', $ma_sv)->first();
        $sinhVien->ma_de_tai_gv = null;
        $sinhVien->ngay = null;
        $sinhVien->loai_sv = null;
        $sinhVien->save();

        return response()->json([
            'success' => true,
        ]);
    }
}
