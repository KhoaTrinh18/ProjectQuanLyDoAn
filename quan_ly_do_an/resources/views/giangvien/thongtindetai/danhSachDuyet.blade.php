@extends('layouts.app')
@section('title', 'Danh sách đề tài đã duyệt')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách đề tài</h2>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover" style="font-size: 13px">
                            <thead style="background: #222e3c;">
                                <tr>
                                    <th scope="col" class="text-white">#</th>
                                    <th scope="col" class="text-white" style="width: 35%;">Tên đề tài</th>
                                    <th scope="col" class="text-white">Lĩnh vực</th>
                                    <th scope="col" class="text-white">Số lượng sinh viên đăng ký</th>
                                    <th scope="col" class="text-white">Trạng thái</th>
                                    <th scope="col" class="text-white"></th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach ($deTais as $key => $deTai)
                                    <tr>
                                        <td scope="row">
                                            {{ $key + 1 }}</td>
                                        <td
                                            style="width: 35%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; word-break: break-word;">
                                            {{ $deTai->ten_de_tai }}
                                        </td>
                                        <td>{{ $deTai->linhVuc->ten_linh_vuc }}</td>
                                        <td class="text-center">
                                            {{ $deTai->so_luong_sv_dang_ky . '/' . $deTai->so_luong_sv_toi_da }}
                                        </td>
                                        <td>
                                            @if ($deTai->da_xac_nhan_huong_dan == 0)
                                                <span class="text-warning">Chờ xác nhận</span>
                                            @else
                                                <span class="text-success">Đã xác nhận</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($deTai->da_xac_nhan_huong_dan == 0)
                                                <a href="{{ route('thong_tin_de_tai.chi_tiet_duyet', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                    class="btn btn-success btn-sm">Xác nhận hướng dẫn</a>
                                            @else
                                                <a href="{{ route('thong_tin_de_tai.chi_tiet_duyet', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                    class="btn btn-danger btn-sm">Hủy xác nhận</a>
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
        $(document).ready(function() {
            const deadline = new Date("{{ $ngayHetHan }}");

            const interval = setInterval(() => {
                const now = new Date();
                if (now >= deadline) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Hết thời gian!',
                        text: 'Bạn đã hết thời gian xác nhận hướng dẫn.',
                        confirmButtonText: 'OK',
                        showConfirmButton: true
                    }).then(() => {
                        window.location.href =
                            "{{ route('thong_tin_de_tai.danh_sach_huong_dan') }}";
                    });
                    clearInterval(interval);
                }
            }, 10);
        })
    </script>
@endsection
