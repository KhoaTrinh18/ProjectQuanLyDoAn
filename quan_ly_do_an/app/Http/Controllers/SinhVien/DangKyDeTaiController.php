<?php

namespace App\Http\Controllers\SinhVien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    BangPhanCongSVDK,
    DeTaiGiangVien,
    SinhVien,
    LinhVuc,
    TaiKhoanSV,
    ThietLap
};

class DangKyDeTaiController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);

        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        $daDangKy = $sinhVien->dang_ky;

        $linhVucs = LinhVuc::orderBy('ma_linh_vuc', 'desc')->get();
        $deTais = DeTaiGiangVien::where(['da_huy' => 0, 'trang_thai' => 2])->orderBy('ma_de_tai', 'desc')->paginate($limit);

        $taikhoan = TaiKhoanSV::where('ma_tk', $maTaiKhoan)->first();
        $thietLap = ThietLap::where('nam_hoc', $taikhoan->nam_hoc)->first();
        $ngayHetHan = Carbon::create(2024, 5, 1)->toDateString();
        if (Carbon::parse($thietLap->ngay_ket_thuc_dang_ky)->lt($ngayHetHan)) {
            $hetHan = 1;
        } else {
            $hetHan = 0;
        }

        return view('sinhvien.dangkydetai.danhSach', compact('deTais', 'linhVucs', 'daDangKy', 'hetHan'));
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

        if ($request->filled('giang_vien')) {
            $query->whereHas('giangViens', function ($q) use ($request) {
                $q->where('ho_ten', 'like', '%' . $request->giang_vien . '%');
            });
        }

        if ($request->filled('trang_thai')) {
            if ($request->trang_thai == 1) {
                $query->where('so_luong_sv_dang_ky', '>', 0);
            } elseif ($request->trang_thai == 0) {
                $query->where('so_luong_sv_dang_ky', 0);
            }
        }

        $limit = $request->input('limit', 10);
        $deTais = $query->where(['da_huy' => 0, 'trang_thai' => 2])->orderBy('ma_de_tai', 'desc')->paginate($limit);

        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        $daDangKy = $sinhVien->dang_ky;

        return response()->json([
            'success' => true,
            'html' => view('sinhvien.dangkydetai.pageAjax', compact('deTais', 'daDangKy'))->render(),
        ]);
    }

    public function dangKy($ma_de_tai)
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        $daDangKy = $sinhVien->dang_ky;

        $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->firstOrFail();

        return view('sinhvien.dangkydetai.dangKy', compact('deTai', 'daDangKy'));
    }

    public function xacNhanDangKy(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        $deTaiGV = DeTaiGiangVien::where('ma_de_tai', $data['ma_de_tai'])->first();
        $deTaiGV->so_luong_sv_dang_ky += 1;
        $deTaiGV->save();

        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        $sinhVien->dang_ky = 1;
        $sinhVien->loai_sv = 'dang_ky';
        $sinhVien->save();

        $phanCongSVDKs = [];
        foreach ($deTaiGV->giangViens as $giangVien) {
            $phanCongSVDKs[] = [
                'ma_sv' => $sinhVien->ma_sv,
                'ma_gvhd' => $giangVien->ma_gv,
                'ma_de_tai' => $data['ma_de_tai'],
                'ngay_dang_ky' => now()->toDateString(),
            ];
        }
        BangPhanCongSVDK::insert($phanCongSVDKs);

        return response()->json(['success' => true]);
    }
}
