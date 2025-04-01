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
    public function danhSachDeTai(Request $request)
    {
        $limit = $request->query('limit', 10);
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        $coDeTai = ($sinhVien->ma_de_tai_sv == null && $sinhVien->ma_de_tai_gv == null) ? 0 : 1;
        $linhVucs = LinhVuc::orderBy('ma_linh_vuc', 'desc')->get();
        $deTais = DeTaiGiangVien::with('linhVuc')->orderBy('ma_de_tai', 'desc')->paginate($limit);
        return view('sinhvien.dangkydetai.danhSachDeTai', compact('deTais', 'linhVucs', 'coDeTai'));
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
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        $coDeTai = ($sinhVien->ma_de_tai_sv == null && $sinhVien->ma_de_tai_gv == null) ? 0 : 1;
    
        return response()->json([
            'success' => true,
            'html' => view('sinhvien.dangkydetai.pageAjax', compact('deTais', 'coDeTai'))->render(),
        ]);
    }

    public function dangKy($ma_de_tai)
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        $coDeTai = ($sinhVien->ma_de_tai_sv == null && $sinhVien->ma_de_tai_gv == null) ? 0 : 1;
        $sinhViens = SinhVien::where('ma_de_tai_gv', $ma_de_tai)->get();
        $deTai = DeTaiGiangVien::with(['linhVuc', 'giangViens'])->where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('sinhvien.dangkydetai.dangKy', compact('deTai', 'sinhViens', 'coDeTai'));
    }

    public function xacNhanDangKy(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        $deTai = DeTaiGiangVien::where('ma_de_tai', $data['ma_de_tai'])->first();
        $deTai->so_luong_sv_dang_ky += 1;
        $deTai->save();

        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        $mssvList[] = $sinhVien->mssv;
        SinhVien::whereIn('mssv', $mssvList)->update([
            'ma_de_tai_gv' => $data['ma_de_tai'],
            'loai_sv' => 2,
            'ngay' => Carbon::now()
        ]);

        return response()->json([
            'success' => true,
            'errors' => []
        ]);
    }
}
