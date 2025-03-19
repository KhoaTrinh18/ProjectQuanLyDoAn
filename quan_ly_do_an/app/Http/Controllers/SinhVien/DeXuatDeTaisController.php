<?php

namespace App\Http\Controllers\SinhVien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{
    DeTaiGiangVien,
    LinhVuc
};

class DeXuatDeTaisController extends Controller
{
    public function deXuat() {
        $linhVucs = LinhVuc::orderBy('ma_linh_vuc', 'desc')->get();
        return view('sinhvien.dexuatdetais.deXuat', compact('linhVucs'));
    }

    public function xacNhanDeXuat() {
        
    }
}
