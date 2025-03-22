<?php

namespace App\Http\Controllers\SinhVien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\{
    DeTaiSinhVien,
    LinhVuc
};

class ThongTinDeTaiController extends Controller
{
    public function thongTin() {
        return view('sinhvien.thongtindetais.thongTin');
    }
}