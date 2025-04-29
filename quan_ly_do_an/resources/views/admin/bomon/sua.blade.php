@extends('layouts.app')
@section('title', 'Cập nhật bộ môn')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Cập nhật bộ môn</h2>
                    </div>
                    <div class="card-body">
                        <form id="form_cap_nhat">
                            <input type="hidden" name="BoMon[ma_bo_mon]" value="{{ $boMon->ma_bo_mon }}">
                            <div class="d-flex mb-3">
                                <label for="BoMon[ten_bo_mon]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Tên bộ môn
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="text" class="form-control form-control-lg shadow-none"
                                        placeholder="Nhập tên bộ môn" name="BoMon[ten_bo_mon]"
                                        style="width: 280px" value="{{ $boMon->ten_bo_mon }}">
                                    <span class="error-message text-danger d-none mt-2 error-ten_bo_mon"></span>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('bo_mon.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
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
                    url: "{{ route('bo_mon.xac_nhan_sua') }}",
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
                                    "{{ route('bo_mon.danh_sach') }}";
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
