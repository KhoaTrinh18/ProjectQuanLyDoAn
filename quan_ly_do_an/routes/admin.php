<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    BoMonController,
    ThietLapController,
    DeTaiGiangVienController,
    DeTaiSinhVienController,
    GiangVienController,
    PhanCongHuongDanController,
    PhanCongPhanBienController,
    HoiDongController,
    PhanCongHoiDongController
};
use App\Http\Middleware\KiemTraDangNhap;

Route::middleware([KiemTraDangNhap::class])->group(function () {
    Route::get('/thiet-lap/danh-sach', [ThietLapController::class, 'danhSach'])->name('thiet_lap.danh_sach');
    Route::get('/thiet-lap/them', [ThietLapController::class, 'them'])->name('thiet_lap.them');

    Route::get('/de-tai-giang-vien/danh-sach', [DeTaiGiangVienController::class, 'danhSach'])->name('de_tai_giang_vien.danh_sach');
    Route::get('/de-tai-giang-vien/page-ajax', [DeTaiGiangVienController::class, 'pageAjax'])->name('de_tai_giang_vien.page_ajax');
    Route::get('/de-tai-giang-vien/chi-tiet/{ma_de_tai}', [DeTaiGiangVienController::class, 'chiTiet'])->name('de_tai_giang_vien.chi_tiet');
    Route::get('/de-tai-giang-vien/duyet/{ma_de_tai}', [DeTaiGiangVienController::class, 'duyet'])->name('de_tai_giang_vien.duyet');
    Route::post('/de-tai-giang-vien/xac-nhan-khong-duyet', [DeTaiGiangVienController::class, 'xacNhanKhongDuyet'])->name('de_tai_giang_vien.xac_nhan_khong_duyet');
    Route::get('/de-tai-giang-vien/xac-nhan-khong-duyet', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::post('/de-tai-giang-vien/xac-nhan-duyet', [DeTaiGiangVienController::class, 'xacNhanDuyet'])->name('de_tai_giang_vien.xac_nhan_duyet');
    Route::get('/de-tai-giang-vien/xac-nhan-duyet', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/de-tai-giang-vien/huy/{ma_de_tai}', [DeTaiGiangVienController::class, 'huy'])->name('de_tai_giang_vien.huy');
    Route::post('/de-tai-giang-vien/xac-nhan-huy', [DeTaiGiangVienController::class, 'xacNhanHuy'])->name('de_tai_giang_vien.xac_nhan_huy');
    Route::get('/de-tai-giang-vien/xac-nhan-huy', function () {
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
    Route::post('/de-tai-sinh-vien/xac-nhan-khong-duyet', [DeTaiSinhVienController::class, 'xacNhanKhongDuyet'])->name('de_tai_sinh_vien.xac_nhan_khong_duyet');
    Route::get('/de-tai-sinh-vien/xac-nhan-khong-duyet', function () {
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

    Route::get('/hoi-dong/danh-sach', [HoiDongController::class, 'danhSach'])->name('hoi_dong.danh_sach');
    Route::get('/hoi-dong/page-ajax', [HoiDongController::class, 'pageAjax'])->name('hoi_dong.page_ajax');
    Route::get('/hoi-dong/chi-tiet/{ma_hoi_dong}', [HoiDongController::class, 'chiTiet'])->name('hoi_dong.chi_tiet');
    Route::get('/hoi-dong/them', [HoiDongController::class, 'them'])->name('hoi_dong.them');
    Route::post('/hoi-dong/xac-nhan-them', [HoiDongController::class, 'xacNhanThem'])->name('hoi_dong.xac_nhan_them');
    Route::get('/hoi-dong/xac-nhan-them', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/hoi-dong/huy/{ma_hoi_dong}', [HoiDongController::class, 'huy'])->name('hoi_dong.huy');
    Route::post('/hoi-dong/xac-nhan-huy', [HoiDongController::class, 'xacNhanHuy'])->name('hoi_dong.xac_nhan_huy');
    Route::get('/hoi-dong/xac-nhan-huy', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/hoi-dong/sua/{ma_hoi_dong}', [HoiDongController::class, 'sua'])->name('hoi_dong.sua');
    Route::post('/hoi-dong/xac-nhan-sua', [HoiDongController::class, 'xacNhanSua'])->name('hoi_dong.xac_nhan_sua');
    Route::get('/hoi-dong/xac-nhan-sua', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/phan-cong-hoi-dong/danh-sach', [PhanCongHoiDongController::class, 'danhSach'])->name('phan_cong_hoi_dong.danh_sach');
    Route::get('/phan-cong-hoi-dong/page-ajax', [PhanCongHoiDongController::class, 'pageAjax'])->name('phan_cong_hoi_dong.page_ajax');
    Route::get('/phan-cong-hoi-dong/chi-tiet/{ma_de_tai}', [PhanCongHoiDongController::class, 'chiTiet'])->name('phan_cong_hoi_dong.chi_tiet');
    Route::get('/phan-cong-hoi-dong/phan-cong/{ma_de_tai}', [PhanCongHoiDongController::class, 'phanCong'])->name('phan_cong_hoi_dong.phan_cong');
    Route::post('/phan-cong-hoi-dong/xac-nhan-phan-cong', [PhanCongHoiDongController::class, 'xacNhanPhanCong'])->name('phan_cong_hoi_dong.xac_nhan_phan_cong');
    Route::get('/phan-cong-hoi-dong/xac-nhan-phan-cong', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/phan-cong-hoi-dong/sua/{ma_de_tai}', [PhanCongHoiDongController::class, 'sua'])->name('phan_cong_hoi_dong.sua');
    Route::post('/phan-cong-hoi-dong/xac-nhan-sua', [PhanCongHoiDongController::class, 'xacNhanSua'])->name('phan_cong_hoi_dong.xac_nhan_sua');
    Route::get('/phan-cong-hoi-dong/xac-nhan-sua', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/giang-vien/danh-sach', [GiangVienController::class, 'danhSach'])->name('giang_vien.danh_sach');
    Route::get('/giang-vien/page-ajax', [GiangVienController::class, 'pageAjax'])->name('giang_vien.page_ajax');
    Route::get('/giang-vien/chi-tiet/{ma_gv}', [GiangVienController::class, 'chiTiet'])->name('giang_vien.chi_tiet');
    Route::get('/giang-vien/them', [GiangVienController::class, 'them'])->name('giang_vien.them');
    Route::post('/giang-vien/xac-nhan-them', [GiangVienController::class, 'xacNhanThem'])->name('giang_vien.xac_nhan_them');
    Route::get('/giang-vien/xac-nhan-them', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/giang-vien/huy/{ma_gv}', [GiangVienController::class, 'huy'])->name('giang_vien.huy');
    Route::post('/giang-vien/xac-nhan-huy', [GiangVienController::class, 'xacNhanHuy'])->name('giang_vien.xac_nhan_huy');
    Route::get('/hoi-dong/xac-nhan-huy', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/giang-vien/sua/{ma_gv}', [GiangVienController::class, 'sua'])->name('giang_vien.sua');
    Route::post('/giang-vien/xac-nhan-sua', [GiangVienController::class, 'xacNhanSua'])->name('giang_vien.xac_nhan_sua');
    Route::get('/giang-vien/xac-nhan-sua', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/giang-vien/danh-sach', [GiangVienController::class, 'danhSach'])->name('giang_vien.danh_sach');
    Route::get('/giang-vien/page-ajax', [GiangVienController::class, 'pageAjax'])->name('giang_vien.page_ajax');
    Route::get('/giang-vien/chi-tiet/{ma_gv}', [GiangVienController::class, 'chiTiet'])->name('giang_vien.chi_tiet');
    Route::get('/giang-vien/them', [GiangVienController::class, 'them'])->name('giang_vien.them');
    Route::post('/giang-vien/xac-nhan-them', [GiangVienController::class, 'xacNhanThem'])->name('giang_vien.xac_nhan_them');
    Route::get('/giang-vien/xac-nhan-them', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/giang-vien/huy/{ma_gv}', [GiangVienController::class, 'huy'])->name('giang_vien.huy');
    Route::post('/giang-vien/xac-nhan-huy', [GiangVienController::class, 'xacNhanHuy'])->name('giang_vien.xac_nhan_huy');
    Route::get('/giang-vien/xac-nhan-huy', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/giang-vien/sua/{ma_gv}', [GiangVienController::class, 'sua'])->name('giang_vien.sua');
    Route::post('/giang-vien/xac-nhan-sua', [GiangVienController::class, 'xacNhanSua'])->name('giang_vien.xac_nhan_sua');
    Route::get('/giang-vien/xac-nhan-sua', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/bo-mon/danh-sach', [BoMonController::class, 'danhSach'])->name('bo_mon.danh_sach');
    Route::get('/bo-mon/them', [BoMonController::class, 'them'])->name('bo_mon.them');
    Route::post('/bo-mon/xac-nhan-them', [BoMonController::class, 'xacNhanThem'])->name('bo_mon.xac_nhan_them');
    Route::get('/bo-mon/xac-nhan-them', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::post('/bo-mon/huy', [BoMonController::class, 'huy'])->name('bo_mon.huy');
    Route::get('/bo-mon/huy', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/bo-mon/sua/{ma_bo_mon}', [BoMonController::class, 'sua'])->name('bo_mon.sua');
    Route::post('/bo-mon/xac-nhan-sua', [BoMonController::class, 'xacNhanSua'])->name('bo_mon.xac_nhan_sua');
    Route::get('/bo-mon/xac-nhan-sua', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
});
