@extends('layouts.app')
@section('title', 'Chi tiết đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Chi tiết đề tài</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTai->ten_de_tai }}</h3>
                        <p><strong>Lĩnh vực:</strong>
                            @if ($deTai->da_cham_diem)
                                <span class="text-success">Đã chấm điểm</span>
                            @else
                                <span class="text-success">Chưa chấm điểm</span>
                            @endif
                        </p>
                        @if ($deTai->giangViens->count() == 1)
                            @php $giangVien = $deTai->giangViens->first(); @endphp
                            <p><strong>Giảng viên ra đề tài:</strong> {{ $giangVien->ho_ten }} - Email:
                                {{ $giangVien->email }} - Số điện thoại: {{ $giangVien->so_dien_thoai }}</p>
                        @else
                            <ul>
                                @foreach ($deTai->giangViens as $giangVien)
                                    <li>{{ $giangVien->ho_ten }} - Email: {{ $giangVien->email }} - SĐT:
                                        {{ $giangVien->so_dien_thoai }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <p><strong>Lĩnh vực:</strong> {{ $deTai->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {!! $deTai->mo_ta !!}</p>
                        @if ($deTai->so_luong_sv_dang_ky < 1)
                            <p><strong>Sinh viên đã đăng ký:
                                </strong>chưa có</p>
                        @elseif ($deTai->so_luong_sv_dang_ky == 1)
                            @php $sinhVien = $deTai->sinhViens->first(); @endphp
                            <p><strong>Sinh viên đã đăng ký:
                                </strong>{{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }})
                            </p>
                        @else
                            <p><strong>Sinh viên đã đăng ký:</strong></p>
                            <ul>
                                @foreach ($deTai->sinhViens as $sinhVien)
                                    <li>{{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }})</li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="text-center">
                            <a href="{{ route('cham_diem_de_tai.danh_sach_huong_dan') }}"
                                class="btn btn-secondary btn-lg">Quay
                                lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
