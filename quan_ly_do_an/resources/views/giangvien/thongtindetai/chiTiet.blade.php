@extends('layouts.app')
@section('title', 'Chi tiết đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Chi tiết đề tài</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTai->ten_de_tai }}</h3>

                        @if ($deTai->giangViens->count() == 1)
                            @php $giangVien = $deTai->giangViens->first(); @endphp
                            <p><strong>Giảng viên ra đề tài:</strong> {{ $giangVien->ho_ten }} - Email:
                                {{ $giangVien->email }} - Số điện thoại: {{ $giangVien->so_dien_thoai }}</p>
                        @else
                            <ul>
                                @foreach ($deTai->giangViens as $giangVien)
                                    <li>{{ $giangVien->ho_ten }} - Email: {{ $giangVien->email }} - SĐT:
                                        {{ $giangVien->so_dien_thoai }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <p><strong>Lĩnh vực:</strong> {{ $deTai->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {!! $deTai->mo_ta !!}</p>

                        @if ($deTai->so_luong_sv_dang_ky < 1)
                            <p><strong>Sinh viên đã đăng ký:
                                </strong><i>Chưa có</i></p>
                        @elseif ($deTai->so_luong_sv_dang_ky == 1)
                            <form class="d-flex align-items-center" id="form_huy_sv">
                                <p class="m-0"><strong>Sinh viên đã đăng ký:
                                    </strong>{{ $deTai->sinhVienDangKys->first()->ho_ten }}
                                    ({{ $deTai->sinhVienDangKys->first()->mssv }}) - Email:
                                    {{ $deTai->sinhVienDangKys->first()->email }} - Số điện thoại:
                                    {{ $deTai->sinhVienDangKys->first()->so_dien_thoai }}
                                </p>
                                <button class="btn btn-danger btn-sm ms-2" type="button" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal">Hủy đăng
                                    ký</button>
                                <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 shadow-sm border-0">
                                            <div class="modal-header bg-light border-bottom-0">
                                                <h5 class="modal-title fw-semibold text-primary" id="confirmModalLabel">Xác
                                                    nhận
                                                    hủy đăng ký</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Đóng"></button>
                                            </div>
                                            <div class="modal-body fs-5 text-secondary"> Bạn có chắc muốn hủy đăng ký của
                                                sinh viên
                                                <strong>{{ $deTai->sinhVienDangKys->first()->ho_ten }}</strong>
                                            </div>
                                            <div class="modal-footer  bg-light border-top-0">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Hủy</button>
                                                <input type="hidden" name="ma_sv"
                                                    value="{{ $deTai->sinhVienDangKys->first()->ma_sv }}">
                                                <input type="hidden" name="ma_de_tai" value="{{ $deTai->ma_de_tai }}">
                                                <button type="submit" class="btn btn-primary" id="dangKy">Xác
                                                    nhận</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @else
                            <p><strong>Sinh viên đã đăng ký:</strong></p>
                            <ul>
                                @foreach ($deTai->sinhVienDangKys as $sinhVien)
                                    <li class="mt-2">
                                        <div class="d-flex align-items-center">
                                            <p class="student-name m-0">{{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }}) -
                                                Email: {{ $sinhVien->email }} - Số điện thoại:
                                                {{ $sinhVien->so_dien_thoai }}
                                            </p>
                                            <button class="btn btn-danger btn-sm ms-2 huy-dang-ky-btn"
                                                data-ma-sv="{{ $sinhVien->ma_sv }}" data-ho-ten="{{ $sinhVien->ho_ten }}"
                                                data-bs-toggle="modal" data-bs-target="#confirmModal">
                                                Hủy đăng ký
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 shadow-sm border-0">
                                        <div class="modal-header bg-light border-bottom-0">
                                            <h5 class="modal-title fw-semibold text-primary" id="confirmModalLabel">Xác
                                                nhận
                                                hủy đăng ký</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body fs-5 text-secondary"> Bạn có chắc muốn hủy đăng ký của
                                            sinh viên <strong id="modalStudentName"></strong>?
                                        </div>
                                        <div class="modal-footer bg-light border-top-0">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <form id="form_huy_sv">
                                                <input type="hidden" name="ma_sv" id="modalMaSV">
                                                <input type="hidden" name="ma_de_tai" value="{{ $deTai->ma_de_tai }}">
                                                <button type="submit" class="btn btn-primary">Xác nhận</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="text-center mt-3">
                            <a href="{{ route('thong_tin_de_tai.danh_sach_duyet') }}" class="btn btn-secondary btn-lg">Quay
                                lại</a>
                            <input type="hidden" name="ma_de_tai" value="{{ $deTai->ma_de_tai }}">
                            <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal"
                                data-bs-target="#confirmModal1">Xác nhận hướng
                                dẫn</button>

                            <div class="modal fade" id="confirmModal1" tabindex="-1" aria-labelledby="confirmModal1Label"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 shadow-sm border-0">
                                        <div class="modal-header bg-light border-bottom-0">
                                            <h5 class="modal-title fw-semibold text-primary" id="confirmModal1Label">
                                                Xác
                                                nhận
                                                hướng dẫn</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body fs-5 text-secondary"> Bạn có chắc chắn muốn xác nhận hướng
                                            dẫn
                                            những sinh viên này ?
                                        </div>
                                        <div class="modal-footer bg-light border-top-0">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <form id="form_xac_nhan">
                                                <input type="hidden" name="ma_de_tai" value="{{ $deTai->ma_de_tai }}">
                                                <button type="submit" class="btn btn-primary" id="xacNhan">Xác
                                                    nhận</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#xacNhan").click(function(event) {
                event.preventDefault();

                let form = $("#form_xac_nhan").get(0);
                let formData = new FormData(form);

                $.ajax({
                    url: "{{ route('thong_tin_de_tai.xac_nhan_huong_dan') }}",
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
                                text: 'Xác nhận hướng dẫn thành công!',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('thong_tin_de_tai.danh_sach_duyet') }}";
                            });
                        } else {
                            if (result.text == 'vuot_muc') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Xác nhận hướng dẫn thất bại! Số lượng sinh viên đăng ký vượt quá số lượng sinh viên tối đa',
                                    confirmButtonText: 'OK',
                                    timer: 2000,
                                    showConfirmButton: false
                                })
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Xác nhận hướng dẫn thất bại! Chưa có sinh viên nào đăng ký',
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
                            text: 'Xác nhận hướng dẫn thất bại! Vui lòng thử lại',
                            confirmButtonText: 'OK',
                            timer: 1000,
                            showConfirmButton: false
                        })
                    }
                });
            });

            $(".huy-dang-ky-btn").click(function() {
                let maSV = $(this).data("ma-sv");
                let hoTen = $(this).data("ho-ten");

                $("#modalStudentName").text(hoTen);
                $("#modalMaSV").val(maSV);
            });

            $("#form_huy_sv").submit(function(event) {
                event.preventDefault();

                let form = $("#form_huy_sv").get(0);
                let formData = new FormData(form);

                $.ajax({
                    url: "{{ route('thong_tin_de_tai.huy_sinh_vien') }}",
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
                                window.location.reload()
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

            var maxWidth = 0;

            $('.student-name').each(function() {
                var width = $(this).outerWidth();
                if (width > maxWidth) {
                    maxWidth = width;
                }
            });

            $('.student-name').each(function() {
                $(this).css('width', maxWidth);
            });

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
                            "{{ route('thong_tin_de_tai.danh_sach_duyet') }}";
                    });
                    clearInterval(interval);
                }
            }, 10);
        });
    </script>
@endsection
