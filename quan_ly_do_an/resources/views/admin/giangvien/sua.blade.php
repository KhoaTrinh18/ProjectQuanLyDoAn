@extends('layouts.app')
@section('title', 'Cập nhật giảng viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Cập nhật giảng viên</h2>
                    </div>
                    <div class="card-body">
                        <form id="form_cap_nhat">
                            <input type="hidden" name="GiangVien[ma_gv]" value="{{ $giangVien->ma_gv }}">
                            <div class="d-flex mb-3">
                                <label for="GiangVien[ten_giang_vien]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Tên giảng viên
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="text" class="form-control form-control-lg shadow-none"
                                        placeholder="Nhập tên giảng viên" name="GiangVien[ten_giang_vien]"
                                        style="width: 280px" value="{{ $giangVien->ho_ten }}">
                                    <span class="error-message text-danger d-none mt-2 error-ten_giang_vien"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="GiangVien[email]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Email
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="text" class="form-control form-control-lg shadow-none"
                                        placeholder="Nhập email" name="GiangVien[email]" style="width: 280px" value="{{ $giangVien->email }}">
                                    <span class="error-message text-danger d-none mt-2 error-email"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="GiangVien[so_dien_thoai]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Số điện thoại
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="text" class="form-control form-control-lg shadow-none"
                                        placeholder="Nhập số điện thoại" name="GiangVien[so_dien_thoai]"
                                        style="width: 280px" value="{{ $giangVien->so_dien_thoai }}">
                                    <span class="error-message text-danger d-none mt-2 error-so_dien_thoai"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="GiangVien[bo_mon]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Bộ môn
                                </label>
                                <div class="ms-2 w-100">
                                    <select class="form-select form-select-lg shadow-none" name="GiangVien[bo_mon]"
                                        style="width: 280px">
                                        <option value="" hidden disabled>Chọn bộ môn</option>
                                        @foreach ($boMons as $boMon)
                                            <option value="{{ $boMon->ma_bo_mon }}" {{ $boMon->ma_bo_mon == $giangVien->ma_bo_mon ? 'selected' : '' }}>{{ $boMon->ten_bo_mon }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error-message text-danger d-none mt-2 error-bo_mon"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="GiangVien[hoc_vi]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Học vị
                                </label>
                                <div class="ms-2 w-100">
                                    <select class="form-select form-select-lg shadow-none" name="GiangVien[hoc_vi]"
                                        style="width: 280px">
                                        <option value="" hidden disabled>Chọn học vị</option>
                                        @foreach ($hocVis as $hocVi)
                                            <option value="{{ $hocVi->ma_hoc_vi }}" {{ $hocVi->ma_hoc_vi == $giangVien->ma_hoc_vi ? 'selected' : '' }} >{{ $hocVi->ten_hoc_vi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error-message text-danger d-none mt-2 error-hoc_vi"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="GiangVien[ten_tk]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Tên tài khoản
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="text" class="form-control form-control-lg shadow-none"
                                        placeholder="Nhập tên tài khoản" name="GiangVien[ten_tk]" style="width: 280px" value="{{ $giangVien->taiKhoan->ten_tk }}">
                                    <span class="error-message text-danger d-none mt-2 error-ten_tk"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="GiangVien[mat_khau]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Mật khẩu
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="text" class="form-control form-control-lg shadow-none"
                                        placeholder="Nhập mật khẩu" name="GiangVien[mat_khau]" style="width: 280px" value="{{ $giangVien->taiKhoan->mat_khau }}">
                                    <span class="error-message text-danger d-none mt-2 error-mat_khau"></span>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('giang_vien.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                    lại</a>
                                <button type="submit" class="btn btn-primary btn-lg" id="capNhat">Xác nhận cập nhật</button>
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
                    url: "{{ route('giang_vien.xac_nhan_sua') }}",
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
                                    "{{ route('giang_vien.danh_sach') }}";
                            });
                        } else {
                            $.each(result.errors, function(field, messages) {
                                let inputField = $("[name='GiangVien[" + field + "]']");
                                inputField.addClass("is-invalid");
                                $('.error-' + field).text(messages[0]).removeClass(
                                    "d-none").addClass("d-block");
                            });
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
                    }
                });
            });
        });
    </script>
@endsection
