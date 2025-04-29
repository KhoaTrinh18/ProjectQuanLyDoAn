<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{
    BangDiemGVTHDChoSVDK,
    BangDiemGVTHDChoSVDX,
    BoMon,
    GiangVien,
    HocVi,
    HoiDong,
    HoiDongGiangVien,
    TaiKhoanGV,
    ThietLap
};

class GiangVienController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);

        $giangViens = GiangVien::where('da_huy', 0)->orderBy('ma_gv', 'desc')->paginate($limit);
        $hocVis = HocVi::orderBy('ma_hoc_vi', 'desc')->get();
        $boMons = BoMon::orderBy('ma_bo_mon', 'desc')->get();

        return view('admin.giangvien.danhSach', compact('giangViens', 'hocVis', 'boMons'));
    }

    public function pageAjax(Request $request)
    {
        $query = GiangVien::query();

        if ($request->filled('hoc_vi')) {
            $query->where('ma_hoc_vi', $request->hoc_vi);
        }

        if ($request->filled('bo_mon')) {
            $query->where('ma_bo_mon', $request->bo_mon);
        }

        $limit = $request->input('limit', 10);
        $giangViens = $query->where('da_huy', 0)->orderBy('ma_gv', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'html' => view('admin.giangvien.pageAjax', compact('giangViens'))->render(),
        ]);
    }

    public function chiTiet($ma_gv)
    {
        $giangVien = GiangVien::where('ma_gv', $ma_gv)->firstOrFail();
        return view('admin.giangvien.chiTiet', compact('giangVien'));
    }

    public function them()
    {
        $hocVis = HocVi::orderBy('ma_hoc_vi', 'desc')->get();
        $boMons = BoMon::orderBy('ma_bo_mon', 'desc')->get();
        return view('admin.giangvien.them', compact('boMons', 'hocVis'));
    }

    public function xacNhanThem(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('GiangVien', []);
        Log::info('giảng viên', $data);

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
                'regex:/^[a-zA-Z0-9_]+$/'
            ],
            'mat_khau' => [
                'required',
                'string',
                'regex:/^[\x20-\x7E]+$/'
            ],
            'loai_tk' => [
                'required'
            ],
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
        
            'mat_khau.required' => 'Mật khẩu không được để trống.',
            'mat_khau.string' => 'Mật khẩu phải là chuỗi.',
            'mat_khau.regex' => 'Mật khẩu không chứa dấu tiếng việt.',

            'loai_tk.required' => 'Loại tài khoản không được để trống.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            $taiKhoan = new TaiKhoanGV();
            $taiKhoan->ten_tk = $data['ten_tk'];
            $taiKhoan->mat_khau = $data['mat_khau'];
            $taiKhoan->loai_tk = $data['loai_tk'];
            $taiKhoan->save();

            $giangVien = new GiangVien();
            $giangVien->ho_ten = $data['ten_giang_vien'];
            $giangVien->email = $data['email'];
            $giangVien->so_dien_thoai = $data['so_dien_thoai'];
            $giangVien->ma_bo_mon = $data['bo_mon'];
            $giangVien->ma_hoc_vi = $data['hoc_vi'];
            $giangVien->ma_tk = $taiKhoan->ma_tk;
            $giangVien->save();

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

    public function sua($ma_hoi_dong)
    {
        $hoiDong = HoiDong::where('ma_hoi_dong', $ma_hoi_dong)->firstOrFail();
        $giangViens = GiangVien::get();
        $chuyenNganhs = BoMon::orderBy('ma_bo_mon', 'desc')->get();

        return view('admin.hoidong.sua', compact('hoiDong', 'giangViens', 'chuyenNganhs'));
    }

    public function xacNhanSua(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('HoiDong', []);

        $validator = Validator::make($data, [
            'ten_hoi_dong' => [
                'required',
                'string',
                'max:255',
            ],
            'phong' => [
                'required'
            ],
            'ngay' => [
                'required'
            ],
            'chu_tich' => [
                'required'
            ],
            'thu_ky' => [
                'required'
            ],
            'uy_vien.*' => [
                'sometimes'
            ]
        ], [
            'ten_hoi_dong.required' => 'Tên hội đồng không được để trống.',
            'ten_hoi_dong.string' => 'Tên hội đồng phải là chuỗi ký tự.',
            'ten_hoi_dong.max' => 'Tên hội đồng không được vượt quá 255 ký tự.',

            'phong.required' => 'Phòng không được để trống.',

            'ngay.required' => 'Ngày tổ chức không được để trống.',

            'chu_tich.required' => 'Bạn phải chọn ít nhất một giảng viên.',

            'thu_ky.required' => 'Bạn phải chọn ít nhất một giảng viên.',
        ]);
        $uyVienList = [];
        $validator->after(function ($validator) use ($request, &$uyVienList) {
            $uyVienList = array_filter($request->input('HoiDong', [])['uy_vien'], function ($value) {
                return trim(strip_tags($value)) !== "";
            });

            if (empty($uyVienList)) {
                $validator->errors()->add('uy_vien', 'Bạn phải chọn ít nhất một giảng viên.');
            }
        });
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            if (BangDiemGVTHDChoSVDK::where('ma_hoi_dong', $data['ma_hoi_dong'])->exists() || BangDiemGVTHDChoSVDX::where('ma_hoi_dong', $data['ma_hoi_dong'])->exists()) {
                return response()->json([
                    'success' => false,
                    'errors' => [],
                ]);
            } else {
                HoiDong::where('ma_hoi_dong', $data['ma_hoi_dong'])->update([
                    'ten_hoi_dong' => $data['ten_hoi_dong'],
                    'ma_bo_mon' => $data['chuyen_nganh'],
                    'phong' => $data['phong'],
                    'ngay' => Carbon::createFromFormat('H:i d-m-Y', $data['ngay'])
                ]);

                HoiDongGiangVien::where('ma_hoi_dong', $data['ma_hoi_dong'])->delete();

                $GV_HD = new HoiDongGiangVien();
                $GV_HD->ma_hoi_dong = $data['ma_hoi_dong'];
                $GV_HD->ma_gv = $data['chu_tich'];
                $GV_HD->chuc_vu = "Chủ tịch";
                $GV_HD->save();

                $GV_HD = new HoiDongGiangVien();
                $GV_HD->ma_hoi_dong = $data['ma_hoi_dong'];
                $GV_HD->ma_gv = $data['thu_ky'];
                $GV_HD->chuc_vu = "Thư ký";
                $GV_HD->save();

                foreach ($data['uy_vien'] as $uy_vien) {
                    $GV_HD = new HoiDongGiangVien();
                    $GV_HD->ma_hoi_dong = $data['ma_hoi_dong'];
                    $GV_HD->ma_gv = $uy_vien;
                    $GV_HD->chuc_vu = "Ủy viên";
                    $GV_HD->save();
                }

                return response()->json([
                    'success' => true,
                    'errors' => [],
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => [],
            ]);
        }
    }

    public function huy($ma_hoi_dong)
    {
        $hoiDong = HoiDong::where('ma_hoi_dong', $ma_hoi_dong)->firstOrFail();
        return view('admin.hoidong.huy', compact('hoiDong'));
    }

    public function xacNhanHuy(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $ma_hoi_dong = $request->input('ma_hoi_dong');

        if (BangDiemGVTHDChoSVDK::where('ma_hoi_dong', $ma_hoi_dong)->exists() || BangDiemGVTHDChoSVDX::where('ma_hoi_dong', $ma_hoi_dong)->exists()) {
            return response()->json([
                'success' => false
            ]);
        } else {

            $hoiDong = HoiDong::where('ma_hoi_dong', $ma_hoi_dong)->first();
            $hoiDong->da_huy = 1;
            $hoiDong->save();

            HoiDongGiangVien::where('ma_hoi_dong', $ma_hoi_dong)->delete();

            return response()->json([
                'success' => true,
            ]);
        }
    }
}
