@extends('layouts.app')
@section('title', 'Duyệt đề tài sinh viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Duyệt đề tài sinh viên</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTaiSV->ten_de_tai }}</h3>

                        @if ($deTaiSV->sinhViens->count() == 1)
                            @php $sinhVien = $deTaiSV->sinhViens->first(); @endphp
                            <p><strong>Sinh viên ra đề tài:</strong> {{ $sinhVien->ho_ten }} - MSSV: {{ $sinhVien->mssv }}
                            @else
                            <p><strong>Sinh viên ra đề tài:</strong></p>
                            <ul>
                                @foreach ($deTaiSV->sinhViens as $sinhVien)
                                    <li>{{ $sinhVien->ho_ten }} - MSSV: {{ $sinhVien->mssv }}
                                @endforeach
                            </ul>
                        @endif

                        <p><strong>Ngày đề xuất:</strong>
                            {{ \Carbon\Carbon::parse($deTaiSV->ngayDeXuat->ngay_de_xuat)->format('d-m-Y') }}</p>
                        <p><strong>Lĩnh vực:</strong> {{ $deTaiSV->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {!! $deTaiSV->mo_ta !!}</p>

                        <form id="form_duyet">
                            <input type="hidden" name="DeTai[ma_de_tai]" value="{{ $deTaiSV->ma_de_tai }}">
                            <div class="text-center">
                                <a href="{{ route('de_tai_sinh_vien.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                    lại</a>
                                <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal">Xác nhận duyệt</button>
                            </div>
                            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 shadow-sm border-0">
                                        <div class="modal-header bg-light border-bottom-0">
                                            <h5 class="modal-title fw-semibold text-primary" id="confirmModalLabel">Xác nhận
                                                duyệt</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body text-center fs-5 text-secondary">
                                            Bạn có chắc chắn muốn duyệt đề tài này không?
                                        </div>
                                        <div class="modal-footer bg-light border-top-0">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary" id="duyet">Xác nhận</button>
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
            $("#duyet").click(function(event) {
                event.preventDefault();

                let form = $("#form_duyet").get(0);
                let formData = new FormData(form);

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");

                $.ajax({
                    url: "{{ route('de_tai_sinh_vien.xac_nhan_duyet') }}",
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
                                text: 'Duyệt thành công!',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('de_tai_sinh_vien.danh_sach') }}";
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất bại!',
                            text: 'Duyệt thất bại! Vui lòng thử lại',
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
