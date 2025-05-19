<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\{
    BangPhanCongSVDX,
    DeTaiGiangVien,
    DeTaiSinhVien,
    SinhVien,
    SinhVienDeTaiSV,
    ThietLap
};

class DeTaiSinhVienController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);
        $thietLap = ThietLap::where('trang_thai', 1)->first();

        $deTaiSVs = DeTaiSinhVien::where(['da_huy' => 0, 'nam_hoc' => $thietLap->nam_hoc])->orderByRaw("FIELD(trang_thai, 1, 2, 0)")->paginate($limit);
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
                $ngay_de_xuat_dau = Carbon::createFromFormat('d-m-Y', $request->ngay_de_xuat_dau);
                $ngay_de_xuat_cuoi = Carbon::createFromFormat('d-m-Y', $request->ngay_de_xuat_cuoi);
                $q->whereBetween('ngay_de_xuat', [$ngay_de_xuat_dau->toDateString(), $ngay_de_xuat_cuoi->toDateString()]);
            });
        } elseif ($request->filled('ngay_de_xuat_dau')) {
            $query->whereHas('ngayDeXuat', function ($q) use ($request) {
                $ngay_de_xuat_dau = Carbon::createFromFormat('d-m-Y', $request->ngay_de_xuat_dau);
                $q->whereDate('ngay_de_xuat', '>=', $ngay_de_xuat_dau->toDateString());
            });
        } elseif ($request->filled('ngay_de_xuat_cuoi')) {
            $query->whereHas('ngayDeXuat', function ($q) use ($request) {
                $ngay_de_xuat_cuoi = Carbon::createFromFormat('d-m-Y', $request->ngay_de_xuat_cuoi);
                $q->whereDate('ngay_de_xuat', '<=', $ngay_de_xuat_cuoi->toDateString());
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
            'da_huy' => 0,
            'duoc_bao_ve' => 1
        ])->pluck('ten_de_tai');

        $tatCaDeTaiGV = DeTaiGiangVien::where('so_luong_sv_dang_ky', '>=', 1)->where([
            'trang_thai' => 2,
            'da_huy' => 0,
            'duoc_bao_ve' => 1
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

        DeTaiSinhVien::where('ma_de_tai', $data['ma_de_tai'])->update([
            'trang_thai' => 2
        ]);
        
        SinhVienDeTaiSV::where('ma_de_tai', $data['ma_de_tai'])->update([
            'trang_thai' => 2
        ]);

        return response()->json(['success' => true]);
    }

    public function xacNhanKhongDuyet(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);
        $lyDoTuChoi = $request->input('lyDoTuChoi');

        $validator = Validator::make(
            ['lyDoTuChoi' => $lyDoTuChoi],  
            [
                'lyDoTuChoi' => 'required',  
            ],
            [
                'lyDoTuChoi.required' => 'Lý do từ chối không được để trống.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        $deTaiSV = DeTaiSinhVien::where('ma_de_tai', $data['ma_de_tai'])->first();
        $deTaiSV->trang_thai = 0;
        $deTaiSV->save();

        $mssvDeTai = $deTaiSV->sinhViens->pluck('mssv');

        SinhVien::whereIn('mssv', $mssvDeTai)->update([
            'dang_ky' => 0,
            'loai_sv' => null,
        ]); 

        SinhVienDeTaiSV::where('ma_de_tai',  $data['ma_de_tai'])->update([
            'trang_thai' => 0
        ]);

        $ngayDeXuat = $deTaiSV->ngayDeXuat->ngay_de_xuat;
        foreach ($deTaiSV->sinhViens as $sinhVien) {
            if ($sinhVien && $sinhVien->email) {
                $emailList[] = $sinhVien->email;
            }
        }

        if(!empty($emailList)) {
            SendEmailJob::dispatch($emailList, $deTaiSV, $ngayDeXuat, $lyDoTuChoi);
        }

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

        if (BangPhanCongSVDX::where("ma_de_tai", $data['ma_de_tai'])->exists()) {
            return response()->json(['success' => false]);
        }

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

        SinhVienDeTaiSV::where('ma_de_tai', $data['ma_de_tai'])->delete();

        return response()->json(['success' => true]);
    }
}
