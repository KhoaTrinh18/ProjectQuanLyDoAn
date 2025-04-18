@extends('layouts.app')
@section('title', 'Danh sách đề tài hướng dẫn')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách đề tài hướng dẫn</h2>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="background: #222e3c;">
                                <tr>
                                    <th scope="col" class="text-white">#</th>
                                    <th scope="col" class="text-white" style="width: 40%;">Tên đề tài</th>
                                    <th scope="col" class="text-white">Sinh viên thực hiện</th>
                                    <th scope="col" class="text-white">Giảng viên hướng dẫn</th>
                                    <th scope="col" class="text-white">Trạng thái</th>
                                    <th scope="col" class="text-white"></th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach ($deTais as $key => $deTai)
                                    @php
                                        if ($deTai->so_luong_sv_dang_ky) {
                                            $phanCong = $phanCongSVDK->where('ma_de_tai', $deTai->ma_de_tai)->first();
                                        } else {
                                            $phanCong = $phanCongSVDX->where('ma_de_tai', $deTai->ma_de_tai)->first();
                                        }

                                        if ($phanCong) {
                                            if ($phanCong->diem_GVHD) {
                                                $daChamDiem = 1;
                                            } else {
                                                $daChamDiem = 0;
                                            }
                                        } else {
                                            $daChamDiem = 0;
                                        }
                                    @endphp
                                    <tr>
                                        <td scope="row">
                                            {{ $key + 1 }}</td>
                                        <td
                                            style="width: 40%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; word-break: break-word;">
                                            {{ $deTai->ten_de_tai }}
                                        </td>
                                        <td>
                                            {!! $deTai->sinhViens->pluck('ho_ten')->implode('<br>') !!}
                                        </td>
                                        <td>
                                            {!! $deTai->giangViens->pluck('ho_ten')->implode('<br>') !!}
                                        </td>
                                        <td class="text-center">
                                            @if ($daChamDiem)
                                                <span class="text-success">Đã chấm điểm</span>
                                            @else
                                                <span class="text-warning">Chưa chấm điểm</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('cham_diem_de_tai.chi_tiet_huong_dan', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                class="btn btn-secondary btn-sm">Xem</a>
                                            @if ($daChamDiem)
                                                <a href="{{ route('cham_diem_de_tai.sua_diem_huong_dan', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                    class="btn btn-primary btn-sm">Sửa điểm</a>
                                            @else
                                                <a href="{{ route('cham_diem_de_tai.cham_diem_huong_dan', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                    class="btn btn-primary btn-sm">Chấm điểm</a>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                                @if ($deTais->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">Không có đề tài</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {});
    </script>
@endsection
