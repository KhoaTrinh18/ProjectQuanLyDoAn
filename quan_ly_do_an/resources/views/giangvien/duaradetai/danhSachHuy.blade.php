@extends('layouts.app')
@section('title', 'Danh sách đề tài hủy')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách đề tài hủy</h2>
                        <a href="{{ route('dua_ra_de_tai.danh_sach') }}" class="btn btn-secondary btn-lg">Quay lại</a>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="background: #222e3c;">
                                <tr>
                                    <th scope="col" class="text-white">#</th>
                                    <th scope="col" class="text-white" style="width: 40%;">Tên đề tài</th>
                                    <th scope="col" class="text-white">Lĩnh vực</th>
                                    <th scope="col" class="text-white">Số lượng sinh viên tối đa</th>
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
                                            style="width: 40%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; word-break: break-word;">
                                            {{ $deTai->ten_de_tai }}
                                        </td>
                                        <td>{{ $deTai->linhVuc->ten_linh_vuc }}</td>
                                        <td class="text-center">{{ $deTai->so_luong_sv_toi_da }}</td>
                                        <td class="text-danger text-center">đã hủy</td>
                                        <td class="text-center">
                                            <a href="{{ route('dua_ra_de_tai.khoi_phuc', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                class="btn btn-primary btn-sm">Khôi phục</a>
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
                        <h5 class="text-center" style="font-weight: bold"><i>Khi đề tài đã được duyệt giảng viên không thể
                                tự hủy. Nếu cần hủy thì phải liên hệ với trưởng khoa trong thời gian quy định!</i>
                        </h5>
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
