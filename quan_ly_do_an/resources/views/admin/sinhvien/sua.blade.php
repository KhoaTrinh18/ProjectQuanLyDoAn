@extends('layouts.app')
@section('title', 'Cập nhật sinh viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Cập nhật sinh viên</h2>
                    </div>
                    <div class="card-body">
                        <form id="form_cap_nhat">
                            <input type="hidden" name="SinhVien[ma_sv]" value="{{ $sinhVien->ma_sv }}">
                            <div class="d-flex mb-3">
                                <label for="SinhVien[mssv]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    MSSV
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="text" class="form-control form-control-lg shadow-none"
                                        placeholder="Nhập mssv" name="SinhVien[mssv]" style="width: 280px"
                                        value="{{ $sinhVien->mssv }}">
                                    <span class="error-message text-danger d-none mt-2 error-mssv"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="SinhVien[ten_sinh_vien]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Tên sinh viên
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="text" class="form-control form-control-lg shadow-none"
                                        placeholder="Nhập tên sinh viên" name="SinhVien[ten_sinh_vien]" style="width: 280px"
                                        value="{{ $sinhVien->ho_ten }}">
                                    <span class="error-message text-danger d-none mt-2 error-ten_sinh_vien"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="SinhVien[lop]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Lớp
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="text" class="form-control form-control-lg shadow-none"
                                        placeholder="Nhập lớp" name="SinhVien[lop]" style="width: 280px"
                                        value="{{ $sinhVien->lop }}">
                                    <span class="error-message text-danger d-none mt-2 error-lop"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="SinhVien[email]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Email
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="text" class="form-control form-control-lg shadow-none"
                                        placeholder="Nhập email" name="SinhVien[email]" style="width: 280px"
                                        value="{{ $sinhVien->email }}">
                                    <span class="error-message text-danger d-none mt-2 error-email"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="SinhVien[so_dien_thoai]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Số điện thoại
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="text" class="form-control form-control-lg shadow-none"
                                        placeholder="Nhập số điện thoại" name="SinhVien[so_dien_thoai]"
                                        style="width: 280px" value="{{ $sinhVien->so_dien_thoai }}">
                                    <span class="error-message text-danger d-none mt-2 error-so_dien_thoai"></span>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('sinh_vien.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
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
                    url: "{{ route('sinh_vien.xac_nhan_sua') }}",
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
                                    "{{ route('sinh_vien.danh_sach') }}";
                            });
                        } else {
                            $.each(result.errors, function(field, messages) {
                                let inputField = $("[name='SinhVien[" + field + "]']");
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
