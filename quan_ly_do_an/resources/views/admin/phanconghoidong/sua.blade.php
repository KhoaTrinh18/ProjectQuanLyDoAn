@extends('layouts.app')
@section('title', 'Cập nhật hội đồng')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Cập nhật hội đồng</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTai->ten_de_tai }}</h3>

                        @if ($deTai->sinhViens->count() == 1)
                            @php $sinhVien = $deTai->sinhViens->first(); @endphp
                            <p><strong>Sinh viên thực hiện:</strong> {{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }}) -
                                Email: {{ $sinhVien->email }} - Số điện thoại:
                                {{ $sinhVien->so_dien_thoai }}
                            @else
                            <p><strong>Sinh viên thực hiện:</strong></p>
                            <ul>
                                @foreach ($deTai->sinhViens as $sinhVien)
                                    <li>{{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }}) -
                                        Email: {{ $sinhVien->email }} - Số điện thoại:
                                        {{ $sinhVien->so_dien_thoai }}
                                @endforeach
                            </ul>
                        @endif

                        @if ($deTai->giangViens->count() == 0)
                            <p><strong>Giảng viên hướng dẫn:</strong> Chưa có</p>
                        @elseif ($deTai->giangViens->count() == 1)
                            @php $giangVien = $deTai->giangViens->first(); @endphp
                            <p><strong>Giảng viên hướng dẫn:</strong> {{ $giangVien->ho_ten }} - Email:
                                {{ $giangVien->email }} - Số điện thoại: {{ $giangVien->so_dien_thoai }}
                            @else
                            <p><strong>Giảng viên hướng dẫn:</strong></p>
                            <ul>
                                @foreach ($deTai->giangViens as $giangVien)
                                    <li>{{ $giangVien->ho_ten }} - Email: {{ $giangVien->email }} - Số điện thoại:
                                        {{ $giangVien->so_dien_thoai }}
                                @endforeach
                            </ul>
                        @endif

                        @if ($deTai->giangVienPhanBiens->count() == 0)
                            <p><strong>Giảng viên phản biện:</strong> <i>Chưa có</i></p>
                        @else
                            @php $giangVien = $deTai->giangVienPhanBiens->first(); @endphp
                            <p><strong>Giảng viên phản biện:</strong> {{ $giangVien->ho_ten }} - Email:
                                {{ $giangVien->email }} - Số điện thoại: {{ $giangVien->so_dien_thoai }}
                        @endif

                        @if ($deTai->sinhViens->first()->loai_sv == 'dang_ky')
                            @if ($deTai->giangViens->count() == 1)
                                @php $giangVien = $deTai->giangViens->first(); @endphp
                                <p><strong>Giảng viên ra đề tài:</strong> {{ $giangVien->ho_ten }} - Email:
                                    {{ $giangVien->email }} - Số điện thoại: {{ $giangVien->so_dien_thoai }}
                                @else
                                <p><strong>Giảng viên ra đề tài:</strong></p>
                                <ul>
                                    @foreach ($deTai->giangViens as $giangVien)
                                        <li>{{ $giangVien->ho_ten }} - Email: {{ $giangVien->email }} - Số điện thoại:
                                            {{ $giangVien->so_dien_thoai }}
                                    @endforeach
                                </ul>
                            @endif
                        @endif

                        <p><strong>Lĩnh vực:</strong> {{ $deTai->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {!! $deTai->mo_ta !!}</p>

                        <form id="form_cap_nhat">
                            <div class="d-flex align-items-center">
                                <label for="DeTai[ma_hoi_dong]"><strong>Hội đồng:</strong></label>
                                <select name="DeTai[ma_hoi_dong]" class="form-select ms-2" style="width: 260px">
                                    <option value="" hidden disabled>Chọn hội đồng</option>
                                    @php
                                        $hoiDongDeTai = $deTai->hoiDongs->first();
                                    @endphp
                                    @foreach ($chuyenNganhs as $chuyenNganh)
                                        <optgroup label="{{ $chuyenNganh->ten_bo_mon }}">
                                            @foreach ($chuyenNganh->hoiDongs as $hoiDong)
                                                @php
                                                    $thietLap = DB::table('thiet_lap')->where('trang_thai', 1)->first();
                                                @endphp
                                                @if ($hoiDong->da_huy != 1 && $hoiDong->nam_hoc == $thietLap->nam_hoc)
                                                    <option value="{{ $hoiDong->ma_hoi_dong }}"
                                                        {{ $hoiDong->ma_hoi_dong == $hoiDongDeTai->ma_hoi_dong ? 'selected' : '' }}>
                                                        {{ $hoiDong->ten_hoi_dong }}</option>
                                                @endif
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <span class="error-message text-danger d-hidden error-ma_hoi_dong m-0 mt-2"></span>

                            <input type="hidden" name="DeTai[ma_de_tai]" value="{{ $deTai->ma_de_tai }}">
                            <div class="text-center">
                                <a href="{{ route('phan_cong_hoi_dong.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                    lại</a>
                                <button type="submit" class="btn btn-primary btn-lg" id="capNhat">Xác nhận cập
                                    nhật</button>
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
            $("#capNhat").click(function(event) {
                event.preventDefault();

                let form = $("#form_cap_nhat").get(0);
                let formData = new FormData(form);

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");

                $.ajax({
                    url: "{{ route('phan_cong_hoi_dong.xac_nhan_sua') }}",
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
                                text: 'Cập nhật thành công!',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('phan_cong_hoi_dong.danh_sach') }}";
                            });
                        } else {
                            if (result.errors) {
                                $.each(result.errors, function(field, messages) {
                                    let inputField = $("[name='DeTai[" + field + "]']");
                                    $('.error-' + field).text(messages[0]).removeClass(
                                        "d-none").addClass("d-block");
                                    inputField.addClass("is-invalid");
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Cập nhật thất bại! Đề tài này đã được chấm điểm',
                                    confirmButtonText: 'OK',
                                    timer: 1000,
                                    showConfirmButton: false
                                })
                            }

                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất bại!',
                            text: 'Cập nhật thất bại! Vui lòng thử lại',
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
