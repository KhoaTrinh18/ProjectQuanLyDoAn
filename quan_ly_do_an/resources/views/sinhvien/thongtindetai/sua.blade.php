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
                                        <option value="" selected>Chọn lĩnh vực</option>
                                        @foreach ($linhVucs as $linhVuc)
                                            <option value="{{ $linhVuc->ma_linh_vuc }}"
                                                {{ $linhVuc->ma_linh_vuc == $deTai->ma_linh_vuc ? 'selected' : '' }}>
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
                                    <textarea class="form-control form-control-lg shadow-none" name="DeTai[mo_ta]" id="mo_ta">{!! $deTai->mo_ta !!}</textarea>
                                    <span class="error-message text-danger d-none mt-2 error-mo_ta"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="DeTai[giang_vien][]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Giảng viên hướng dẫn
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
                                    <span class="error-message text-danger d-none mt-2 error-giang_vien"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="DeTai[mssv][]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary flex-column"
                                    style="width: 250px">
                                    Sinh viên làm chung
                                    <p class="m-0">(nếu có)</p>
                                </label>
                                <div class="ms-2 w-100">
                                    @for ($i = 0; $i < 3; $i++)
                                        @if ($i < $mssvList->count())
                                            <div class="d-flex align-items-center mt-2">
                                                <input type="text" class="form-control form-control-lg shadow-none me-2"
                                                    placeholder="Nhập MSSV" name="DeTai[mssv][{{ $i }}]"
                                                    style="width: 150px" value="{{ $mssvList[$i] }}">
                                                <span
                                                    class="error-message text-danger d-none error-mssv-{{ $i }}"></span>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center mt-2">
                                                <input type="text" class="form-control form-control-lg shadow-none me-2"
                                                    placeholder="Nhập MSSV" name="DeTai[mssv][{{ $i }}]"
                                                    style="width: 150px">
                                                <span
                                                    class="error-message text-danger d-none error-mssv-{{ $i }}"></span>
                                            </div>
                                        @endif
                                    @endfor
                                </div>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('thong_tin_de_tai.thong_tin') }}" class="btn btn-secondary btn-lg">
                                    Quay lại
                                </a>
                                <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal">
                                    Xác nhận cập nhật
                                </button>
                            </div>
                            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 shadow-sm border-0">
                                        <div class="modal-header bg-light border-bottom-0">
                                            <h5 class="modal-title fw-semibold text-primary" id="confirmModalLabel">Xác
                                                nhận
                                                cập nhật</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body text-center fs-5 text-secondary">
                                            Bạn có chắc chắn muốn cập nhật đề tài này không?
                                        </div>
                                        <div class="modal-footer bg-light border-top-0">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary" id="capNhat">Xác
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

@php
    $giangVienMaGv = $deTai->giangVienDuKiens->pluck('ma_gv')->toArray();
@endphp

@section('scripts')
    <script>
        $(document).ready(function() {
            var selectedGiangViens = [];

            $('#so_luong_giang_vien').change(function() {
                var soLuong = $(this).val();
                var chuyenNganhs = @json($chuyenNganhs);
                var giangVienSelects = $('#giang_vien_selects').empty();
                var giangVienMaGv = @json($giangVienMaGv);

                for (var i = 0; i < soLuong; i++) {
                    var selectWrapper = $('<div class="mt-2 d-flex align-items-center">');

                    var select = $('<select>')
                        .attr({
                            name: 'DeTai[giang_vien][' + i + ']',
                        })
                        .addClass('form-select form-select-lg shadow-none')
                        .css('width', '280px');

                    select.append('<option value="" selected hidden disabled>Chọn giảng viên ' + (i + 1) +
                        '</option>');

                    chuyenNganhs.forEach(function(cn) {
                        var optgroup = $('<optgroup>')
                            .attr('label', cn.ten_bo_mon);
                        cn.giang_viens.forEach(function(gv) {
                            var option = $('<option>')
                                .val(gv.ma_gv)
                                .text(gv.ho_ten);
                            if (giangVienMaGv[i] == gv.ma_gv) {
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

                var chuTich = $('select[name="HoiDong[chu_tich]"]').val();
                if (chuTich) selectedGiangViens.push(chuTich);

                var thuKy = $('select[name="HoiDong[thu_ky]"]').val();
                if (thuKy) selectedGiangViens.push(thuKy);

                $('#giang_vien_selects select').each(function() {
                    var val = $(this).val();
                    if (val) selectedGiangViens.push(val);
                });
            }

            function updateAllSelects() {
                $('#giang_vien_selects select').each(function() {
                    var allSelects = $(
                        'select[name="HoiDong[chu_tich]"], select[name="HoiDong[thu_ky]"], #giang_vien_selects select'
                    );

                    allSelects.each(function() {
                        var currentSelect = $(this);
                        var currentValue = currentSelect.val();

                        currentSelect.find('option').prop('disabled', false);

                        selectedGiangViens.forEach(function(id) {
                            if (id !== currentValue) {
                                currentSelect.find('option[value="' + id + '"]').prop(
                                    'disabled', true);
                            }
                        });
                    });
                });
            }

            function bindAllSelects() {
                $('select[name="HoiDong[chu_tich]"], select[name="HoiDong[thu_ky]"], #giang_vien_selects select')
                    .off('change').on('change', function() {
                        updateSelectedGiangViens();
                        updateAllSelects();
                    });
            }

            $('#so_luong_giang_vien').val(1).trigger('change');

            $("#capNhat").click(function(event) {
                event.preventDefault();

                let form = $("#form_cap_nhat").get(0);
                let formData = new FormData(form);
                formData.set("DeTai[mo_ta]", $("#mo_ta").summernote("code"));

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");
                $(".note-editor").css("border", "");

                $.ajax({
                    url: "{{ route('thong_tin_de_tai.xac_nhan_sua') }}",
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
                                    "{{ route('thong_tin_de_tai.thong_tin') }}";
                            });

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

            const deadline = new Date("{{ $ngayHetHan }}");

            const interval = setInterval(() => {
                const now = new Date();
                if (now >= deadline) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Hết thời gian!',
                        text: 'Bạn đã hết thời gian sửa đề tài.',
                        confirmButtonText: 'OK',
                        showConfirmButton: true
                    }).then(() => {
                        window.location.href =
                            "{{ route('thong_tin_de_tai.thong_tin') }}";
                    });
                    clearInterval(interval);
                }
            }, 10);
        });
    </script>
@endsection
