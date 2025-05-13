@extends('layouts.app')
@section('title', 'Danh sách đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách đề tài</h2>
                        <div>
                            <a href="{{ route('dua_ra_de_tai.dua_ra') }}" class="btn btn-success btn-lg">Đưa ra</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover" style="font-size: 13px">
                            <thead style="background: #222e3c;">
                                <tr>
                                    <th scope="col" class="text-white">#</th>
                                    <th scope="col" class="text-white" style="width: 32%;">Tên đề tài</th>
                                    <th scope="col" class="text-white">Lĩnh vực</th>
                                    <th scope="col" class="text-white">Số lượng sinh viên tối đa</th>
                                    <th scope="col" class="text-white">Ngày đưa ra</th>
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
                                            style="width: 32%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; word-break: break-word;">
                                            {{ $deTai->ten_de_tai }}
                                        </td>
                                        <td>{{ $deTai->linhVuc->ten_linh_vuc }}</td>
                                        <td class="text-center">{{ $deTai->so_luong_sv_toi_da }}</td>
                                        <td>{{ \Carbon\Carbon::create($deTai->ngayDuaRa->ngay_dua_ra)->format('d-m-Y') }}</td>
                                        <td>
                                            @if ($deTai->trang_thai == 1)
                                                <span class="text-warning">Chờ duyệt</span>
                                            @elseif ($deTai->trang_thai == 2)
                                                <span class="text-success">Đã duyệt</span>
                                            @else
                                                <span class="text-danger">Không duyệt</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('dua_ra_de_tai.chi_tiet', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                class="btn btn-secondary btn-sm">Xem</a>
                                            @if ($deTai->trang_thai == 1 && $checkNgayHetHan == 0)
                                                <a href="{{ route('dua_ra_de_tai.huy', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                    class="btn btn-danger btn-sm">hủy</a>
                                                <a href="{{ route('dua_ra_de_tai.sua', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                    class="btn btn-primary btn-sm">Sửa</a>
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
                        <h5 class="text-center" style="font-weight: bold"><i>Khi đề tài đã được duyệt giảng viên không thể
                                tự hủy. Nếu cần hủy thì phải liên hệ với trưởng khoa trước thời gian đăng ký 1 tuần ({{ $ngayHetHan }})!</i>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
