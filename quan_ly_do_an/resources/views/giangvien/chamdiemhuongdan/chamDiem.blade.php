@extends('layouts.app')
@section('title', 'Chấm điểm đề tài hướng dẫn')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Chấm điểm đề tài hướng dẫn</h2>
                    </div>
                    <div class="card-body">
                        <p style="font-size: 16px"><strong>Tên đề tài:</strong> {{ $deTai->ten_de_tai }}</p>
                        <form id="form_cham_diem">
                            <div class="d-flex mb-3">
                                <label for="ChamDiem[0][bao_ve]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Xác nhận bảo vệ
                                </label>
                                <div class="ms-2 w-100 d-flex align-items-center gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="ChamDiem[0][bao_ve]"
                                            id="ChamDiem_bao_ve_1" value="1" checked>
                                        <label class="form-check-label" for="ChamDiem_bao_ve_1">Được bảo vệ</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="ChamDiem[0][bao_ve]"
                                            id="ChamDiem_bao_ve_0" value="0">
                                        <label class="form-check-label" for="ChamDiem_bao_ve_0">Không được bảo vệ</label>
                                    </div>
                                </div>
                            </div>
                            <p><i>*Nếu bạn chọn <b>không được bảo vệ</b> thì không cần phải chấm điểm sinh viên</i></p>
                            <input type="hidden" name="ma_de_tai" value="{{ $deTai->ma_de_tai }}">
                            <div id="diem_nhan_xet">
                                @foreach ($deTai->sinhViens as $i => $sinhVien)
                                    <input type="hidden" name="ChamDiem[{{ $i }}][ma_sv]"
                                        value="{{ $sinhVien->ma_sv }}">
                                    @if ($sinhVien->trang_thai == 3)
                                        <p style="font-size: 16px"><strong>Sinh viên {{ $i + 1 }}:</strong>
                                            {{ $sinhVien->ho_ten }} - {{ $sinhVien->mssv }} (<span class="text-danger">Nghỉ
                                                giữa chừng</span>)</p>
                                    @else
                                        <p style="font-size: 16px"><strong>Sinh viên {{ $i + 1 }}:</strong>
                                            {{ $sinhVien->ho_ten }} - {{ $sinhVien->mssv }}</p>
                                        <div class="d-flex mb-3">
                                            <label for="ChamDiem[{{ $i }}][diem]"
                                                class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                                style="width: 250px">
                                                Báo cáo
                                            </label>
                                            <div class="ms-2 w-100 d-flex align-items-center">
                                                @if (!empty($sinhVien->bao_cao))
                                                    <a href="{{ route('tai_bao_cao', ['ma_sinh_vien' => $sinhVien->ma_sv]) }}"
                                                        download="{{ basename($sinhVien->bao_cao) }}" title="Tải file báo cáo">{{ basename($sinhVien->bao_cao) }}</a>
                                                @else
                                                    <i>Chưa có file báo cáo</i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex mb-3">
                                            <label for="ChamDiem[{{ $i }}][diem]"
                                                class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                                style="width: 250px">
                                                Điểm
                                            </label>
                                            <div class="ms-2 w-100">
                                                <input type="text"
                                                    class="form-control form-control-lg shadow-none text-center"
                                                    name="ChamDiem[{{ $i }}][diem]" style="width: 90px"
                                                    maxlength="4">
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
                                                <textarea class="form-control form-control-lg shadow-none nhan_xet" name="ChamDiem[{{ $i }}][nhan_xet]"></textarea>
                                                <span
                                                    class="error-message text-danger d-none mt-2 error-ChamDiem{{ $i }}nhan_xet"></span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="text-center">
                                <a href="{{ route('cham_diem_huong_dan.danh_sach') }}"
                                    class="btn btn-secondary btn-lg">Quay lại</a>
                                <button class="btn btn-primary btn-lg" type="submit" id="chamDiem">Xác nhận chấm
                                    điểm</button>
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

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");
                $(".note-editor").css("border", "");

                $.ajax({
                    url: "{{ route('cham_diem_huong_dan.xac_nhan_cham_diem') }}",
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
                                text: 'Chấm điểm thành công!',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('cham_diem_huong_dan.danh_sach') }}";
                            });
                        } else {
                            console.log(result.errors);
                            $.each(result.errors, function(field, messages) {
                                let fieldName = field.replace(/\./g, '][').replace(
                                    /^(.+?)\]\[/, '$1[') + ']';
                                let inputField = $("[name='" + fieldName + "']");
                                console.log(fieldName);
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
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất bại!',
                            text: 'Chấm điểm thất bại! Vui lòng thử lại',
                            confirmButtonText: 'OK',
                            timer: 1000,
                            showConfirmButton: false
                        })
                    },
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

            function toggleDiemNhanXet() {
                var baoVe = $('input[name="ChamDiem[0][bao_ve]"]:checked').val();
                if (baoVe === "0") {
                    $('#diem_nhan_xet').slideUp();
                } else {
                    $('#diem_nhan_xet').slideDown();
                }
            }

            toggleDiemNhanXet();

            $('input[name="ChamDiem[0][bao_ve]"]').on('change', function() {
                toggleDiemNhanXet();
            });
        });
    </script>
@endsection
