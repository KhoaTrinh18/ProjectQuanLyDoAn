@extends('layouts.app')
@section('title', 'Chi tiết đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Chi tiêt đề tài</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTai->ten_de_tai }}</h3>
                        @if ($deTai->giangViens)
                            <p><strong>Giảng viên ra đề tài:</strong>
                                {{ $deTai->giangViens->pluck('ho_ten')->implode(', ') }}
                            </p>
                        @endif
                        <p><strong>Lĩnh vực:</strong> {{ $deTai->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {!! $deTai->mo_ta !!}</p>
                        <div class="text-center">
                            <a href="{{ route('thong_tin_de_tai.thong_tin') }}" class="btn btn-secondary btn-lg">Quay lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
