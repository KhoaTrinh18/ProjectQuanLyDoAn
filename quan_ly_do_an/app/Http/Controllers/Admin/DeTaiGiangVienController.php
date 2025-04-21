<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\{
    DeTaiGiangVien,
    GiangVienDeTaiGV
};

class DeTaiGiangVienController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);

        $deTaiGVs = DeTaiGiangVien::where(['da_huy' => 0])->orderBy('ma_de_tai', 'desc')->paginate($limit);
        return view('admin.detaigiangvien.danhSach', compact('deTaiGVs'));
    }

    public function pageAjax(Request $request)
    {
        $query = DeTaiGiangVien::query();

        if ($request->filled('ten_de_tai')) {
            $query->where('ten_de_tai', 'like', '%' . $request->ten_de_tai . '%');
        }

        if ($request->filled('ngay_dua_ra_dau') && $request->filled('ngay_dua_ra_cuoi')) {
            $query->whereHas('ngayDuaRa', function ($q) use ($request) {
                $q->whereBetween('ngay_dua_ra', [$request->ngay_dua_ra_dau, $request->ngay_dua_ra_cuoi]);
            });
        } elseif ($request->filled('ngay_dua_ra_dau')) {
            $query->whereHas('ngayDuaRa', function ($q) use ($request) {
                $q->whereDate('ngay_dua_ra', '>=', $request->ngay_dua_ra_dau);
            });
        } elseif ($request->filled('ngay_dua_ra_cuoi')) {
            $query->whereHas('ngayDuaRa', function ($q) use ($request) {
                $q->whereDate('ngay_dua_ra', '<=', $request->ngay_dua_ra_cuoi);
            });
        }

        if ($request->filled('giang_vien')) {
            $query->whereHas('giangViens', function ($q) use ($request) {
                $q->where('ho_ten', 'like', '%' . $request->giang_vien . '%');
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
        $deTaiGVs = $query->where(['da_huy' => 0])->orderBy('ma_de_tai', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'html' => view('admin.detaigiangvien.pageAjax', compact('deTaiGVs'))->render(),
        ]);
    }

    public function chiTiet($ma_de_tai)
    {
        $deTaiGV = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('admin.detaigiangvien.chiTiet', compact('deTaiGV'));
    }

    public function duyet($ma_de_tai)
    {
        $deTaiGV = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('admin.detaigiangvien.duyet', compact('deTaiGV'));
    }

    public function xacNhanDuyet(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        $deTaiGV = DeTaiGiangVien::where('ma_de_tai', $data['ma_de_tai'])->first();
        $deTaiGV->trang_thai = 2;
        $deTaiGV->save();

        return response()->json(['success' => true]);
    }

    public function huy($ma_de_tai)
    {
        $deTaiGV = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('admin.detaigiangvien.huy', compact('deTaiGV'));
    }

    public function xacNhanHuy(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        $deTaiGV = DeTaiGiangVien::where('ma_de_tai', $data['ma_de_tai'])->first();
        $deTaiGV->da_huy = 1;
        $deTaiGV->save();

        GiangVienDeTaiGV::where('ma_de_tai', $data['ma_de_tai'])
            ->update(['da_huy' => 1]);

        return response()->json(['success' => true]);
    }
}
