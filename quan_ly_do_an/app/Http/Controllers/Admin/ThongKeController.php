<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\{
    BoMon,
    DeTaiGiangVien,
    DeTaiSinhVien,
    GiangVien,
    GiangVienDeTaiGV,
    HocVi,
    HoiDongGiangVien,
    SinhVien,
    SinhVienDeTaiSV,
    TaiKhoanGV,
    TaiKhoanSV,
    ThietLap
};

class ThongKeController extends Controller
{
    public function thongKe()
    {
        $thietLap = ThietLap::where('trang_thai', 1)->first();
        $dtTrangThai = DeTaiGiangVien::select('trang_thai', DB::raw('count(*) as so_luong'))
            ->where('nam_hoc', $thietLap->nam_hoc)
            ->groupBy('trang_thai')
            ->get();

        $dtMapping = [
            0 => 'Không duyệt',
            1 => 'Chờ duyệt',
            2 => 'Đã duyệt',
        ];

        $thongKeDeTai = [
            'Không duyệt' => 0,
            'Chờ duyệt' => 0,
            'Đã duyệt' => 0,
        ];

        foreach ($dtTrangThai as $item) {
            $tenTrangThai = $dtMapping[$item->trang_thai] ?? 'Không xác định';
            $thongKeDeTai[$tenTrangThai] = $item->so_luong;
        }

        $svTrangThai = SinhVien::select('trang_thai', DB::raw('count(*) as so_luong'))
            ->where('nam_hoc', $thietLap->nam_hoc)
            ->groupBy('trang_thai')
            ->get();

        $svMapping = [
            0 => 'Không hoàn thành',
            1 => 'Đang thực hiện',
            2 => 'Đã hoàn thành',
        ];

        $thongKeSinhVien = [
            'Không hoàn thành' => 0,
            'Đang thực hiện' => 0,
            'Đã hoàn thành' => 0,
        ];

        foreach ($svTrangThai as $item) {
            $tenTrangThai = $svMapping[$item->trang_thai] ?? 'Nghỉ giữa chừng';
            $thongKeSinhVien[$tenTrangThai] = $item->so_luong;
        }

        $svTheoNam = SinhVien::select('nam_hoc', 'trang_thai', DB::raw('count(*) as so_luong'))
            ->groupBy('nam_hoc', 'trang_thai')
            ->orderBy('nam_hoc')
            ->get();

        $dataSinhVienTheoNam = [];

        foreach ($svTheoNam as $item) {
            $nam = $item->nam_hoc;
            if (!isset($dataSinhVienTheoNam[$nam])) {
                $dataSinhVienTheoNam[$nam] = [
                    'tham_gia' => 0,
                    'hoan_thanh' => 0,
                ];
            }
            $dataSinhVienTheoNam[$nam]['tham_gia'] += $item->so_luong;
            if ($item->trang_thai == 2) {
                $dataSinhVienTheoNam[$nam]['hoan_thanh'] = $item->so_luong;
            }
        }
        return view('admin.thongKe', compact('thongKeDeTai', 'thongKeSinhVien', 'dataSinhVienTheoNam'));
    }
}
