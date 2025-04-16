@extends('layouts.app')
@section('title', 'Duyệt đề tài giảng viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Duyệt đề tài giảng viên</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTaiGV->ten_de_tai }}</h3>

                        @if ($deTaiGV->giangViens->count() == 1)
                            @php $giangVien = $deTaiGV->giangViens->first(); @endphp
                            <p><strong>Giảng viên ra đề tài:</strong> {{ $giangVien->ho_ten }} - Email:
                                {{ $giangVien->email }} - Số điện thoại: {{ $giangVien->so_dien_thoai }}</p>
                        @else
                            <ul>
                                @foreach ($deTaiGV->giangViens as $giangVien)
                                    <li>{{ $giangVien->ho_ten }} - Email: {{ $giangVien->email }} - SĐT:
                                        {{ $giangVien->so_dien_thoai }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <p><strong>Ngày đưa ra:</strong> {{ $deTaiGV->ngayDuaRa->ngay_dua_ra }}</p>
                        <p><strong>Lĩnh vực:</strong> {{ $deTaiGV->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {!! $deTaiGV->mo_ta !!}</p>

                        <div class="text-center">
                            <a href="{{ route('de_tai_giang_vien.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
