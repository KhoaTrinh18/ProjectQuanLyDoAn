@extends('layouts.app')
@section('title', 'Hủy sinh viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Hủy sinh viên</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <p><strong>MSSV:</strong> {{ $sinhVien->mssv }}</p>
                        <p><strong>Tên sinh viên:</strong> {{ $sinhVien->ho_ten }}</p>
                        <p><strong>Lớp:</strong> {{ $sinhVien->lop }}</p>
                        <p><strong>Email:</strong> {{ $sinhVien->email }}</p>
                        <p><strong>Số điện thoại:</strong> {{ $sinhVien->so_dien_thoai }}</p>
                        @if ($sinhVien->dang_ky == 0)
                            <p><strong>Tên đề tài: </strong><i>Chưa có</i></p>
                        @else
                            <p><strong>Tên đề tài: </strong>{{ $deTai->ten_de_tai }}</p>
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
                                <p><strong>Giảng viên phản biện: </strong><i>Chưa có</i></p>
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
                                        $deTaiHoiDong = DB::table('bang_diem_gvthd_cho_svdx')
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
                        @endif
                        <form id="form_huy">
                            <input type="hidden" name="ma_sv" value="{{ $sinhVien->ma_sv }}">
                            <div class="text-center">
                                <a href="{{ route('sinh_vien.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                    lại</a>
                                <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal">Xác nhận hủy</button>
                            </div>
                            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 shadow-sm border-0">
                                        <div class="modal-header bg-light border-bottom-0">
                                            <h5 class="modal-title fw-semibold text-primary" id="confirmModalLabel">Xác nhận
                                                hủy</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body text-center fs-5 text-secondary">
                                            Khi hủy sinh viên này sẽ hủy toàn bộ thông tin liên quan đến sinh viên và cập nhật trạng thái sinh viên thành <strong>nghỉ giữa chừng</strong>. Bạn có chắc chắn muốn hủy sinh viên này không?
                                        </div>
                                        <div class="modal-footer bg-light border-top-0">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary" id="huy">Xác nhận</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
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
                    url: "{{ route('sinh_vien.xac_nhan_huy') }}",
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
                                window.location.href =
                                    "{{ route('sinh_vien.danh_sach') }}";
                            });
                        } else {
                            if (result.error == 'dua_ra') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Hủy thất bại! Vì giảng viên này đưa ra đề tài',
                                    confirmButtonText: 'OK',
                                    timer: 2000,
                                    showConfirmButton: false
                                })
                            } else if (result.error == 'phan_cong_huong_dan') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Hủy thất bại! Vì giảng viên này đã được phân công hướng dẫn',
                                    confirmButtonText: 'OK',
                                    timer: 2000,
                                    showConfirmButton: false
                                })
                            } else if (result.error == 'phan_cong_phan_bien') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Hủy thất bại! Vì giảng viên này đã được phân công phản biện',
                                    confirmButtonText: 'OK',
                                    timer: 2000,
                                    showConfirmButton: false
                                })
                            } else if (result.error == 'phan_cong_hoi_dong') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Hủy thất bại! Vì giảng viên này đã được phân công hội đồng',
                                    confirmButtonText: 'OK',
                                    timer: 2000,
                                    showConfirmButton: false
                                })
                            }
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
