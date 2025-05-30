<?php

namespace App\Http\Controllers\GiangVien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    BoMon,
    DeTaiGiangVien,
    GiangVien,
    LinhVuc,
    GiangVienDeTaiGV,
    ThietLap
};

class DuaRaDeTaiController extends Controller
{
    public function danhSach()
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();

        $thietLap = ThietLap::where('trang_thai', 1)->first();

        $maDeTais = GiangVienDeTaiGV::where('ma_gv', $giangVien->ma_gv)->pluck('ma_de_tai');
        $deTais = DeTaiGiangVien::whereIn('ma_de_tai', $maDeTais)
            ->where(['da_huy' => 0, 'nam_hoc' => $thietLap->nam_hoc])
            ->orderByRaw("FIELD(trang_thai, 1, 3, 2, 0)")
            ->orderBy('ma_de_tai', 'desc')
            ->get();

        $ngayHetHan = Carbon::create($thietLap->ngay_dang_ky)->subDays(8)->format('d-m-Y');
        $ngayHomNay = Carbon::now()->toDateString();
        $ngayHetHanSS = Carbon::create($thietLap->ngay_dang_ky)->subDays(8)->toDateString();
        if($ngayHomNay > $ngayHetHanSS) {
            $checkNgayHetHan = 1;
        } else {
            $checkNgayHetHan = 0;
        }

