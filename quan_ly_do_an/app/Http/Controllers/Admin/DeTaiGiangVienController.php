<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    DeTaiGiangVien,
    GiangVien,
    LinhVuc,
    GiangVienDeTai,
    ThietLap
};

class DeTaiGiangVienController extends Controller
{
    public function danhSach(Request $request) {
        $limit = $request->query('limit', 10);

        $deTaiGVs = DeTaiGiangVien::with(['giangViens', 'ngayDuaRa'])->where(['da_huy' => 0, 'trang_thai' => 1])->orderBy('ma_de_tai', 'desc')->paginate($limit);
        return view('admin.detaigiangvien.danhSach', compact('deTaiGVs'));
    }

    public function pageAjax(Request $request) {
        $query = DeTaiGiangVien::query();
        $limit = $request->input('limit', 10);
        $deTaiGVs = $query->orderBy('ma_de_tai', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'html' => view('admin.detaigiangvien.pageAjax', compact('deTaiGVs'))->render(),
        ]);
    }
}
