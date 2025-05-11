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
                        <table class="table table-bordered table-striped table-hover" style="font-size: 13px">
                            <thead style="background: #222e3c;">
                                <tr>
                                    <th scope="col" class="text-white">#</th>
                                    <th scope="col" class="text-white" style="width: 35%;">Tên đề tài</th>
                                    <th scope="col" class="text-white">Lĩnh vực</th>
                                    <th scope="col" class="text-white">Sinh viên thực hiện (Điểm)</th>
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
                                        <td>
                                            {{ $deTai->linhVuc->ten_linh_vuc }}
                                        </td>
                                        <td>
                                            @foreach ($deTai->sinhViens as $sinhVien)
                                                @php
                                                    if (isset($deTai->so_luong_sv_dang_ky)) {
                                                        $phanCong = $phanCongSVDK
                                                            ->where('ma_de_tai', $deTai->ma_de_tai)
                                                            ->where('ma_sv', $sinhVien->ma_sv)
                                                            ->first();
                                                    } else {
                                                        $phanCong = $phanCongSVDX
                                                            ->where('ma_de_tai', $deTai->ma_de_tai)
                                                            ->where('ma_sv', $sinhVien->ma_sv)
                                                            ->first();
                                                    }

                                                    $daChamDiem = 0;
                                                    if ($phanCong->diem_gvhd) {
                                                        $daChamDiem = 1;
                                                    } else {
                                                        $daChamDiem = 0;
                                                    }
                                                @endphp
                                                {{ $sinhVien->ho_ten }} ({!! $phanCong->diem_gvhd !== null ? number_format($phanCong->diem_gvhd, 2) : '<em>Chưa có</em>' !!})<br>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @if ($daChamDiem)
                                                <span class="text-success">Đã chấm điểm</span>
                                            @else
                                                <span class="text-warning">Chưa chấm điểm</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('cham_diem_huong_dan.chi_tiet', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                class="btn btn-secondary btn-sm">Xem</a>
                                            @if ($daChamDiem)
                                                <a href="{{ route('cham_diem_huong_dan.sua_diem', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                    class="btn btn-primary btn-sm">Sửa điểm</a>
                                            @else
                                                <a href="{{ route('cham_diem_huong_dan.cham_diem', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
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
