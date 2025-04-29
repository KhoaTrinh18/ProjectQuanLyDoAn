@extends('layouts.app')
@section('title', 'Danh sách đề tài không được duyệt')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách đề tài không được duyệt</h2>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="background: #222e3c;">
                                <tr>
                                    <th scope="col" class="text-white">#</th>
                                    <th scope="col" class="text-white" style="width: 40%;">Tên đề tài</th>
                                    <th scope="col" class="text-white">Lĩnh vực</th>
                                    <th scope="col" class="text-white">Sinh viên</th>
                                    <th scope="col" class="text-white">Ngày đề xuất</th>
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
                                        <td>{!! $deTai->sinhViens->pluck('ho_ten')->implode('<br>') !!}</td>
                                        <td>{{ \Carbon\Carbon::parse($deTai->ngayDeXuat->ngay_de_xuat)->format('d-m-Y') }}</td>
                                        <td><span class="text-danger">Không duyệt</span></td>
                                        <td class="text-center">
                                            <a href="{{ route('thong_tin_de_tai.chi_tiet_khong_duyet', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                class="btn btn-secondary btn-sm">Xem</a>
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
