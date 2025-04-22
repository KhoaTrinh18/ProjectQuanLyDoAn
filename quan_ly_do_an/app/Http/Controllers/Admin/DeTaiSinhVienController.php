<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\{
    DeTaiGiangVien,
    DeTaiSinhVien,
    SinhVien,
    SinhVienDeTaiSV
};

class DeTaiSinhVienController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);

        $deTaiSVs = DeTaiSinhVien::where(['da_huy' => 0])->orderBy('ma_de_tai', 'desc')->paginate($limit);
        return view('admin.detaisinhvien.danhSach', compact('deTaiSVs'));
    }

    public function pageAjax(Request $request)
    {
        $query = DeTaiSinhVien::query();

        if ($request->filled('ten_de_tai')) {
            $query->where('ten_de_tai', 'like', '%' . $request->ten_de_tai . '%');
        }

        if ($request->filled('ngay_de_xuat_dau') && $request->filled('ngay_de_xuat_cuoi')) {
            $query->whereHas('ngayDeXuat', function ($q) use ($request) {
                $q->whereBetween('ngay_de_xuat', [$request->ngay_de_xuat_dau, $request->ngay_de_xuat_cuoi]);
            });
        } elseif ($request->filled('ngay_de_xuat_dau')) {
            $query->whereHas('ngayDeXuat', function ($q) use ($request) {
                $q->whereDate('ngay_de_xuat', '>=', $request->ngay_de_xuat_dau);
            });
        } elseif ($request->filled('ngay_de_xuat_cuoi')) {
            $query->whereHas('ngayDeXuat', function ($q) use ($request) {
                $q->whereDate('ngay_de_xuat', '<=', $request->ngay_de_xuat_cuoi);
            });
        }

        if ($request->filled('sinh_vien')) {
            $query->whereHas('sinhViens', function ($q) use ($request) {
                $q->where('ho_ten', 'like', '%' . $request->sinh_vien . '%');
            });
        }

        if ($request->filled('trang_thai')) {
            if ($request->trang_thai == 0) {
                $query->where('trang_thai', 0);
            } elseif ($request->trang_thai == 1) {
                $query->where('trang_thai', 1);
            } else {
                $query->where('trang_thai', 2);
            }
        }

        $limit = $request->input('limit', 10);
        $deTaiSVs = $query->where(['da_huy' => 0])->orderBy('ma_de_tai', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'html' => view('admin.detaisinhvien.pageAjax', compact('deTaiSVs'))->render(),
        ]);
    }

    public function chiTiet($ma_de_tai)
    {
        $deTaiSV = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('admin.detaisinhvien.chiTiet', compact('deTaiSV'));
    }

    public function duyet($ma_de_tai)
    {
        $deTaiSV = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->firstOrFail();
        $tenDeTaiMoi = $deTaiSV->ten_de_tai;

        $tatCaDeTaiSV = DeTaiSinhVien::where('ma_de_tai', '!=', $ma_de_tai)->where([
            'trang_thai' => 2,
            'da_huy' => 0
        ])->pluck('ten_de_tai');

        $tatCaDeTaiGV = DeTaiGiangVien::where('so_luong_sv_dang_ky', '>=', 0)->where([
            'trang_thai' => 2,
            'da_huy' => 0
        ])->pluck('ten_de_tai');

        $tatCaDeTai = $tatCaDeTaiSV->merge($tatCaDeTaiGV);

        $trungLap = [];

        foreach ($tatCaDeTai as $deTaiCu) {
            similar_text(Str::lower($tenDeTaiMoi), Str::lower($deTaiCu), $percent);
            if ($percent >= 70) {
                $trungLap[] = [
                    'de_tai' => $deTaiCu,
                    'percent' => round($percent),
                ];
            }
        }

        return view('admin.detaisinhvien.duyet', compact('deTaiSV', 'trungLap'));
    }

    public function xacNhanDuyet(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        $deTaiSV = DeTaiSinhVien::where('ma_de_tai', $data['ma_de_tai'])->first();
        $deTaiSV->trang_thai = 2;
        $deTaiSV->save();

        return response()->json(['success' => true]);
    }

    public function xacNhanKhongDuyet(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        $deTaiGV = DeTaiSinhVien::where('ma_de_tai', $data['ma_de_tai'])->first();
        $deTaiGV->trang_thai = 0;
        $deTaiGV->save();

        return response()->json(['success' => true]);
    }

    public function huy($ma_de_tai)
    {
        $deTaiSV = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('admin.detaisinhvien.huy', compact('deTaiSV'));
    }

    public function xacNhanHuy(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        $deTaiSV = DeTaiSinhVien::where('ma_de_tai', $data['ma_de_tai'])->first();
        $deTaiSV->da_huy = 1;
        $deTaiSV->save();

        $sinhViens = $deTaiSV->sinhViens->pluck('ma_sv');
        $sinhVienList = SinhVien::whereIn('ma_sv', $sinhViens)->get();
        foreach ($sinhVienList as $sinhVien) {
            $sinhVien->dang_ky = 0;
            $sinhVien->loai_sv = null;
            $sinhVien->save();
        }

        SinhVienDeTaiSV::where('ma_de_tai', $data['ma_de_tai'])
            ->update(['da_huy' => 1]);

        return response()->json(['success' => true]);
    }
}
