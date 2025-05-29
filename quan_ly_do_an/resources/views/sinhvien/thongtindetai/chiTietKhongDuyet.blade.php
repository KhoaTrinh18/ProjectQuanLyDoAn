@extends('layouts.app')
@section('title', 'Chi tiết đề tài không được duyệt')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Chi tiết đề tài không được duyệt</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTai->ten_de_tai }}</h3>
                        <p><strong>Ngày đề xuất:</strong>
                            {{ \Carbon\Carbon::parse($deTai->ngayDeXuat->ngay_de_xuat)->format('d-m-Y') }}</p>
                        <p><strong>Lĩnh vực:</strong> {{ $deTai->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {!! $deTai->mo_ta !!}</p>

                        <form id="form_de_xuat">
                            <input type="hidden" name="ma_de_tai" value="{{ $deTai->ma_de_tai }}">
                            <div class="text-center">
                                <a href="{{ route('thong_tin_de_tai.danh_sach_khong_duyet') }}"
                                    class="btn btn-secondary btn-lg">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
