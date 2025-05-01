@extends('layouts.app')
@section('title', 'Thông tin đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    @if (!$daDangKy || $deTai->trang_thai == 0)
                        <div class="card-header d-flex justify-content-center align-items-center flex-column">
                            <h2 style="font-weight: bold"><i>Bạn chưa có đề tài</i></h2>
                            <h5 style="font-weight: bold"><i>(Vui lòng đề xuất hoặc đăng ký!)</i></h5>
                        </div>
                    @else
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h2 style="font-weight: bold">Thông tin đề tài</h2>
                        </div>
                        <div class="card-body" style="font-size: 16px">
                            <p><strong>Đề tài: </strong>{{ $deTai->ten_de_tai }} (<a
                                    href="{{ route('thong_tin_de_tai.chi_tiet') }}">Chi tiết</a>)</p>

                            @if ($loaiDeTai == 'de_tai_sv')
                                <p><strong>Hình thức:</strong> Đề xuất</p>
                                <p><strong>Ngày đề xuất:</strong>
                                    {{ \Carbon\Carbon::parse($deTai->ngayDeXuat->ngay_de_xuat)->format('d-m-Y') }}
                                </p>
                            @else
                                <p><strong>Hình thức:</strong> Đăng ký</p>
                                <p><strong>Ngày đăng ký:</strong>
                                    {{ \Carbon\Carbon::parse($ngayDangKy)->format('d-m-Y') }}
                                </p>
                            @endif

                            @if ($deTai->so_luong_sv_dang_ky == 1)
                                <p><strong>Sinh viên đã đăng ký:
                                    </strong>{{ $deTai->sinhViens->first()->ho_ten }}
                                    ({{ $deTai->sinhViens->first()->mssv }})
                                </p>
                            @elseif ($deTai->so_luong_sv_dang_ky > 1)
                                <p><strong>Sinh viên đã đăng ký:</strong></p>
                                <ul>
                                    @foreach ($deTai->sinhViens as $sinhVien)
                                        <li>{{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }})</li>
                                    @endforeach
                                </ul>
                            @endif

                            @if ($deTai->so_luong_sv_de_xuat == 1)
                                <p><strong>Sinh viên đã đề xuất:
                                    </strong>{{ $deTai->sinhViens->first()->ho_ten }}
                                    ({{ $deTai->sinhViens->first()->mssv }})
                                </p>
                            @elseif ($deTai->so_luong_sv_de_xuat > 1)
                                <p><strong>Sinh viên đã đề xuất:</strong></p>
                                <ul>
                                    @foreach ($deTai->sinhViens as $sinhVien)
                                        <li>{{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }})</li>
                                    @endforeach
                                </ul>
                            @endif

                            @if ($loaiDeTai == 'de_tai_sv')
                                <p><strong>Trạng thái: </strong>
                                    @if ($deTai->trang_thai == 1)
                                        <span class="text-warning">Đang xử lý</span>
                                    @elseif($deTai->trang_thai == 2)
                                        <span class="text-success">Đã duyệt</span>
                                    @endif
                                </p>
                            @else
                                <p><strong>Trạng thái: </strong>
                                    <span class="text-success">Đã duyệt</span>
                                </p>
                            @endif

                            @if ($checkNgayHetHan == 1)
                                @if ($deTai->giangVienHuongDans->isEmpty())
                                    <p><strong>Giảng viên hướng dẫn: </strong><i>Chưa có</i></p>
                                @else
                                    @if ($deTai->giangVienHuongDans->count() === 1)
                                        @php $gv = $deTai->giangVienHuongDans->first(); @endphp
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
                                            @foreach ($deTai->giangVienHuongDans as $gv)
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

                                @if ($deTai->giangVienPhanBiens->isEmpty())
                                    <p><strong>Giảng viên phản biện: </strong>Chưa có</p>
                                @else
                                    @php $gv = $deTai->giangVienPhanBiens->first(); @endphp
                                    <p class="mb-0"><strong>Giảng viên phản biện: </strong>{{ $gv->ho_ten }}</p>
                                    <ul>
                                        <li><strong>Điểm:
                                            </strong><i>{{ $gv->pivot->diem_gvpb ? number_format($gv->pivot->diem_gvpb, 2) : 'Chưa có' }}</i>
                                        </li>
                                        <li><strong>Nhận xét: </strong>{!! $gv->pivot->nhan_xet ?? '<em>Chưa có</em>' !!}</li>
                                    </ul>
                                @endif

                                @if ($deTai->hoiDongs->isEmpty())
                                    <p><strong>Hội đồng: </strong><i>Chưa có</i></p>
                                @else
                                    @php
                                        $hoiDong = $deTai->hoiDongs->first();
                                        $chuTich = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Chủ tịch')->first();
                                        $thuKy = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Thư ký')->first();
                                        $uyViens = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Ủy viên')->get();

                                        $deTaiHoiDong = [];

                                        if (isset($deTai->so_luong_sv_dang_ky)) {
                                            $deTaiHoiDong = DB::table('bang_diem_gvthd_cho_svdk')
                                                ->where('ma_de_tai', $deTai->ma_de_tai)
                                                ->get();
                                        } else {
                                            $deTaiHoiDong = DB::table('bang_diem_gvthd_cho_svdk')
                                                ->where('ma_de_tai', $deTai->ma_de_tai)
                                                ->get();
                                        }
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
                            @elseif ($loaiDeTai == 'de_tai_sv' && $deTai->trang_thai == 1)
                                <form id="form_huy">
                                    <input type="hidden" name="ma_de_tai" value="{{ $deTai->ma_de_tai }}">
                                    <div class="text-center">
                                        <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal"
                                            data-bs-target="#cancelModal">Hủy</button>
                                        <a class="btn btn-lg btn-primary"
                                            href="{{ route('thong_tin_de_tai.sua', ['ma_de_tai' => $deTai->ma_de_tai]) }}">Sửa</a>
                                    </div>
                                    <div class="modal fade" id="cancelModal" tabindex="-1"
                                        aria-labelledby="cancelModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4 shadow-sm border-0">
                                                <div class="modal-header bg-light border-bottom-0">
                                                    <h5 class="modal-title fw-semibold text-primary" id="cancelModalLabel">
                                                        Xác nhận
                                                        hủy</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Đóng"></button>
                                                </div>
                                                <div class="modal-body text-center fs-5 text-secondary">
                                                    Bạn có chắc chắn muốn hủy đề tài này?
                                                </div>
                                                <div class="modal-footer bg-light border-top-0">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Hủy</button>
                                                    <button type="submit" class="btn btn-primary" id="huy">Xác
                                                        nhận</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <h5 class="text-center mt-4" style="font-weight: bold"><i>Sinh viên có thể hủy hoặc sửa khi
                                        chưa
                                        duyệt đề tài đã đề xuất trước thời gian kết thúc đăng ký!</i>
                                </h5>
                            @elseif ($loaiDeTai == 'de_tai_sv' && $deTai->trang_thai == 2)
                                <h5 class="text-center" style="font-weight: bold"><i>Khi đề tài đã duyệt, sinh viên muốn hủy
                                        phải liên hệ với
                                        trưởng khoa trước thời gian kết thúc đăng ký!</i>
                                </h5>
                            @else
                                <h5 class="text-center" style="font-weight: bold"><i>Sau khi đăng ký đề tài, sinh viên muốn
                                        hủy phải liên hệ với giảng viên trước thời gian kết thúc đăng ký!</i>
                                </h5>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#huy").click(function(event) {
                event.preventDefault();

                let form = $("#form_huy").get(0);
                let formData = new FormData(form);

                $.ajax({
                    url: "{{ route('thong_tin_de_tai.huy') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(result) {
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Hủy thành công!',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Thất bại!',
                                text: 'Hủy thất bại! Do đã hết thời hạn hủy đề tài',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất bại!',
                            text: 'Hủy thất bại! Vui lòng thử lại',
                            confirmButtonText: 'OK',
                            timer: 1000,
                            showConfirmButton: false
                        })
                    },
                });
            });
        });
    </script>
@endsection
