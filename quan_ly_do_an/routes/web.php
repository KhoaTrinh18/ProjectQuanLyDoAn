<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DangKyDeTaisController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dang-ky-de-tai', [DangKyDeTaisController::class, 'index'])->name('dang_ky_de_tai.index');
Route::get('/dang-ky-de-tai/page-ajax', [DangKyDeTaisController::class, 'pageAjax'])->name('dang_ky_de_tai.pageAjax');
