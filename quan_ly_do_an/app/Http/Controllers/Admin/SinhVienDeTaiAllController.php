<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    BangDiemGVPBChoSVDK,
    BangDiemGVPBChoSVDX,
    BangDiemGVTHDChoSVDK,
    BangDiemGVTHDChoSVDX,
    BangPhanCongSVDK,
    BangPhanCongSVDX,
    BoMon,
    DeTaiGiangVien,
    DeTaiSinhVien,
    GiangVien,
    GiangVienDeTaiGV,
    HocVi,
    HoiDongGiangVien,
    SinhVien,
    SinhVienDeTaiSV,
    TaiKhoanGV,
    TaiKhoanSV,
    ThietLap
};
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class SinhVienDeTaiAllController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);
        $thietLap = ThietLap::where('trang_thai', 1)->first();

        $sinhViens = SinhVien::orderBy('ma_sv', 'desc')->paginate($limit);

        return view('admin.sinhviendetaiall.danhSach', compact('sinhViens'));
    }

    public function pageAjax(Request $request)
    {
        $query = SinhVien::query();

        if ($request->filled('hoc_vi')) {
            $query->where('ma_hoc_vi', $request->hoc_vi);
        }

        if ($request->filled('bo_mon')) {
            $query->where('ma_bo_mon', $request->bo_mon);
        }

        $limit = $request->input('limit', 10);
        $thietLap = ThietLap::where('trang_thai', 1)->first();
        $sinhViens = $query->orderBy('ma_sv', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'html' => view('admin.sinhvien.pageAjax', compact('sinhViens'))->render(),
        ]);
    }

    public function chiTiet($ma_sv)
    {
        $sinhVien = SinhVien::where('ma_sv', $ma_sv)->firstOrFail();
        if ($sinhVien->dang_ky == 1) {
            if ($sinhVien->loai_sv == 'de_xuat') {
                $sinhVienDTSV = SinhVienDeTaiSV::where('ma_sv', $sinhVien->ma_sv)->where('trang_thai', '!=', 0)->first();
                $deTai = DeTaiSinhVien::where(['ma_de_tai' => $sinhVienDTSV->ma_de_tai, 'da_huy' => 0])->first();
            } else {
                $phanCongSVDK = BangPhanCongSVDK::where('ma_sv', $sinhVien->ma_sv)->first();
                $deTai = DeTaiGiangVien::where(['ma_de_tai' => $phanCongSVDK->ma_de_tai, 'da_huy' => 0])->first();
            }
            return view('admin.sinhVien.chiTiet', compact('sinhVien', 'deTai'));
        }
        return view('admin.sinhVien.chiTiet', compact('sinhVien'));
    }
}
