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
    BangPhanCongSVDK,
    BangPhanCongSVDX,
    BoMon,
    DeTaiGiangVien,
    DeTaiSinhVien,
    GiangVien
};

class PhanCongPhanBienController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);

        $maDeTaiDXs = BangPhanCongSVDX::distinct()->pluck('ma_de_tai');
        $deTaiSVs = DeTaiSinhVien::whereIn('ma_de_tai', $maDeTaiDXs)->orderBy('ma_de_tai', 'desc')->get();

        $maDeTaiDKs = BangPhanCongSVDK::distinct()->pluck('ma_de_tai');
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

        return view('admin.phancongphanbien.danhSach', compact('deTais', 'chuyenNganhs'));
    }

    public function pageAjax(Request $request)
    {
        $limit = $request->query('limit', 10);

        $maDeTaiDXs = BangPhanCongSVDX::distinct()->pluck('ma_de_tai');
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

        if ($request->filled('giang_vien_phan_bien')) {
            $deTaiSVs->whereHas('giangVienPhanBiens', function ($q) use ($request) {
                $q->where('giang_vien.ma_gv', $request->giang_vien_phan_bien);
            });
        }

        if ($request->filled('sinh_vien')) {
            $deTaiSVs->whereHas('sinhViens', function ($q) use ($request) {
                $q->where('ho_ten', 'like', '%' . $request->sinh_vien . '%');
            });
        }

        if ($request->filled('trang_thai')) {
            if ($request->trang_thai == 0) {
                $deTaiSVs->whereDoesntHave('giangVienPhanBiens');
            } else {
                $deTaiSVs->whereHas('giangVienPhanBiens');
            }
        }

        $deTaiSVs = $deTaiSVs->get();

        $maDeTaiDKs = BangPhanCongSVDK::distinct()->pluck('ma_de_tai');
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

        if ($request->filled('giang_vien_phan_bien')) {
            $deTaiGVs->whereHas('giangVienPhanBiens', function ($q) use ($request) {
                $q->where('giang_vien.ma_gv', $request->giang_vien_phan_bien);
            });
        }

        if ($request->filled('sinh_vien')) {
            $deTaiGVs->whereHas('sinhViens', function ($q) use ($request) {
                $q->where('ho_ten', 'like', '%' . $request->sinh_vien . '%');
            });
        }

        if ($request->filled('trang_thai')) {
            if ($request->trang_thai == 0) {
                $deTaiGVs->whereDoesntHave('giangVienPhanBiens');
            } else {
                $deTaiGVs->whereHas('giangVienPhanBiens');
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
            'html' => view('admin.phancongphanbien.pageAjax', compact('deTais'))->render()
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

        return view('admin.phancongphanbien.chiTiet', compact('deTai'));
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

        return view('admin.phancongphanbien.phanCong', compact('deTai', 'chuyenNganhs'));
    }

    public function xacNhanPhanCong(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        $validator = Validator::make($data, [
            'ma_gvpb' => [
                'required'
            ]
        ], [
            'ma_gvpb.required' => 'Bạn phải chọn ít nhất một giảng viên.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        $deTai = DeTaiSinhVien::where('ma_de_tai', $data['ma_de_tai'])->first();
        if ($deTai) {
            $phanCongs = BangPhanCongSVDX::where('ma_de_tai', $data['ma_de_tai'])->get();
            foreach ($phanCongs as $phanCong) {
                $phanCongPhanBien = new BangDiemGVPBChoSVDX();
                $phanCongPhanBien->ma_sv = $phanCong->ma_sv;
                $phanCongPhanBien->ma_gvhd = $phanCong->ma_gvhd;
                $phanCongPhanBien->ma_de_tai = $data['ma_de_tai'];
                $phanCongPhanBien->ma_gvpb = $data['ma_gvpb'];
                $phanCongPhanBien->save();
            }
        } else {
            $phanCongs = BangPhanCongSVDK::where('ma_de_tai', $data['ma_de_tai'])->get();
            foreach ($phanCongs as $phanCong) {
                $phanCongPhanBien = new BangDiemGVPBChoSVDK();
                $phanCongPhanBien->ma_sv = $phanCong->ma_sv;
                $phanCongPhanBien->ma_gvhd = $phanCong->ma_gvhd;
                $phanCongPhanBien->ma_de_tai = $data['ma_de_tai'];
                $phanCongPhanBien->ma_gvpb = $data['ma_gvpb'];
                $phanCongPhanBien->save();
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

        return view('admin.phancongphanbien.sua', compact('deTai', 'chuyenNganhs'));
    }

    public function xacNhanSua(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        $validator = Validator::make($data, [
            'ma_gvpb' => [
                'required'
            ]
        ], [
            'ma_gvpb.required' => 'Bạn phải chọn ít nhất một giảng viên.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        $updated = BangDiemGVPBChoSVDK::where('ma_de_tai', $data['ma_de_tai'])->update(['ma_gvpb' => $data['ma_gvpb']]);

        if ($updated === 0) {
            BangDiemGVPBChoSVDX::where('ma_de_tai', $data['ma_de_tai'])->update(['ma_gvpb' => $data['ma_gvpb']]);
        }

        return response()->json(['success' => true]);
    }
}
