@extends('layouts.app')
@section('title', 'Chỉnh sửa đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Chỉnh sửa đề tài</h2>
                    </div>
                    <div class="card-body">
                        <form id="form_dua_ra">
                            <input type="hidden" name="DeTai[ma_de_tai]" value="{{ $deTai->ma_de_tai }}">
                            <div class="d-flex mb-3">
                                <label for="DeTai[ten_de_tai]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Tên đề tài
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="text" class="form-control form-control-lg shadow-none"
                                        placeholder="Nhập tên đề tài" name="DeTai[ten_de_tai]"
                                        value="{{ $deTai->ten_de_tai }}">
                                    <span class="error-message text-danger d-none mt-2 error-ten_de_tai"></span>
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
                                        <option value="">Chọn lĩnh vực</option>
                                        @foreach ($linhVucs as $linhVuc)
                                            <option value="{{ $linhVuc->ma_linh_vuc }}"
                                                {{ $deTai->ma_linh_vuc == $linhVuc->ma_linh_vuc ? 'selected' : '' }}>
                                                {{ $linhVuc->ten_linh_vuc }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error-message text-danger d-none mt-2 error-ma_linh_vuc"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="DeTai[mo_ta]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Mô tả
                                </label>
                                <div class="ms-2 w-100">
                                    <textarea class="form-control form-control-lg shadow-none" name="DeTai[mo_ta]" id="mo_ta">
                                        {!! $deTai->mo_ta !!}
                                    </textarea>
                                    <span class="error-message text-danger d-none mt-2 error-mo_ta"></span>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('dua_ra_de_tai.danh_sach') }}" class="btn btn-secondary btn-lg">
                                    Quay lại
                                </a>
                                <button class="btn btn-primary btn-lg" type="submit" id="chinhSua">
                                    Xác nhận chỉnh sửa
                                </button>
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
            $("#chinhSua").click(function(event) {
                event.preventDefault();

                let form = $("#form_dua_ra").get(0);
                let formData = new FormData(form);
                formData.set("DeTai[mo_ta]", $("#mo_ta").summernote("code"));

                $(".error-message").text('').removeClass("d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");
                $(".note-editor").css("border", "");

                $.ajax({
                    url: "{{ route('dua_ra_de_tai.xac_nhan_chinh_sua') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(result) {
                        if (result.success) {
                            alert("Chỉnh sửa thành công!");
                            window.location.href = "{{ route('dua_ra_de_tai.danh_sach') }}";
                        } else {
                            $.each(result.errors, function(field, messages) {
                                let inputField = $("[name='DeTai[" + field + "]']");
                                if (field == 'mo_ta') {
                                    $("#mo_ta").siblings(".note-editor").css("border",
                                        "1px solid red");
                                }
                                inputField.addClass("is-invalid");
                                $('.error-' + field).text(messages[0]).removeClass(
                                    "d-none").addClass("d-block");
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
