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

class ChamDiemDeTaiController extends Controller
{
    public function danhSachHuongDan()
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
        $maDeTais = GiangVienDeTaiGV::where('ma_gv', $giangVien->ma_gv)->pluck('ma_de_tai');
        $deTais = DeTaiGiangVien::with(['linhVuc', 'sinhViens'])
            ->whereIn('ma_de_tai', $maDeTais)
            ->where(['da_huy' => 0, 'trang_thai' => 2])
            ->where('so_luong_sv_dang_ky', '>', 0)
            ->orderBy('ma_de_tai', 'desc')
            ->get();

        return view('giangvien.chamdiemdetai.danhSachHuongDan', compact('deTais'));
    }

    public function chiTietHuongDan($ma_de_tai) {
        $deTai = DeTaiGiangVien::with(['linhVuc', 'giangViens', 'sinhViens'])->where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('giangvien.chamdiemdetai.chiTietHuongDan', compact('deTai'));
    }

    public function chamDiem() {}
}
