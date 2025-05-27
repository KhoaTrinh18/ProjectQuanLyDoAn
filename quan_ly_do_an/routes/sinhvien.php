<?php

use App\Http\Controllers\DoiMatKhauController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SinhVien\{
    DangKyDeTaiController,
    DeXuatDeTaiController,
    ThongTinDeTaiController
};
use App\Http\Middleware\{
    KiemTraDangNhap,
    KiemTraDoiMatKhau
};


Route::middleware([KiemTraDangNhap::class . ':sinhvien'])->group(function () {
    Route::get('/dang-ky-de-tai', [DangKyDeTaiController::class, 'danhSach'])->name('dang_ky_de_tai.danh_sach');
    Route::get('/dang-ky-de-tai/page-ajax', [DangKyDeTaiController::class, 'pageAjax'])->name('dang_ky_de_tai.page_ajax');
    Route::get('/dang-ky-de-tai/dang-ky/{ma_de_tai}', [DangKyDeTaiController::class, 'dangKy'])->name('dang_ky_de_tai.dang_ky');
    Route::post('/dang-ky-de-tai/xac-nhan-dang-ky', [DangKyDeTaiController::class, 'xacNhanDangKy'])->name('dang_ky_de_tai.xac_nhan_dang_ky');
    Route::get('/dang-ky-de-tai/xac-nhan-dang-ky', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::middleware(KiemTraDoiMatKhau::class)->group(function () {
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
        Route::post('/thong-tin-de-tai/xac-nhan-sua', [ThongTinDeTaiController::class, 'xacNhanSua'])->name('thong_tin_de_tai.xac_nhan_sua');
        Route::get('/thong-tin-de-tai/xac-nhan-sua', function () {
            return redirect()->back()->with('error', 'Sai đường dẫn');
        });
        Route::get('/thong-tin-de-tai/danh-sach-khong-duyet', [ThongTinDeTaiController::class, 'danhSachKhongDuyet'])->name('thong_tin_de_tai.danh_sach_khong_duyet');
        Route::get('/thong-tin-de-tai/chi-tiet-khong-duyet/{ma_de_tai}', [ThongTinDeTaiController::class, 'chiTietKhongDuyet'])->name('thong_tin_de_tai.chi_tiet_khong_duyet');
        Route::get('/thong-tin-de-tai/nop-bao-cao', [ThongTinDeTaiController::class, 'nopBaoCao'])->name('thong_tin_de_tai.nop_bao_cao');
        Route::post('/thong-tin-de-tai/xac-nhan-nop', [ThongTinDeTaiController::class, 'xacNhanNop'])->name('thong_tin_de_tai.xac_nhan_nop');
        Route::get('/thong-tin-de-tai/xac-nhan-nop', function () {
            return redirect()->back()->with('error', 'Sai đường dẫn');
        });
        Route::post('/thong-tin-de-tai/tai-bao-cao', [ThongTinDeTaiController::class, 'taiBaoCao'])->name('thong_tin_de_tai.tai_bao_cao');
        Route::get('/thong-tin-de-tai/tai-bao-cao', function () {
            return redirect()->back()->with('error', 'Sai đường dẫn');
        });
    });

    Route::post('/doi-mat-khau', [DoiMatKhauController::class, 'doiMatKhau'])->name('doi_mat_khau');
    Route::get('/doi-mat-khau', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/thong-tin-sinh-vien', function () {
        return view('sinhvien.thongTinSinhVien');
    })->name('thong_tin_sinh_vien');
});
