<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\{
    BangDiemGVPBChoSVDK,
    BangDiemGVPBChoSVDX,
    BangDiemGVTHDChoSVDK,
    BangDiemGVTHDChoSVDX,
    BangPhanCongSVDK,
    BangPhanCongSVDX,
    BoMon,
    DeTaiGiangVien,
    DeTaiSinhVien,
    SinhVien,
    SinhVienDeTaiSV,
    TaiKhoanSV,
    ThietLap
};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

class SinhVienController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);
        $thietLap = ThietLap::where('trang_thai', 1)->first();

        $sinhViens = SinhVien::where('nam_hoc', $thietLap->nam_hoc)->orderBy('ma_sv', 'desc')->paginate($limit);
        $chuyenNganhs = BoMon::where('da_huy', 0)->orderBy('ma_bo_mon', 'desc')->get();

        return view('admin.sinhvien.danhSach', compact('sinhViens', 'chuyenNganhs'));
    }

    public function pageAjax(Request $request)
    {
        $query = SinhVien::query();

        if ($request->filled('sinh_vien')) {
            $query->where('ho_ten', 'like', '%' . $request->sinh_vien . '%');
        }

        if ($request->filled('mssv')) {
            $query->where('mssv', $request->mssv);
        }

        if ($request->filled('lop')) {
            $query->where('lop', 'like', '%' . $request->lop . '%');
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('ten_de_tai')) {
            $tuKhoa = strtolower($request->ten_de_tai);

            $query->whereHas('deTaiDeXuat', function ($q) use ($tuKhoa) {
                $q->where('ten_de_tai', 'like', '%' . $tuKhoa . '%');
            });

            $query->orWhereHas('deTaiDangKy', function ($q) use ($tuKhoa) {
                $q->where('ten_de_tai', 'like', '%' . $tuKhoa . '%');
            });
        }

        if ($request->filled('giang_vien')) {
            $query->whereHas('deTaiDeXuat', function ($q) use ($request) {
                $q->whereHas('giangViens', function ($q2) use ($request) {
                    $q2->where('giang_vien.ma_gv', $request->giang_vien);
                });
            });

            $query->orWhereHas('deTaiDangKy', function ($q) use ($request) {
                $q->whereHas('giangViens', function ($q2) use ($request) {
                    $q2->where('giang_vien.ma_gv', $request->giang_vien);
                });
            });
        }

        $limit = $request->input('limit', 10);
        $thietLap = ThietLap::where('trang_thai', 1)->first();
        $sinhViens = $query->where('nam_hoc', $thietLap->nam_hoc)->orderBy('ma_sv', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'html' => view('admin.sinhvien.pageAjax', compact('sinhViens'))->render(),
        ]);
    }

    public function chiTiet($ma_sv)
    {
        $sinhVien = SinhVien::where('ma_sv', $ma_sv)->firstOrFail();
        if ($sinhVien->dang_ky == 1) {
            if ($sinhVien->loai_sv == 'de_xuat') {
                $sinhVienDTSV = SinhVienDeTaiSV::where('ma_sv', $sinhVien->ma_sv)->where('trang_thai', '!=', 0)->first();
                $deTai = DeTaiSinhVien::where(['ma_de_tai' => $sinhVienDTSV->ma_de_tai, 'da_huy' => 0])->first();
            } else {
                $phanCongSVDK = BangPhanCongSVDK::where('ma_sv', $sinhVien->ma_sv)->first();
                $deTai = DeTaiGiangVien::where(['ma_de_tai' => $phanCongSVDK->ma_de_tai, 'da_huy' => 0])->first();
            }
            return view('admin.sinhVien.chiTiet', compact('sinhVien', 'deTai'));
        }
        return view('admin.sinhVien.chiTiet', compact('sinhVien'));
    }

    public function them()
    {
        return view('admin.sinhvien.them');
    }

    public function xacNhanThem(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $thietLap = ThietLap::where('trang_thai', 1)->first();

        $data = $request->input('GiangVien', []);

        $validator = Validator::make($request->all(), [
            'file' => [
                'required',
                'file',
                'mimes:csv,txt'
            ]
        ], [
            'file.required' => 'File CSV không được để trống.',
            'file.mimes' => 'File phải có định dạng CSV.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            $file = $request->file('file');

            $handle = fopen($file, 'r');
            $row = 0;
            $errors = [];
            $rows = [];

            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if ($row++ === 0) {
                    continue;
                }

                if (count($data) != 5) {
                    $errors[] = "Dòng {$row}: Không đúng cột dữ liệu.";
                    continue;
                }

                $lineValidator = Validator::make([
                    'mssv'          => $data[0],
                    'ho_ten'        => $data[1],
                    'lop'           => $data[2],
                    'email'         => $data[3],
                    'so_dien_thoai' => $data[4],
                ], [
                    'mssv'          => 'required|numeric',
                    'ho_ten'        => ['required', 'regex:/^[\p{L}\s]+$/u'],
                    'lop'           => 'required',
                    'email'         => ['required', 'regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/'],
                    'so_dien_thoai' => ['required', 'regex:/^(0|\+84)[0-9]{9,10}$/'],
                ], [
                    'mssv.required'   => 'Dòng ' . $row . ': MSSV không được để trống.',
                    'mssv.numeric'          => 'Dòng ' . $row . ': MSSV phải là chuỗi số.',

                    'ho_ten.required'   => 'Dòng ' . $row . ': Họ tên không được để trống.',
                    'ho_ten.regex'          => 'Dòng ' . $row . ': Họ tên chỉ được chứa chữ và khoảng trắng.',

                    'lop.required'          => 'Dòng ' . $row . ': Lớp không được để trống.',

                    'email.required'   => 'Dòng ' . $row . ': Email không được để trống.',
                    'email.regex'           => 'Dòng ' . $row . ': Email không hợp lệ.',

                    'so_dien_thoai.required'   => 'Dòng ' . $row . ': Số điện thoại không được để trống.',
                    'so_dien_thoai.regex'   => 'Dòng ' . $row . ': Số điện thoại không hợp lệ.',
                ]);

                if ($lineValidator->fails()) {
                    $errors = array_merge($errors, $lineValidator->errors()->all());
                    continue;
                }

                $rows[] = [
                    'mssv'          => trim($data[0]),
                    'ho_ten'        => trim($data[1]),
                    'lop'           => trim($data[2]),
                    'email'         => trim($data[3]),
                    'so_dien_thoai' => trim($data[4]),
                    'nam_hoc'       => $thietLap->nam_hoc,
                ];
            }

            fclose($handle);

            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'errors' => $errors,
                ]);
            }

            if (SinhVien::where(['dang_ky' => 1, 'nam_hoc' => $thietLap->nam_hoc])->exists()) {
                return response()->json([
                    'success' => false,
                    'errors' => true,
                ]);
            }

            SinhVien::where('nam_hoc', $thietLap->nam_hoc)->delete();
            TaiKhoanSV::where('nam_hoc', $thietLap->nam_hoc)->delete();
            SinhVien::insert($rows);

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

    public function sua($ma_sv)
    {
        $sinhVien = SinhVien::where('ma_sv', $ma_sv)->firstOrFail();

        return view('admin.sinhvien.sua', compact('sinhVien'));
    }

    public function xacNhanSua(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('SinhVien', []);

        $validator = Validator::make($data, [
            'mssv'          => 'required|numeric',
            'ten_sinh_vien' => ['required', 'regex:/^[\p{L}\s]+$/u'],
            'lop'           => 'required',
            'email'         => ['required', 'regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/'],
            'so_dien_thoai' => ['required', 'regex:/^(0|\+84)[0-9]{9,10}$/'],
        ], [
            'mssv.required' => 'MSSV không được để trống.',
            'mssv.numeric' => 'MSSV phải là chuỗi số.',

            'ten_sinh_vien.required' => 'Tên sinh viên không được để trống.',
            'ten_sinh_vien.regex' => 'Tên sinh viên chỉ được chứa chữ và khoảng trắng.',

            'lop.required' => 'Lớp không được để trống.',

            'email.required' => 'Email không được để trống.',
            'email.regex' => 'Email không hợp lệ.',

            'so_dien_thoai.required' => 'Số điện thoại không được để trống.',
            'so_dien_thoai.regex' => 'Số điện thoại không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            SinhVien::where('ma_sv', $data['ma_sv'])->update([
                'mssv' => $data['mssv'],
                'ho_ten' => $data['ten_sinh_vien'],
                'lop' => $data['lop'],
                'email' => $data['email'],
                'so_dien_thoai' => $data['so_dien_thoai'],
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

    public function huy($ma_sv)
    {
        $sinhVien = SinhVien::where('ma_sv', $ma_sv)->firstOrFail();
        if ($sinhVien->dang_ky == 1) {
            if ($sinhVien->loai_sv == 'de_xuat') {
                $sinhVienDTSV = SinhVienDeTaiSV::where('ma_sv', $sinhVien->ma_sv)->where('trang_thai', '!=', 0)->first();
                $deTai = DeTaiSinhVien::where(['ma_de_tai' => $sinhVienDTSV->ma_de_tai, 'da_huy' => 0])->first();
            } else {
                $phanCongSVDK = BangPhanCongSVDK::where('ma_sv', $sinhVien->ma_sv)->first();
                $deTai = DeTaiGiangVien::where(['ma_de_tai' => $phanCongSVDK->ma_de_tai, 'da_huy' => 0])->first();
            }
            return view('admin.sinhVien.huy', compact('sinhVien', 'deTai'));
        }
        return view('admin.sinhVien.huy', compact('sinhVien'));
    }

    public function xacNhanHuy(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $ma_sv = $request->input('ma_sv');

        $sinhVien = SinhVien::where('ma_sv', $ma_sv)->first();


        if ($sinhVien->loai_sv == 'de_xuat') {
            BangDiemGVTHDChoSVDX::where('ma_sv', $ma_sv)->update([
                'da_huy' => 1
            ]);
            BangDiemGVPBChoSVDX::where('ma_sv', $ma_sv)->update([
                'da_huy' => 1
            ]);
            BangPhanCongSVDX::where('ma_sv', $ma_sv)->update([
                'da_huy' => 1
            ]);
        } else {
            BangDiemGVTHDChoSVDK::where('ma_sv', $ma_sv)->update([
                'da_huy' => 1
            ]);
            BangDiemGVPBChoSVDK::where('ma_sv', $ma_sv)->update([
                'da_huy' => 1
            ]);
            BangPhanCongSVDK::where('ma_sv', $ma_sv)->update([
                'da_huy' => 1
            ]);
        }

        SinhVien::where('ma_sv', $ma_sv)->update([
            'diem' => 0,
            'trang_thai' => 3
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function taiCSVMau()
    {
        $columns = ['mssv', 'ho_ten', 'lop', 'email', 'so_dien_thoai'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, ['63134337', 'Trịnh Đăng Khoa', '63.CNTT-3', 'a@example.com', '0123456789']);
            fclose($file);
        };

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="mau_danh_sach_sinh_vien.csv"',
        ];

        return response()->stream($callback, 200, $headers);
    }

    public function taoTaiKhoan()
    {
        $thietLap = ThietLap::where('trang_thai', 1)->first();
        TaiKhoanSV::where('nam_hoc', $thietLap->nam_hoc)->delete();

        $sinhViens = SinhVien::where('nam_hoc', $thietLap->nam_hoc)->orderBy('ma_sv', 'desc')->get();
        $count = 1;

        foreach ($sinhViens as $sinhVien) {
            $randomStr = Str::random(8);

            $taiKhoan = new TaiKhoanSV();
            $taiKhoan->ten_tk = $sinhVien->mssv;
            $taiKhoan->mat_khau = $randomStr;
            $taiKhoan->nam_hoc = $thietLap->nam_hoc;
            $taiKhoan->save();

            SinhVien::where('ma_sv', $sinhVien->ma_sv)->update([
                'ma_tk' => $taiKhoan->ma_tk
            ]);
            $count++;
        }

        return back()->with('success', "Đã tạo tài khoản cho {$count} sinh viên.");
    }

    public function taiDSSinhVien()
    {
        $thietLap = ThietLap::where('trang_thai', 1)->first();
        $sinhViens = SinhVien::where('nam_hoc', $thietLap->nam_hoc)
            ->orderBy('ma_sv', 'desc')
            ->get();

        $filename = 'danh_sach_sinh_vien.csv';

        return Response::streamDownload(function () use ($sinhViens) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['MSSV', 'Họ tên', 'Lớp', 'Email', 'Số điện thoại', 'Đề tài', 'Giảng viên hướng dẫn', 'Điểm', 'Trạng thái']);

            foreach ($sinhViens as $sinhVien) {
                $deTai = $sinhVien->deTaiDeXuat->pluck('ten_de_tai')->first()
                    ?: $sinhVien->deTaiDangKy->pluck('ten_de_tai')->first()
                    ?: 'Chưa có';

                $giangViens = $sinhVien->deTaiDeXuat->first()?->giangViens?->pluck('ho_ten')->implode(', ')
                    ?: $sinhVien->deTaiDangKy->first()?->giangViens?->pluck('ho_ten')->implode(', ')
                    ?: 'Chưa có';
                
                if($sinhVien->trang_thai == 0) {
                    $trangThai = "Không hoàn thành";
                } else if ($sinhVien->trang_thai == 2){
                    $trangThai = "Đẫ hoàn thành";
                } else if ($sinhVien->trang_thai == 3){
                    $trangThai = "Nghỉ giữa chừng";
                } else {
                    $trangThai = "Đang thực hiện";
                }

                fputcsv($handle, [
                    $sinhVien->mssv,
                    $sinhVien->ho_ten,
                    $sinhVien->lop,
                    $sinhVien->email,
                    "'" . $sinhVien->so_dien_thoai,
                    $deTai,
                    $giangViens,
                    $sinhVien->diem,
                    $trangThai
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    public function taiDSTaiKhoan()
    {
        $thietLap = ThietLap::where('trang_thai', 1)->first();
        $sinhViens = SinhVien::where('nam_hoc', $thietLap->nam_hoc)
            ->orderBy('ma_sv', 'desc')
            ->get();

        $filename = 'danh_sach_tai_khoan.csv';

        return Response::streamDownload(function () use ($sinhViens) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['MSSV', 'Họ tên', 'Lớp', 'Tên tài khoản', 'Mật khẩu']);

            foreach ($sinhViens as $sinhVien) {
                fputcsv($handle, [
                    $sinhVien->mssv,
                    $sinhVien->ho_ten,
                    $sinhVien->lop,
                    $sinhVien->taiKhoan->ten_tk,
                    $sinhVien->taiKhoan->mat_khau
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    public function capNhatTrangThai()
    {
        $thietLap = ThietLap::where('trang_thai', 1)->first();
        $sinhViens = SinhVien::where('nam_hoc', $thietLap->nam_hoc)->get();

        foreach ($sinhViens as $sinhVien) {
            $diemGVHD = [];
            $diemHDDG = [];
            $diemTong = 0;

            if ($sinhVien->loai_sv == null || $sinhVien->trang_thai == 3 || $sinhVien->trang_thai == 0) continue;

            if ($sinhVien->loai_sv == 'de_xuat') {
                $sinhVienDTSV = SinhVienDeTaiSV::where('ma_sv', $sinhVien->ma_sv)->where('trang_thai', '!=', 0)->first();
                $deTai = DeTaiSinhVien::where(['ma_de_tai' => $sinhVienDTSV->ma_de_tai, 'da_huy' => 0])->first();
            } else {
                $phanCongSVDK = BangPhanCongSVDK::where('ma_sv', $sinhVien->ma_sv)->first();
                $deTai = DeTaiGiangVien::where(['ma_de_tai' => $phanCongSVDK->ma_de_tai, 'da_huy' => 0])->first();
            }
            Log::info($sinhVien->ma_sv);

            $gvhd = $deTai->giangVienHuongDans()->wherePivot('ma_sv', $sinhVien->ma_sv)->first();
            $gvpb = $deTai->giangVienPhanBiens()->wherePivot('ma_sv', $sinhVien->ma_sv)->first();
            $hoiDong = $deTai->HoiDongs->first(); 

            if (!$gvhd || !$gvpb || !$hoiDong) continue;

            foreach ($deTai->giangVienHuongDans()->wherePivot('ma_sv', $sinhVien->ma_sv)->get() as $gv) {
                $diemGVHD[] = $gv->pivot->diem_gvhd;
            }
            $diemTongGVHD = array_sum($diemGVHD) / count($diemGVHD);

            $diemTongGVPB = $gvpb->pivot->diem_gvpb;
            
            $chuTich = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Chủ tịch')->first();
            $thuKy = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Thư ký')->first();
            $uyViens = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Ủy viên')->get();

            $deTaiHoiDong = [];

            if (isset($deTai->so_luong_sv_dang_ky)) {
                $deTaiHoiDong = BangDiemGVTHDChoSVDK::where(['ma_de_tai' => $deTai->ma_de_tai, 'ma_sv' => $sinhVien->ma_sv])->get();
            } else {
                $deTaiHoiDong = BangDiemGVTHDChoSVDX::where(['ma_de_tai' => $deTai->ma_de_tai, 'ma_sv' => $sinhVien->ma_sv])->get();
            }

            $diemHDDG[] = $deTaiHoiDong->where('ma_gvthd', $chuTich->ma_gv)->first()->diem_gvthd;
            $diemHDDG[] = $deTaiHoiDong->where('ma_gvthd', $thuKy->ma_gv)->first()->diem_gvthd;
            foreach ($uyViens as $uyVien) {
                $diemHDDG[] = $deTaiHoiDong->where('ma_gvthd', $uyVien->ma_gv)->first()->diem_gvthd;
            }

            $diemTongHDDG = array_sum($diemHDDG) / count($diemHDDG);

            if (in_array(null, $diemGVHD, true) || in_array(null, $diemHDDG, true) || empty($diemTongGVPB)) continue;

            $diemTong = ($diemTongGVHD + $diemTongGVPB * 2 + $diemTongHDDG * 3) / 6;

            if ($diemTong >= 5.0) {
                SinhVien::where('ma_sv', $sinhVien->ma_sv)->update([
                    'trang_thai' => 2,
                    'diem' => number_format($diemTong, 1)
                ]);
            } else {
                SinhVien::where('ma_sv', $sinhVien->ma_sv)->update([
                    'trang_thai' => 0,
                    'diem' => number_format($diemTong, 1)
                ]);
            }
        }
        return back()->with('success', "Cập trạng thái sinh viên thành công.");
    }
}
