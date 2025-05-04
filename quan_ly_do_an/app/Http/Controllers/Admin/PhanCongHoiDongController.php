<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use App\Models\{
    BangDiemGVPBChoSVDX,
    BangDiemGVPBChoSVDK,
    BangDiemGVTHDChoSVDK,
    BangDiemGVTHDChoSVDX,
    BangPhanCongSVDK,
    BangPhanCongSVDX,
    BoMon,
    DeTaiGiangVien,
    DeTaiSinhVien,
    GiangVien,
    HoiDong,
    ThietLap
};

class PhanCongHoiDongController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);
        $thietLap = ThietLap::where('trang_thai', 1)->first();

        $maDeTaiDXs = BangDiemGVPBChoSVDX::distinct()->where('nam_hoc', $thietLap->nam_hoc)->pluck('ma_de_tai');
        $deTaiSVs = DeTaiSinhVien::whereIn('ma_de_tai', $maDeTaiDXs)->orderBy('ma_de_tai', 'desc')->get();

        $maDeTaiDKs = BangDiemGVPBChoSVDK::distinct()->where('nam_hoc', $thietLap->nam_hoc)->pluck('ma_de_tai');
        $deTaiGVs = DeTaiGiangVien::whereIn('ma_de_tai', $maDeTaiDKs)->orderBy('ma_de_tai', 'desc')->get();

        $merged = $deTaiSVs->merge($deTaiGVs)->unique('ma_de_tai')->values();

        $page = LengthAwarePaginator::resolveCurrentPage();
        $deTais = new LengthAwarePaginator(
            $merged->forPage($page, $limit),
            $merged->count(),
            $limit,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        $chuyenNganhs = BoMon::where('da_huy', 0)->orderBy('ma_bo_mon', 'desc')->get();

        return view('admin.phanconghoidong.danhSach', compact('deTais', 'chuyenNganhs'));
    }

    public function pageAjax(Request $request)
    {
        $limit = $request->query('limit', 10);
        $thietLap = ThietLap::where('trang_thai', 1)->first();

        $maDeTaiDXs = BangPhanCongSVDX::distinct()->where('nam_hoc', $thietLap->nam_hoc)->pluck('ma_de_tai');
        $deTaiSVs = DeTaiSinhVien::query()
            ->whereIn('ma_de_tai', $maDeTaiDXs)
            ->orderBy('ma_de_tai', 'desc');

        if ($request->filled('ten_de_tai')) {
            $deTaiSVs->where('ten_de_tai', 'like', '%' . $request->ten_de_tai . '%');
        }

        if ($request->filled('giang_vien_huong_dan')) {
            $deTaiSVs->whereHas('giangViens', function ($q) use ($request) {
                $q->where('giang_vien.ma_gv', $request->giang_vien_huong_dan);
            });
        }

        if ($request->filled('hoi_dong')) {
            $deTaiSVs->whereHas('hoiDongs', function ($q) use ($request) {
                $q->where('hoi_dong.ma_hoi_dong', $request->hoi_dong);
            });
        }

        if ($request->filled('sinh_vien')) {
            $deTaiSVs->whereHas('sinhViens', function ($q) use ($request) {
                $q->where('ho_ten', 'like', '%' . $request->sinh_vien . '%');
            });
        }

        if ($request->filled('trang_thai')) {
            if ($request->trang_thai == 0) {
                $deTaiSVs->whereDoesntHave('hoiDongs');
            } else {
                $deTaiSVs->whereHas('hoiDongs');
            }
        }

        $deTaiSVs = $deTaiSVs->get();

        $maDeTaiDKs = BangPhanCongSVDK::distinct()->where('nam_hoc', $thietLap->nam_hoc)->pluck('ma_de_tai');
        $deTaiGVs = DeTaiGiangVien::query()
            ->whereIn('ma_de_tai', $maDeTaiDKs)
            ->orderBy('ma_de_tai', 'desc');

        if ($request->filled('ten_de_tai')) {
            $deTaiGVs->where('ten_de_tai', 'like', '%' . $request->ten_de_tai . '%');
        }

        if ($request->filled('giang_vien_huong_dan')) {
            $deTaiGVs->whereHas('giangViens', function ($q) use ($request) {
                $q->where('giang_vien.ma_gv', $request->giang_vien_huong_dan);
            });
        }

        if ($request->filled('hoi_dong')) {
            $deTaiGVs->whereHas('hoiDongs', function ($q) use ($request) {
                $q->where('hoi_dong.ma_hoi_dong', $request->hoi_dong);
            });
        }

        if ($request->filled('sinh_vien')) {
            $deTaiGVs->whereHas('sinhViens', function ($q) use ($request) {
                $q->where('ho_ten', 'like', '%' . $request->sinh_vien . '%');
            });
        }

        if ($request->filled('trang_thai')) {
            if ($request->trang_thai == 0) {
                $deTaiGVs->whereDoesntHave('hoiDongs');
            } else {
                $deTaiGVs->whereHas('hoiDongs');
            }
        }

        $deTaiGVs = $deTaiGVs->get();

        $merged = $deTaiSVs->merge($deTaiGVs)->unique('ma_de_tai')->values();

        $page = LengthAwarePaginator::resolveCurrentPage();
        $deTais = new LengthAwarePaginator(
            $merged->forPage($page, $limit),
            $merged->count(),
            $limit,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return response()->json([
            'success' => true,
            'html' => view('admin.phanconghoidong.pageAjax', compact('deTais'))->render()
        ]);
    }

    public function chiTiet($ma_de_tai)
    {
        $deTai = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->first();

        if (!$deTai) {
            $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->first();
        }

        if (!$deTai) {
            abort(404, 'Đề tài không tồn tại');
        }

        return view('admin.phanconghoidong.chiTiet', compact('deTai'));
    }

    public function phanCong($ma_de_tai)
    {
        $deTai = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->first();

        if (!$deTai) {
            $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->first();
        }

        if (!$deTai) {
            abort(404, 'Đề tài không tồn tại');
        }

        $chuyenNganhs = BoMon::where('da_huy', 0)->orderBy('ma_bo_mon', 'desc')->get();

        return view('admin.phanconghoidong.phanCong', compact('deTai', 'chuyenNganhs'));
    }

    public function xacNhanPhanCong(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        $validator = Validator::make($data, [
            'ma_hoi_dong' => [
                'required'
            ]
        ], [
            'ma_hoi_dong.required' => 'Bạn phải chọn ít nhất một hội đồng.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        $deTai = DeTaiSinhVien::where('ma_de_tai', $data['ma_de_tai'])->first();
        $hoiDong = HoiDong::where('ma_hoi_dong', $data['ma_hoi_dong'])->first();
        $thietLap = ThietLap::where('trang_thai', 1)->first();

        if ($deTai) {
            $phanCongs = BangDiemGVPBChoSVDX::where('ma_de_tai', $data['ma_de_tai'])->get();
            foreach ($phanCongs as $phanCong) {
                foreach ($hoiDong->giangViens as $giangVien) {
                    $phanCongHoiDong = new BangDiemGVTHDChoSVDX();
                    $phanCongHoiDong->ma_sv = $phanCong->ma_sv;
                    $phanCongHoiDong->ma_gvhd = $phanCong->ma_gvhd;
                    $phanCongHoiDong->ma_de_tai = $data['ma_de_tai'];
                    $phanCongHoiDong->ma_gvpb = $phanCong->ma_gvpb;
                    $phanCongHoiDong->ma_gvthd = $giangVien->ma_gv;
                    $phanCongHoiDong->ma_hoi_dong = $data['ma_hoi_dong'];
                    $phanCongHoiDong->nam_hoc = $thietLap->nam_hoc;
                    $phanCongHoiDong->save();
                }
            }
        } else {
            $phanCongs = BangDiemGVPBChoSVDK::where('ma_de_tai', $data['ma_de_tai'])->get();
            foreach ($phanCongs as $phanCong) {
                foreach ($hoiDong->giangViens as $giangVien) {
                    $phanCongHoiDong = new BangDiemGVTHDChoSVDK();
                    $phanCongHoiDong->ma_sv = $phanCong->ma_sv;
                    $phanCongHoiDong->ma_gvhd = $phanCong->ma_gvhd;
                    $phanCongHoiDong->ma_de_tai = $data['ma_de_tai'];
                    $phanCongHoiDong->ma_gvpb = $phanCong->ma_gvpb;
                    $phanCongHoiDong->ma_gvthd = $giangVien->ma_gv;
                    $phanCongHoiDong->ma_hoi_dong = $data['ma_hoi_dong'];
                    $phanCongHoiDong->nam_hoc = $thietLap->nam_hoc;
                    $phanCongHoiDong->save();
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function sua($ma_de_tai)
    {
        $deTai = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->first();

        if (!$deTai) {
            $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->first();
        }

        if (!$deTai) {
            abort(404, 'Đề tài không tồn tại');
        }
        $chuyenNganhs = BoMon::where('da_huy', 0)->orderBy('ma_bo_mon', 'desc')->get();

        return view('admin.phanconghoidong.sua', compact('deTai', 'chuyenNganhs'));
    }

    public function xacNhanSua(Request $request)
    {

        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        $validator = Validator::make($data, [
            'ma_hoi_dong' => [
                'required'
            ]
        ], [
            'ma_hoi_dong.required' => 'Bạn phải chọn ít nhất một hội đồng.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        if (BangDiemGVTHDChoSVDK::where('ma_de_tai', $data['ma_de_tai'])->whereNotNull('diem_gvthd')->exists() || BangDiemGVTHDChoSVDX::where('ma_de_tai', $data['ma_de_tai'])->whereNotNull('diem_gvthd')->exists()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $deTai = DeTaiSinhVien::where('ma_de_tai', $data['ma_de_tai'])->first();
        $hoiDong = HoiDong::where('ma_hoi_dong', $data['ma_hoi_dong'])->first();
        $thietLap = ThietLap::where('trang_thai', 1)->first();

        if ($deTai) {
            BangDiemGVTHDChoSVDX::where('ma_de_tai', $data['ma_de_tai'])->delete();
            $phanCongs = BangDiemGVPBChoSVDX::where('ma_de_tai', $data['ma_de_tai'])->get();
            foreach ($phanCongs as $phanCong) {
                foreach ($hoiDong->giangViens as $giangVien) {
                    $phanCongHoiDong = new BangDiemGVTHDChoSVDX();
                    $phanCongHoiDong->ma_sv = $phanCong->ma_sv;
                    $phanCongHoiDong->ma_gvhd = $phanCong->ma_gvhd;
                    $phanCongHoiDong->ma_de_tai = $data['ma_de_tai'];
                    $phanCongHoiDong->ma_gvpb = $phanCong->ma_gvpb;
                    $phanCongHoiDong->ma_gvthd = $giangVien->ma_gv;
                    $phanCongHoiDong->ma_hoi_dong = $data['ma_hoi_dong'];
                    $phanCongHoiDong->nam_hoc = $thietLap->nam_hoc;
                    $phanCongHoiDong->save();
                }
            }
        } else {
            BangDiemGVTHDChoSVDK::where('ma_de_tai', $data['ma_de_tai'])->delete();
            $phanCongs = BangDiemGVPBChoSVDK::where('ma_de_tai', $data['ma_de_tai'])->get();
            foreach ($phanCongs as $phanCong) {
                foreach ($hoiDong->giangViens as $giangVien) {
                    $phanCongHoiDong = new BangDiemGVTHDChoSVDK();
                    $phanCongHoiDong->ma_sv = $phanCong->ma_sv;
                    $phanCongHoiDong->ma_gvhd = $phanCong->ma_gvhd;
                    $phanCongHoiDong->ma_de_tai = $data['ma_de_tai'];
                    $phanCongHoiDong->ma_gvpb = $phanCong->ma_gvpb;
                    $phanCongHoiDong->ma_gvthd = $giangVien->ma_gv;
                    $phanCongHoiDong->ma_hoi_dong = $data['ma_hoi_dong'];
                    $phanCongHoiDong->nam_hoc = $thietLap->nam_hoc;
                    $phanCongHoiDong->save();
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function huy($ma_de_tai)
    {
        $deTai = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->first();

        if (!$deTai) {
            $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->first();
        }

        if (!$deTai) {
            abort(404, 'Đề tài không tồn tại');
        }

        return view('admin.phanconghoidong.huy', compact('deTai'));
    }

    public function xacNhanHuy(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $ma_de_tai = $request->input('ma_de_tai');

        if (BangDiemGVTHDChoSVDK::where('ma_de_tai', $ma_de_tai)->whereNotNull('diem_gvthd')->exists() || BangDiemGVTHDChoSVDX::where('ma_de_tai', $ma_de_tai)->whereNotNull('diem_gvthd')->exists()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $deleted = BangDiemGVTHDChoSVDK::where('ma_de_tai', $ma_de_tai)->delete();

        if ($deleted === 0) {
            BangDiemGVTHDChoSVDX::where('ma_de_tai', $ma_de_tai)->delete();
        }

        return response()->json(['success' => true]);
    }
}
