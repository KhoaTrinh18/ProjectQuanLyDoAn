<?php

namespace App\Http\Controllers\GiangVien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    BangPhanCongSVDK,
    DeTaiGiangVien,
    GiangVien,
    SinhVien,
    GiangVienDeTaiGV
};

class ChamDiemDeTaiController extends Controller
{
    public function danhSachHuongDan()
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
        $maDeTais = GiangVienDeTaiGV::where('ma_gv', $giangVien->ma_gv)->pluck('ma_de_tai');
        $deTais = DeTaiGiangVien::with(['linhVuc', 'sinhViens' => function ($query) use ($giangVien) {
            $query->wherePivot('ma_gvhd', $giangVien->ma_gv);
        }])
            ->whereIn('ma_de_tai', $maDeTais)
            ->where(['da_huy' => 0, 'trang_thai' => 2])
            ->where('so_luong_sv_dang_ky', '>', 0)
            ->orderBy('ma_de_tai', 'desc')
            ->get();
        $phanCongSVDK = BangPhanCongSVDK::where('ma_gvhd', $giangVien->ma_gv)->get();
        return view('giangvien.chamdiemdetai.danhSachHuongDan', compact('deTais', 'phanCongSVDK'));
    }

    public function chiTietHuongDan($ma_de_tai)
    {
        $deTai = DeTaiGiangVien::with(['linhVuc', 'giangViens', 'sinhViens'])->where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('giangvien.chamdiemdetai.chiTietHuongDan', compact('deTai'));
    }


    public function chamDiemHuongDan($ma_de_tai)
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
        $deTai = DeTaiGiangVien::with(['sinhViens' => function ($query) use ($giangVien) {
            $query->wherePivot('ma_gvhd', $giangVien->ma_gv);
        }])->where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('giangvien.chamdiemdetai.chamDiemHuongDan', compact('deTai'));
    }

    public function xacNhanChamDiemHuongDan(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('ChamDiem', []);
        $ma_de_tai = $request->input('ma_de_tai');

        $rules = [];
        $messages = [];

        foreach ($data as $index => $item) {
            $rules["ChamDiem.$index.diem"] = [
                'required',
                'numeric',
                'between:0,10',
                'regex:/^\d+(\.\d{1,2})?$/'
            ];
            $rules["ChamDiem.$index.nhan_xet"] = [
                'required',
                function ($attribute, $value, $fail) use ($index) {
                    $cleanValue = trim(strip_tags(str_replace(["\xc2\xa0", "&nbsp;"], ' ', $value)));
                    if ($cleanValue === '') {
                        $fail('Nhận xét cho sinh viên ' . ($index + 1) . ' không được để trống.');
                    }
                },
            ];

            $messages["ChamDiem.$index.diem.required"] = "Điểm cho sinh viên " . ($index + 1) . " không được để trống.";
            $messages["ChamDiem.$index.diem.numeric"] = "Điểm cho sinh viên " . ($index + 1) . " phải là số.";
            $messages["ChamDiem.$index.diem.between"] = "Điểm cho sinh viên " . ($index + 1) . " phải từ 0 đến 10.";

            $messages["ChamDiem.$index.nhan_xet.required"] = "Nhận xét cho sinh viên " . ($index + 1) . " không được để trống.";
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            $maTaiKhoan = session()->get('ma_tai_khoan');
            $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
            foreach ($data as $chamDiem) {
                BangPhanCongSVDK::where(['ma_de_tai' => $ma_de_tai, 'ma_gvhd' => $giangVien->ma_gv])
                    ->where('ma_sv', $chamDiem['ma_sv'])
                    ->update([
                        'diem_GVHD' => $chamDiem['diem'],
                        'nhan_xet' => $chamDiem['nhan_xet'],
                    ]);
            }

            $deTaiGV = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->first();
            $deTaiGV->da_cham_diem = 1;
            $deTaiGV->save();

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

    public function suaDiemHuongDan($ma_de_tai)
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
        $deTai = DeTaiGiangVien::with(['sinhViens' => function ($query) use ($giangVien) {
            $query->wherePivot('ma_gvhd', $giangVien->ma_gv);
        }])->where('ma_de_tai', $ma_de_tai)->firstOrFail();
        $phanCongSVDK = BangPhanCongSVDK::where(['ma_de_tai' => $ma_de_tai, 'ma_gvhd' => $giangVien->ma_gv])->get();
        return view('giangvien.chamdiemdetai.suaDiemHuongDan', compact('deTai', 'phanCongSVDK'));
    }

    public function xacNhanSuaDiemHuongDan(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('ChamDiem', []);
        $ma_de_tai = $request->input('ma_de_tai');

        $rules = [];
        $messages = [];

        foreach ($data as $index => $item) {
            $rules["ChamDiem.$index.diem"] = [
                'required',
                'numeric',
                'between:0,10',
                'regex:/^\d+(\.\d{1,2})?$/'
            ];
            $rules["ChamDiem.$index.nhan_xet"] = [
                'required',
                function ($attribute, $value, $fail) use ($index) {
                    $cleanValue = trim(strip_tags(str_replace(["\xc2\xa0", "&nbsp;"], ' ', $value)));
                    if ($cleanValue === '') {
                        $fail('Nhận xét cho sinh viên ' . ($index + 1) . ' không được để trống.');
                    }
                },
            ];

            $messages["ChamDiem.$index.diem.required"] = "Điểm cho sinh viên " . ($index + 1) . " không được để trống.";
            $messages["ChamDiem.$index.diem.numeric"] = "Điểm cho sinh viên " . ($index + 1) . " phải là số.";
            $messages["ChamDiem.$index.diem.between"] = "Điểm cho sinh viên " . ($index + 1) . " phải từ 0 đến 10.";

            $messages["ChamDiem.$index.nhan_xet.required"] = "Nhận xét cho sinh viên " . ($index + 1) . " không được để trống.";
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            $maTaiKhoan = session()->get('ma_tai_khoan');
            $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
            foreach ($data as $chamDiem) {
                BangPhanCongSVDK::where(['ma_de_tai' => $ma_de_tai, 'ma_gvhd' => $giangVien->ma_gv])
                    ->where('ma_sv', $chamDiem['ma_sv'])
                    ->update([
                        'diem_GVHD' => $chamDiem['diem'],
                        'nhan_xet' => $chamDiem['nhan_xet'],
                    ]);
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
}
