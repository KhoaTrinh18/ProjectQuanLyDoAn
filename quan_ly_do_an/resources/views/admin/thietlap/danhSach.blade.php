@extends('layouts.app')
@section('title', 'Danh sách thiết lập')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách thiết lập</h2>
                        <div>
                            <a href="{{ route('thiet_lap.them') }}" class="btn btn-success btn-lg">Thêm mới</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="background: #222e3c;">
                                <tr>
                                    <th scope="col" class="text-white">#</th>
                                    <th scope="col" class="text-white" style="width: 40%;">Năm học</th>
                                    <th scope="col" class="text-white">Ngày kết thúc</th>
                                    <th scope="col" class="text-white">Trạng thái</th>
                                    <th scope="col" class="text-white"></th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach ($thietLaps as $key => $thietLap)
                                    <tr>
                                        <td scope="row">
                                            {{ $key + 1 }}</td>
                                        <td
                                            style="width: 40%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; word-break: break-word;">
                                            {{ $thietLap->nam_hoc }}
                                        </td>
                                        <td>{{ $thietLap->ngay_ket_thuc }}</td>
                                        <td>
                                            @if ($thietLap->trang_thai == 1)
                                                <span class="text-warning">Đang hoạt động</span>
                                            @else
                                                <span class="text-success">Đã hoàn thành</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{-- @if ($deTai->trang_thai != 1)
                                                <a href="{{ route('dua_ra_de_tai.chi_tiet', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                    class="btn btn-secondary btn-sm">Xem</a>
                                            @else
                                                <a href="{{ route('dua_ra_de_tai.huy', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                    class="btn btn-danger btn-sm">hủy</a>
                                                <a href="{{ route('dua_ra_de_tai.chinh_sua', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                    class="btn btn-primary btn-sm">Chỉnh sửa</a>
                                            @endif --}}
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($thietLaps->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">Không có thiết lập</td>
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
