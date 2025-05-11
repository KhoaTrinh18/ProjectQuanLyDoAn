@extends('layouts.app')
@section('title', 'Chi tiết sinh viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Chi tiết sinh viên</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <p><strong>MSSV:</strong> {{ $sinhVien->mssv }}</p>
                        <p><strong>Tên sinh viên:</strong> {{ $sinhVien->ho_ten }}</p>
                        <p><strong>Lớp:</strong> {{ $sinhVien->lop }}</p>
                        <p><strong>Email:</strong> {{ $sinhVien->email }}</p>
                        <p><strong>Số điện thoại:</strong> {{ $sinhVien->so_dien_thoai }}</p>
                        @if (!isset($sinhVien->taiKhoan))
                            <p><strong>Tài khoản: </strong><i>Chưa có</i></p>
                        @else
                            <p><strong>Tên tài khoản:</strong> {{ $sinhVien->taiKhoan->ten_tk }}</p>
                            <p><strong>Mật khẩu:</strong> {{ $sinhVien->taiKhoan->mat_khau }}</p>
                        @endif
                        <p><strong>Trạng thái:</strong>
                            @if ($sinhVien->trang_thai == 0)
                                <span class="text-danger">Không hoàn thành</span>
                            @elseif($sinhVien->trang_thai == 1)
                                <span class="text-warning">Đang thực hiện</span>
                            @elseif($sinhVien->trang_thai == 2)
                                <span class="text-success">Đã hoàn thành</span>
                            @else
                                <span class="text-danger">Nghỉ giữa chừng</span>
                            @endif
                        </p>
                        @if ($sinhVien->dang_ky == 0)
                            <p><strong>Tên đề tài: </strong><i>Chưa có</i></p>
                        @else
                            <p><strong>Tên đề tài: </strong>{{ $deTai->ten_de_tai }}</p>

                            @php $giangVienHDs = $deTai->giangVienHuongDans()->wherePivot('ma_sv', $sinhVien->ma_sv)->get(); @endphp
                            @if ($giangVienHDs->isEmpty())
                                <p><strong>Giảng viên hướng dẫn: </strong><i>Chưa có</i></p>
                            @else
                                @if ($giangVienHDs->count() === 1)
                                    @php $gv = $giangVienHDs->first(); @endphp
                                    <p class="mb-0"><strong>Giảng viên hướng dẫn: </strong>{{ $gv->ho_ten }}</p>
                                    <ul>
                                        <li><strong>Điểm:
                                            </strong><i>{{ $gv->pivot->diem_gvhd ? number_format($gv->pivot->diem_gvhd, 2) : 'Chưa có' }}</i>
                                        </li>
                                        <li><strong>Nhận xét: </strong>{!! $gv->pivot->nhan_xet ?? '<em>Chưa có</em>' !!}</li>
                                    </ul>
                                @else
                                    <p class="mb-0"><strong>Giảng viên hướng dẫn:</strong></p>
                                    <ul>
                                        @foreach ($giangVienHDs as $gv)
                                            <li class="mb-2">
                                                {{ $gv->ho_ten }}<br>
                                                <strong>Điểm:
                                                </strong><i>{{ $gv->pivot->diem_gvhd ? number_format($gv->pivot->diem_gvhd, 2) : 'Chưa có' }}</i><br>
                                                <strong>Nhận xét: </strong>{!! $gv->pivot->nhan_xet ?? '<em>Chưa có</em>' !!}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endif

                            @php $giangVienPB = $deTai->giangVienPhanBiens()->wherePivot('ma_sv', $sinhVien->ma_sv)->first(); @endphp
                            @if (empty($giangVienPB))
                                <p><strong>Giảng viên phản biện: </strong><i>Chưa có</i></p>
                            @else
                                @php $gv = $giangVienPB; @endphp
                                <p class="mb-0"><strong>Giảng viên phản biện: </strong>{{ $gv->ho_ten }}</p>
                                <ul>
                                    <li><strong>Điểm:
                                        </strong><i>{{ $gv->pivot->diem_gvpb ? number_format($gv->pivot->diem_gvpb, 2) : 'Chưa có' }}</i>
                                    </li>
                                    <li><strong>Nhận xét: </strong>{!! $gv->pivot->nhan_xet ?? '<em>Chưa có</em>' !!}</li>
                                </ul>
                            @endif

                            @php
                                $deTaiHoiDong = [];

                                if (isset($deTai->so_luong_sv_dang_ky)) {
                                    $deTaiHoiDong = DB::table('bang_diem_gvthd_cho_svdk')
                                        ->where(['ma_de_tai' => $deTai->ma_de_tai, 'ma_sv' => $sinhVien->ma_sv])
                                        ->get();
                                } else {
                                    $deTaiHoiDong = DB::table('bang_diem_gvthd_cho_svdx')
                                        ->where(['ma_de_tai' => $deTai->ma_de_tai, 'ma_sv' => $sinhVien->ma_sv])
                                        ->get();
                                }
                            @endphp

                            @if ($deTai->hoiDongs->isEmpty() || $deTaiHoiDong->isEmpty())
                                <p><strong>Hội đồng: </strong><i>Chưa có</i></p>
                            @else
                                @php
                                    $hoiDong = $deTai->hoiDongs()->first();
                                    $chuTich = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Chủ tịch')->first();
                                    $thuKy = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Thư ký')->first();
                                    $uyViens = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Ủy viên')->get();
                                @endphp
                                <p><strong>Hội đồng:</strong> {{ $hoiDong->ten_hoi_dong }}</p>
                                <p><strong>Ngày tổ chức:</strong>
                                    {{ \Carbon\Carbon::parse($hoiDong->ngay)->format('H:i d-m-Y') }}</p>
                                <p><strong>Phòng:</strong> {{ $hoiDong->phong }}</p>
                                <p class="mb-0"><strong>Chủ tịch: </strong>{{ $chuTich->ho_ten }}</p>
                                <ul class="mb-3">
                                    <li><strong>Điểm:
                                        </strong><i>{{ $deTaiHoiDong->where('ma_gvthd', $chuTich->ma_gv)->first()->diem_gvthd ? number_format($deTaiHoiDong->where('ma_gvthd', $chuTich->ma_gv)->first()->diem_gvthd, 2) : 'Chưa có' }}</i>
                                    </li>
                                    <li><strong>Nhận xét: </strong>{!! $deTaiHoiDong->where('ma_gvthd', $chuTich->ma_gv)->first()->nhan_xet ?? '<em>Chưa có</em>' !!}</li>
                                </ul>

                                <p class="mb-0"><strong>Thư ký: </strong>{{ $thuKy->ho_ten }}</p>
                                <ul class="mb-3">
                                    <li><strong>Điểm:
                                        </strong><i>{{ $deTaiHoiDong->where('ma_gvthd', $thuKy->ma_gv)->first()->diem_gvthd ? number_format($deTaiHoiDong->where('ma_gvthd', $thuKy->ma_gv)->first()->diem_gvthd, 2) : 'Chưa có' }}</i>
                                    </li>
                                    <li><strong>Nhận xét: </strong>{!! $deTaiHoiDong->where('ma_gvthd', $thuKy->ma_gv)->first()->nhan_xet ?? '<em>Chưa có</em>' !!}</li>
                                </ul>

                                @if ($uyViens->count() === 1)
                                    @php $uyVien = $uyViens->first(); @endphp
                                    <p class="mb-0"><strong>Ủy viên: </strong>{{ $uyVien->ho_ten }}</p>
                                    <ul>
                                        <li><strong>Điểm:
                                            </strong><i>{{ $deTaiHoiDong->where('ma_gvthd', $uyVien->ma_gv)->first()->diem_gvthd ? number_format($deTaiHoiDong->where('ma_gvthd', $uyVien->ma_gv)->first()->diem_gvthd, 2) : 'Chưa có' }}</i>
                                        </li>
                                        <li><strong>Nhận xét: </strong>{!! $deTaiHoiDong->where('ma_gvthd', $uyVien->ma_gv)->first()->nhan_xet ?? '<em>Chưa có</em>' !!}</li>
                                    </ul>
                                @else
                                    <p class="mb-0"><strong>Ủy viên:</strong></p>
                                    <ul>
                                        @foreach ($uyViens as $uyVien)
                                            <li class="mb-2">
                                                {{ $uyVien->ho_ten }}<br>
                                                <strong>Điểm:
                                                </strong><i>{{ $deTaiHoiDong->where('ma_gvthd', $uyVien->ma_gv)->first()->diem_gvthd ? number_format($deTaiHoiDong->where('ma_gvthd', $uyVien->ma_gv)->first()->diem_gvthd, 2) : 'Chưa có' }}</i><br>
                                                <strong>Nhận xét: </strong>{!! $deTaiHoiDong->where('ma_gvthd', $uyVien->ma_gv)->first()->nhan_xet ?? '<em>Chưa có</em>' !!}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endif
                        @endif
                        <p style="font-size: 20px"><strong>Điểm tổng:</strong>
                            <i>{{ $sinhVien->diem ?? 'Chưa có' }}</i>
                        </p>

                        <div class="text-center">
                            <a href="{{ route('sinh_vien.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
