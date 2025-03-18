<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeTaiGiangVien;

class DangKyDeTaisController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $deTais = DeTaiGiangVien::with('linhVuc')->orderBy('ma_de_tai', 'desc')->paginate($limit);
        return view('dangkydetais.index', compact('deTais'));
    }

    public function pageAjax(Request $request)
    {
        $limit = $request->query('limit', 10);
        $deTais = DeTaiGiangVien::with('linhVuc')->orderBy('ma_de_tai', 'desc')->paginate($limit);

        return view('dangkydetais.pageAjax', compact('deTais'));
    }
}
