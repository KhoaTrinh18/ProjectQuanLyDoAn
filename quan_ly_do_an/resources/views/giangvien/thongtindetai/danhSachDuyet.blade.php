@extends('layouts.app')
@section('title', 'Danh sách đề tài đã duyệt')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách đề tài đã duyệt</h2>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="background: #222e3c;">
                                <tr>
                                    <th scope="col" class="text-white">#</th>
                                    <th scope="col" class="text-white" style="width: 30%;">Tên đề tài</th>
                                    <th scope="col" class="text-white">Lĩnh vực</th>
                                    <th scope="col" class="text-white">Sinh viên đăng ký (Ngày đăng ký)</th>
                                    <th scope="col" class="text-white">Số lượng sinh viên đăng ký</th>
                                    <th scope="col" class="text-white"></th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach ($deTais as $key => $deTai)
                                    <tr>
                                        <td scope="row">
                                            {{ $key + 1 }}</td>
                                        <td
                                            style="width: 30%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; word-break: break-word;">
                                            {{ $deTai->ten_de_tai }}
                                        </td>
                                        <td>{{ $deTai->linhVuc->ten_linh_vuc }}</td>
                                        <td>
                                            @if ($deTai->sinhViens->count() == 0)
                                                chưa có
                                            @else
                                                @foreach ($deTai->sinhViens as $sinhVien)
                                                    @php
                                                        $phanCongSVDK = DB::Table('bang_phan_cong_svdk')
                                                            ->where('ma_sv', $sinhVien->ma_sv)
                                                            ->first();
                                                        $ngayDangKy = \Carbon\Carbon::create(
                                                            $phanCongSVDK->ngay_dang_ky,
                                                        )->format('d-m-Y');
                                                    @endphp
                                                    {{ $sinhVien->ho_ten }} ({{ $ngayDangKy }})</br>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($deTai->so_luong_sv_dang_ky < $deTai->so_luong_sv_toi_da)
                                                <span
                                                    class="text-danger">{{ $deTai->so_luong_sv_dang_ky . '/' . $deTai->so_luong_sv_toi_da }}</span>
                                            @else
                                                <span
                                                    class="text-success">{{ $deTai->so_luong_sv_dang_ky . '/' . $deTai->so_luong_sv_toi_da }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('thong_tin_de_tai.chi_tiet_duyet', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                class="btn btn-danger btn-sm">Hủy đăng ký</a>
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
