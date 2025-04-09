<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    ThietLapController,
    DeTaiGiangVienController
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
    
});
