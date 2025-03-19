<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SinhVien\{
    DangKyDeTaisController, 
    DeXuatDeTaisController
};

Route::get('/dang-ky-de-tai', [DangKyDeTaisController::class, 'index'])->name('dang_ky_de_tai.index');
Route::get('/dang-ky-de-tai/page-ajax', [DangKyDeTaisController::class, 'pageAjax'])->name('dang_ky_de_tai.page_ajax');
Route::get('/dang-ky-de-tai/dang-ky/{ma_de_tai}', [DangKyDeTaisController::class, 'dangKy'])->name('dang_ky_de_tai.dang_ky');

Route::get('/de-xuat-de-tai/de-xuat', [DeXuatDeTaisController::class, 'deXuat'])->name('de_xuat_de_tai.de_xuat');