<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    DeTaiSinhVien,
    GiangVien,
    LinhVuc,
    GiangVienDeTai,
    ThietLap
};

class DeTaiSinhVienController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);

        $deTaiSVs = DeTaiSinhVien::with(['sinhViens', 'ngayDeXuat'])->where(['da_huy' => 0])->orderBy('ma_de_tai', 'desc')->paginate($limit);
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
        $deTaiSVs = $query->with(['sinhViens', 'ngayDeXuat'])->where(['da_huy' => 0])->orderBy('ma_de_tai', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'html' => view('admin.detaisinhvien.pageAjax', compact('deTaiSVs'))->render(),
        ]);
    }

    public function duyet($ma_de_tai) {
        $deTaiSV = DeTaiSinhVien::with(['linhVuc', 'sinhViens', 'ngayDeXuat'])->where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('admin.detaisinhvien.duyet', compact('deTaiSV'));
    }

    public function xacNhanDuyet(Request $request) {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        $deTaiSV = DeTaiSinhVien::where('ma_de_tai', $data['ma_de_tai'])->first();
        $deTaiSV->trang_thai = 2;
        $deTaiSV->save();

        return response()->json(['success' => true]);
    }
}
