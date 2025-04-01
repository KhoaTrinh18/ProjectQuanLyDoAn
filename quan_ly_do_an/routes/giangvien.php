<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GiangVien\{
    DuaRaDeTaiController
};
use App\Http\Middleware\KiemTraDangNhap;

Route::middleware([KiemTraDangNhap::class])->group(function () {
    Route::get('/dua-ra-de-tai/dua-ra', [DuaRaDeTaiController::class, 'duaRa'])->name('dua_ra_de_tai.dua_ra');
    Route::post('/dua-ra-de-tai/xac-nhan-dua-ra', [DuaRaDeTaiController::class, 'xacNhanDuaRa'])->name('dua_ra_de_tai.xac_nhan_dua_ra');
    Route::get('/dua-ra-de-tai/xac-nhan-dua-ra', function() {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/dua-ra-de-tai/danh-sach-de-tai', [DuaRaDeTaiController::class, 'danhSachDeTai'])->name('dua_ra_de_tai.danh_sach_de_tai');
    Route::get('/dua-ra-de-tai/chi-tiet-de-tai/{ma_de_tai}', [DuaRaDeTaiController::class, 'chiTietDeTai'])->name('dua_ra_de_tai.chi_tiet_de_tai');
});
