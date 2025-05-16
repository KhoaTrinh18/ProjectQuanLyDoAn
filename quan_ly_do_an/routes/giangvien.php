<?php

use App\Http\Controllers\DoiMatKhauController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GiangVien\{
    ChamDiemHoiDongController,
    DuaRaDeTaiController,
    ThongTinDeTaiController,
    ChamDiemHuongDanController,
    ChamDiemPhanBienController,
};
use App\Http\Middleware\KiemTraDangNhap;

Route::middleware([KiemTraDangNhap::class . ':giangvien'])->group(function () {
    Route::get('/dua-ra-de-tai/dua-ra', [DuaRaDeTaiController::class, 'duaRa'])->name('dua_ra_de_tai.dua_ra');
    Route::post('/dua-ra-de-tai/xac-nhan-dua-ra', [DuaRaDeTaiController::class, 'xacNhanDuaRa'])->name('dua_ra_de_tai.xac_nhan_dua_ra');
    Route::get('/dua-ra-de-tai/xac-nhan-dua-ra', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/dua-ra-de-tai/danh-sach', [DuaRaDeTaiController::class, 'danhSach'])->name('dua_ra_de_tai.danh_sach');
    Route::get('/dua-ra-de-tai/chi-tiet/{ma_de_tai}', [DuaRaDeTaiController::class, 'chiTiet'])->name('dua_ra_de_tai.chi_tiet');
    Route::get('/dua-ra-de-tai/sua/{ma_de_tai}', [DuaRaDeTaiController::class, 'sua'])->name('dua_ra_de_tai.sua');
    Route::post('/dua-ra-de-tai/xac-nhan-sua', [DuaRaDeTaiController::class, 'xacNhanSua'])->name('dua_ra_de_tai.xac_nhan_sua');
    Route::get('/dua-ra-de-tai/xac-nhan-sua', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/dua-ra-de-tai/huy/{ma_de_tai}', [DuaRaDeTaiController::class, 'huy'])->name('dua_ra_de_tai.huy');
    Route::post('/dua-ra-de-tai/xac-nhan-huy', [DuaRaDeTaiController::class, 'xacNhanHuy'])->name('dua_ra_de_tai.xac_nhan_huy');
    Route::get('/dua-ra-de-tai/xac-nhan-huy', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/thong-tin-de-tai/danh-sach-duyet', [ThongTinDeTaiController::class, 'danhSachDuyet'])->name('thong_tin_de_tai.danh_sach_duyet');
    Route::get('/thong-tin-de-tai/chi-tiet-duyet/{ma_de_tai}', [ThongTinDeTaiController::class, 'chiTietDuyet'])->name('thong_tin_de_tai.chi_tiet_duyet');
    Route::post('/thong-tin-de-tai/huy-sinh-vien', [ThongTinDeTaiController::class, 'huySinhVien'])->name('thong_tin_de_tai.huy_sinh_vien');
    Route::get('/thong-tin-de-tai/huy-sinh-vien', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/thong-tin-de-tai/danh-sach-all', [ThongTinDeTaiController::class, 'danhSachAll'])->name('thong_tin_de_tai.danh_sach_all');
    Route::get('/thong-tin-de-tai/page-ajax', [ThongTinDeTaiController::class, 'pageAjax'])->name('thong_tin_de_tai.page_ajax');
    Route::get('/thong-tin-de-tai/chi-tiet-all/{ma_de_tai}', [ThongTinDeTaiController::class, 'chiTietAll'])->name('thong_tin_de_tai.chi_tiet_all');

    Route::get('/cham-diem-huong-dan/danh-sach', [ChamDiemHuongDanController::class, 'danhSach'])->name('cham_diem_huong_dan.danh_sach');
    Route::get('/cham-diem-huong-dan/chi-tiet/{ma_de_tai}', [ChamDiemHuongDanController::class, 'chiTiet'])->name('cham_diem_huong_dan.chi_tiet');
    Route::get('/cham-diem-huong-dan/cham-diem/{ma_de_tai}', [ChamDiemHuongDanController::class, 'chamDiem'])->name('cham_diem_huong_dan.cham_diem');
    Route::post('/cham-diem-huong-dan/xac-nhan-cham-diem', [ChamDiemHuongDanController::class, 'xacNhanChamDiem'])->name('cham_diem_huong_dan.xac_nhan_cham_diem');
    Route::get('/cham-diem-huong-dan/xac-nhan-cham-diem', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/cham-diem-huong-dan/sua-diem/{ma_de_tai}', [ChamDiemHuongDanController::class, 'suaDiem'])->name('cham_diem_huong_dan.sua_diem');
    Route::post('/cham-diem-huong-dan/xac-nhan-sua-diem', [ChamDiemHuongDanController::class, 'xacNhanSuaDiem'])->name('cham_diem_huong_dan.xac_nhan_sua_diem');
    Route::get('/cham-diem-huong-dan/xac-nhan-sua-diem', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/cham-diem-phan-bien/danh-sach', [ChamDiemPhanBienController::class, 'danhSach'])->name('cham_diem_phan_bien.danh_sach');
    Route::get('/cham-diem-phan-bien/chi-tiet/{ma_de_tai}', [ChamDiemPhanBienController::class, 'chiTiet'])->name('cham_diem_phan_bien.chi_tiet');
    Route::get('/cham-diem-phan-bien/cham-diem/{ma_de_tai}', [ChamDiemPhanBienController::class, 'chamDiem'])->name('cham_diem_phan_bien.cham_diem');
    Route::post('/cham-diem-phan-bien/xac-nhan-cham-diem', [ChamDiemPhanBienController::class, 'xacNhanChamDiem'])->name('cham_diem_phan_bien.xac_nhan_cham_diem');
    Route::get('/cham-diem-phan-bien/xac-nhan-cham-diem', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/cham-diem-phan-bien/sua-diem/{ma_de_tai}', [ChamDiemPhanBienController::class, 'suaDiem'])->name('cham_diem_phan_bien.sua_diem');
    Route::post('/cham-diem-phan-bien/xac-nhan-sua-diem', [ChamDiemPhanBienController::class, 'xacNhanSuaDiem'])->name('cham_diem_phan_bien.xac_nhan_sua_diem');
    Route::get('/cham-diem-phan-bien/xac-nhan-sua-diem', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/cham-diem-hoi-dong/danh-sach', [ChamDiemHoiDongController::class, 'danhSach'])->name('cham_diem_hoi_dong.danh_sach');
    Route::get('/cham-diem-hoi-dong/chi-tiet/{ma_de_tai}', [ChamDiemHoiDongController::class, 'chiTiet'])->name('cham_diem_hoi_dong.chi_tiet');
    Route::get('/cham-diem-hoi-dong/cham-diem/{ma_de_tai}', [ChamDiemHoiDongController::class, 'chamDiem'])->name('cham_diem_hoi_dong.cham_diem');
    Route::post('/cham-diem-hoi-dong/xac-nhan-cham-diem', [ChamDiemHoiDongController::class, 'xacNhanChamDiem'])->name('cham_diem_hoi_dong.xac_nhan_cham_diem');
    Route::get('/cham-diem-hoi-dong/xac-nhan-cham-diem', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/cham-diem-hoi-dong/sua-diem/{ma_de_tai}', [ChamDiemHoiDongController::class, 'suaDiem'])->name('cham_diem_hoi_dong.sua_diem');
    Route::post('/cham-diem-hoi-dong/xac-nhan-sua-diem', [ChamDiemHoiDongController::class, 'xacNhanSuaDiem'])->name('cham_diem_hoi_dong.xac_nhan_sua_diem');
    Route::get('/cham-diem-hoi-dong/xac-nhan-sua-diem', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::post('/doi-mat-khau-giang-vien', [DoiMatKhauController::class, 'doiMatKhau'])->name('doi_mat_khau_gv');
    Route::get('/doi-mat-khau-giang-vien', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/thong-tin-giang-vien', function () {
        return view('giangvien.thongTinGiangVien');
    })->name('thong_tin_giang_vien');
});
