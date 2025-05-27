@extends('layouts.app')
@section('title', 'Chi tiết đề tài hội đồng')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Chi tiết đề tài hội đồng</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTai->ten_de_tai }}</h3>
                        @if ($deTai->sinhViens->count() == 1)
                            @php
                                $sinhVien = $deTai->sinhViens->first();
                                if (isset($deTai->so_luong_sv_dang_ky)) {
                                    $phanCongSV = $phanCongHoiDongSVDK
                                        ->where('ma_de_tai', $deTai->ma_de_tai)
                                        ->where('ma_sv', $sinhVien->ma_sv)
                                        ->first();
                                } else {
                                    $phanCongSV = $phanCongHoiDongSVDX
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
                                    {!! $phanCongSV->diem_gvthd !== null ? number_format($phanCongSV->diem_gvthd, 1) : '<em>Chưa có</em>' !!}</p>
                            @endif
                        @else
                            <p><strong>Sinh viên thực hiện:</strong></p>
                            <ul>
                                @foreach ($deTai->sinhViens as $sinhVien)
                                    @php
                                        if (isset($deTai->so_luong_sv_dang_ky)) {
                                            $phanCongSV = $phanCongHoiDongSVDK
                                                ->where('ma_de_tai', $deTai->ma_de_tai)
                                                ->where('ma_sv', $sinhVien->ma_sv)
                                                ->first();
                                        } else {
                                            $phanCongSV = $phanCongHoiDongSVDX
                                                ->where('ma_de_tai', $deTai->ma_de_tai)
                                                ->where('ma_sv', $sinhVien->ma_sv)
                                                ->first();
                                        }
                                    @endphp
                                    @if ($sinhVien->trang_thai == 3)
                                        <p>{{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }}) (<span
                                                class="text-danger">Nghỉ giữa
                                                chừng</span>)<br></p>
                                    @else
                                        <li>{{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }}) - Điểm:
                                            {!! $phanCongSV->diem_gvthd !== null ? number_format($phanCongSV->diem_gvthd, 1) : '<em>Chưa có</em>' !!}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif

                        @if ($deTai->giangViens->count() == 1)
                            @php $giangVien = $deTai->giangViens->first(); @endphp
                            <p><strong>Giảng viên hướng dẫn:</strong> {{ $giangVien->ho_ten }} - Email:
                                {{ $giangVien->email }} - Số điện thoại: {{ $giangVien->so_dien_thoai }}</p>
                        @else
                            <p><strong>Giảng viên hướng dẫn:</strong></p>
                            <ul>
                                @foreach ($deTai->giangViens as $giangVien)
                                    <li>{{ $giangVien->ho_ten }} - Email: {{ $giangVien->email }} - SĐT:
                                        {{ $giangVien->so_dien_thoai }}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if ($deTai->hoiDongs->count() == 0)
                            <p><strong>Hội đồng:</strong> Chưa có</p>
                        @elseif ($deTai->hoiDongs->count() == 1)
                            @php $hoiDong = $deTai->hoiDongs->first(); @endphp
                            <p><strong>Hội đồng:</strong> {{ $hoiDong->ten_hoi_dong }}</p>
                            <p><strong>Phòng:</strong> {{ $hoiDong->phong }}</p>
                            <p><strong>Ngày tổ chức:</strong>
                                {{ \Carbon\Carbon::parse($hoiDong->ngay)->format('H:i d-m-Y') }}</p>

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
                            <a href="{{ route('cham_diem_hoi_dong.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
