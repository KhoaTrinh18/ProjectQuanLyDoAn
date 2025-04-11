@extends('layouts.app')
@section('title', 'Sửa điểm đề tài hướng dẫn')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Sửa điểm đề tài hướng dẫn</h2>
                    </div>
                    <div class="card-body">
                        <p style="font-size: 16px"><strong>Tên đề tài:</strong> {{ $deTai->ten_de_tai }}</p>
                        <form id="form_cham_diem">
                            <input type="hidden" name="ma_de_tai" value="{{ $deTai->ma_de_tai }}">
                            @foreach ($deTai->sinhViens as $i => $sinhVien)
                                @php
                                    $phanCong = $phanCongSVDK->where('ma_sv', $sinhVien->ma_sv)->first();
                                @endphp
                                <input type="hidden" name="ChamDiem[{{ $i }}][ma_sv]"
                                    value="{{ $sinhVien->ma_sv }}">
                                <p style="font-size: 16px"><strong>Sinh viên {{ $i + 1 }}:</strong>
                                    {{ $sinhVien->ho_ten }} - {{ $sinhVien->mssv }}</p>
                                <div class="d-flex mb-3">
                                    <label for="ChamDiem[{{ $i }}][diem]"
                                        class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                        style="width: 250px">
                                        Điểm
                                    </label>
                                    <div class="ms-2 w-100">
                                        <input type="text" class="form-control form-control-lg shadow-none text-center"
                                            name="ChamDiem[{{ $i }}][diem]" style="width: 90px" maxlength="4"
                                            value="{{ $phanCong->diem_GVHD }}">
                                        <span
                                            class="error-message text-danger d-none mt-2 error-ChamDiem{{ $i }}diem"></span>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <label for="ChamDiem[{{ $i }}][nhan_xet]"
                                        class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                        style="width: 250px">
                                        Nhận xét
                                    </label>
                                    <div class="ms-2 w-100">
                                        <textarea class="form-control form-control-lg shadow-none nhan_xet" name="ChamDiem[{{ $i }}][nhan_xet]">{{ $phanCong->nhan_xet }}</textarea>
                                        <span
                                            class="error-message text-danger d-none mt-2 error-ChamDiem{{ $i }}nhan_xet"></span>
                                    </div>
                                </div>
                            @endforeach
                            <div class="text-center">
                                <a href="{{ route('cham_diem_de_tai.danh_sach_huong_dan') }}"
                                    class="btn btn-secondary btn-lg">Quay lại</a>
                                <button class="btn btn-primary btn-lg" type="submit" id="chamDiem">Cập nhật điểm</button>
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
            $("#chamDiem").click(function(event) {
                event.preventDefault();

                let form = $("#form_cham_diem").get(0);
                let formData = new FormData(form);
                $('.nhan_xet').each(function(index) {
                    let name = $(this).attr('name');
                    let content = $(this).summernote('code');
                    formData.set(name, content);
                });

                $(".error-message").text('').removeClass("d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");
                $(".note-editor").css("border", "");

                $.ajax({
                    url: "{{ route('cham_diem_de_tai.xac_nhan_cham_diem_huong_dan') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(result) {
                        if (result.success) {
                            alert("Cập nhật điểm thành công!");
                            window.location.href =
                                "{{ route('cham_diem_de_tai.danh_sach_huong_dan') }}";
                        } else {
                            console.log(result.errors);
                            $.each(result.errors, function(field, messages) {
                                let fieldName = field.replace(/\./g, '][').replace(
                                    /^(.+?)\]\[/, '$1[') + ']';
                                let inputField = $("[name='" + fieldName + "']");
                                if (inputField.hasClass('nhan_xet')) {
                                    inputField.siblings(".note-editor").css("border",
                                        "1px solid red");
                                } else {
                                    inputField.addClass("is-invalid");
                                }
                                let errorClass = 'error-' + fieldName.replace(/\[/g, '')
                                    .replace(/\]/g, '');
                                $('.' + errorClass).text(messages[0]).removeClass(
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

            $('.nhan_xet').summernote({
                height: 300,
                minHeight: 300,
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
