@extends('layouts.app')
@section('title', 'Đưa ra đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Đưa ra đề tài</h2>
                    </div>
                    <div class="card-body">
                        <form id="form_dua_ra">
                            <div class="d-flex mb-3">
                                <label for="DeTai[ten_de_tai]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Tên đề tài
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="text" class="form-control form-control-lg shadow-none"
                                        placeholder="Nhập tên đề tài" name="DeTai[ten_de_tai]">
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
                                        <option value="" selected>Chọn lĩnh vực</option>
                                        @foreach ($linhVucs as $linhVuc)
                                            <option value="{{ $linhVuc->ma_linh_vuc }}">{{ $linhVuc->ten_linh_vuc }}
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
                                    <textarea class="form-control form-control-lg shadow-none" name="DeTai[mo_ta]" id="mo_ta"></textarea>
                                    <span class="error-message text-danger d-none mt-2 error-mo_ta"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="DeTai[slsv_toi_da]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary flex-column"
                                    style="width: 250px">
                                    Số lượng sinh viên tối đa
                                </label>
                                <div class="ms-2 w-100">
                                    <select name="DeTai[slsv_toi_da]" class="form-select form-select-lg shadow-none"
                                        style="width: 70px">
                                        @for ($i = 1; $i <= 4; $i++)
                                            <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                    <span class="error-message text-danger d-none mt-2 error-slsv_toi_da"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="DeTai[giang_vien][]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary flex-column"
                                    style="width: 250px">
                                    Giảng viên (nếu có)
                                </label>
                                <div class="ms-2 w-100">
                                    <select id="so_luong_giang_vien" class="form-select form-select-lg shadow-none"
                                        style="width: 70px">
                                        @for ($i = 1; $i <= 3; $i++)
                                            <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                    <div id="giang_vien_selects"></div>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('dua_ra_de_tai.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                    lại</a>
                                <button class="btn btn-primary btn-lg" type="submit" id="duaRa">Xác nhận đưa
                                    ra</button>
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
            var selectedGiangViens = [];

            $('#so_luong_giang_vien').change(function() {
                var soLuong = $(this).val();
                var chuyenNganhs = @json($chuyenNganhs);
                var giangVienSelects = $('#giang_vien_selects').empty();

                for (var i = 0; i < soLuong; i++) {
                    var selectWrapper = $('<div class="mt-2 d-flex align-items-center">');

                    var select = $('<select>')
                        .attr({
                            name: 'DeTai[giang_vien][' + i + ']',
                        })
                        .addClass('form-select form-select-lg shadow-none')
                        .css('width', '250px');

                    select.append('<option value="" selected hidden disabled>Chọn giảng viên ' + (i + 1) + '</option>');

                    chuyenNganhs.forEach(function(cn) {
                        var optgroup = $('<optgroup>')
                            .attr('label', cn.ten_bo_mon);
                        cn.giang_viens.forEach(function(gv) {
                            var option = $('<option>')
                                .val(gv.ma_gv)
                                .text(gv.ho_ten);
                            optgroup.append(option);
                        });

                        select.append(optgroup);
                    });

                    selectWrapper.append(select);
                    selectWrapper.append(
                        '<span class="error-message text-danger d-hidden error-giangvien-[' + i +
                        '] ms-2"></span>'
                    );
                    giangVienSelects.append(selectWrapper);
                }

                bindAllSelects();
                updateSelectedGiangViens();
                updateAllSelects();
            });


            function updateSelectedGiangViens() {
                selectedGiangViens = [];

                $('#giang_vien_selects select').each(function() {
                    var val = $(this).val();
                    if (val) selectedGiangViens.push(val);
                });
            }

            function updateAllSelects() {
                $('#giang_vien_selects select').each(function() {
                    var currentSelect = $(this);
                    var currentValue = currentSelect.val();

                    currentSelect.find('option').prop('disabled', false);

                    selectedGiangViens.forEach(function(id) {
                        if (id !== currentValue) {
                            currentSelect.find('option[value="' + id + '"]:not(:selected)').prop(
                                'disabled', true);
                        }
                    });
                });
            }

            function bindAllSelects() {
                $('#giang_vien_selects select')
                    .off('change').on('change', function() {
                        updateSelectedGiangViens();
                        updateAllSelects();
                    });
            }

            $('#so_luong_giang_vien').val(1).trigger('change');


            $("#duaRa").click(function(event) {
                event.preventDefault();

                let form = $("#form_dua_ra").get(0);
                let formData = new FormData(form);
                formData.set("DeTai[mo_ta]", $("#mo_ta").summernote("code"));

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");
                $(".note-editor").css("border", "");

                $.ajax({
                    url: "{{ route('dua_ra_de_tai.xac_nhan_dua_ra') }}",
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
                                text: 'Đưa ra thành công!',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('dua_ra_de_tai.danh_sach') }}";
                            });
                        } else {
                            $.each(result.errors, function(field, messages) {
                                console.log(result);
                                let inputField = $("[name='DeTai[" + field + "]']");
                                if (field == 'mo_ta') {
                                    let summernoteEditor = $("#mo_ta").siblings(
                                        ".note-editor");
                                    summernoteEditor.css("border", "1px solid red");
                                }
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
                            text: 'Đưa ra thất bại! Vui lòng thử lại',
                            confirmButtonText: 'OK',
                            timer: 1000,
                            showConfirmButton: false
                        })
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

            const deadline = new Date("{{ $ngayHetHan }}");

            const interval = setInterval(() => {
                const now = new Date();
                if (now >= deadline) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Hết thời gian!',
                        text: 'Bạn đã hết thời gian đưa ra đề tài.',
                        confirmButtonText: 'OK',
                        showConfirmButton: true
                    }).then(() => {
                        window.location.href =
                            "{{ route('dua_ra_de_tai.danh_sach') }}";
                    });
                    clearInterval(interval);
                }
            }, 10);
        });
    </script>
@endsection
