<?php

namespace App\Http\Controllers\SinhVien;

use App\Events\HetHanEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    DeTaiSinhVien,
    LinhVuc,
    SinhVien,
    SinhVienDeTaiSV,
    TaiKhoanSV,
    ThietLap
};

class DeXuatDeTaiController extends Controller
{
    public function deXuat()
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        $daDangKy = $sinhVien->dang_ky;

        $linhVucs = LinhVuc::orderBy('ma_linh_vuc', 'desc')->get();

        $thietLap = ThietLap::where('trang_thai', 1)->first();
        $ngayHetHan = Carbon::create($thietLap->ngay_ket_thuc_dang_ky)->setTime(23, 59, 59)->toIso8601String();

        return view('sinhvien.dexuatdetai.deXuat', compact('linhVucs', 'daDangKy', 'ngayHetHan'));
    }

    public function xacNhanDeXuat(Request $request)
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
            'mssv.*' => [
                'sometimes',
                'nullable',
            ]
        ], [
            'ten_de_tai.required' => 'Tên đề tài không được để trống.',
            'ten_de_tai.string' => 'Tên đề tài phải là chuỗi ký tự.',
            'ten_de_tai.max' => 'Tên đề tài không được vượt quá 255 ký tự.',

            'ma_linh_vuc.required' => 'Lĩnh vực không được để trống.',
        ]);
        $mssvList = [];
        $validator->after(function ($validator) use ($request, &$mssvList) {
            $mssvList = array_filter($request->input('DeTai', [])['mssv'], function ($value) {
                return trim(strip_tags($value)) !== "";
            });

            foreach ($mssvList as $index => $mssv) {
                if (!preg_match('/^\d+$/', $mssv)) {
                    $validator->errors()->add("mssv.$index", "MSSV chỉ được chứa số.");
                    continue;
                }

                $sinhVien = SinhVien::where('mssv', $mssv)->first();
                if (!$sinhVien) {
                    $validator->errors()->add("mssv.$index", "MSSV không tồn tại.");
                    continue;
                }

                if ($sinhVien->dang_ky) {
                    $validator->errors()->add("mssv.$index", "MSSV đã đăng ký hoặc đề xuất đề tài.");
                }
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }
        
        try {
            $thietLap = ThietLap::where('trang_thai', 1)->first();

            $deTaiSV = new DeTaiSinhVien();
            $deTaiSV->ten_de_tai = $data['ten_de_tai'];
            $deTaiSV->ma_linh_vuc = $data['ma_linh_vuc'];
            $deTaiSV->mo_ta = $data['mo_ta'];
            $deTaiSV->trang_thai = 1;
            $deTaiSV->so_luong_sv_de_xuat = count($mssvList) + 1;
            $deTaiSV->nam_hoc = $thietLap->nam_hoc;
            $deTaiSV->save();

            $maTaiKhoan = session()->get('ma_tai_khoan');
            $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
            $mssvList[] = $sinhVien->mssv;
            $sinhViens = SinhVien::whereIn('mssv', $mssvList)->get();
            SinhVien::whereIn('mssv', $mssvList)->update([
                'dang_ky' => 1,
                'loai_sv' => 'de_xuat',
            ]); 

            $sinhVienDTSVs = [];
            foreach($sinhViens as $sinhVien) {
                $sinhVienDTSVs[] = [
                    'ma_sv' => $sinhVien->ma_sv,
                    'ma_de_tai' => $deTaiSV->ma_de_tai,
                    'ngay_de_xuat' => now()->toDateString(),
                    'trang_thai' => 1
                ];
            }
            SinhVienDeTaiSV::insert($sinhVienDTSVs);
            
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
}
