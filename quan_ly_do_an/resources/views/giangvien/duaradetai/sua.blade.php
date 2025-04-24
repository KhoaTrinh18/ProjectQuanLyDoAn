@extends('layouts.app')
@section('title', 'Cập nhật đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Cập nhật đề tài</h2>
                    </div>
                    <div class="card-body">
                        <form id="form_cap_nhat">
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
                            <div class="d-flex mb-3">
                                <label for="DeTai[slsv_toi_da]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary flex-column"
                                    style="width: 250px">
                                    Số lượng sinh viên tối đa
                                </label>
                                <div class="ms-2 w-100">
                                    <select name="DeTai[slsv_toi_da]" class="form-select form-select-lg shadow-none"
                                        style="width: 70px">
                                        @for ($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}" {{ $i == $deTai->so_luong_sv_toi_da ? 'selected' : '' }}>
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
                                        @for ($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                    <div id="giang_vien_selects"></div>
                                    <span class="error-message text-danger d-none mt-2 error-giang_vien"></span>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('dua_ra_de_tai.danh_sach') }}" class="btn btn-secondary btn-lg">
                                    Quay lại
                                </a>
                                <button class="btn btn-primary btn-lg" type="submit" id="sua">
                                    Xác nhận cập nhật
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

            $('#so_luong_giang_vien').change(function() {
                var soLuong = $(this).val();
                var chuyenNganhs = @json($chuyenNganhs);
                var giangVienSelects = $('#giang_vien_selects').empty();
                var giangViensDT = @json($giangViensDT);

                for (var i = 0; i < soLuong; i++) {
                    var selectWrapper = $('<div class="mt-2 d-flex align-items-center">');

                    var select = $('<select>')
                        .attr({
                            name: 'DeTai[giang_vien][' + i + ']',
                        })
                        .addClass('form-select form-select-lg shadow-none')
                        .css('width', '250px');

                    select.append('<option value="">Chọn giảng viên ' + (i + 1) + '</option>');

                    chuyenNganhs.forEach(function(cn) {
                        var optgroup = $('<optgroup>')
                            .attr('label', cn.ten_bo_mon);
                        cn.giang_viens.forEach(function(gv) {
                            var option = $('<option>')
                                .val(gv.ma_gv)
                                .text(gv.ho_ten);
                            if (giangViensDT[i] == gv.ma_gv) {
                                option.prop('selected', true);
                            }
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

            $('#so_luong_giang_vien').val(@json($giangViensDT->count())).trigger('change');

            $("#sua").click(function(event) {
                event.preventDefault();

                let form = $("#form_cap_nhat").get(0);
                let formData = new FormData(form);
                formData.set("DeTai[mo_ta]", $("#mo_ta").summernote("code"));

                $(".error-message").text('').removeClass("d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");
                $(".note-editor").css("border", "");

                $.ajax({
                    url: "{{ route('dua_ra_de_tai.xac_nhan_sua') }}",
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
                                    "{{ route('dua_ra_de_tai.danh_sach') }}";
                            });
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
