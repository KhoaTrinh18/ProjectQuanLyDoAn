@extends('layouts.app')
@section('title', 'Danh sách đề tài hội đồng')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách đề tài hội đồng</h2>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover" style="font-size: 13px">
                            <thead style="background: #222e3c;">
                                <tr>
                                    <th scope="col" class="text-white">#</th>
                                    <th scope="col" class="text-white" style="width: 23%;">Tên đề tài</th>
                                    <th scope="col" class="text-white">Sinh viên thực hiện (Điểm)</th>
                                    <th scope="col" class="text-white">Giảng viên hướng dẫn</th>
                                    <th scope="col" class="text-white">Hội đồng</th>
                                    <th scope="col" class="text-white">Chức vụ</th>
                                    <th scope="col" class="text-white">Trạng thái</th>
                                    <th scope="col" class="text-white"></th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach ($deTais as $key => $deTai)
                                    @php
                                        if (isset($deTai->so_luong_sv_dang_ky)) {
                                            $phanCongHoiDong = $phanCongHoiDongSVDK
                                                ->where('ma_de_tai', $deTai->ma_de_tai)
                                                ->first();
                                        } else {
                                            $phanCongHoiDong = $phanCongHoiDongSVDX
                                                ->where('ma_de_tai', $deTai->ma_de_tai)
                                                ->first();
                                        }

                                        $daChamDiem = 0;
                                        if (isset($phanCongHoiDong->diem_gvthd)) {
                                            $daChamDiem = 1;
                                        } else {
                                            $daChamDiem = 0;
                                        }
                                    @endphp
                                    <tr>
                                        <td scope="row">
                                            {{ $key + 1 }}</td>
                                        <td
                                            style="width: 23%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; word-break: break-word;">
                                            {{ $deTai->ten_de_tai }}
                                        </td>
                                        <td>
                                            @foreach ($deTai->sinhViens as $sinhVien)
                                                @php
                                                    if (isset($deTai->so_luong_sv_dang_ky)) {
                                                        $phanCongHoiDong = $phanCongHoiDongSVDK
                                                            ->where('ma_de_tai', $deTai->ma_de_tai)
                                                            ->where('ma_sv', $sinhVien->ma_sv)
                                                            ->first();
                                                    } else {
                                                        $phanCongHoiDong = $phanCongHoiDongSVDX
                                                            ->where('ma_de_tai', $deTai->ma_de_tai)
                                                            ->where('ma_sv', $sinhVien->ma_sv)
                                                            ->first();
                                                    }

                                                    $daChamDiem = 0;
                                                    if ($phanCongHoiDong->diem_gvthd) {
                                                        $daChamDiem = 1;
                                                    } else {
                                                        $daChamDiem = 0;
                                                    }
                                                @endphp
                                                {{ $sinhVien->ho_ten }} ({!! $phanCongHoiDong->diem_gvthd !== null ? number_format($phanCongHoiDong->diem_gvthd, 2) : '<em>Chưa có</em>' !!})<br>
                                            @endforeach
                                        </td>
                                        <td>
                                            {!! $deTai->giangViens->pluck('ho_ten')->implode('<br>') !!}
                                        </td>
                                        <td>
                                            {{ $deTai->hoiDongs->first()->ten_hoi_dong }}
                                        </td>
                                        <td>
                                            {{ $HoiDong_GiangVien->where('ma_hoi_dong', $deTai->hoiDongs->first()->ma_hoi_dong)->first()->chuc_vu }}
                                        </td>
                                        <td class="text-center">
                                            @if ($daChamDiem)
                                                <span class="text-success">Đã chấm điểm</span>
                                            @else
                                                <span class="text-warning">Chưa chấm điểm</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('cham_diem_hoi_dong.chi_tiet', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                class="btn btn-secondary btn-sm">Xem</a>
                                            @if ($daChamDiem)
                                                <a href="{{ route('cham_diem_hoi_dong.sua_diem', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                    class="btn btn-primary btn-sm">Sửa điểm</a>
                                            @else
                                                <a href="{{ route('cham_diem_hoi_dong.cham_diem', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                                    class="btn btn-primary btn-sm">Chấm điểm</a>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                                @if ($deTais->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center">Không có đề tài</td>
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
