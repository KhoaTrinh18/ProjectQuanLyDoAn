<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\LinhVuc;

class LinhVucController extends Controller
{
    public function danhSach(Request $request)
    {
        $limit = $request->query('limit', 10);

        $linhVucs = LinhVuc::where('da_huy', 0)->orderBy('ma_linh_vuc', 'desc')->paginate($limit);

        return view('admin.linhvuc.danhSach', compact('linhVucs'));
    }

    public function them()
    {
        return view('admin.linhvuc.them');
    }

    public function xacNhanThem(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('LinhVuc', []);

        $validator = Validator::make($data, [
            'ten_linh_vuc' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s]+$/u'
            ]
        ], [
            'ten_linh_vuc.required' => 'Tên lĩnh vực không được để trống.',
            'ten_linh_vuc.string' => 'Tên lĩnh vực phải là chuỗi ký tự.',
            'ten_linh_vuc.max' => 'Tên lĩnh vực không được vượt quá 255 ký tự.',
            'ten_linh_vuc.regex' => 'Tên lĩnh vực chỉ được chứa chữ cái và khoảng trắng, không chứa số hoặc ký tự đặc biệt.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            $linhvuc = new linhvuc();
            $linhvuc->ten_linh_vuc = $data['ten_linh_vuc'];
            $linhvuc->save();

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

    public function sua($ma_linh_vuc)
    {
        $linhVuc = LinhVuc::where('ma_linh_vuc', $ma_linh_vuc)->firstOrFail();

        return view('admin.linhvuc.sua', compact('linhVuc'));
    }

    public function xacNhanSua(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect()->back()->with('error', 'Bạn không thể truy cập trực tiếp trang này!');
        }

        $data = $request->input('LinhVuc', []);

        $validator = Validator::make($data, [
            'ten_linh_vuc' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s]+$/u'
            ]
        ], [
            'ten_linh_vuc.required' => 'Tên lĩnh vực không được để trống.',
            'ten_linh_vuc.string' => 'Tên lĩnh vực phải là chuỗi ký tự.',
            'ten_linh_vuc.max' => 'Tên lĩnh vực không được vượt quá 255 ký tự.',
            'ten_linh_vuc.regex' => 'Tên lĩnh vực chỉ được chứa chữ cái và khoảng trắng, không chứa số hoặc ký tự đặc biệt.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        try {
            LinhVuc::where('ma_linh_vuc', $data['ma_linh_vuc'])->update([
                'ten_linh_vuc' => $data['ten_linh_vuc'],
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
        $ma_linh_vuc = $request->input('ma_linh_vuc');
        $linhVuc = LinhVuc::where('ma_linh_vuc', $ma_linh_vuc)->first();

        if($linhVuc->deTaiGVs->count() != 0) {
            return response()->json([
                'success' => false,
                'error' => 'de_tai_gv'
            ]);
        } else if($linhVuc->deTaiSVs->count() != 0){
            return response()->json([
                'success' => false,
                'error' => 'de_tai_sv'
            ]);
        } else {
            LinhVuc::where('ma_linh_vuc', $ma_linh_vuc)->update([
                'da_huy' => 1
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
