<?php

use App\Http\Controllers\DangNhapController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\{
    KiemTraDangNhap,
    KiemTraDangXuat
};

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([KiemTraDangXuat::class])->group(function () {
    Route::get('/dang-nhap', [DangNhapController::class, 'dangNhap'])->name('dang_nhap');
    Route::post('/dang-nhap/xac-nhan-dang-nhap', [DangNhapController::class, 'xacNhanDangNhap'])->name('xac_nhan_dang_nhap');
    Route::get('/dang-nhap/xac-nhan-dang-nhap', function() {
        return redirect()->back()->with('error', 'Sai đường dẫn');
    });
});

Route::post('/dang-xuat', [DangNhapController::class, 'dangXuat'])->name('dang_xuat');
Route::get('/dang-xuat', function() {
    return redirect()->back()->with('error', 'Sai đường dẫn');
});

require __DIR__ . '/admin.php';
require __DIR__ . '/giangvien.php';
require __DIR__ . '/sinhvien.php';
