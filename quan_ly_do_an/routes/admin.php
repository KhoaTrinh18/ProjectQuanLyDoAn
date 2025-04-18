<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    ThietLapController,
    DeTaiGiangVienController,
    DeTaiSinhVienController,
    PhanCongHuongDanController,
    PhanCongPhanBienController
};
use App\Http\Middleware\KiemTraDangNhap;

Route::middleware([KiemTraDangNhap::class])->group(function () {
    Route::get('/thiet-lap/danh-sach', [ThietLapController::class, 'danhSach'])->name('thiet_lap.danh_sach');
    Route::get('/thiet-lap/them', [ThietLapController::class, 'them'])->name('thiet_lap.them');

    Route::get('/de-tai-giang-vien/danh-sach', [DeTaiGiangVienController::class, 'danhSach'])->name('de_tai_giang_vien.danh_sach');
    Route::get('/de-tai-giang-vien/page-ajax', [DeTaiGiangVienController::class, 'pageAjax'])->name('de_tai_giang_vien.page_ajax');
    Route::get('/de-tai-giang-vien/chi-tiet/{ma_de_tai}', [DeTaiGiangVienController::class, 'chiTiet'])->name('de_tai_giang_vien.chi_tiet');
    Route::get('/de-tai-giang-vien/duyet/{ma_de_tai}', [DeTaiGiangVienController::class, 'duyet'])->name('de_tai_giang_vien.duyet');
    Route::post('/de-tai-giang-vien/xac-nhan-duyet', [DeTaiGiangVienController::class, 'xacNhanDuyet'])->name('de_tai_giang_vien.xac_nhan_duyet');
    Route::get('/de-tai-giang-vien/xac-nhan-duyet', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/de-tai-sinh-vien/danh-sach', [DeTaiSinhVienController::class, 'danhSach'])->name('de_tai_sinh_vien.danh_sach');
    Route::get('/de-tai-sinh-vien/page-ajax', [DeTaiSinhVienController::class, 'pageAjax'])->name('de_tai_sinh_vien.page_ajax');
    Route::get('/de-tai-sinh-vien/chi-tiet/{ma_de_tai}', [DeTaiSinhVienController::class, 'chiTiet'])->name('de_tai_sinh_vien.chi_tiet');
    Route::get('/de-tai-sinh-vien/duyet/{ma_de_tai}', [DeTaiSinhVienController::class, 'duyet'])->name('de_tai_sinh_vien.duyet');
    Route::post('/de-tai-sinh-vien/xac-nhan-duyet', [DeTaiSinhVienController::class, 'xacNhanDuyet'])->name('de_tai_sinh_vien.xac_nhan_duyet');
    Route::get('/de-tai-sinh-vien/xac-nhan-duyet', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/de-tai-sinh-vien/huy/{ma_de_tai}', [DeTaiSinhVienController::class, 'huy'])->name('de_tai_sinh_vien.huy');
    Route::post('/de-tai-sinh-vien/xac-nhan-huy', [DeTaiSinhVienController::class, 'xacNhanHuy'])->name('de_tai_sinh_vien.xac_nhan_huy');
    Route::get('/de-tai-sinh-vien/xac-nhan-huy', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/phan-cong-huong-dan/danh-sach', [PhanCongHuongDanController::class, 'danhSach'])->name('phan_cong_huong_dan.danh_sach');
    Route::get('/phan-cong-huong-dan/page-ajax', [PhanCongHuongDanController::class, 'pageAjax'])->name('phan_cong_huong_dan.page_ajax');
    Route::get('/phan-cong-huong-dan/chi-tiet/{ma_de_tai}', [PhanCongHuongDanController::class, 'chiTiet'])->name('phan_cong_huong_dan.chi_tiet');
    Route::get('/phan-cong-huong-dan/phan-cong/{ma_de_tai}', [PhanCongHuongDanController::class, 'phanCong'])->name('phan_cong_huong_dan.phan_cong');
    Route::post('/phan-cong-huong-dan/xac-nhan-phan-cong', [PhanCongHuongDanController::class, 'xacNhanPhanCong'])->name('phan_cong_huong_dan.xac_nhan_phan_cong');
    Route::get('/phan-cong-huong-dan/xac-nhan-phan-cong', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/phan-cong-huong-dan/sua/{ma_de_tai}', [PhanCongHuongDanController::class, 'sua'])->name('phan_cong_huong_dan.sua');
    Route::post('/phan-cong-huong-dan/xac-nhan-sua', [PhanCongHuongDanController::class, 'xacNhanSua'])->name('phan_cong_huong_dan.xac_nhan_sua');
    Route::get('/phan-cong-huong-dan/xac-nhan-sua', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/phan-cong-phan-bien/danh-sach', [PhanCongPhanBienController::class, 'danhSach'])->name('phan_cong_phan_bien.danh_sach');
    Route::get('/phan-cong-phan-bien/page-ajax', [PhanCongPhanBienController::class, 'pageAjax'])->name('phan_cong_phan_bien.page_ajax');
    Route::get('/phan-cong-phan-bien/chi-tiet/{ma_de_tai}', [PhanCongPhanBienController::class, 'chiTiet'])->name('phan_cong_phan_bien.chi_tiet');
    Route::get('/phan-cong-phan-bien/phan-cong/{ma_de_tai}', [PhanCongPhanBienController::class, 'phanCong'])->name('phan_cong_phan_bien.phan_cong');
    Route::post('/phan-cong-phan-bien/xac-nhan-phan-cong', [PhanCongPhanBienController::class, 'xacNhanPhanCong'])->name('phan_cong_phan_bien.xac_nhan_phan_cong');
    Route::get('/phan-cong-phan-bien/xac-nhan-phan-cong', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/phan-cong-phan-bien/sua/{ma_de_tai}', [PhanCongPhanBienController::class, 'sua'])->name('phan_cong_phan_bien.sua');
    Route::post('/phan-cong-phan-bien/xac-nhan-sua', [PhanCongPhanBienController::class, 'xacNhanSua'])->name('phan_cong_phan_bien.xac_nhan_sua');
    Route::get('/phan-cong-phan-bien/xac-nhan-sua', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
});
