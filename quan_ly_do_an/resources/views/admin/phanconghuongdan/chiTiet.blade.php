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

                        @if ($deTai->sinhViens->count() == 1)
                            @php $sinhVien = $deTai->sinhViens->first(); @endphp
                            <p><strong>Sinh viên thực hiện:</strong> {{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }}) -
                                Email: {{ $sinhVien->email }} - Số điện thoại:
                                {{ $sinhVien->so_dien_thoai }}
                            @else
                            <p><strong>Sinh viên thực hiện:</strong></p>
                            <ul>
                                @foreach ($deTai->sinhViens as $sinhVien)
                                    <li>{{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }}) - Email: {{ $sinhVien->email }} - Số
                                        điện thoại:
                                        {{ $sinhVien->so_dien_thoai }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if ($deTai->giangViens->count() == 0)
                            <p><strong>Giảng viên hướng dẫn:</strong> <i>Chưa có</i></p>
                        @elseif ($deTai->giangViens->count() == 1)
                            @php $giangVien = $deTai->giangViens->first(); @endphp
                            <p><strong>Giảng viên hướng dẫn:</strong> {{ $giangVien->ho_ten }} - Email:
                                {{ $giangVien->email }} - Số điện thoại: {{ $giangVien->so_dien_thoai }}
                            @else
                            <p><strong>Giảng viên hướng dẫn:</strong></p>
                            <ul>
                                @foreach ($deTai->giangViens as $giangVien)
                                    <li>{{ $giangVien->ho_ten }} - Email: {{ $giangVien->email }} - Số điện thoại:
                                        {{ $giangVien->so_dien_thoai }}
                                @endforeach
                            </ul>
                        @endif

                        @if ($deTai->sinhViens->first()->loai_sv == 'dang_ky')
                            @if ($deTai->giangViens->count() == 1)
                                @php $giangVien = $deTai->giangViens->first(); @endphp
                                <p><strong>Giảng viên ra đề tài:</strong> {{ $giangVien->ho_ten }} - Email:
                                    {{ $giangVien->email }} - Số điện thoại: {{ $giangVien->so_dien_thoai }}
                                @else
                                <p><strong>Giảng viên ra đề tài:</strong></p>
                                <ul>
                                    @foreach ($deTai->giangViens as $giangVien)
                                        <li>{{ $giangVien->ho_ten }} - Email: {{ $giangVien->email }} - Số điện thoại:
                                            {{ $giangVien->so_dien_thoai }}
                                    @endforeach
                                </ul>
                            @endif
                        @endif

                        <p><strong>Lĩnh vực:</strong> {{ $deTai->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {!! $deTai->mo_ta !!}</p>
                        <div class="text-center">
                            <a href="{{ route('phan_cong_huong_dan.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
