<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\SinhVien;
use Illuminate\Support\Facades\Log;

class TaiBaoCaoController extends Controller
{
    public function taiBaoCao($ma_sinh_vien)
    {
        Log::info($ma_sinh_vien);
        $sinhVien = SinhVien::where('ma_sv', $ma_sinh_vien)->first();
        

        if (!$sinhVien || !$sinhVien->bao_cao) {
            return redirect()->back()->with('error', 'Không tìm thấy file báo cáo.');
        }

        $path = storage_path('app/public/' . $sinhVien->bao_cao);

        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File báo cáo không tồn tại.');
        }

        return response()->download($path, basename($path));
    }
}
