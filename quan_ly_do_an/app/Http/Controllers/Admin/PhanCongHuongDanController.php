<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use App\Models\{
    BangDiemGVPBChoSVDX,
    BangPhanCongSVDK,
    BangPhanCongSVDX,
    BoMon,
    DeTaiGiangVien,
    DeTaiSinhVien,
    GiangVien,
    SinhVien,
    ThietLap
};
use Illuminate\Support\Facades\Response;

class PhanCongHuongDanController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);
        $thietLap = ThietLap::where('trang_thai', 1)->first();

        $maDeTais = BangPhanCongSVDX::distinct()->where(['nam_hoc' => $thietLap->nam_hoc])->pluck('ma_de_tai');
        $deTaiSVs = DeTaiSinhVien::whereIn('ma_de_tai', $maDeTais)->where('da_xac_nhan_huong_dan', 1)->orderBy('ma_de_tai', 'desc')->get();

        $maDeTais = BangPhanCongSVDK::distinct()->where(['nam_hoc' => $thietLap->nam_hoc])->pluck('ma_de_tai');
        $deTaiGVs = DeTaiGiangVien::whereIn('ma_de_tai', $maDeTais)->where('da_xac_nhan_huong_dan', 1)->orderBy('ma_de_tai', 'desc')->get();

        $merged = $deTaiSVs->merge($deTaiGVs)->unique('ma_de_tai');

        $page = LengthAwarePaginator::resolveCurrentPage();
        $deTais = new LengthAwarePaginator(
            $merged->forPage($page, $limit),
            $merged->count(),
            $limit,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        $chuyenNganhs = BoMon::where('da_huy', 0)->orderBy('ma_bo_mon', 'desc')->get();

        return view('admin.phanconghuongdan.danhSach', compact('deTais', 'chuyenNganhs'));
    }

    public function pageAjax(Request $request)
    {
        $limit = $request->query('limit', 10);
        $thietLap = ThietLap::where('trang_thai', 1)->first();

        $maDeTais = BangPhanCongSVDX::distinct()->where(['nam_hoc' => $thietLap->nam_hoc])->pluck('ma_de_tai');
        $deTaiSVs = DeTaiSinhVien::query()->whereIn('ma_de_tai', $maDeTais)->orderBy('ma_de_tai', 'desc');

        if ($request->filled('ten_de_tai')) {
            $deTaiSVs->where('ten_de_tai', 'like', '%' . $request->ten_de_tai . '%');
        }

        if ($request->filled('giang_vien')) {
            $deTaiSVs->whereHas('giangViens', function ($q) use ($request) {
                $q->where('giang_vien.ma_gv', $request->giang_vien);
            });
        }

        if ($request->filled('sinh_vien')) {
            $deTaiSVs->whereHas('sinhViens', function ($q) use ($request) {
                $q->where('ho_ten', 'like', '%' . $request->sinh_vien . '%');
            });
        }

        if ($request->filled('trang_thai')) {
            if ($request->trang_thai == 0) {
                $deTaiSVs->whereDoesntHave('giangViens');
            } else {
                $deTaiSVs->whereHas('giangViens');
            }
        }

        $deTaiSVs = $deTaiSVs->get();

        $maDeTais = BangPhanCongSVDK::distinct()->where('nam_hoc', $thietLap->nam_hoc)->pluck('ma_de_tai');
        $deTaiGVs = DeTaiGiangVien::query()
            ->whereIn('ma_de_tai', $maDeTais)->orderBy('ma_de_tai', 'desc');

        if ($request->filled('ten_de_tai')) {
            $deTaiGVs->where('ten_de_tai', 'like', '%' . $request->ten_de_tai . '%');
        }

        if ($request->filled('giang_vien')) {
            $deTaiGVs->whereHas('giangViens', function ($q) use ($request) {
                $q->where('giang_vien.ma_gv', $request->giang_vien);
            });
        }

        if ($request->filled('sinh_vien')) {
            $deTaiGVs->whereHas('sinhViens', function ($q) use ($request) {
                $q->where('ho_ten', 'like', '%' . $request->sinh_vien . '%');
            });
        }

        if ($request->filled('trang_thai')) {
            if ($request->trang_thai == 0) {
                $deTaiGVs->whereDoesntHave('giangViens');
            } else {
                $deTaiGVs->whereHas('giangViens');
            }
        }

        $deTaiGVs = $deTaiGVs->get();

        $merged = $deTaiSVs->merge($deTaiGVs)->unique('ma_de_tai');

        $page = LengthAwarePaginator::resolveCurrentPage();
        $deTais = new LengthAwarePaginator(
            $merged->forPage($page, $limit),
            $merged->count(),
            $limit,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return response()->json([
            'success' => true,
            'html' => view('admin.phanconghuongdan.pageAjax', compact('deTais'))->render()
        ]);
    }

    public function chiTiet($ma_de_tai)
    {
        $deTai = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->first();

        if (!$deTai) {
            $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->first();
        }

        if (!$deTai) {
            abort(404, 'Đề tài không tồn tại');
        }

        return view('admin.phanconghuongdan.chiTiet', compact('deTai'));
    }

    public function taiDanhSachHuongDan()
    {
        $thietLap = ThietLap::where('trang_thai', 1)->first();
        $sinhViens = SinhVien::where('nam_hoc', $thietLap->nam_hoc)
            ->orderBy('ma_sv', 'desc')
            ->get();

        $filename = 'danh_sach_sinh_vien_huong_dan.csv';

        return Response::streamDownload(function () use ($sinhViens) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['MSSV', 'Họ tên', 'Lớp', 'Email', 'Số điện thoại', 'Đề tài', 'Giảng viên hướng dẫn']);

            foreach ($sinhViens as $sinhVien) {
                $deTai = $sinhVien->deTaiDeXuat->pluck('ten_de_tai')->first()
                    ?: $sinhVien->deTaiDangKy->pluck('ten_de_tai')->first()
                    ?: 'Chưa có';

                $giangViens = $sinhVien->deTaiDeXuat->first()?->giangViens?->pluck('ho_ten')->implode(', ')
                    ?: $sinhVien->deTaiDangKy->first()?->giangViens?->pluck('ho_ten')->implode(', ')
                    ?: 'Chưa có';

                fputcsv($handle, [
                    $sinhVien->mssv,
                    $sinhVien->ho_ten,
                    $sinhVien->lop,
                    $sinhVien->email,
                    "'" . $sinhVien->so_dien_thoai,
                    $deTai,
                    $giangViens
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }
}
