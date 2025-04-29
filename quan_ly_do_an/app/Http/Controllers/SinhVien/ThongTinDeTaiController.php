<?php

namespace App\Http\Controllers\SinhVien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    BangPhanCongSVDK,
    SinhVien,
    DeTaiGiangVien,
    DeTaiSinhVien,
    LinhVuc,
    SinhVienDeTaiSV,
    TaiKhoanSV,
    ThietLap
};

class ThongTinDeTaiController extends Controller
{
    public function thongTin()
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        $daDangKy = $sinhVien->dang_ky;

        if ($daDangKy) {
            if ($sinhVien->loai_sv == 'de_xuat') {
                $sinhVienDTSV = SinhVienDeTaiSV::where('ma_sv', $sinhVien->ma_sv)->where('trang_thai', '!=', 0)->first();
                $deTai = DeTaiSinhVien::where(['ma_de_tai' => $sinhVienDTSV->ma_de_tai, 'da_huy' => 0])->first();
                $loaiDeTai = 'de_tai_sv';
            } else {
                $phanCongSVDK = BangPhanCongSVDK::where('ma_sv', $sinhVien->ma_sv)->first();
                $deTai = DeTaiGiangVien::where(['ma_de_tai' => $phanCongSVDK->ma_de_tai, 'da_huy' => 0])->first();
                $loaiDeTai = 'de_tai_gv';
            }

            $thietLap = ThietLap::where('trang_thai', 1)->first();
            $ngayHomNay = Carbon::now()->toDateString();
            if (Carbon::parse($thietLap->ngay_thuc_hien)->lt($ngayHomNay)) {
                $ngayThucHien = 1;
            } else {
                $ngayThucHien = 0;
            }

            return view('sinhvien.thongtindetai.thongTin', compact('deTai', 'loaiDeTai', 'daDangKy', 'ngayThucHien'));
        } else {
            return view('sinhvien.thongtindetai.thongTin', compact('daDangKy'));
        }
    }

    public function chiTiet()
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();

        if ($sinhVien->loai_sv == 'de_xuat') {
            $sinhVienDTSV = SinhVienDeTaiSV::where('ma_sv', $sinhVien->ma_sv)->first();
            $deTai = DeTaiSinhVien::where(['ma_de_tai' => $sinhVienDTSV->ma_de_tai, 'da_huy' => 0])->first();
        } else {
            $phanCongSVDK = BangPhanCongSVDK::where('ma_sv', $sinhVien->ma_sv)->first();
            $deTai = DeTaiGiangVien::where(['ma_de_tai' => $phanCongSVDK->ma_de_tai, 'da_huy' => 0])->first();
        }

        return view('sinhvien.thongtindetai.chiTiet', compact('deTai'));
    }

    public function huy(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $ma_de_tai = $request->input('ma_de_tai');

        $deTai = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->first();
        $deTai->da_huy = 1;
        $deTai->trang_thai = null;
        $deTai->save();

        $maSVs = $deTai->sinhViens->pluck('ma_sv')->toArray();

        SinhVienDeTaiSV::where('ma_de_tai', $ma_de_tai)->delete();

        SinhVien::whereIn('ma_sv', $maSVs)->update([
            'loai_sv' => null,
            'dang_ky' => 0 
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function sua($ma_de_tai)
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        $daDangKy = $sinhVien->dang_ky;

        $linhVucs = LinhVuc::orderBy('ma_linh_vuc', 'desc')->get();

        $taikhoan = TaiKhoanSV::where('ma_tk', $maTaiKhoan)->first();
        $thietLap = ThietLap::where('nam_hoc', $taikhoan->nam_hoc)->first();
        $ngayHetHan = Carbon::create($thietLap->ngay_ket_thuc_dang_ky)->setTime(23, 59, 59)->toIso8601String();

        $deTai = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->first();

        $mssvList = $deTai->sinhViens->where('ma_sv', '!=', $sinhVien->ma_sv)->pluck('mssv');

        return view('sinhvien.thongtindetai.sua', compact('deTai', 'linhVucs', 'daDangKy', 'ngayHetHan', 'mssvList'));
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

                $data = $request->input('DeTai', []);
                $deTai = DeTaiSinhVien::where('ma_de_tai', $data['ma_de_tai'])->first();
                $mssvDeTai = $deTai->sinhViens->pluck('mssv')->toArray();
                if ($sinhVien->dang_ky && !in_array($mssv, $mssvDeTai)) {
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
            $deTai = DeTaiSinhVien::where('ma_de_tai', $data['ma_de_tai'])->first();
            DeTaiSinhVien::where('ma_de_tai', $data['ma_de_tai'])->update([
                'ten_de_tai' =>  $data['ten_de_tai'],
                'ma_linh_vuc' => $data['ma_linh_vuc'],
                'mo_ta' => $data['mo_ta'],
                'so_luong_sv_de_xuat' => count($mssvList) + 1
            ]);

            $mssvDeTai = $deTai->sinhViens->pluck('mssv');

            SinhVien::whereIn('mssv', $mssvDeTai)->update([
                'dang_ky' => 0,
                'loai_sv' => null,
            ]); 

            $maTaiKhoan = session()->get('ma_tai_khoan');
            $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
            $mssvList[] = $sinhVien->mssv;
            $sinhViens = SinhVien::whereIn('mssv', $mssvList)->get();
            SinhVien::whereIn('mssv', $mssvList)->update([
                'dang_ky' => 1,
                'loai_sv' => 'de_xuat',
            ]); 

            SinhVienDeTaiSV::where('ma_de_tai', $data['ma_de_tai'])->delete();

            $sinhVienDTSVs = [];
            foreach($sinhViens as $sinhVien) {
                $sinhVienDTSVs[] = [
                    'ma_sv' => $sinhVien->ma_sv,
                    'ma_de_tai' => $data['ma_de_tai'],
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

    public function danhSachKhongDuyet()
    {
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $sinhVien = SinhVien::where('ma_tk', $maTaiKhoan)->first();
        $maDeTais = SinhVienDeTaiSV::where(['ma_sv' => $sinhVien->ma_sv, 'trang_thai' => 0])->pluck('ma_de_tai');
        $deTais = DeTaiSinhVien::whereIn('ma_de_tai', $maDeTais)->orderBy('ma_de_tai', 'desc')->get();

        return view('sinhvien.thongtindetai.danhSachKhongDuyet', compact('deTais'));
    }

    public function chiTietKhongDuyet($ma_de_tai)
    {
        $deTai = DeTaiSinhVien::where('ma_de_tai', $ma_de_tai)->firstOrFail();
        return view('sinhvien.thongtindetai.chiTietKhongDuyet', compact('deTai'));
    }
}
