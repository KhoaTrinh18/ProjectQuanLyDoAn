<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    ThietLapController,
    DeTaiGiangVienController,
    DeTaiSinhVienController,
    PhanCongHuongDanController
};
use App\Http\Middleware\KiemTraDangNhap;

Route::middleware([KiemTraDangNhap::class])->group(function () {
    Route::get('/thiet-lap/danh-sach', [ThietLapController::class, 'danhSach'])->name('thiet_lap.danh_sach');
    Route::get('/thiet-lap/them', [ThietLapController::class, 'them'])->name('thiet_lap.them');

    Route::get('/de-tai-giang-vien/danh-sach', [DeTaiGiangVienController::class, 'danhSach'])->name('de_tai_giang_vien.danh_sach');
    Route::get('/de-tai-giang-vien/page-ajax', [DeTaiGiangVienController::class, 'pageAjax'])->name('de_tai_giang_vien.page_ajax');
    Route::get('/de-tai-giang-vien/duyet/{ma_de_tai}', [DeTaiGiangVienController::class, 'duyet'])->name('de_tai_giang_vien.duyet');
    Route::post('/de-tai-giang-vien/xac-nhan-duyet', [DeTaiGiangVienController::class, 'xacNhanDuyet'])->name('de_tai_giang_vien.xac_nhan_duyet');
    Route::get('/de-tai-giang-vien/xac-nhan-duyet', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/de-tai-sinh-vien/danh-sach', [DeTaiSinhVienController::class, 'danhSach'])->name('de_tai_sinh_vien.danh_sach');
    Route::get('/de-tai-sinh-vien/page-ajax', [DeTaiSinhVienController::class, 'pageAjax'])->name('de_tai_sinh_vien.page_ajax');
    Route::get('/de-tai-sinh-vien/duyet/{ma_de_tai}', [DeTaiSinhVienController::class, 'duyet'])->name('de_tai_sinh_vien.duyet');
    Route::post('/de-tai-sinh-vien/xac-nhan-duyet', [DeTaiSinhVienController::class, 'xacNhanDuyet'])->name('de_tai_sinh_vien.xac_nhan_duyet');
    Route::get('/de-tai-sinh-vien/xac-nhan-duyet', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/phan-cong-huong-dan/danh-sach', [PhanCongHuongDanController::class, 'danhSach'])->name('phan_cong_huong_dan.danh_sach');
    Route::get('/phan-cong-huong-dan/page-ajax', [PhanCongHuongDanController::class, 'pageAjax'])->name('phan_cong_huong_dan.page_ajax');
    Route::get('/phan-cong-huong-dan/phan-cong/{ma_de_tai}', [PhanCongHuongDanController::class, 'phanCong'])->name('phan_cong_huong_dan.phan_cong');
    Route::post('/phan-cong-huong-dan/xac-nhan-phan-cong', [PhanCongHuongDanController::class, 'xacNhanPhanCong'])->name('phan_cong_huong_dan.xac_nhan_phan_cong');
    Route::get('/phan-cong-huong-dan/xac-nhan-phan-cong', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
});
