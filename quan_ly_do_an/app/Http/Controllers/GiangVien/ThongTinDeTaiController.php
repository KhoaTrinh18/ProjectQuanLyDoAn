<?php

namespace App\Http\Controllers\GiangVien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    BangPhanCongSVDK,
    BangPhanCongSVDX,
    DeTaiGiangVien,
    DeTaiSinhVien,
    GiangVien,
    SinhVien,
    GiangVienDeTaiGV,
    LinhVuc,
    ThietLap
};

class ThongTinDeTaiController extends Controller
{
    public function danhSachDuyet()
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
        $thietLap = ThietLap::where('trang_thai', 1)->first();
        $ngayHetHan = Carbon::create($thietLap->ngay_ket_thuc_dang_ky)->setTime(23, 59, 59)->toIso8601String();

        $maDeTais = GiangVienDeTaiGV::where('ma_gv', $giangVien->ma_gv)->pluck('ma_de_tai');
        $deTais = DeTaiGiangVien::whereIn('ma_de_tai', $maDeTais)
            ->where(['da_huy' => 0, 'trang_thai' => 2, 'nam_hoc' => $thietLap->nam_hoc])
            ->orderBy('da_xac_nhan_huong_dan', 'asc')
            ->orderBy('ma_de_tai', 'desc')
            ->get();
        return view('giangvien.thongtindetai.danhSachDuyet', compact('deTais', 'ngayHetHan'));
    }

    public function chiTietDuyet($ma_de_tai)
    {
        $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->firstOrFail();
        $thietLap = ThietLap::where('trang_thai', 1)->first();
        $ngayHetHan = Carbon::create($thietLap->ngay_ket_thuc_dang_ky)->setTime(23, 59, 59)->toIso8601String();

        return view('giangvien.thongtindetai.chiTiet', compact('deTai', 'ngayHetHan'));
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

    public function xacNhanHuongDan(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $ma_de_tai = $request->input('ma_de_tai');

        $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->first();
        foreach ($deTai->giangViens as $giangVien) {
            $slSinhVien = $deTai->sinhViens->count() + $giangVien->sinhVienDangKys->count() +  $giangVien->sinhVienDeXuats->count();
            if ($giangVien->hocVi->sl_sinh_vien_huong_dan < $slSinhVien) {
                return response()->json([
                    'success' => false,
                    'text' => 'du_huong_dan'
                ]);
            }
        }

        if ($deTai->so_luong_sv_dang_ky > $deTai->so_luong_sv_toi_da) {
            return response()->json([
                'success' => false,
                'text' => 'vuot_muc'
            ]);
        } elseif ($deTai->so_luong_sv_dang_ky == 0) {
            return response()->json([
                'success' => false,
                'text' => 'khong_co'
            ]);
        }

        $deTai->da_xac_nhan_huong_dan = 1;
        $deTai->save();

        return response()->json([
            'success' => true,
        ]);
    }

    public function huyXacNhan(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $ma_de_tai = $request->input('ma_de_tai');

        $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->first();
        $deTai->da_xac_nhan_huong_dan = 0;
        $deTai->save();

        $maSinhViens = $deTai->sinhViens->pluck('ma_sv');

        return response()->json([
            'success' => true,
        ]);
    }

    public function danhSachHuongDan(Request $request)
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
        $thietLap = ThietLap::where('trang_thai', 1)->first();

        $phanCongSVDK = BangPhanCongSVDK::where(['ma_gvhd' => $giangVien->ma_gv])->get();
        $maDeTais = $phanCongSVDK->pluck('ma_de_tai');
        $deTaiGVs = DeTaiGiangVien::whereIn('ma_de_tai', $maDeTais)
            ->where(['da_huy' => 0, 'trang_thai' => 2, 'nam_hoc' => $thietLap->nam_hoc, 'da_xac_nhan_huong_dan' => 1])
            ->orderBy('ma_de_tai', 'desc')
            ->get();

        $phanCongSVDX = BangPhanCongSVDX::where(['ma_gvhd' => $giangVien->ma_gv])->get();
        $maDeTais = $phanCongSVDX->pluck('ma_de_tai');
        $deTaiSVs = DeTaiSinhVien::whereIn('ma_de_tai', $maDeTais)
            ->where(['da_huy' => 0, 'trang_thai' => 2, 'nam_hoc' => $thietLap->nam_hoc])
            ->orderBy('ma_de_tai', 'desc')
            ->get();

        $deTais = $deTaiSVs->merge($deTaiGVs)->unique('ma_de_tai')->values();
        return view('giangvien.thongtindetai.danhSachHuongDan', compact('deTais'));
    }

    public function pageAjax(Request $request)
    {
        $query = DeTaiGiangVien::query();

        if ($request->filled('ten_de_tai')) {
            $query->where('ten_de_tai', 'like', '%' . $request->ten_de_tai . '%');
        }

        if ($request->filled('ma_linh_vuc')) {
            $query->where('ma_linh_vuc', $request->ma_linh_vuc);
        }

        if ($request->filled('nam_hoc')) {
            $query->where('nam_hoc', $request->nam_hoc);
        }

        $maTaiKhoan = session()->get('ma_tai_khoan');
        $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
        $limit = $request->query('limit', 10);

        $maDeTais = GiangVienDeTaiGV::where('ma_gv', $giangVien->ma_gv)->pluck('ma_de_tai');
        $deTais = $query->whereIn('ma_de_tai', $maDeTais)
            ->where(['da_huy' => 0, 'trang_thai' => 2])
            ->where('so_luong_sv_dang_ky', '>', 0)
            ->orderBy('ma_de_tai', 'desc')
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'html' => view('giangvien.thongtindetai.pageAjax', compact('deTais'))->render(),
        ]);
    }

    public function chiTietHuongDan($ma_de_tai)
    {
        $deTai = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->first();

        if (!$deTai) {
            $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->first();
        }

        if (!$deTai) {
            abort(404, 'Đề tài không tồn tại');
        }

        return view('giangvien.thongtindetai.chiTietHuongDan', compact('deTai'));
    }
}
