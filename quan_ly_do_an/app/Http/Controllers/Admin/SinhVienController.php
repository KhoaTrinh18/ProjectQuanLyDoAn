<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    BangDiemGVPBChoSVDK,
    BangDiemGVPBChoSVDX,
    BangPhanCongSVDK,
    BangPhanCongSVDX,
    BoMon,
    GiangVien,
    GiangVienDeTaiGV,
    HocVi,
    HoiDongGiangVien,
    SinhVien,
    TaiKhoanGV,
    TaiKhoanSV,
    ThietLap
};
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class SinhVienController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);
        $thietLap = ThietLap::where('trang_thai', 1)->first();

        $sinhViens = SinhVien::where(['da_huy' => 0, 'nam_hoc' => $thietLap->nam_hoc])->orderBy('ma_sv', 'desc')->paginate($limit);

        return view('admin.sinhvien.danhSach', compact('sinhViens'));
    }

    public function pageAjax(Request $request)
    {
        $query = SinhVien::query();

        if ($request->filled('hoc_vi')) {
            $query->where('ma_hoc_vi', $request->hoc_vi);
        }

        if ($request->filled('bo_mon')) {
            $query->where('ma_bo_mon', $request->bo_mon);
        }

        $limit = $request->input('limit', 10);
        $thietLap = ThietLap::where('trang_thai', 1)->first();
        $sinhViens = $query->where(['da_huy' => 0, 'nam_hoc' => $thietLap->nam_hoc])->orderBy('ma_sv', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'html' => view('admin.sinhvien.pageAjax', compact('sinhViens'))->render(),
        ]);
    }

    public function chiTiet($ma_gv)
    {
        $giangVien = GiangVien::where('ma_gv', $ma_gv)->firstOrFail();
        return view('admin.giangvien.chiTiet', compact('giangVien'));
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
            $thietLap = ThietLap::where('trang_thai', 1)->first();
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
                Log::info($errors);
                return response()->json([
                    'success' => false,
                    'errors' => $errors,
                ]);
            }

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

    public function sua($ma_gv)
    {
        $giangVien = GiangVien::where('ma_gv', $ma_gv)->firstOrFail();
        $hocVis = HocVi::orderBy('ma_hoc_vi', 'desc')->get();
        $boMons = BoMon::where('da_huy', 0)->orderBy('ma_bo_mon', 'desc')->get();

        return view('admin.giangvien.sua', compact('giangVien', 'boMons', 'hocVis'));
    }

    public function xacNhanSua(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('GiangVien', []);
        $giangVien = GiangVien::where('ma_gv', $data['ma_gv'])->first();

        $validator = Validator::make($data, [
            'ten_giang_vien' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s]+$/u'
            ],
            'email' => [
                'required',
                'regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/'
            ],
            'so_dien_thoai' => [
                'required',
                'regex:/^(0|\+84)[0-9]{9}$/'
            ],
            'bo_mon' => [
                'required'
            ],
            'hoc_vi' => [
                'required'
            ],
            'ten_tk' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('tai_khoan_gv', 'ten_tk')->ignore($giangVien->taiKhoan->ma_tk, 'ma_tk')
            ],
            'mat_khau' => [
                'required',
                'string',
                'regex:/^[\x20-\x7E]+$/'
            ]
        ], [
            'ten_giang_vien.required' => 'Tên giảng viên không được để trống.',
            'ten_giang_vien.string' => 'Tên giảng viên phải là chuỗi ký tự.',
            'ten_giang_vien.max' => 'Tên giảng viên không được vượt quá 255 ký tự.',
            'ten_giang_vien.regex' => 'Tên giảng viên chỉ được chứa chữ cái và khoảng trắng, không chứa số hoặc ký tự đặc biệt.',

            'email.required' => 'Email không được để trống.',
            'email.regex' => 'Email không đúng định dạng.',

            'so_dien_thoai.required' => 'Số điện thoại không được để trống.',
            'so_dien_thoai.regex' => 'Số điện thoại không đúng định dạng.',

            'bo_mon.required' => 'Bộ môn không được để trống.',

            'hoc_vi.required' => 'Học vị không được để trống.',

            'ten_tk.required' => 'Tên tài khoản không được để trống.',
            'ten_tk.string' => 'Tên tài khoản phải là chuỗi.',
            'ten_tk.max' => 'Tên tài khoản không được vượt quá 50 ký tự.',
            'ten_tk.regex' => 'Tên tài khoản chỉ được chứa chữ cái, số và dấu gạch dưới (_).',
            'ten_tk.unique' => 'Tên tài khoản đã tồn tại.',

            'mat_khau.required' => 'Mật khẩu không được để trống.',
            'mat_khau.string' => 'Mật khẩu phải là chuỗi.',
            'mat_khau.regex' => 'Mật khẩu không chứa dấu tiếng việt.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            TaiKhoanGV::where('ma_tk', $giangVien->taiKhoan->ma_tk)->update([
                'ten_tk' => $data['ten_tk'],
                'mat_khau' => $data['mat_khau'],
            ]);

            GiangVien::where('ma_gv', $data['ma_gv'])->update([
                'ho_ten' => $data['ten_giang_vien'],
                'email' => $data['email'],
                'so_dien_thoai' => $data['so_dien_thoai'],
                'ma_bo_mon' => $data['bo_mon'],
                'ma_hoc_vi' => $data['hoc_vi']
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

    public function huy($ma_gv)
    {
        $giangVien = GiangVien::where('ma_gv', $ma_gv)->firstOrFail();
        $hocVis = HocVi::orderBy('ma_hoc_vi', 'desc')->get();
        $boMons = BoMon::where('da_huy', 0)->orderBy('ma_bo_mon', 'desc')->get();

        return view('admin.giangvien.huy', compact('giangVien', 'boMons', 'hocVis'));
    }

    public function xacNhanHuy(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $ma_gv = $request->input('ma_gv');

        if (GiangVienDeTaiGV::where('ma_gv', $ma_gv)->exists()) {
            return response()->json([
                'success' => false,
                'error' => 'dua_ra'
            ]);
        } else if (BangPhanCongSVDK::where('ma_gvhd', $ma_gv)->exists() || BangPhanCongSVDX::where('ma_gvhd', $ma_gv)->exists()) {
            return response()->json([
                'success' => false,
                'error' => 'phan_cong_huong_dan'
            ]);
        } else if (BangDiemGVPBChoSVDK::where('ma_gvpb', $ma_gv)->exists() || BangDiemGVPBChoSVDX::where('ma_gvpb', $ma_gv)->exists()) {
            return response()->json([
                'success' => false,
                'error' => 'phan_cong_phan_bien'
            ]);
        } else if (HoiDongGiangVien::where('ma_gv', $ma_gv)->exists()) {
            return response()->json([
                'success' => false,
                'error' => 'phan_cong_hoi_dong'
            ]);
        } else {
            $giangVien = GiangVien::where('ma_gv', $ma_gv)->first();
            $giangVien->da_huy = 1;
            $giangVien->save();

            return response()->json([
                'success' => true,
            ]);
        }
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
        $sinhViens = SinhVien::where(['da_huy' => 0, 'nam_hoc' => $thietLap->nam_hoc])->orderBy('ma_sv', 'desc')->get();
        $count = 1;

        foreach ($sinhViens as $sinhVien) {
            $randomStr = Str::random(8);

            $taiKhoan = new TaiKhoanSV();
            $taiKhoan->ten_tk = "sv{$count}";
            $taiKhoan->mat_khau = $randomStr;
            $taiKhoan->nam_hoc = $thietLap->nam_hoc;
            $taiKhoan->save();

            SinhVien::where('ma_sv', $sinhVien->ma_sv)->update([
                'ma_tk' => $taiKhoan->ma_tk
            ]);

            // (Tuỳ chọn) Gửi email thông báo tài khoản + mật khẩu
            // Mail::to($sv->email)->queue(new NewStudentAccount($user, $plainPassword));
            $count++;
        }

        return back()->with('success', "Đã tạo tài khoản cho {$count} sinh viên.");
    }
}
