<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SinhVien\{
    DangKyDeTaiController,
    DeXuatDeTaiController,
    ThongTinDeTaiController
};
use App\Http\Middleware\{
    KiemTraDangNhap,
    KiemTraHetHanDangKy
};


Route::middleware([KiemTraDangNhap::class. ':sinhvien'])->group(function () {
    Route::get('/dang-ky-de-tai', [DangKyDeTaiController::class, 'danhSach'])->name('dang_ky_de_tai.danh_sach');
    Route::get('/dang-ky-de-tai/page-ajax', [DangKyDeTaiController::class, 'pageAjax'])->name('dang_ky_de_tai.page_ajax');
    Route::middleware([KiemTraHetHanDangKy::class])->group(function () {
        Route::get('/dang-ky-de-tai/dang-ky/{ma_de_tai}', [DangKyDeTaiController::class, 'dangKy'])->name('dang_ky_de_tai.dang_ky');
        Route::post('/dang-ky-de-tai/xac-nhan-dang-ky', [DangKyDeTaiController::class, 'xacNhanDangKy'])->name('dang_ky_de_tai.xac_nhan_dang_ky');
        Route::get('/dang-ky-de-tai/xac-nhan-dang-ky', function () {
            return redirect()->back()->with('error', 'Sai đường dẫn');
        });
    });

    Route::get('/de-xuat-de-tai/de-xuat', [DeXuatDeTaiController::class, 'deXuat'])->name('de_xuat_de_tai.de_xuat');
    Route::post('/de-xuat-de-tai/xac-nhan-de-xuat', [DeXuatDeTaiController::class, 'xacNhanDeXuat'])->name('de_xuat_de_tai.xac_nhan_de_xuat');
    Route::get('/de-xuat-de-tai/xac-nhan-de-xuat', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/thong-tin-de-tai/thong-tin', [ThongTinDeTaiController::class, 'thongTin'])->name('thong_tin_de_tai.thong_tin');
    Route::get('/thong-tin-de-tai/chi-tiet', [ThongTinDeTaiController::class, 'chiTiet'])->name('thong_tin_de_tai.chi_tiet');
    Route::post('/thong-tin-de-tai/huy', [ThongTinDeTaiController::class, 'huy'])->name('thong_tin_de_tai.huy');
    Route::get('/thong-tin-de-tai/huy', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/thong-tin-de-tai/sua/{ma_de_tai}', [ThongTinDeTaiController::class, 'sua'])->name('thong_tin_de_tai.sua');
    Route::post('/thong-tin-de-tai/xacNhanSua', [ThongTinDeTaiController::class, 'xacNhanSua'])->name('thong_tin_de_tai.xac_nhan_sua');
    Route::get('/thong-tin-de-tai/xacNhanSua', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/thong-tin-de-tai/danh-sach-de-tai-huy', [ThongTinDeTaiController::class, 'danhSachDeTaiHuy'])->name('thong_tin_de_tai.danh_sach_de_tai_huy');
    Route::get('/thong-tin-de-tai/chi-tiet-de-tai-huy/{ma_de_tai}', [ThongTinDeTaiController::class, 'chiTietDeTaiHuy'])->name('thong_tin_de_tai.chi_tiet_de_tai_huy');
    Route::post('/thong-tin-de-tai/xac-nhan-de-xuat', [ThongTinDeTaiController::class, 'xacNhanDeXuat'])->name('thong_tin_de_tai.xac_nhan_de_xuat');
    Route::get('/thong-tin-de-tai/xac-nhan-de-xuat', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
});
