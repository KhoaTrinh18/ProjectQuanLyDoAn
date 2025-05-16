<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\{
    TaiKhoanSV,
    TaiKhoanGV,
};

class DoiMatKhauController extends Controller
{
    public function doiMatKhau(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('MatKhau', []);

        $validator = Validator::make($data, [
            'mk_cu' => ['required', function ($attribute, $value, $fail) {
                $maTaiKhoan = session()->get('ma_tai_khoan');
                $taiKhoan = TaiKhoanSV::where('ma_tk', $maTaiKhoan)->first();
                if (!isset($taiKhoan)) {
                    $taiKhoan = TaiKhoanGV::where('ma_tk', $maTaiKhoan)->first();
                }
                if ($value != $taiKhoan->mat_khau) {
                    $fail('Mật khẩu cũ không đúng.');
                }
            }],
            'mk_moi' => [
                'required',
                function ($attribute, $value, $fail) {
                    $maTaiKhoan = session()->get('ma_tai_khoan');
                    $taiKhoan = TaiKhoanSV::where('ma_tk', $maTaiKhoan)->first();
                    if (!isset($taiKhoan)) {
                        $taiKhoan = TaiKhoanGV::where('ma_tk', $maTaiKhoan)->first();
                        return;
                    }
                    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/', $value)) {
                        $fail('Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ cái, số và ký tự đặc biệt.');
                    }
                }
            ],
            'mk_xac_nhan' => ['required', 'same:mk_moi'],
        ], [
            'mk_cu.required' => 'Mật khẩu cũ không được để trống.',

            'mk_moi.required' => 'Mật khẩu không được để trống.',

            'mk_xac_nhan.required' => 'Mật khẩu xác nhận không được để trống.',
            'mk_xac_nhan.same' => 'Mật khẩu xác nhận không trùng khớp với mật khẩu mới.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            $maTaiKhoan = session()->get('ma_tai_khoan');
            $update = TaiKhoanSV::where('ma_tk', $maTaiKhoan)->update([
                'mat_khau' => $data['mk_xac_nhan'],
                'da_dang_nhap' => 1
            ]);

            if (!$update) {
                $update = TaiKhoanGV::where('ma_tk', $maTaiKhoan)->update([
                    'mat_khau' => $data['mk_xac_nhan'],
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
