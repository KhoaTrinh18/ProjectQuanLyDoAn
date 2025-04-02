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
    Route::get('/dua-ra-de-tai/danh-sach', [DuaRaDeTaiController::class, 'danhSach'])->name('dua_ra_de_tai.danh_sach');
    Route::get('/dua-ra-de-tai/chi-tiet/{ma_de_tai}', [DuaRaDeTaiController::class, 'chiTiet'])->name('dua_ra_de_tai.chi_tiet');
    Route::get('/dua-ra-de-tai/chinh-sua/{ma_de_tai}', [DuaRaDeTaiController::class, 'chinhSua'])->name('dua_ra_de_tai.chinh_sua');
    Route::post('/dua-ra-de-tai/xac-nhan-chinh-sua', [DuaRaDeTaiController::class, 'xacNhanChinhSua'])->name('dua_ra_de_tai.xac_nhan_chinh_sua');
    Route::get('/dua-ra-de-tai/xac-nhan-chinh-sua', function() {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/dua-ra-de-tai/huy/{ma_de_tai}', [DuaRaDeTaiController::class, 'huy'])->name('dua_ra_de_tai.huy');
    Route::post('/dua-ra-de-tai/xac-nhan-huy', [DuaRaDeTaiController::class, 'xacNhanHuy'])->name('dua_ra_de_tai.xac_nhan_huy');
    Route::get('/dua-ra-de-tai/xac-nhan-huy', function() {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/dua-ra-de-tai/danh-sach-huy', [DuaRaDeTaiController::class, 'danhSachHuy'])->name('dua_ra_de_tai.danh_sach_huy');
    Route::get('/dua-ra-de-tai/khoiPhuc/{ma_de_tai}', [DuaRaDeTaiController::class, 'khoiPhuc'])->name('dua_ra_de_tai.khoi_phuc');
    Route::post('/dua-ra-de-tai/xac-nhan-khoi-phuc', [DuaRaDeTaiController::class, 'xacNhanKhoiPhuc'])->name('dua_ra_de_tai.xac_nhan_khoi_phuc');
    Route::get('/dua-ra-de-tai/xac-nhan-khoi-phuc', function() {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
});
