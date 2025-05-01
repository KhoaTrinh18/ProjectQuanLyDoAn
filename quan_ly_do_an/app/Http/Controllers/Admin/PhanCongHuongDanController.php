<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use App\Models\{
    BangPhanCongSVDK,
    BangPhanCongSVDX,
    BoMon,
    DeTaiGiangVien,
    DeTaiSinhVien,
    GiangVien
};

class PhanCongHuongDanController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);

        $deTaiSVs = DeTaiSinhVien::where(['da_huy' => 0, 'trang_thai' => 2])->orderBy('ma_de_tai', 'desc')->get();

        $maDeTais = BangPhanCongSVDK::distinct()->pluck('ma_de_tai');
        $deTaiGVs = DeTaiGiangVien::whereIn('ma_de_tai', $maDeTais)->orderBy('ma_de_tai', 'desc')->get();

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

        return view('admin.phanconghuongdan.danhSach', compact('deTais', 'chuyenNganhs'));
    }

    public function pageAjax(Request $request)
    {
        $limit = $request->query('limit', 10);

        $deTaiSVs = DeTaiSinhVien::query()
            ->where(['da_huy' => 0, 'trang_thai' => 2])
            ->orderBy('ma_de_tai', 'desc');

        if ($request->filled('ten_de_tai')) {
            $deTaiSVs->where('ten_de_tai', 'like', '%' . $request->ten_de_tai . '%');
        }

        if ($request->filled('giang_vien')) {
            $deTaiSVs->whereHas('giangViens', function ($q) use ($request) {
                $q->where('giang_vien.ma_gv', $request->giang_vien);
            });
        }

        if ($request->filled('sinh_vien')) {
            $deTaiSVs->whereHas('sinhViens', function ($q) use ($request) {
                $q->where('ho_ten', 'like', '%' . $request->sinh_vien . '%');
            });
        }

        if ($request->filled('trang_thai')) {
            if ($request->trang_thai == 0) {
                $deTaiSVs->whereDoesntHave('giangViens');
            } else {
                $deTaiSVs->whereHas('giangViens');
            }
        }

        $deTaiSVs = $deTaiSVs->get();

        $maDeTais = BangPhanCongSVDK::distinct()->pluck('ma_de_tai');
        $deTaiGVs = DeTaiGiangVien::query()
            ->whereIn('ma_de_tai', $maDeTais)
            ->orderBy('ma_de_tai', 'desc');

        if ($request->filled('ten_de_tai')) {
            $deTaiGVs->where('ten_de_tai', 'like', '%' . $request->ten_de_tai . '%');
        }

        if ($request->filled('giang_vien')) {
            $deTaiGVs->whereHas('giangViens', function ($q) use ($request) {
                $q->where('giang_vien.ma_gv', $request->giang_vien);
            });
        }

        if ($request->filled('sinh_vien')) {
            $deTaiGVs->whereHas('sinhViens', function ($q) use ($request) {
                $q->where('ho_ten', 'like', '%' . $request->sinh_vien . '%');
            });
        }

        if ($request->filled('trang_thai')) {
            if ($request->trang_thai == 0) {
                $deTaiGVs->whereDoesntHave('giangViens');
            } else {
                $deTaiGVs->whereHas('giangViens');
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
            'html' => view('admin.phanconghuongdan.pageAjax', compact('deTais'))->render()
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

        return view('admin.phanconghuongdan.chiTiet', compact('deTai'));
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

        $chuyenNganhs = BoMon::with('giangViens')->where('da_huy', 0)->orderBy('ma_bo_mon', 'desc')->get();
        Log::info($chuyenNganhs);

        return view('admin.phanconghuongdan.phanCong', compact('deTai', 'chuyenNganhs'));
    }

    public function xacNhanPhanCong(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        $validator = Validator::make($data, [
            'giang_vien.*' => [
                'sometimes'
            ]
        ]);
        $giangVienList = [];
        $validator->after(function ($validator) use ($request, &$giangVienList) {
            $giangVienList = array_filter($request->input('DeTai', [])['giang_vien'], function ($value) {
                return trim(strip_tags($value)) !== "";
            });

            if (empty($giangVienList)) {
                $validator->errors()->add('giangvien', 'Bạn phải chọn ít nhất một giảng viên.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        $deTai = DeTaiSinhVien::where('ma_de_tai', $data['ma_de_tai'])->first();
        foreach ($deTai->sinhViens as $sinhVien) {
            foreach ($giangVienList as $giangVien) {
                $phanCong = new BangPhanCongSVDX();
                $phanCong->ma_sv = $sinhVien->ma_sv;
                $phanCong->ma_gvhd = $giangVien;
                $phanCong->ma_de_tai = $data['ma_de_tai'];
                $phanCong->save();
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

        $giangViens = GiangVien::get();

        return view('admin.phanconghuongdan.sua', compact('deTai', 'giangViens'));
    }

    public function xacNhanSua(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        $validator = Validator::make($data, [
            'giang_vien.*' => [
                'sometimes'
            ]
        ]);
        $giangVienList = [];
        $validator->after(function ($validator) use ($request, &$giangVienList) {
            $giangVienList = array_filter($request->input('DeTai', [])['giang_vien'], function ($value) {
                return trim(strip_tags($value)) !== "";
            });

            if (empty($giangVienList)) {
                $validator->errors()->add('giangvien', 'Bạn phải chọn ít nhất một giảng viên.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        $deTai = DeTaiSinhVien::where('ma_de_tai', $data['ma_de_tai'])->first();

        BangPhanCongSVDX::where('ma_de_tai', $data['ma_de_tai'])->delete();
        foreach ($deTai->sinhViens as $sinhVien) {
            foreach ($giangVienList as $giangVien) {
                $phanCong = new BangPhanCongSVDX();
                $phanCong->ma_sv = $sinhVien->ma_sv;
                $phanCong->ma_gvhd = $giangVien;
                $phanCong->ma_de_tai = $data['ma_de_tai'];
                $phanCong->save();
            }
        }

        return response()->json(['success' => true]);
    }
}
