@extends('layouts.app')
@section('title', 'Chi tiết hội đồng')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Chi tiết hội đồng</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $hoiDong->ten_hoi_dong }}</h3>
                        <p><strong>Chuyên nghành:</strong> {{ $hoiDong->chuyenNganh->ten_bo_mon }}</p>
                        <p><strong>Phòng:</strong> {{ $hoiDong->phong }}</p>
                        <p><strong>Ngày tổ chức:</strong> {{ \Carbon\Carbon::parse($hoiDong->ngay)->format('H:i d-m-Y') }}</p>
                        <p><strong>Năm học:</strong> {{ $hoiDong->nam_hoc }}</p>

                        @php $chuTich = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Chủ tịch')->first(); @endphp
                        <p><strong>Chủ tịch:</strong> {{ $chuTich->ho_ten }} - Email:
                            {{ $chuTich->email }} - Số điện thoại: {{ $chuTich->so_dien_thoai }}</p>

                        @php $thuKy = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Thư ký')->first(); @endphp
                        <p><strong>Thư ký:</strong> {{ $thuKy->ho_ten }} - Email:
                            {{ $thuKy->email }} - Số điện thoại: {{ $thuKy->so_dien_thoai }}</p>

                        @if ($hoiDong->giangViens()->wherePivot('chuc_vu', 'Ủy viên')->count() == 1)
                            @php $uyVien = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Ủy viên')->first(); @endphp
                            <p><strong>Ủy viên:</strong> {{ $uyVien->ho_ten }} - Email:
                                {{ $uyVien->email }} - Số điện thoại: {{ $uyVien->so_dien_thoai }}
                            @else
                                @php $uyViens = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Ủy viên')->get(); @endphp
                            <p><strong>Ủy viên:</strong></p>
                            <ul>
                                @foreach ($uyViens as $uyVien)
                                    <li>{{ $uyVien->ho_ten }} - Email: {{ $uyVien->email }} - Số điện thoại:
                                        {{ $uyVien->so_dien_thoai }}
                                @endforeach
                            </ul>
                        @endif

                        <div class="text-center">
                            <a href="{{ route('hoi_dong.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
