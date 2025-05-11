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

        $sinhViens = SinhVien::orderBy('ma_sv', 'desc')->where('trang_thai', '!=', 1)->paginate($limit);
        $thietLaps = ThietLap::orderBy('ma_thiet_lap', 'desc')->get();
        $chuyenNganhs = BoMon::where('da_huy', 0)->orderBy('ma_bo_mon', 'desc')->get();

        return view('admin.sinhviendetaiall.danhSach', compact('sinhViens', 'chuyenNganhs', 'thietLaps'));
    }

    public function pageAjax(Request $request)
    {
        $query = SinhVien::query();

        if ($request->filled('sinh_vien')) {
            $query->where('ho_ten', 'like', '%' . $request->sinh_vien . '%');
        }

        if ($request->filled('mssv')) {
            $query->where('mssv', $request->mssv);
        }

        if ($request->filled('lop')) {
            $query->where('lop', 'like', '%' . $request->lop . '%');
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('ten_de_tai')) {
            $tuKhoa = strtolower($request->ten_de_tai);

            $query->whereHas('deTaiDeXuat', function ($q) use ($tuKhoa) {
                $q->where('ten_de_tai', 'like', '%' . $tuKhoa . '%');
            });

            $query->orWhereHas('deTaiDangKy', function ($q) use ($tuKhoa) {
                $q->where('ten_de_tai', 'like', '%' . $tuKhoa . '%');
            });
        }

        if ($request->filled('giang_vien')) {
            $query->whereHas('deTaiDeXuat', function ($q) use ($request) {
                $q->whereHas('giangViens', function ($q2) use ($request) {
                    $q2->where('giang_vien.ma_gv', $request->giang_vien);
                });
            });

            $query->orWhereHas('deTaiDangKy', function ($q) use ($request) {
                $q->whereHas('giangViens', function ($q2) use ($request) {
                    $q2->where('giang_vien.ma_gv', $request->giang_vien);
                });
            });
        }

        if ($request->filled('nam_hoc')) {
            $query->where('nam_hoc', $request->nam_hoc);
        }

        $limit = $request->input('limit', 10);
        $sinhViens = $query->orderBy('ma_sv', 'desc')->where('trang_thai', '!=', 1)->paginate($limit);

        return response()->json([
            'success' => true,
            'html' => view('admin.sinhviendetaiall.pageAjax', compact('sinhViens'))->render(),
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
            return view('admin.sinhviendetaiall.chiTiet', compact('sinhVien', 'deTai'));
        }
        return view('admin.sinhviendetaiall.chiTiet', compact('sinhVien'));
    }
}
