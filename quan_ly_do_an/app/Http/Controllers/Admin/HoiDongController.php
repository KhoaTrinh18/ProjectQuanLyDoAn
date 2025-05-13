<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    BangDiemGVTHDChoSVDK,
    BangDiemGVTHDChoSVDX,
    BoMon,
    HoiDong,
    HoiDongGiangVien,
    ThietLap
};

class HoiDongController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);

        $hoiDongs = HoiDong::where('da_huy', 0)->orderBy('ma_hoi_dong', 'desc')->paginate($limit);
        $thietLaps = ThietLap::orderBy('ma_thiet_lap', 'desc')->get();
        $chuyenNganhs = BoMon::where('da_huy', 0)->orderBy('ma_bo_mon', 'desc')->get();

        return view('admin.hoidong.danhSach', compact('hoiDongs', 'thietLaps', 'chuyenNganhs'));
    }

    public function pageAjax(Request $request)
    {
        $query = HoiDong::query();

        if ($request->filled('ngay_to_chuc_dau') && $request->filled('ngay_to_chuc_cuoi')) {
            $ngay_to_chuc_dau = Carbon::createFromFormat('d-m-Y', $request->ngay_to_chuc_dau);
            $ngay_to_chuc_cuoi = Carbon::createFromFormat('d-m-Y', $request->ngay_to_chuc_cuoi);
            $query->whereBetween('ngay', [$ngay_to_chuc_dau, $ngay_to_chuc_cuoi]);
        } elseif ($request->filled('ngay_to_chuc_dau')) {
            $ngay_to_chuc_dau = Carbon::createFromFormat('d-m-Y', $request->ngay_to_chuc_dau);
            $query->whereDate('ngay', '>=', $ngay_to_chuc_dau);
        } elseif ($request->filled('ngay_to_chuc_cuoi')) {
            $ngay_to_chuc_cuoi = Carbon::createFromFormat('d-m-Y', $request->ngay_to_chuc_cuoi);
            $query->whereDate('ngay', '<=', $ngay_to_chuc_cuoi);
        }

        if ($request->filled('nam_hoc')) {
            $query->where('nam_hoc', $request->nam_hoc);
        }

        $limit = $request->input('limit', 10);
        $hoiDongs = $query->where('da_huy', 0)->orderBy('ma_hoi_dong', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'html' => view('admin.hoidong.pageAjax', compact('hoiDongs'))->render(),
        ]);
    }

    public function chiTiet($ma_hoi_dong)
    {
        $hoiDong = HoiDong::where('ma_hoi_dong', $ma_hoi_dong)->firstOrFail();
        return view('admin.hoidong.chiTiet', compact('hoiDong'));
    }

    public function them()
    {
        $chuyenNganhs = BoMon::where('da_huy', 0)->with('giangViens')->orderBy('ma_bo_mon', 'desc')->get();
        return view('admin.hoidong.them', compact('chuyenNganhs'));
    }

    public function xacNhanThem(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('HoiDong', []);

        $validator = Validator::make($data, [
            'ten_hoi_dong' => [
                'required',
                'string',
                'max:255'
            ],
            'chuyen_nganh' => [
                'required'
            ],
            'phong' => [
                'required',
                'string',
                'max:255'
            ],
            'ngay' => [
                'required'
            ],
            'chu_tich' => [
                'required'
            ],
            'thu_ky' => [
                'required'
            ],
            'uy_vien.*' => [
                'sometimes'
            ]
        ], [
            'ten_hoi_dong.required' => 'Tên hội đồng không được để trống.',
            'ten_hoi_dong.string' => 'Tên hội đồng phải là chuỗi ký tự.',
            'ten_hoi_dong.max' => 'Tên hội đồng không được vượt quá 255 ký tự.',

            'chuyen_nganh.required' => 'Chuyên nghành không được để trống.',

            'phong.required' => 'Phòng không được để trống.',
            'phong.string' => 'Phòng phải là chuỗi ký tự.',
            'phong.max' => 'Phòng không được vượt quá 255 ký tự.',

            'ngay.required' => 'Ngày tổ chức không được để trống.',

            'chu_tich.required' => 'Bạn phải chọn ít nhất một giảng viên.',

            'thu_ky.required' => 'Bạn phải chọn ít nhất một giảng viên.',
        ]);
        $uyVienList = [];
        $validator->after(function ($validator) use ($request, &$uyVienList) {
            $uyVienList = array_filter($request->input('HoiDong', [])['uy_vien'], function ($value) {
                return trim(strip_tags($value)) !== "";
            });

            if (empty($uyVienList)) {
                $validator->errors()->add('uy_vien', 'Bạn phải chọn ít nhất một giảng viên.');
            }
        });
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            $thietLap = ThietLap::where('trang_thai', 1)->first();

            $hoiDong = new HoiDong();
            $hoiDong->ten_hoi_dong = $data['ten_hoi_dong'];
            $hoiDong->ma_bo_mon = $data['chuyen_nganh'];
            $hoiDong->phong = $data['phong'];
            $hoiDong->ngay = Carbon::createFromFormat('H:i d-m-Y', $data['ngay']);
            $hoiDong->nam_hoc = $thietLap->nam_hoc;
            $hoiDong->save();

            $GV_HD = new HoiDongGiangVien();
            $GV_HD->ma_hoi_dong = $hoiDong->ma_hoi_dong;
            $GV_HD->ma_gv = $data['chu_tich'];
            $GV_HD->chuc_vu = "Chủ tịch";
            $GV_HD->save();

            $GV_HD = new HoiDongGiangVien();
            $GV_HD->ma_hoi_dong = $hoiDong->ma_hoi_dong;
            $GV_HD->ma_gv = $data['thu_ky'];
            $GV_HD->chuc_vu = "Thư ký";
            $GV_HD->save();

            foreach ($data['uy_vien'] as $uy_vien) {
                $GV_HD = new HoiDongGiangVien();
                $GV_HD->ma_hoi_dong = $hoiDong->ma_hoi_dong;
                $GV_HD->ma_gv = $uy_vien;
                $GV_HD->chuc_vu = "Ủy viên";
                $GV_HD->save();
            }

            return response()->json([
                'success' => true,
                'errors' => [],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => [],
            ]);
        }
    }

    public function sua($ma_hoi_dong)
    {
        $hoiDong = HoiDong::where('ma_hoi_dong', $ma_hoi_dong)->firstOrFail();
        $chuyenNganhs = BoMon::where('da_huy', 0)->orderBy('ma_bo_mon', 'desc')->get();

        return view('admin.hoidong.sua', compact('hoiDong', 'chuyenNganhs'));
    }

    public function xacNhanSua(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('HoiDong', []);

        $validator = Validator::make($data, [
            'ten_hoi_dong' => [
                'required',
                'string',
                'max:255',
            ],
            'phong' => [
                'required'
            ],
            'ngay' => [
                'required'
            ],
            'chu_tich' => [
                'required'
            ],
            'thu_ky' => [
                'required'
            ],
            'uy_vien.*' => [
                'sometimes'
            ]
        ], [
            'ten_hoi_dong.required' => 'Tên hội đồng không được để trống.',
            'ten_hoi_dong.string' => 'Tên hội đồng phải là chuỗi ký tự.',
            'ten_hoi_dong.max' => 'Tên hội đồng không được vượt quá 255 ký tự.',

            'phong.required' => 'Phòng không được để trống.',

            'ngay.required' => 'Ngày tổ chức không được để trống.',

            'chu_tich.required' => 'Bạn phải chọn ít nhất một giảng viên.',

            'thu_ky.required' => 'Bạn phải chọn ít nhất một giảng viên.',
        ]);
        $uyVienList = [];
        $validator->after(function ($validator) use ($request, &$uyVienList) {
            $uyVienList = array_filter($request->input('HoiDong', [])['uy_vien'], function ($value) {
                return trim(strip_tags($value)) !== "";
            });

            if (empty($uyVienList)) {
                $validator->errors()->add('uy_vien', 'Bạn phải chọn ít nhất một giảng viên.');
            }
        });
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            if (BangDiemGVTHDChoSVDK::where('ma_hoi_dong', $data['ma_hoi_dong'])->exists() || BangDiemGVTHDChoSVDX::where('ma_hoi_dong', $data['ma_hoi_dong'])->exists()) {
                return response()->json(['success' => false]);
            }

            HoiDong::where('ma_hoi_dong', $data['ma_hoi_dong'])->update([
                'ten_hoi_dong' => $data['ten_hoi_dong'],
                'ma_bo_mon' => $data['chuyen_nganh'],
                'phong' => $data['phong'],
                'ngay' => Carbon::createFromFormat('H:i d-m-Y', $data['ngay'])
            ]);

            HoiDongGiangVien::where('ma_hoi_dong', $data['ma_hoi_dong'])->delete();

            $GV_HD = new HoiDongGiangVien();
            $GV_HD->ma_hoi_dong = $data['ma_hoi_dong'];
            $GV_HD->ma_gv = $data['chu_tich'];
            $GV_HD->chuc_vu = "Chủ tịch";
            $GV_HD->save();

            $GV_HD = new HoiDongGiangVien();
            $GV_HD->ma_hoi_dong = $data['ma_hoi_dong'];
            $GV_HD->ma_gv = $data['thu_ky'];
            $GV_HD->chuc_vu = "Thư ký";
            $GV_HD->save();

            foreach ($data['uy_vien'] as $uy_vien) {
                $GV_HD = new HoiDongGiangVien();
                $GV_HD->ma_hoi_dong = $data['ma_hoi_dong'];
                $GV_HD->ma_gv = $uy_vien;
                $GV_HD->chuc_vu = "Ủy viên";
                $GV_HD->save();
            }

            return response()->json([
                'success' => true,
                'errors' => [],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => [],
            ]);
        }
    }

    public function huy($ma_hoi_dong)
    {
        $hoiDong = HoiDong::where('ma_hoi_dong', $ma_hoi_dong)->firstOrFail();
        return view('admin.hoidong.huy', compact('hoiDong'));
    }

    public function xacNhanHuy(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $ma_hoi_dong = $request->input('ma_hoi_dong');

        if (BangDiemGVTHDChoSVDK::where('ma_hoi_dong', $ma_hoi_dong)->exists() || BangDiemGVTHDChoSVDX::where('ma_hoi_dong', $ma_hoi_dong)->exists()) {
            return response()->json([
                'success' => false
            ]);
        } else {
            $hoiDong = HoiDong::where('ma_hoi_dong', $ma_hoi_dong)->first();
            $hoiDong->da_huy = 1;
            $hoiDong->save();

            HoiDongGiangVien::where('ma_hoi_dong', $ma_hoi_dong)->delete();

            return response()->json([
                'success' => true,
            ]);
        }
    }
}
