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
    BangPhanCongSVDK,
    BangPhanCongSVDX,
    BoMon,
    GiangVien,
    GiangVienDeTaiGV,
    HocVi,
    HoiDong,
    HoiDongGiangVien,
    TaiKhoanGV,
    ThietLap
};
use Illuminate\Validation\Rule;

class BoMonController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);

        $boMons = BoMon::where('da_huy', 0)->orderBy('ma_bo_mon', 'desc')->paginate($limit);

        return view('admin.bomon.danhSach', compact('boMons'));
    }

    public function pageAjax(Request $request)
    {
        $query = GiangVien::query();

        if ($request->filled('hoc_vi')) {
            $query->where('ma_hoc_vi', $request->hoc_vi);
        }

        if ($request->filled('bo_mon')) {
            $query->where('ma_bo_mon', $request->bo_mon);
        }

        $limit = $request->input('limit', 10);
        $giangViens = $query->where('da_huy', 0)->orderBy('ma_gv', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'html' => view('admin.giangvien.pageAjax', compact('giangViens'))->render(),
        ]);
    }

    public function chiTiet($ma_gv)
    {
        $giangVien = GiangVien::where('ma_gv', $ma_gv)->firstOrFail();
        return view('admin.giangvien.chiTiet', compact('giangVien'));
    }

    public function them()
    {
        return view('admin.bomon.them');
    }

    public function xacNhanThem(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('BoMon', []);

        $validator = Validator::make($data, [
            'ten_bo_mon' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s]+$/u'
            ]
        ], [
            'ten_bo_mon.required' => 'Tên bộ môn không được để trống.',
            'ten_bo_mon.string' => 'Tên bộ môn phải là chuỗi ký tự.',
            'ten_bo_mon.max' => 'Tên bộ môn không được vượt quá 255 ký tự.',
            'ten_bo_mon.regex' => 'Tên bộ môn chỉ được chứa chữ cái và khoảng trắng, không chứa số hoặc ký tự đặc biệt.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            $boMon = new BoMon();
            $boMon->ten_bo_mon = $data['ten_bo_mon'];
            $boMon->save();

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

    public function sua($ma_bo_mon)
    {
        $boMon = BoMon::where('ma_bo_mon', $ma_bo_mon)->firstOrFail();

        return view('admin.bomon.sua', compact('boMon'));
    }

    public function xacNhanSua(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('BoMon', []);

        $validator = Validator::make($data, [
            'ten_bo_mon' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s]+$/u'
            ]
        ], [
            'ten_bo_mon.required' => 'Tên bộ môn không được để trống.',
            'ten_bo_mon.string' => 'Tên bộ môn phải là chuỗi ký tự.',
            'ten_bo_mon.max' => 'Tên bộ môn không được vượt quá 255 ký tự.',
            'ten_bo_mon.regex' => 'Tên bộ môn chỉ được chứa chữ cái và khoảng trắng, không chứa số hoặc ký tự đặc biệt.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            BoMon::where('ma_bo_mon', $data['ma_bo_mon'])->update([
                'ten_bo_mon' => $data['ten_bo_mon'],
            ]);

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

    public function huy(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }
        $ma_bo_mon = $request->input('ma_bo_mon');
        $boMon = BoMon::where('ma_bo_mon', $ma_bo_mon)->first();

        if($boMon->giangViens->count() != 0) {
            return response()->json([
                'success' => false,
                'error' => 'giang_vien'
            ]);
        } else if ($boMon->hoiDongs->count() != 0) {
            return response()->json([
                'success' => false,
                'error' => 'hoi_dong'
            ]);
        } else {
            BoMon::where('ma_bo_mon', $ma_bo_mon)->update([
                'da_huy' => 1
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
