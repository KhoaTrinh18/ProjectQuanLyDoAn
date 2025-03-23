<?php

namespace App\Http\Controllers\SinhVien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\{
    DeTaiGiangVien,
    SinhVien
};

class DangKyDeTaiController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $deTais = DeTaiGiangVien::with('linhVuc')->orderBy('ma_de_tai', 'desc')->paginate($limit);
        return view('sinhvien.dangkydetais.index', compact('deTais'));
    }

    public function pageAjax(Request $request)
    {
        $limit = $request->query('limit', 10);
        $deTais = DeTaiGiangVien::with('linhVuc')->orderBy('ma_de_tai', 'desc')->paginate($limit);
        return view('sinhvien.dangkydetais.pageAjax', compact('deTais'));
    }

    public function dangKy($ma_de_tai)
    {
        $deTai = DeTaiGiangVien::with(['linhVuc', 'giangViens'])->where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('sinhvien.dangkydetais.dangKy', compact('deTai'));
    }

    public function xacNhanDangKy(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }
        $ma_de_tai = $request->input('ma_de_tai');

        $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->first();
        $deTai->da_dang_ky = 1; 
        $deTai->save(); 
        
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        $mssvList[] = $sinhVien->mssv;
        SinhVien::whereIn('mssv', $mssvList)->update([
            'ma_de_tai_gv' => $ma_de_tai,
            'loai_sv' => 2,
            'ngay' => Carbon::now()
        ]);
        return response()->json([
            'success' => true,
        ]);
    }
}
