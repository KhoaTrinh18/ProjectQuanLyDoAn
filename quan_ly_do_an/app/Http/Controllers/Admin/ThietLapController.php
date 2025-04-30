<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    DeTaiGiangVien,
    GiangVien,
    LinhVuc,
    GiangVienDeTai,
    ThietLap
};

class ThietLapController extends Controller
{
    public function danhSach()
    {
        $thietLaps = ThietLap::orderBy('ma_thiet_lap', 'desc')->where('trang_thai', '!=', 0)->get();
        return view('admin.thietlap.danhSach', compact('thietLaps'));
    }

    public function them()
    {
        if (ThietLap::exists()) {
            $thietLap = ThietLap::orderBy('ma_thiet_lap', 'desc')->first();
            [$namDau, $namCuoi] = explode('-', $thietLap->nam_hoc);
            $namDau += 1;
            $namCuoi += 1;
            $check = true;
        } else {
            $namDau = '';
            $namCuoi = '';
            $check = false;
        }
        return view('admin.thietlap.them', compact('namDau', 'namCuoi', 'check'));
    }

    public function xacNhanThem(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('ThietLap', []);

        $validator = Validator::make($data, [
            'nam_hoc_dau' => [
                'required',
                'integer',
            ],
            'nam_hoc_cuoi' => [
                'required',
                'integer',
            ],
            'ngay_dang_ky' => [
                'required'
            ],
            'ngay_ket_thuc_dang_ky' => [
                'required',
                'after:ngay_dang_ky'
            ],
            'ngay_thuc_hien' => [
                'required',
                'after:ngay_ket_thuc_dang_ky'
            ],
            'ngay_ket_thuc_thuc_hien' => [
                'required',
                'after:ngay_dang_ky'
            ]
        ]);

        $validator->after(function ($validator) use ($data) {
            if (empty($data['nam_hoc_dau'])) {
                $validator->errors()->add('nam_hoc', 'Năm học bắt đầu không được để trống.');
            }

            if (empty($data['nam_hoc_cuoi'])) {
                $validator->errors()->add('nam_hoc', 'Năm học kết thúc không được để trống.');
            }
        
            if (!is_numeric($data['nam_hoc_dau'])) {
                $validator->errors()->add('nam_hoc', 'Năm học bắt đầu phải là số.');
            }

            if (!is_numeric($data['nam_hoc_cuoi'])) {
                $validator->errors()->add('nam_hoc', 'Năm học kết thúc phải là số.');
            }
        
            if ((int)$data['nam_hoc_cuoi'] !== (int)$data['nam_hoc_dau'] + 1) {
                $validator->errors()->add('nam_hoc', 'Năm học kết thúc phải lớn hơn năm học bắt đầu đúng 1 năm.');
            }

            if (empty($data['ngay_dang_ky'])) {
                $validator->errors()->add('thoi_gian_dang_ky', 'Ngày đăng ký không được để trống.');
            } else {
                $ngayDangKy = strtotime($data['ngay_dang_ky']);
                $startDate = strtotime("01-09-{$data['nam_hoc_dau']}");
                $endDate = strtotime("30-09-{$data['nam_hoc_cuoi']}");
        
                if ($ngayDangKy < $startDate || $ngayDangKy > $endDate) {
                    $validator->errors()->add('thoi_gian_dang_ky', "Ngày đăng ký phải nằm trong khoảng từ 01-09-{$data['nam_hoc_dau']} đến 30-09-{$data['nam_hoc_cuoi']}.");
                }
            }
        
            if (empty($data['ngay_ket_thuc_dang_ky'])) {
                $validator->errors()->add('thoi_gian_dang_ky', 'Ngày kết thúc đăng ký không được để trống.');
            } else {
                $ngayKetThucDangKy = strtotime($data['ngay_ket_thuc_dang_ky']);
                $startDate = strtotime("01-09-{$data['nam_hoc_dau']}");
                $endDate = strtotime("30-09-{$data['nam_hoc_cuoi']}");
        
                if ($ngayKetThucDangKy < $startDate || $ngayKetThucDangKy > $endDate) {
                    $validator->errors()->add('thoi_gian_dang_ky', "Ngày kết thúc đăng ký phải nằm trong khoảng từ 01-09-{$data['nam_hoc_dau']} đến 30-09-{$data['nam_hoc_cuoi']}.");
                }
            }

            if (strtotime($data['ngay_ket_thuc_dang_ky']) <= strtotime($data['ngay_dang_ky'])) {
                $validator->errors()->add('thoi_gian_dang_ky', 'Ngày kết thúc đăng ký phải sau ngày đăng ký.');
            } else {}
            
            if (empty($data['ngay_thuc_hien'])) {
                $validator->errors()->add('thoi_gian_thuc_hien', 'Ngày thực hiện không được để trống.');
            } else {
                $ngayThucHien = strtotime($data['ngay_thuc_hien']);
                $startDate = strtotime("01-09-{$data['nam_hoc_dau']}");
                $endDate = strtotime("30-09-{$data['nam_hoc_cuoi']}");
        
                if ($ngayThucHien < $startDate || $ngayThucHien > $endDate) {
                    $validator->errors()->add('thoi_gian_thuc_hien', "Ngày thực hiện phải nằm trong khoảng từ 01-09-{$data['nam_hoc_dau']} đến 30-09-{$data['nam_hoc_cuoi']}.");
                }
            }

            if (strtotime($data['ngay_thuc_hien']) <= strtotime($data['ngay_ket_thuc_dang_ky'])) {
                $validator->errors()->add('thoi_gian_thuc_hien', 'Ngày thực hiện phải sau ngày kết thúc đăng ký.');
            }
        
            if (empty($data['ngay_ket_thuc_thuc_hien'])) {
                $validator->errors()->add('thoi_gian_thuc_hien', 'Ngày kết thúc thực hiện không được để trống.');
            } else {
                $ngayKetThucThucHien = strtotime($data['ngay_ket_thuc_thuc_hien']);
                $startDate = strtotime("01-09-{$data['nam_hoc_dau']}");
                $endDate = strtotime("30-09-{$data['nam_hoc_cuoi']}");
        
                if ($ngayKetThucThucHien < $startDate || $ngayKetThucThucHien > $endDate) {
                    $validator->errors()->add('thoi_gian_thuc_hien', "Ngày kết thúc thực hiện phải nằm trong khoảng từ 01-09-{$data['nam_hoc_dau']} đến 30-09-{$data['nam_hoc_cuoi']}.");
                }
            }

            if (strtotime($data['ngay_ket_thuc_thuc_hien']) <= strtotime($data['ngay_thuc_hien'])) {
                $validator->errors()->add('thoi_gian_thuc_hien', 'Ngày kết thúc thực hiện phải sau ngày thực hiện.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            if (ThietLap::exists()) {
                $thietLapCuoi = ThietLap::orderBy('ma_thiet_lap', 'desc')->first();
                $thietLapCuoi->trang_thai = 2;
                $thietLapCuoi->save();
            }

            $thietLap = new ThietLap();
            $thietLap->nam_hoc = implode('-', [$data['nam_hoc_dau'], $data['nam_hoc_cuoi']]);
            $thietLap->ngay_dang_ky = Carbon::createFromFormat('d-m-Y', $data['ngay_dang_ky']);
            $thietLap->ngay_ket_thuc_dang_ky = Carbon::createFromFormat('d-m-Y', $data['ngay_ket_thuc_dang_ky']);
            $thietLap->ngay_thuc_hien = Carbon::createFromFormat('d-m-Y', $data['ngay_thuc_hien']);
            $thietLap->ngay_ket_thuc_thuc_hien = Carbon::createFromFormat('d-m-Y', $data['ngay_ket_thuc_thuc_hien']);
            $thietLap->trang_thai = 1;
            $thietLap->save();

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

    public function sua($ma_thiet_lap)
    {
        $thietLap = ThietLap::where('ma_thiet_lap', $ma_thiet_lap)->firstOrFail();
        [$namDau, $namCuoi] = explode('-', $thietLap->nam_hoc);

        return view('admin.thietlap.sua', compact('namDau', 'namCuoi', 'thietLap'));
    }

    public function xacNhanSua(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('ThietLap', []);
        Log::info('data', [$data]);

        $validator = Validator::make($data, [
            'ngay_dang_ky' => [
                'required'
            ],
            'ngay_ket_thuc_dang_ky' => [
                'required',
                'after:ngay_dang_ky'
            ],
            'ngay_thuc_hien' => [
                'required',
                'after:ngay_ket_thuc_dang_ky'
            ],
            'ngay_ket_thuc_thuc_hien' => [
                'required',
                'after:ngay_dang_ky'
            ]
        ]);

        $validator->after(function ($validator) use ($data) {
            if (empty($data['ngay_dang_ky'])) {
                $validator->errors()->add('thoi_gian_dang_ky', 'Ngày đăng ký không được để trống.');
            } else {
                $ngayDangKy = strtotime($data['ngay_dang_ky']);
                $startDate = strtotime("01-09-{$data['nam_hoc_dau']}");
                $endDate = strtotime("30-09-{$data['nam_hoc_cuoi']}");
        
                if ($ngayDangKy < $startDate || $ngayDangKy > $endDate) {
                    $validator->errors()->add('thoi_gian_dang_ky', "Ngày đăng ký phải nằm trong khoảng từ 01-09-{$data['nam_hoc_dau']} đến 30-09-{$data['nam_hoc_cuoi']}.");
                }
            }
        
            if (empty($data['ngay_ket_thuc_dang_ky'])) {
                $validator->errors()->add('thoi_gian_dang_ky', 'Ngày kết thúc đăng ký không được để trống.');
            } else {
                $ngayKetThucDangKy = strtotime($data['ngay_ket_thuc_dang_ky']);
                $startDate = strtotime("01-09-{$data['nam_hoc_dau']}");
                $endDate = strtotime("30-09-{$data['nam_hoc_cuoi']}");
        
                if ($ngayKetThucDangKy < $startDate || $ngayKetThucDangKy > $endDate) {
                    $validator->errors()->add('thoi_gian_dang_ky', "Ngày kết thúc đăng ký phải nằm trong khoảng từ 01-09-{$data['nam_hoc_dau']} đến 30-09-{$data['nam_hoc_cuoi']}.");
                }
            }

            if (strtotime($data['ngay_ket_thuc_dang_ky']) <= strtotime($data['ngay_dang_ky'])) {
                $validator->errors()->add('thoi_gian_dang_ky', 'Ngày kết thúc đăng ký phải sau ngày đăng ký.');
            } else {}
            
            if (empty($data['ngay_thuc_hien'])) {
                $validator->errors()->add('thoi_gian_thuc_hien', 'Ngày thực hiện không được để trống.');
            } else {
                $ngayThucHien = strtotime($data['ngay_thuc_hien']);
                $startDate = strtotime("01-09-{$data['nam_hoc_dau']}");
                $endDate = strtotime("30-09-{$data['nam_hoc_cuoi']}");
        
                if ($ngayThucHien < $startDate || $ngayThucHien > $endDate) {
                    $validator->errors()->add('thoi_gian_thuc_hien', "Ngày thực hiện phải nằm trong khoảng từ 01-09-{$data['nam_hoc_dau']} đến 30-09-{$data['nam_hoc_cuoi']}.");
                }
            }

            if (strtotime($data['ngay_thuc_hien']) <= strtotime($data['ngay_ket_thuc_dang_ky'])) {
                $validator->errors()->add('thoi_gian_thuc_hien', 'Ngày thực hiện phải sau ngày kết thúc đăng ký.');
            }
        
            if (empty($data['ngay_ket_thuc_thuc_hien'])) {
                $validator->errors()->add('thoi_gian_thuc_hien', 'Ngày kết thúc thực hiện không được để trống.');
            } else {
                $ngayKetThucThucHien = strtotime($data['ngay_ket_thuc_thuc_hien']);
                $startDate = strtotime("01-09-{$data['nam_hoc_dau']}");
                $endDate = strtotime("30-09-{$data['nam_hoc_cuoi']}");
        
                if ($ngayKetThucThucHien < $startDate || $ngayKetThucThucHien > $endDate) {
                    $validator->errors()->add('thoi_gian_thuc_hien', "Ngày kết thúc thực hiện phải nằm trong khoảng từ 01-09-{$data['nam_hoc_dau']} đến 30-09-{$data['nam_hoc_cuoi']}.");
                }
            }

            if (strtotime($data['ngay_ket_thuc_thuc_hien']) <= strtotime($data['ngay_thuc_hien'])) {
                $validator->errors()->add('thoi_gian_thuc_hien', 'Ngày kết thúc thực hiện phải sau ngày thực hiện.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            ThietLap::where('ma_thiet_lap', $data['ma_thiet_lap'])->update([
                'ngay_dang_ky' => Carbon::createFromFormat('d-m-Y', $data['ngay_dang_ky']),
                'ngay_ket_thuc_dang_ky' => Carbon::createFromFormat('d-m-Y', $data['ngay_ket_thuc_dang_ky']),
                'ngay_thuc_hien' => Carbon::createFromFormat('d-m-Y', $data['ngay_thuc_hien']),
                'ngay_ket_thuc_thuc_hien' => Carbon::createFromFormat('d-m-Y', $data['ngay_ket_thuc_thuc_hien'])
            ]);

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

    public function huy(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }
        
        $ma_thiet_lap = $request->input('ma_thiet_lap');
        ThietLap::where('ma_thiet_lap', $ma_thiet_lap)->update([
            'trang_thai' => 0
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
