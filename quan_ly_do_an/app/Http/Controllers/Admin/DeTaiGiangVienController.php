<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\TuChoiDeTaiMail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\{
    DeTaiGiangVien,
    DeTaiSinhVien,
    GiangVienDeTaiGV
};
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
                $ngay_dua_ra_dau = Carbon::createFromFormat('d-m-Y', $request->ngay_dua_ra_dau);
                $ngay_dua_ra_cuoi = Carbon::createFromFormat('d-m-Y', $request->ngay_dua_ra_cuoi);
                $q->whereBetween('ngay_dua_ra', [$ngay_dua_ra_dau->toDateString(), $ngay_dua_ra_cuoi->toDateString()]);
            });
        } elseif ($request->filled('ngay_dua_ra_dau')) {
            $query->whereHas('ngayDuaRa', function ($q) use ($request) {
                $ngay_dua_ra_dau = Carbon::createFromFormat('d-m-Y', $request->ngay_dua_ra_dau);
                $q->whereDate('ngay_dua_ra', '>=', $ngay_dua_ra_dau->toDateString());
            });
        } elseif ($request->filled('ngay_dua_ra_cuoi')) {
            $query->whereHas('ngayDuaRa', function ($q) use ($request) {
                $ngay_dua_ra_cuoi = Carbon::createFromFormat('d-m-Y', $request->ngay_dua_ra_cuoi);
                $q->whereDate('ngay_dua_ra', '<=', $ngay_dua_ra_cuoi->toDateString());
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
        $deTaiGVs = $query->where('da_huy', 0)->orderBy('ma_de_tai', 'desc')->paginate($limit);

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

        $tenDeTaiMoi = $deTaiGV->ten_de_tai;

        $tatCaDeTaiGV = DeTaiGiangVien::where('ma_de_tai', '!=', $ma_de_tai)->where('so_luong_sv_dang_ky', '>=', 1)->where([
            'trang_thai' => 2,
            'da_huy' => 0
        ])->pluck('ten_de_tai');

        $tatCaDeTaiSV = DeTaiSinhVien::where([
            'trang_thai' => 2,
            'da_huy' => 0
        ])->pluck('ten_de_tai');

        $tatCaDeTai = $tatCaDeTaiGV->merge($tatCaDeTaiSV);

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

        return view('admin.detaigiangvien.duyet', compact('deTaiGV', 'trungLap'));
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

        $deTaiGV = DeTaiGiangVien::where('ma_de_tai', $data['ma_de_tai'])->first();
        $deTaiGV->trang_thai = 0;
        $deTaiGV->save();
        
        $ngayDuaRa = $deTaiGV->ngayDuaRa->ngay_dua_ra;

        foreach ($deTaiGV->giangViens as $giangVien) {
            if ($giangVien && $giangVien->email) {
                $emailList[] = $giangVien->email;
            }
        }

        if(!empty($emailList)) {
            SendEmailJob::dispatch($emailList, $deTaiGV, $ngayDuaRa, $lyDoTuChoi);
        }

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
