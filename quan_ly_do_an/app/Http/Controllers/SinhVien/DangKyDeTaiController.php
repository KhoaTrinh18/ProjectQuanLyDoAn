<?php

namespace App\Http\Controllers\SinhVien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DeTaiGiangVien;

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

    public function dangKy($ma_de_tai) {
        $deTai = DeTaiGiangVien::with('linhVuc')->where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('sinhvien.dangkydetais.dangKy', compact('deTai'));
    }
}
