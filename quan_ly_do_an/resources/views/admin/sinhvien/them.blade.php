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
                                <a href="{{ route('sinh_vien.tai_csv_mau') }}" class="btn btn-outline-primary btn-lg" style="width: 207.8px">Tải file
                                    CSV mẫu</a>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('sinh_vien.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                    lại</a>
                                <button type="submit" class="btn btn-success btn-lg" id="them">Xác nhận thêm
                                    mới</button>
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
                            }
                            $.each(result.errors, function(field, messages) {
                                let inputField = $("[name='" + field + "'");
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
