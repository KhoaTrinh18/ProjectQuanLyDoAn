@extends('layouts.app')
@section('title', 'Chi tiết giảng viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Chi tiết giảng viên</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <p><strong>Tên giảng viên:</strong> {{ $giangVien->ho_ten }}</p>
                        <p><strong>Email:</strong> {{ $giangVien->email }}</p>
                        <p><strong>Số điện thoại:</strong> {{ $giangVien->so_dien_thoai }}</p>
                        <p><strong>Bộ môn:</strong> {{ $giangVien->boMon->ten_bo_mon }}</p>
                        <p><strong>Học vị:</strong> {{ $giangVien->hocVi->ten_hoc_vi }}</p>
                        <p><strong>Tài khoản:</strong> {{ $giangVien->taiKhoan->ten_tk }}</p>
                        <p><strong>Mật khẩu:</strong> {{ $giangVien->taiKhoan->mat_khau }}</p>

                        <div class="text-center">
                            <a href="{{ route('giang_vien.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
