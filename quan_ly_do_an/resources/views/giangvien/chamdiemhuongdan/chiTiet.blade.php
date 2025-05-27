@extends('layouts.app')
@section('title', 'Chi tiết đề tài hướng dẫn')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Chi tiết đề tài hướng dẫn</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTai->ten_de_tai }}</h3>

                        <p><strong>Xác nhận bảo vệ: </strong>
                            @if ($deTai->duoc_bao_ve == 1)
                                <span class="text-success">Được bảo vệ</span>
                            @elseif (isset($deTai->duoc_bao_ve))
                                <span class="text-danger">Không được bảo vệ</span>
                            @else
                                <i>Chưa có</i>
                            @endif
                        </p>

                        @if ($deTai->sinhViens->count() == 1)
                            @php
                                $sinhVien = $deTai->sinhViens->first();
                                if (isset($deTai->so_luong_sv_dang_ky)) {
                                    $phanCongSV = $phanCongSVDK
                                        ->where('ma_de_tai', $deTai->ma_de_tai)
                                        ->where('ma_sv', $sinhVien->ma_sv)
                                        ->first();
                                } else {
                                    $phanCongSV = $phanCongSVDX
                                        ->where('ma_de_tai', $deTai->ma_de_tai)
                                        ->where('ma_sv', $sinhVien->ma_sv)
                                        ->first();
                                }
                            @endphp
                            @if ($sinhVien->trang_thai == 3)
                                <p>Sinh viên thực hiện: {{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }}) (<span
                                        class="text-danger">Nghỉ giữa
                                        chừng</span>)<br></p>
                            @else
                                <p><strong>Sinh viên thực hiện:</strong> {{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }}) -
                                    Điểm:
                                    {!! $phanCongSV->diem_gvhd !== null ? number_format($phanCongSV->diem_gvhd, 1) : '<em>Chưa có</em>' !!}</p>
                            @endif
                        @else
                            <p><strong>Sinh viên thực hiện:</strong></p>
                            <ul>
                                @foreach ($deTai->sinhViens as $sinhVien)
                                    @php
                                        if (isset($deTai->so_luong_sv_dang_ky)) {
                                            $phanCongSV = $phanCongSVDK
                                                ->where('ma_de_tai', $deTai->ma_de_tai)
                                                ->where('ma_sv', $sinhVien->ma_sv)
                                                ->first();
                                        } else {
                                            $phanCongSV = $phanCongSVDX
                                                ->where('ma_de_tai', $deTai->ma_de_tai)
                                                ->where('ma_sv', $sinhVien->ma_sv)
                                                ->first();
                                        }
                                    @endphp
                                    @if ($sinhVien->trang_thai == 3)
                                        <li>{{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }}) (<span
                                                class="text-danger">Nghỉ giữa
                                                chừng</span>)<br></li>
                                    @else
                                        <li>{{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }}) - Điểm:
                                            {!! $phanCongSV->diem_gvhd !== null ? number_format($phanCongSV->diem_gvhd, 1) : '<em>Chưa có</em>' !!}
                                    @endif
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
                            <a href="{{ route('cham_diem_huong_dan.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
