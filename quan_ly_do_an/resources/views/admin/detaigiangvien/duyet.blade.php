@extends('layouts.app')
@section('title', 'Duyệt đề tài giảng viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Duyệt đề tài giảng viên</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTaiGV->ten_de_tai }}</h3>

                        @if ($deTaiGV->giangViens->count() == 1)
                            @php $giangVien = $deTaiGV->giangViens->first(); @endphp
                            <p><strong>Giảng viên ra đề tài:</strong> {{ $giangVien->ho_ten }} - Email:
                                {{ $giangVien->email }} - Số điện thoại: {{ $giangVien->so_dien_thoai }}</p>
                        @else
                            <ul>
                                @foreach ($deTaiGV->giangViens as $giangVien)
                                    <li>{{ $giangVien->ho_ten }} - Email: {{ $giangVien->email }} - SĐT:
                                        {{ $giangVien->so_dien_thoai }}</li>
                                @endforeach
                            </ul>
                        @endif
                        
                        <p><strong>Ngày đưa ra:</strong> {{ $deTaiGV->ngayDuaRa->ngay_dua_ra }}</p>
                        <p><strong>Lĩnh vực:</strong> {{ $deTaiGV->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {!! $deTaiGV->mo_ta !!}</p>

                        <form id="form_dang_ky">
                            <input type="hidden" name="DeTai[ma_de_tai]" value="{{ $deTaiGV->ma_de_tai }}">
                            <div class="text-center">
                                <a href="{{ route('de_tai_giang_vien.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                    lại</a>
                                @if ($deTaiGV->trang_thai == 1)
                                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                        data-bs-target="#confirmModal">Xác nhận duyệt</button>
                                @endif
                            </div>
                            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmModalLabel">Xác nhận đăng ký</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">Bạn có chắc chắn muốn duyệt đề tài này không?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary" id="dangKy">Xác
                                                nhận</button>
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
            $("#dangKy").click(function(event) {
                event.preventDefault();

                let form = $("#form_dang_ky").get(0);
                let formData = new FormData(form);

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");

                $.ajax({
                    url: "{{ route('de_tai_giang_vien.xac_nhan_duyet') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(result) {
                        if (result.success) {
                            alert("Duyệt thành công!");
                            window.location.href =
                            "{{ route('de_tai_giang_vien.danh_sach') }}";
                        }
                    },
                    error: function(xhr) {
                        alert("Đăng ký thất bại! Vui lòng thử lại.");
                    },
                });
            });
        });
    </script>
@endsection
