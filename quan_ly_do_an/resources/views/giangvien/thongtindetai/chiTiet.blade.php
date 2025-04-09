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
                                </strong>chưa có</p>
                        @elseif ($deTai->so_luong_sv_dang_ky == 1)
                            <form class="d-flex align-item-center" id="form_huy_sv">
                                <p class="m-0"><strong>Sinh viên đã đăng ký:
                                    </strong>{{ $deTai->sinhViens->first()->ho_ten }} ({{ $deTai->sinhViens->first()->mssv }})
                                </p>
                                <button class="btn btn-danger btn-sm ms-2" type="button" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal">Hủy đăng
                                    ký</button>
                                <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmModalLabel">Xác nhận hủy đăng ký</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body"> Bạn có chắc muốn hủy đăng ký của sinh viên
                                                {{ $deTai->sinhViens->first()->ho_ten }}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Hủy</button>
                                                <input type="hidden" name="ma_sv"
                                                    value="{{ $deTai->sinhViens->first()->ma_sv }}">
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
                                @foreach ($sinhViens as $sv)
                                    <li class="mt-2">
                                        <div class="d-flex align-items-center">
                                            <p class="student-name m-0">{{ $sv->ho_ten }} ({{ $sv->mssv }})</p>
                                            <button class="btn btn-danger btn-sm ms-2 huy-dang-ky-btn"
                                                data-ma-sv="{{ $sv->ma_sv }}" data-ho-ten="{{ $sv->ho_ten }}"
                                                data-bs-toggle="modal" data-bs-target="#confirmModal">
                                                Hủy đăng ký
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Xác nhận hủy đăng ký</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Bạn có chắc muốn hủy đăng ký của sinh viên <strong
                                                id="modalStudentName"></strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <form id="form_huy_sv">
                                                @csrf
                                                <input type="hidden" name="ma_sv" id="modalMaSV">
                                                <input type="hidden" name="ma_de_tai" value="{{ $deTai->ma_de_tai }}">
                                                <button type="submit" class="btn btn-primary">Xác nhận</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="text-center">
                            <a href="{{ route('thong_tin_de_tai.danh_sach_duyet') }}" class="btn btn-secondary btn-lg">Quay
                                lại</a>
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
                            alert("Hủy sinh viên thành công!");
                            window.location.href =
                                "{{ route('thong_tin_de_tai.danh_sach_duyet') }}";
                        }
                    },
                    error: function(xhr) {
                        alert("Hủy thất bại! Vui lòng thử lại.");
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
        });
    </script>
@endsection
