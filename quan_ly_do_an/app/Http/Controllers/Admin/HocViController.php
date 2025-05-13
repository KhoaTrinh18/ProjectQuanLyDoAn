<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\HocVi;

class HocViController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);

        $hocVis = HocVi::where('da_huy', 0)->orderBy('ma_hoc_vi', 'desc')->paginate($limit);

        return view('admin.hocvi.danhSach', compact('hocVis'));
    }

    public function them()
    {
        return view('admin.hocvi.them');
    }

    public function xacNhanThem(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('HocVi', []);

        $validator = Validator::make($data, [
            'ten_hoc_vi' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s]+$/u'
            ],
            'sl_de_tai_huong_dan' => [
                'required',
                'integer'
            ]
        ], [
            'ten_hoc_vi.required' => 'Tên học vị không được để trống.',
            'ten_hoc_vi.string' => 'Tên học vị phải là chuỗi ký tự.',
            'ten_hoc_vi.max' => 'Tên học vị không được vượt quá 255 ký tự.',
            'ten_hoc_vi.regex' => 'Tên học vị chỉ được chứa chữ cái và khoảng trắng, không chứa số hoặc ký tự đặc biệt.',

            'sl_de_tai_huong_dan.require' => 'Số lượng đề tài hướng dẫn không được để trống.',
            'sl_de_tai_huong_dan.integer' => 'Số lượng đề tài hướng dẫn phải là số.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            $hocVi = new HocVi();
            $hocVi->ten_hoc_vi = $data['ten_hoc_vi'];
            $hocVi->sl_de_tai_huong_dan = $data['sl_de_tai_huong_dan'];
            $hocVi->save();

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

    public function sua($ma_hoc_vi)
    {
        $hocVi = hocVi::where('ma_hoc_vi', $ma_hoc_vi)->firstOrFail();

        return view('admin.hocvi.sua', compact('hocVi'));
    }

    public function xacNhanSua(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('HocVi', []);

        $validator = Validator::make($data, [
            'ten_hoc_vi' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s]+$/u'
            ],
            'sl_de_tai_huong_dan' => [
                'required',
                'integer'
            ]
        ], [
            'ten_hoc_vi.required' => 'Tên học vị không được để trống.',
            'ten_hoc_vi.string' => 'Tên học vị phải là chuỗi ký tự.',
            'ten_hoc_vi.max' => 'Tên học vị không được vượt quá 255 ký tự.',
            'ten_hoc_vi.regex' => 'Tên học vị chỉ được chứa chữ cái và khoảng trắng, không chứa số hoặc ký tự đặc biệt.',

            'sl_de_tai_huong_dan.require' => 'Số lượng đề tài hướng dẫn không được để trống.',
            'sl_de_tai_huong_dan.integer' => 'Số lượng đề tài hướng dẫn phải là số.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            HocVI::where('ma_hoc_vi', $data['ma_hoc_vi'])->update([
                'ten_hoc_vi' => $data['ten_hoc_vi'],
                'sl_de_tai_huong_dan' => $data['sl_de_tai_huong_dan'],
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
        $ma_hoc_vi = $request->input('ma_hoc_vi');
        $hocVi = HocVi::where('ma_hoc_vi', $ma_hoc_vi)->first();

        if ($hocVi->giangViens->count() != 0) {
            return response()->json([
                'success' => false,
            ]);
        } else {
            HocVi::where('ma_hoc_vi', $ma_hoc_vi)->update([
                'da_huy' => 1
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
