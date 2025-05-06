<?php

namespace App\Http\Controllers\GiangVien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    BangPhanCongSVDK,
    BangPhanCongSVDX,
    DeTaiGiangVien,
    DeTaiSinhVien,
    GiangVien,
    ThietLap
};

class ChamDiemHuongDanController extends Controller
{
    public function danhSach()
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
        $thietLap = ThietLap::where('trang_thai', 1)->first();

        $phanCongSVDK = BangPhanCongSVDK::where('ma_gvhd', $giangVien->ma_gv)->get();
        $maDeTais = $phanCongSVDK->pluck('ma_de_tai');
        $deTaiGVs = DeTaiGiangVien::whereIn('ma_de_tai', $maDeTais)
            ->where(['da_huy' => 0, 'trang_thai' => 2, 'nam_hoc' => $thietLap->nam_hoc])
            ->orderBy('ma_de_tai', 'desc')
            ->get();

        $phanCongSVDX = BangPhanCongSVDX::where('ma_gvhd', $giangVien->ma_gv)->get();
        $maDeTais = $phanCongSVDX->pluck('ma_de_tai');
        $deTaiSVs = DeTaiSinhVien::whereIn('ma_de_tai', $maDeTais)
            ->where(['da_huy' => 0, 'trang_thai' => 2, 'nam_hoc' => $thietLap->nam_hoc])
            ->orderBy('ma_de_tai', 'desc')
            ->get();

        $deTais = $deTaiSVs->merge($deTaiGVs)->unique('ma_de_tai')->values();

        return view('giangvien.chamdiemhuongdan.danhSach', compact('deTais', 'phanCongSVDK', 'phanCongSVDX', 'giangVien'));
    }

    public function chiTiet($ma_de_tai)
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();

        $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->first();

        if (!$deTai) {
            $deTai = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->first();
        }

        if (!$deTai) {
            abort(404, 'Đề tài không tồn tại');
        }

        $phanCongSVDK = BangPhanCongSVDK::where('ma_gvhd', $giangVien->ma_gv)->get();
        $phanCongSVDX = BangPhanCongSVDX::where('ma_gvhd', $giangVien->ma_gv)->get();

        return view('giangvien.chamdiemhuongdan.chiTiet', compact('deTai', 'phanCongSVDK', 'phanCongSVDX', 'giangVien'));
    }


    public function chamDiem($ma_de_tai)
    {
        $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->first();

        if (!$deTai) {
            $deTai = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->first();
        }

        if (!$deTai) {
            abort(404, 'Đề tài không tồn tại');
        }

        return view('giangvien.chamdiemhuongdan.chamDiem', compact('deTai'));
    }

    public function xacNhanChamDiem(Request $request)
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
                $updated = BangPhanCongSVDK::where(['ma_de_tai' => $ma_de_tai, 'ma_gvhd' => $giangVien->ma_gv, 'ma_sv' => $chamDiem['ma_sv']])
                    ->update([
                        'diem_gvhd' => $chamDiem['diem'],
                        'nhan_xet' => $chamDiem['nhan_xet'],
                    ]);

                if ($updated === 0) {
                    break;
                }
            }

            foreach ($data as $chamDiem) {
                $updated = BangPhanCongSVDX::where(['ma_de_tai' => $ma_de_tai, 'ma_gvhd' => $giangVien->ma_gv, 'ma_sv' => $chamDiem['ma_sv']])
                    ->update([
                        'diem_gvhd' => $chamDiem['diem'],
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

    public function suaDiem($ma_de_tai)
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $giangVien = GiangVien::where('ma_tk', $maTaiKhoan)->first();
        
        $deTai = DeTaiGiangVien::where('ma_de_tai', $ma_de_tai)->first();

        if (!$deTai) {
            $deTai = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->first();
        }

        if (!$deTai) {
            abort(404, 'Đề tài không tồn tại');
        }

        $phanCongSVDK = BangPhanCongSVDK::where(['ma_de_tai' => $ma_de_tai, 'ma_gvhd' => $giangVien->ma_gv])->get();
        $phanCongSVDX = BangPhanCongSVDX::where(['ma_de_tai' => $ma_de_tai, 'ma_gvhd' => $giangVien->ma_gv])->get();

        return view('giangvien.chamdiemhuongdan.suaDiem', compact('deTai', 'phanCongSVDK', 'phanCongSVDX'));
    }

    public function xacNhanSuaDiem(Request $request)
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
                $updated = BangPhanCongSVDK::where(['ma_de_tai' => $ma_de_tai, 'ma_gvhd' => $giangVien->ma_gv,  'ma_sv' => $chamDiem['ma_sv']])
                    ->update([
                        'diem_gvhd' => $chamDiem['diem'],
                        'nhan_xet' => $chamDiem['nhan_xet'],
                    ]);

                if ($updated === 0) {
                    break;
                }
            }
           
            foreach ($data as $chamDiem) {
                $updated = BangPhanCongSVDX::where(['ma_de_tai' => $ma_de_tai, 'ma_gvhd' => $giangVien->ma_gv, 'ma_sv' => $chamDiem['ma_sv']])
                    ->update([
                        'diem_gvhd' => $chamDiem['diem'],
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
