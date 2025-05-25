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
                                </strong><i>Chưa có</i></p>
                        @elseif ($deTai->so_luong_sv_dang_ky == 1)
                            <form class="d-flex align-items-center" id="form_huy_sv">
                                <p class="m-0"><strong>Sinh viên thực hiện:
                                    </strong>{{ $deTai->sinhViens->first()->ho_ten }}
                                    ({{ $deTai->sinhViens->first()->mssv }}) - Email:
                                    {{ $deTai->sinhViens->first()->email }} - Số điện thoại:
                                    {{ $deTai->sinhViens->first()->so_dien_thoai }}
                                </p>
                            </form>
                        @else
                            <p><strong>Sinh viên thực hiện:</strong></p>
                            <ul>
                                @foreach ($deTai->sinhViens as $sinhVien)
                                    <li class="mt-2">
                                        <div class="d-flex align-items-center">
                                            <p class="student-name m-0">{{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }}) -
                                                Email: {{ $sinhVien->email }} - Số điện thoại:
                                                {{ $sinhVien->so_dien_thoai }}
                                            </p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="text-center mt-3">
                            <a href="{{ route('thong_tin_de_tai.danh_sach_huong_dan') }}" class="btn btn-secondary btn-lg">Quay
                                lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
