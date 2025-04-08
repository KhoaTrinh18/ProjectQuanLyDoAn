<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    DeTaiGiangVien,
    GiangVien,
    LinhVuc,
    GiangVienDeTai,
    ThietLap
};

class ThietLapController extends Controller
{
    public function them()
    {
    //     $maTaiKhoan = session()->get('ma_tai_khoan');
    //     $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
    //     $coDeTai = ($sinhVien->ma_de_tai_sv == null && $sinhVien->ma_de_tai_gv == null) ? 0 : 1;

    //     $linhVucs = LinhVuc::orderBy('ma_linh_vuc', 'desc')->get();

    //     $taikhoan = TaiKhoan::where('ma_tk', $maTaiKhoan)->first();
    //     $thietLap = ThietLap::where('nam_hoc', $taikhoan->nam_hoc)->first();
    //     $ngayHetHan = Carbon::create(2024, 5, 1)->toDateString();
    //     if(Carbon::parse($thietLap->ngay_ket_thuc_dang_ky)->lt($ngayHetHan)) {
    //         $hetHan = 1;
    //     } else {
    //         $hetHan = 0;
    //     }

        return view('admin.thietlap.them');
    }

    public function danhSach() {
        $thietLaps = ThietLap::orderBy('ma_thiet_lap', 'desc')->get();
        return view('admin.thietlap.danhSach', compact('thietLaps'));
    }
}
