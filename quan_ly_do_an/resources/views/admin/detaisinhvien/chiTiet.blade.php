@extends('layouts.app')
@section('title', 'Chi tiết đề tài sinh viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Chi tiết đề tài sinh viên</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTaiSV->ten_de_tai }}</h3>

                        @if ($deTaiSV->sinhViens->count() == 1)
                            @php $sinhVien = $deTaiSV->sinhViens->first(); @endphp
                            <p><strong>Sinh viên đề xuất:</strong> {{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }}) - Email:
                                {{ $sinhVien->email }} - Số điện thoại: {{ $sinhVien->so_dien_thoai }}
                            @else
                            <p><strong>Sinh viên đề xuất:</strong></p>
                            <ul>
                                @foreach ($deTaiSV->sinhViens as $sinhVien)
                                    <li>{{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }}) - Email: {{ $sinhVien->email }} - Số
                                        điện thoại: {{ $sinhVien->so_dien_thoai }}
                                @endforeach
                            </ul>
                        @endif

                        <p><strong>Ngày đề xuất:</strong>
                            {{ \Carbon\Carbon::parse($deTaiSV->ngayDeXuat->ngay_de_xuat)->format('d-m-Y') }}</p>
                        @if ($deTaiSV->giangVienDuKiens->count() == 1)
                            @php $giangVien = $deTaiSV->giangVienDuKiens->first(); @endphp
                            <p><strong>Giảng viên hướng dẫn (dự kiến):</strong>
                                {{ $giangVien->ho_ten }} - Email:
                                {{ $giangVien->email }} - Số điện thoại: {{ $giangVien->so_dien_thoai }}</p>
                        @else
                            <p><strong>Giảng viên hướng dẫn (dự kiến):</strong></p>
                            <ul>
                                @foreach ($deTaiSV->giangVienDuKiens as $giangVien)
                                    <li>{{ $giangVien->ho_ten }} - Email: {{ $giangVien->email }} - SĐT:
                                        {{ $giangVien->so_dien_thoai }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <p><strong>Lĩnh vực:</strong> {{ $deTaiSV->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {!! $deTaiSV->mo_ta !!}</p>

                        <div class="text-center">
                            <a href="{{ route('de_tai_sinh_vien.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
