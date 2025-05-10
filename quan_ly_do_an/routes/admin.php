<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    BoMonController,
    ThietLapController,
    DeTaiGiangVienController,
    DeTaiSinhVienController,
    GiangVienController,
    HocViController,
    PhanCongHuongDanController,
    PhanCongPhanBienController,
    HoiDongController,
    PhanCongHoiDongController,
    SinhVienController,
    SinhVienDeTaiAllController
};
use App\Http\Middleware\KiemTraDangNhap;

Route::middleware([KiemTraDangNhap::class. ':admin'])->group(function () {
    Route::get('/thiet-lap/danh-sach', [ThietLapController::class, 'danhSach'])->name('thiet_lap.danh_sach');
    Route::get('/thiet-lap/them', [ThietLapController::class, 'them'])->name('thiet_lap.them');
    Route::post('/thiet-lap/xac-nhan-them', [ThietLapController::class, 'xacNhanThem'])->name('thiet_lap.xac_nhan_them');
    Route::get('/thiet-lap/xac-nhan-them', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/thiet-lap/sua/{ma_thiet_lap}', [ThietLapController::class, 'sua'])->name('thiet_lap.sua');
    Route::post('/thiet-lap/xac-nhan-sua', [ThietLapController::class, 'xacNhanSua'])->name('thiet_lap.xac_nhan_sua');
    Route::get('/thiet-lap/xac-nhan-sua', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::post('/thiet-lap/huy', [ThietLapController::class, 'huy'])->name('thiet_lap.huy');
    Route::get('/thiet-lap/huy', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

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
    Route::get('/phan-cong-phan-bien/huy/{ma_de_tai}', [PhanCongPhanBienController::class, 'huy'])->name('phan_cong_phan_bien.huy');
    Route::post('/phan-cong-phan-bien/xac-nhan-huy', [PhanCongPhanBienController::class, 'xacNhanHuy'])->name('phan_cong_phan_bien.xac_nhan_huy');
    Route::get('/phan-cong-phan-bien/xac-nhan-huy', function () {
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
    Route::get('/phan-cong-hoi-dong/huy/{ma_de_tai}', [PhanCongHoiDongController::class, 'huy'])->name('phan_cong_hoi_dong.huy');
    Route::post('/phan-cong-hoi-dong/xac-nhan-huy', [PhanCongHoiDongController::class, 'xacNhanHuy'])->name('phan_cong_hoi_dong.xac_nhan_huy');
    Route::get('/phan-cong-hoi-dong/xac-nhan-huy', function () {
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

    Route::get('/hoc-vi/danh-sach', [HocViController::class, 'danhSach'])->name('hoc_vi.danh_sach');
    Route::get('/hoc-vi/them', [HocViController::class, 'them'])->name('hoc_vi.them');
    Route::post('/hoc-vi/xac-nhan-them', [HocViController::class, 'xacNhanThem'])->name('hoc_vi.xac_nhan_them');
    Route::get('/hoc-vi/xac-nhan-them', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::post('/hoc-vi/huy', [HocViController::class, 'huy'])->name('hoc_vi.huy');
    Route::get('/hoc-vi/huy', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/hoc-vi/sua/{ma_hoc_vi}', [HocViController::class, 'sua'])->name('hoc_vi.sua');
    Route::post('/hoc-vi/xac-nhan-sua', [HocViController::class, 'xacNhanSua'])->name('hoc_vi.xac_nhan_sua');
    Route::get('/hoc-vi/xac-nhan-sua', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });

    Route::get('/sinh-vien/danh-sach', [SinhVienController::class, 'danhSach'])->name('sinh_vien.danh_sach');
    Route::get('/sinh-vien/page-ajax', [SinhVienController::class, 'pageAjax'])->name('sinh_vien.page_ajax');
    Route::get('/sinh-vien/chi-tiet/{ma_sv}', [SinhVienController::class, 'chiTiet'])->name('sinh_vien.chi_tiet');
    Route::get('/sinh-vien/them', [SinhVienController::class, 'them'])->name('sinh_vien.them');
    Route::post('/sinh-vien/xac-nhan-them', [SinhVienController::class, 'xacNhanThem'])->name('sinh_vien.xac_nhan_them');
    Route::get('/sinh-vien/xac-nhan-them', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/sinh-vien/huy/{ma_sv}', [SinhVienController::class, 'huy'])->name('sinh_vien.huy');
    Route::post('/sinh-vien/xac-nhan-huy', [SinhVienController::class, 'xacNhanHuy'])->name('sinh_vien.xac_nhan_huy');
    Route::get('/sinh-vien/xac-nhan-huy', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/sinh-vien/sua/{ma_sv}', [SinhVienController::class, 'sua'])->name('sinh_vien.sua');
    Route::post('/sinh-vien/xac-nhan-sua', [SinhVienController::class, 'xacNhanSua'])->name('sinh_vien.xac_nhan_sua');
    Route::get('/sinh-vien/xac-nhan-sua', function () {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
    Route::get('/sinh-vien/tai-csv-mau', [SinhVienController::class, 'taiCSVMau'])->name('sinh_vien.tai_csv_mau');
    Route::get('/sinh-vien/tao-tai-khoan', [SinhVienController::class, 'taoTaiKhoan'])->name('sinh_vien.tao_tai_khoan');
    Route::get('/sinh-vien/tai-danh-sach-tai-khoan', [SinhVienController::class, 'taiDSTaiKhoan'])->name('sinh_vien.tai_ds_tai_khoan');
    Route::get('/sinh-vien/tai-danh-sach-sinh-vien', [SinhVienController::class, 'taiDSSinhVien'])->name('sinh_vien.tai_ds_sinh_vien');

    Route::get('/sinh-vien-de-tai-all/danh-sach', [SinhVienDeTaiAllController::class, 'danhSach'])->name('sinh_vien_de_tai_all.danh_sach');
    Route::get('/sinh-vien-de-tai-all/page-ajax', [SinhVienDeTaiAllController::class, 'pageAjax'])->name('sinh_vien_de_tai_all.page_ajax');
    Route::get('/sinh-vien-de-tai-all/chi-tiet/{ma_sv}', [SinhVienDeTaiAllController::class, 'chiTiet'])->name('sinh_vien_de_tai_all.chi_tiet');
});
