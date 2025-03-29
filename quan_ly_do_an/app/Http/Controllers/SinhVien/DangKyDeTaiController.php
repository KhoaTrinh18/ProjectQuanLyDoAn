<?php

namespace App\Http\Controllers\SinhVien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    DeTaiGiangVien,
    SinhVien,
    LinhVuc
};

class DangKyDeTaiController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $linhVucs = LinhVuc::orderBy('ma_linh_vuc', 'desc')->get();
        $deTais = DeTaiGiangVien::with('linhVuc')->orderBy('ma_de_tai', 'desc')->paginate($limit);
        return view('sinhvien.dangkydetai.index', compact('deTais', 'linhVucs'));
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
            $query->where('da_dang_ky', $request->trang_thai);
        }
    
        $limit = $request->input('limit', 10);
        $deTais = $query->orderBy('ma_de_tai', 'desc')->paginate($limit);
    
        return response()->json([
            'success' => true,
            'html' => view('sinhvien.dangkydetai.pageAjax', compact('deTais'))->render(),
        ]);
    }

    public function dangKy($ma_de_tai)
    {
        $deTai = DeTaiGiangVien::with(['linhVuc', 'giangViens'])->where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('sinhvien.dangkydetai.dangKy', compact('deTai'));
    }

    public function xacNhanDangKy(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);
        $validator = Validator::make($data, [
            'mssv.*' => [
                'sometimes',
                'nullable',
            ]
        ]);
        $mssvList = [];
        
        $validator->after(function ($validator) use ($request, &$mssvList) {
            $mssvList = array_filter($request->input('DeTai', [])['mssv'], function ($value) {
                return trim(strip_tags($value)) !== "";
            });

            foreach ($mssvList as $index => $mssv) {
                if (!preg_match('/^\d+$/', $mssv)) {
                    $validator->errors()->add("mssv.$index", "MSSV chỉ được chứa số.");
                    continue;
                }

                $sinhVien = SinhVien::where('mssv', $mssv)->first();
                if (!$sinhVien) {
                    $validator->errors()->add("mssv.$index", "MSSV không tồn tại.");
                    continue;
                }

                if (!empty($sinhVien->ma_de_tai_sv) || !empty($sinhVien->ma_de_tai_gv)) {
                    $validator->errors()->add("mssv.$index", "MSSV đã đăng ký hoặc đề xuất đề tài.");
                }
            }
        });

         if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        $deTai = DeTaiGiangVien::where('ma_de_tai', $data['ma_de_tai'])->first();
        $deTai->da_dang_ky = 1;
        $deTai->trang_thai = 1;
        $deTai->save();

        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        $mssvList[] = $sinhVien->mssv;
        SinhVien::whereIn('mssv', $mssvList)->update([
            'ma_de_tai_gv' => $data['ma_de_tai'],
            'loai_sv' => 2,
            'ngay' => Carbon::now()
        ]);

        session(['co_de_tai' => 1]);

        return response()->json([
            'success' => true,
            'errors' => []
        ]);
    }
}
