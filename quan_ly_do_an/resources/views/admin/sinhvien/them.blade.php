@extends('layouts.app')
@section('title', 'Thêm mới sinh viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Thêm mới sinh viên</h2>
                    </div>
                    <div class="card-body">
                        <form id="form_them">
                            <div class="d-flex mb-2">
                                <label for="file"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Danh sách sinh viên (CSV)
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="file" class="form-control form-control-lg shadow-none" name="file"
                                        style="width: 280px">
                                    <span class="error-message text-danger d-none mt-2 error-file"></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <a href="{{ route('sinh_vien.tai_csv_mau') }}" class="btn btn-outline-primary btn-lg"
                                    style="width: 207.8px">Tải file
                                    CSV mẫu</a>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('sinh_vien.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                    lại</a>
                                <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal">
                                    Xác nhận thêm mới
                                </button>
                            </div>
                            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 shadow-sm border-0">
                                        <div class="modal-header bg-light border-bottom-0">
                                            <h5 class="modal-title fw-semibold text-primary" id="confirmModalLabel">Xác
                                                nhận
                                                thêm mới</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body text-center fs-5 text-secondary">
                                            Khi thêm mới danh sách sẽ xóa toàn bộ danh sách sinh viên cũ và tài khoản liên
                                            quan. Bạn có chắc chắn muốn thêm danh sách sinh viên ?
                                        </div>
                                        <div class="modal-footer bg-light border-top-0">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary" id="them">Xác
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
            $("#them").click(function(event) {
                event.preventDefault();

                let form = $("#form_them").get(0);
                let formData = new FormData(form);


                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");

                $.ajax({
                    url: "{{ route('sinh_vien.xac_nhan_them') }}",
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
                                text: 'Thêm mới thành công!',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('sinh_vien.danh_sach') }}";
                            });
                        } else {
                            console.log(result.errors);
                            if (result.errors == true) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Thêm mới thất bại! Đã có sinh viên đăng ký nên không thể đổi danh sách sinh viên',
                                    confirmButtonText: 'OK',
                                    timer: 2000,
                                    showConfirmButton: false
                                })
                            } else {
                                $('#confirmModal').modal('hide');
                                if ($.isArray(result.errors)) {
                                    const allErrors = result.errors;
                                    const total = allErrors.length;
                                    const maxShow = 10;
                                    let display = [`Tổng cộng: ${total} lỗi.`]
                                        .concat(allErrors.slice(0, maxShow));

                                    if (allErrors.length > maxShow) {
                                        const remaining = allErrors.length - maxShow;
                                        display.push(`...`);
                                    }

                                    $("[name='file']").addClass('is-invalid');
                                    $('.error-file')
                                        .html(display.join('<br>'))
                                        .removeClass('d-none')
                                        .addClass('d-block');
                                } else {
                                    $.each(result.errors, function(field, messages) {
                                        let inputField = $("[name='" + field + "'");
                                        inputField.addClass("is-invalid");
                                        $('.error-' + field).text(messages[0])
                                            .removeClass(
                                                "d-none").addClass("d-block");
                                    });
                                }
                            }
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất bại!',
                            text: 'Thêm mới thất bại! Vui lòng thử lại',
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
