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
    DeTaiGiangVien,
    DeTaiSinhVien,
    GiangVien,
    LinhVuc,
    GiangVienDeTai,
    ThietLap
};

class PhanCongHuongDanController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);

        $deTaiSVs = DeTaiSinhVien::with(['sinhViens', 'giangViens'])->where(['da_huy' => 0, 'trang_thai' => 2])->get();

        $maDeTais = BangPhanCongSVDK::distinct()->pluck('ma_de_tai');
        $deTaiGVs = DeTaiGiangVien::with(['sinhViens', 'giangViens'])->whereIn('ma_de_tai', $maDeTais)->get();

        $merged = $deTaiSVs->merge($deTaiGVs)->unique('ma_de_tai')->values();

        $page = LengthAwarePaginator::resolveCurrentPage();
        $deTais = new LengthAwarePaginator(
            $merged->forPage($page, $limit),
            $merged->count(),
            $limit,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.phanconghuongdan.danhSach', compact('deTais'));
    }

    public function pageAjax(Request $request)
    {
        $limit = $request->query('limit', 10);

        $deTaiSVs = DeTaiSinhVien::query()
            ->with(['sinhViens', 'giangViens'])
            ->where(['da_huy' => 0, 'trang_thai' => 2]);

        if ($request->filled('ten_de_tai')) {
            $deTaiSVs->where('ten_de_tai', 'like', '%' . $request->ten_de_tai . '%');
        }

        if ($request->filled('giang_vien')) {
            $deTaiSVs->whereHas('giangViens', function ($q) use ($request) {
                $q->where('ho_ten', 'like', '%' . $request->giang_vien . '%');
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
            ->with(['sinhViens', 'giangViens'])
            ->whereIn('ma_de_tai', $maDeTais);

        if ($request->filled('ten_de_tai')) {
            $deTaiGVs->where('ten_de_tai', 'like', '%' . $request->ten_de_tai . '%');
        }

        if ($request->filled('giang_vien')) {
            $deTaiGVs->whereHas('giangViens', function ($q) use ($request) {
                $q->where('ho_ten', 'like', '%' . $request->giang_vien . '%');
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


    public function phanCong($ma_de_tai)
    {
        $deTai = DeTaiSinhVien::with(['giangViens', 'sinhViens'])
            ->where('ma_de_tai', $ma_de_tai)
            ->first();

        if (!$deTai) {
            $deTai = DeTaiGiangVien::with(['giangViens', 'sinhViens'])
                ->where('ma_de_tai', $ma_de_tai)
                ->first();
        }

        if (!$deTai) {
            abort(404, 'Đề tài không tồn tại');
        }

        $giangViens = GiangVien::get();

        return view('admin.phanconghuongdan.phanCong', compact('deTai', 'giangViens'));
    }

    public function xacNhanPhanCong(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        Log::info('đề tài', [$data]);

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

            // foreach ($giangVienList as $index => $giangVien) {
            //     if ($giangVien == null) {
            //         $validator->errors()->add("giangvien.$index", "Giảng viên không được để trống.");
            //     }
            // }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        $deTai = DeTaiSinhVien::where('ma_de_tai', $data['ma_de_tai'])->first();
        foreach($deTai->sinhViens as $sinhVien) {
            foreach($giangVienList as $giangVien) {
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