        return view('giangvien.duaradetai.danhSach', compact('deTais', 'ngayHetHan', 'checkNgayHetHan'));
    }

    public function chiTiet($ma_de_tai)
    {
        $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('giangvien.duaradetai.chiTiet', compact('deTai'));
    }

    public function duaRa()
    {
        $linhVucs = LinhVuc::orderBy('ma_linh_vuc', 'desc')->where('da_huy', 0)->get();
        $chuyenNganhs = BoMon::where('da_huy', 0)->with('giangViens')->orderBy('ma_bo_mon', 'desc')->get();
        $thietLap = ThietLap::where('trang_thai', 1)->first();
        $ngayHetHan = Carbon::create($thietLap->ngay_dang_ky)->subDays(8)->setTime(23, 59, 59)->toIso8601String();

        return view('giangvien.duaradetai.duaRa', compact('linhVucs', 'chuyenNganhs', 'ngayHetHan'));
    }

    public function xacNhanDuaRa(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        $validator = Validator::make($data, [
            'ten_de_tai' => [
                'required',
                'string',
                'max:255',
            ],
            'ma_linh_vuc' => [
                'required',
            ],
            'mo_ta' => [
                'required',
                function ($attribute, $value, $fail) {
                    $cleanValue = trim(strip_tags(str_replace(["\xc2\xa0", "&nbsp;"], ' ', $value)));

                    if ($cleanValue === '') {
                        $fail('Mô tả không được để trống.');
                    }
                }
            ],
            'slsv_toi_da' => [
                'required',
            ],
        ], [
            'ten_de_tai.required' => 'Tên đề tài không được để trống.',
            'ten_de_tai.string' => 'Tên đề tài phải là chuỗi ký tự.',
            'ten_de_tai.max' => 'Tên đề tài không được vượt quá 255 ký tự.',

            'ma_linh_vuc.required' => 'Lĩnh vực không được để trống.',

            'slsv_toi_da.required' => 'Số lượng sinh viên tối đa không được để trống.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            $thietLap = ThietLap::where('trang_thai', 1)->first();

            $deTaiGV = new DeTaiGiangVien();
            $deTaiGV->ten_de_tai = $data['ten_de_tai'];
            $deTaiGV->ma_linh_vuc = $data['ma_linh_vuc'];
            $deTaiGV->mo_ta = $data['mo_ta'];
            $deTaiGV->trang_thai = 1;
            $deTaiGV->so_luong_sv_toi_da = $data['slsv_toi_da'];
            $deTaiGV->nam_hoc = $thietLap->nam_hoc;
            $deTaiGV->save();

            $maTaiKhoan = session()->get('ma_tai_khoan');
            $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();

            $giangViens = array_filter($data['giang_vien'], function ($value) {
                return trim(strip_tags($value)) !== "";
            });
            $giangViens[] = $giangVien->ma_gv;

            foreach ($giangViens as $gv) {
                $GV_DT = new GiangVienDeTaiGV();
                $GV_DT->ma_gv = $gv;
                $GV_DT->ma_de_tai = $deTaiGV->ma_de_tai;
                $GV_DT->ngay_dua_ra = Carbon::now();
                $GV_DT->trang_thai = 1;
                $GV_DT->save();
            }

            return response()->json([
                'success' => true,
                'errors' => [],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => [],
            ]);
        }
    }

    public function sua($ma_de_tai)
    {
        $linhVucs = LinhVuc::orderBy('ma_linh_vuc', 'desc')->where('da_huy', 0)->get();
        $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->firstOrFail();
        $chuyenNganhs = BoMon::where('da_huy', 0)->with('giangViens')->orderBy('ma_bo_mon', 'desc')->get();

        $maTaiKhoan = session()->get('ma_tai_khoan');
        $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
        $giangViensDT = $deTai->giangViens->where('ma_gv', '!=', $giangVien->ma_gv)->pluck('ma_gv');
        $thietLap = ThietLap::where('trang_thai', 1)->first();
        $ngayHetHan = Carbon::create($thietLap->ngay_dang_ky)->subDays(8)->setTime(23, 59, 59)->toIso8601String();

        return view('giangvien.duaradetai.sua', compact('deTai', 'linhVucs', 'chuyenNganhs', 'giangViensDT', 'ngayHetHan'));
    }

    public function xacNhanSua(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('DeTai', []);

        $validator = Validator::make($data, [
            'ten_de_tai' => [
                'required',
                'string',
                'max:255',
            ],
            'ma_linh_vuc' => [
                'required',
            ],
            'mo_ta' => [
                'required',
                function ($attribute, $value, $fail) {
                    $cleanValue = trim(strip_tags(str_replace(["\xc2\xa0", "&nbsp;"], ' ', $value)));

                    if ($cleanValue === '') {
                        $fail('Mô tả không được để trống.');
                    }
                }
            ],
            'slsv_toi_da' => [
                'required',
            ],
        ], [
            'ten_de_tai.required' => 'Tên đề tài không được để trống.',
            'ten_de_tai.string' => 'Tên đề tài phải là chuỗi ký tự.',
            'ten_de_tai.max' => 'Tên đề tài không được vượt quá 255 ký tự.',

            'ma_linh_vuc.required' => 'Lĩnh vực không được để trống.',

            'slsv_toi_da.required' => 'Số lượng sinh viên tối đa không được để trống.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            DeTaiGiangVien::where('ma_de_tai', $data['ma_de_tai'])->update([
                'ten_de_tai' => $data['ten_de_tai'],
                'ma_linh_vuc' => $data['ma_linh_vuc'],
                'mo_ta' => $data['mo_ta'],
                'so_luong_sv_toi_da' => $data['slsv_toi_da']
            ]);
            $ngayDuaRa = GiangVienDeTaiGV::where('ma_de_tai', $data['ma_de_tai'])->first()->ngay_dua_ra;

            GiangVienDeTaiGV::where('ma_de_tai', $data['ma_de_tai'])->delete();

            $maTaiKhoan = session()->get('ma_tai_khoan');
            $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
            $data['giang_vien'][] = $giangVien->ma_gv;

            foreach ($data['giang_vien'] as $gv) {
                if($gv == null) continue;
                $GV_DT = new GiangVienDeTaiGV();
                $GV_DT->ma_gv = $gv;
                $GV_DT->ma_de_tai = $data['ma_de_tai'];
                $GV_DT->ngay_dua_ra = $ngayDuaRa;
                $GV_DT->trang_thai = 1;
                $GV_DT->save();
            }

            return response()->json([
                'success' => true,
                'errors' => [],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => [],
            ]);
        }
    }

    public function huy($ma_de_tai)
    {
        $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->firstOrFail();
        $thietLap = ThietLap::where('trang_thai', 1)->first();
        $ngayHetHan = Carbon::create($thietLap->ngay_dang_ky)->subDays(8)->setTime(23, 59, 59)->toIso8601String();

        return view('giangvien.duaradetai.huy', compact('deTai', 'ngayHetHan'));
    }

    public function xacNhanHuy(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $ma_de_tai = $request->input('ma_de_tai');

        $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->first();
        $deTai->da_huy = 1;
        $deTai->trang_thai = null;
        $deTai->save();

        GiangVienDeTaiGV::where('ma_de_tai', $ma_de_tai)->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
