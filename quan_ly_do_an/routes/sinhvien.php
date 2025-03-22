<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SinhVien\{
    DangKyDeTaiController, 
    DeXuatDeTaiController,
    ThongTinDeTaiController
};
use App\Http\Middleware\KiemTraDangNhap;

Route::middleware([KiemTraDangNhap::class])->group(function () {
    Route::get('/dang-ky-de-tai', [DangKyDeTaiController::class, 'index'])->name('dang_ky_de_tai.index');
    Route::get('/dang-ky-de-tai/page-ajax', [DangKyDeTaiController::class, 'pageAjax'])->name('dang_ky_de_tai.page_ajax');
    Route::get('/dang-ky-de-tai/dang-ky/{ma_de_tai}', [DangKyDeTaiController::class, 'dangKy'])->name('dang_ky_de_tai.dang_ky');

    Route::get('/de-xuat-de-tai/de-xuat', [DeXuatDeTaiController::class, 'deXuat'])->name('de_xuat_de_tai.de_xuat');
    Route::post('/de-xuat-de-tai/xac-nhan-de-xuat', [DeXuatDeTaiController::class, 'xacNhanDeXuat'])->name('de_xuat_de_tai.xac_nhan_de_xuat');

    Route::get('/thong-tin-de-tai/thong-tin', [ThongTinDeTaiController::class, 'thongTin'])->name('thong_tin_de_tai.thong_tin');
});
