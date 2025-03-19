@extends('layouts.app')
@section('title', 'Chi tiết đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">{{ $deTai->ten_de_tai }}</h2>
                    </div>
                    <div class="card-body">
                        {{-- <p><strong>Giảng viên hướng dẫn:</strong> {{ $deTai->giang_vien }}</p> --}}
                        <p><strong>Lĩnh vực:</strong> {{ $deTai->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {{ $deTai->mo_ta }}</p>

                        <form action="{{ route('dang_ky_de_tai.dang_ky', $deTai->ma_de_tai) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Đăng ký đề tài</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

