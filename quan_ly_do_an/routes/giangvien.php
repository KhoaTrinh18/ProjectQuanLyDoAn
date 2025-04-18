<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GiangVien\{
    DuaRaDeTaiController,
    ThongTinDeTaiController,
    ChamDiemDeTaiController
};
use App\Http\Middleware\KiemTraDangNhap;

Route::middleware([KiemTraDangNhap::class. ':giangvien'])->group(function () {
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
    Route::get('/dua-ra-de-tai/khoi-phuc/{ma_de_tai}', [DuaRaDeTaiController::class, 'khoiPhuc'])->name('dua_ra_de_tai.khoi_phuc');
    Route::post('/dua-ra-de-tai/xac-nhan-khoi-phuc', [DuaRaDeTaiController::class, 'xacNhanKhoiPhuc'])->name('dua_ra_de_tai.xac_nhan_khoi_phuc');
    Route::get('/dua-ra-de-tai/xac-nhan-khoi-phuc', function() {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/thong-tin-de-tai/danh-sach-duyet', [ThongTinDeTaiController::class, 'danhSachDuyet'])->name('thong_tin_de_tai.danh_sach_duyet');
    Route::get('/thong-tin-de-tai/chi-tiet-duyet/{ma_de_tai}', [ThongTinDeTaiController::class, 'chiTietDuyet'])->name('thong_tin_de_tai.chi_tiet_duyet');
    Route::post('/thong-tin-de-tai/huy-sinh-vien', [ThongTinDeTaiController::class, 'huySinhVien'])->name('thong_tin_de_tai.huy_sinh_vien');
    Route::get('/thong-tin-de-tai/huy-sinh-vien', function() {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/cham-diem-de-tai/danh-sach-huong-dan', [ChamDiemDeTaiController::class, 'danhSachHuongDan'])->name('cham_diem_de_tai.danh_sach_huong_dan');
    Route::get('/cham-diem-de-tai/chi-tiet-huong-dan/{ma_de_tai}', [ChamDiemDeTaiController::class, 'chiTietHuongDan'])->name('cham_diem_de_tai.chi_tiet_huong_dan');
    Route::get('/cham-diem-de-tai/cham-diem-huong-dan/{ma_de_tai}', [ChamDiemDeTaiController::class, 'chamDiemHuongDan'])->name('cham_diem_de_tai.cham_diem_huong_dan');
    Route::post('/cham-diem-de-tai/xac-nhan-cham-diem-huong-dan', [ChamDiemDeTaiController::class, 'xacNhanChamDiemHuongDan'])->name('cham_diem_de_tai.xac_nhan_cham_diem_huong_dan');
    Route::get('/thong-tin-de-tai/xac-nhan-cham-diem-huong-dan', function() {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/cham-diem-de-tai/sua-diem-huong-dan/{ma_de_tai}', [ChamDiemDeTaiController::class, 'suaDiemHuongDan'])->name('cham_diem_de_tai.sua_diem_huong_dan');
    Route::post('/cham-diem-de-tai/xac-nhan-sua-diem-huong-dan', [ChamDiemDeTaiController::class, 'xacNhanSuaDiemHuongDan'])->name('cham_diem_de_tai.xac_nhan_sua_diem_huong_dan');
    Route::get('/thong-tin-de-tai/xac-nhan-sua-diem-huong-dan', function() {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
});
