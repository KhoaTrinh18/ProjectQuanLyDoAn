<?php

namespace App\Http\Controllers\GiangVien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    DeTaiGiangVien,
    GiangVien,
    LinhVuc,
    GiangVienDeTai
};

class DuaRaDeTaiController extends Controller
{
    public function danhSachDeTai()
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
        $maDeTais = GiangVienDeTai::where('ma_gv', $giangVien->ma_gv)->pluck('ma_de_tai');
        $deTais = DeTaiGiangVien::with(['linhVuc', 'giangViens'])
            ->whereIn('ma_de_tai', $maDeTais)
            ->orderBy('ma_de_tai', 'desc')
            ->get();
        return view('giangvien.duaradetai.danhSachDeTai', compact('deTais'));
    }

    public function chiTietDeTai($ma_de_tai)
    {
        $deTai = DeTaiGiangVien::with(['linhVuc', 'giangViens'])->where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('giangvien.duaradetai.chiTietDeTai', compact('deTai'));
    }

    public function duaRa()
    {
        $linhVucs = LinhVuc::orderBy('ma_linh_vuc', 'desc')->get();
        return view('giangvien.duaradetai.duaRa', compact('linhVucs'));
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
            // 'mssv.*' => [
            //     'sometimes',
            //     'nullable',
            // ]
        ], [
            'ten_de_tai.required' => 'Tên đề tài không được để trống.',
            'ten_de_tai.string' => 'Tên đề tài phải là chuỗi ký tự.',
            'ten_de_tai.max' => 'Tên đề tài không được vượt quá 255 ký tự.',

            'ma_linh_vuc.required' => 'Lĩnh vực không được để trống.',
        ]);
        // $validator->after(function ($validator) use ($request) {
        //     $mssvList = array_filter($request->input('DeTai', [])['mssv'], function ($value) {
        //         return trim(strip_tags($value)) !== "";
        //     });

        //     foreach ($mssvList as $index => $mssv) {
        //         if (!preg_match('/^\d+$/', $mssv)) {
        //             $validator->errors()->add("mssv.$index", "MSSV chỉ được chứa số.");
        //             continue;
        //         }

        //         $sinhVien = SinhVien::where('mssv', $mssv)->first();
        //         if (!$sinhVien) {
        //             $validator->errors()->add("mssv.$index", "MSSV không tồn tại.");
        //             continue;
        //         }

        //         if (!empty($sinhVien->ma_de_tai_sv) || !empty($sinhVien->ma_de_tai_gv)) {
        //             $validator->errors()->add("mssv.$index", "MSSV đã đăng ký hoặc đề xuất đề tài.");
        //         }
        //     }
        // });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            $deTaiGV = new DeTaiGiangVien();
            $deTaiGV->ten_de_tai = $data['ten_de_tai'];
            $deTaiGV->ma_linh_vuc = $data['ma_linh_vuc'];
            $deTaiGV->mo_ta = $data['mo_ta'];
            $deTaiGV->save();

            $maTaiKhoan = session()->get('ma_tai_khoan');
            $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
            Log::info("giảng viên:", [$deTaiGV->ma_de_tai]);
            // $mssvList[] = $sinhVien->mssv;
            $GV_DT = new GiangVienDeTai();
            $GV_DT->ma_gv = $giangVien->ma_gv;
            $GV_DT->ma_de_tai = $deTaiGV->ma_de_tai;
            $GV_DT->ngay_dua_ra = Carbon::now();
            $GV_DT->save();

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
