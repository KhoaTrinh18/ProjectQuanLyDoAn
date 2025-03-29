@extends('layouts.app')
@section('title', 'Đề xuất đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    @if (session('co_de_tai') == 1)
                        <div class="card-header d-flex justify-content-center align-items-center">
                            <h2 style="font-weight: bold"><i>Bạn đã đăng ký hoặc đề xuất đề tài!</i></h2>
                        </div>
                    @else
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h2 style="font-weight: bold">Đề xuất đề tài</h2>
                        </div>
                        <div class="card-body">
                            <form id="form_de_xuat">
                                <div class="d-flex mb-3">
                                    <label for="DeTai[ten_de_tai]"
                                        class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                        style="width: 250px">
                                        Tên đề tài
                                    </label>
                                    <div class="ms-2 w-100">
                                        <input type="text" class="form-control form-control-lg shadow-none"
                                            placeholder="Nhập tên đề tài" name="DeTai[ten_de_tai]">
                                        <span class="error-message text-danger d-block mt-2 error-ten_de_tai"></span>

                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <label for="DeTai[ma_linh_vuc]"
                                        class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                        style="width: 250px">
                                        Lĩnh vực
                                    </label>
                                    <div class="ms-2 w-100">
                                        <select class="form-select form-select-lg shadow-none" name="DeTai[ma_linh_vuc]">
                                            <option value="" selected>Chọn lĩnh vực</option>
                                            @foreach ($linhVucs as $linhVuc)
                                                <option value="{{ $linhVuc->ma_linh_vuc }}">{{ $linhVuc->ten_linh_vuc }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="error-message text-danger d-block mt-2 error-ma_linh_vuc"></span>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <label for="DeTai[mo_ta]"
                                        class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                        style="width: 250px">
                                        Mô tả
                                    </label>
                                    <div class="ms-2 w-100">
                                        <textarea class="form-control form-control-lg shadow-none" name="DeTai[mo_ta]" id="mo_ta"></textarea>
                                        <span class="error-message text-danger d-none mt-2 error-mo_ta"></span>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <label for="DeTai[mssv][]"
                                        class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary flex-column"
                                        style="width: 250px">
                                        Sinh viên làm chung
                                        <p>(nếu có)</p>
                                    </label>
                                    <div class="ms-2 w-100">
                                        <div class="d-flex mt-2 align-items-center">
                                            <input type="text" class="form-control form-control-lg shadow-none me-2"
                                                placeholder="Nhập MSSV" name="DeTai[mssv][0]" style="width: 150px">
                                            <span class="error-message text-danger d-none error-mssv-0"></span>
                                        </div>
                                        <div class="d-flex mt-2 align-items-center">
                                            <input type="text" class="form-control form-control-lg shadow-none me-2"
                                                placeholder="Nhập MSSV" name="DeTai[mssv][1]" style="width: 150px">
                                            <span class="error-message text-danger d-none error-mssv-1 "></span>
                                        </div>
                                        <div class="d-flex mt-2 align-items-center">
                                            <input type="text" class="form-control form-control-lg shadow-none me-2"
                                                placeholder="Nhập MSSV" name="DeTai[mssv][2]" style="width: 150px">
                                            <span class="error-message text-danger d-none error-mssv-2"></span>
                                        </div>
                                        <div class="d-flex mt-2 align-items-center">
                                            <input type="text" class="form-control form-control-lg shadow-none me-2"
                                                placeholder="Nhập MSSV" name="DeTai[mssv][3]" style="width: 150px">
                                            <span class="error-message text-danger d-none error-mssv-3"></span>
                                        </div>
                                        <div class="d-flex mt-2 align-items-center">
                                            <input type="text" class="form-control form-control-lg shadow-none me-2"
                                                placeholder="Nhập MSSV" name="DeTai[mssv][4]" style="width: 150px">
                                            <span class="error-message text-danger d-none error-mssv-4"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary btn-lg w-25" data-bs-toggle="modal"
                                        data-bs-target="#confirmModal">
                                        Đề xuất
                                    </button>
                                </div>
                                <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmModalLabel">Xác nhận đề xuất</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Khi bạn đã đề xuất 1 đề tài thì không thể đăng ký đề tài có trong danh sách
                                                đề
                                                tài. Bạn có chắc chắn muốn
                                                đề xuất đề tài này không?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Hủy</button>
                                                <button type="submit" class="btn btn-primary" id="deXuat">Xác
                                                    nhận</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#deXuat").click(function(event) {
                event.preventDefault();

                let form = $("#form_de_xuat").get(0);
                let formData = new FormData(form);
                formData.set("DeTai[mo_ta]", $("#mo_ta").summernote("code"));

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");
                $(".note-editor").css("border", "");

                $.ajax({
                    url: "{{ route('de_xuat_de_tai.xac_nhan_de_xuat') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(result) {
                        if (result.success) {
                            alert("Đề xuất thành công!");
                            location.reload();
                        } else {
                            $("#confirmModal").modal('hide');

                            $.each(result.errors, function(field, messages) {
                                let inputField = $("[name='DeTai[" + field + "]']");
                                if (field == 'mo_ta') {
                                    let summernoteEditor = $("#mo_ta").siblings(
                                        ".note-editor");
                                    summernoteEditor.css("border", "1px solid red");
                                }
                                $('.error-' + field).text(messages[0]).removeClass(
                                    "d-none").addClass("d-block");
                                if (field.startsWith("mssv.")) {
                                    let index = field.split('.')[1];
                                    $(".error-mssv-" + index).text(messages[0])
                                        .removeClass("d-none").show();
                                    $("[name='DeTai[mssv][" + index + "]']").addClass(
                                        "is-invalid");
                                }
                                inputField.addClass("is-invalid");
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Lỗi khi gửi dữ liệu:", error);
                        alert("Lỗi khi gửi dữ liệu! Vui lòng thử lại.");
                    }
                });
            });

            $('#mo_ta').summernote({
                height: 400,
                minHeight: 400,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['insert', ['picture', 'link']],
                    ['view', ['fullscreen', 'codeview']]
                ],
                callbacks: {
                    onInit: function() {
                        $('.note-editor').addClass('m-0');
                    }
                }
            });
        });
    </script>
@endsection
