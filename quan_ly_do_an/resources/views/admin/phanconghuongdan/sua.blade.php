@extends('layouts.app')
@section('title', 'Cập nhật giảng viên hướng dẫn')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Cập nhật giảng viên hướng dẫn</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTai->ten_de_tai }}</h3>

                        @if ($deTai->sinhViens->count() == 1)
                            @php $sinhVien = $deTai->sinhViens->first(); @endphp
                            <p><strong>Sinh viên thực hiện:</strong> {{ $sinhVien->ho_ten }} - MSSV: {{ $sinhVien->mssv }}
                            @else
                            <p><strong>Sinh viên thực hiện:</strong></p>
                            <ul>
                                @foreach ($deTai->sinhViens as $sinhVien)
                                    <li>{{ $sinhVien->ho_ten }} - MSSV: {{ $sinhVien->mssv }}
                                @endforeach
                            </ul>
                        @endif

                        @if ($deTai->giangViens->count() == 0)
                            <p><strong>Giảng viên hướng dẫn:</strong> Chưa có</p>
                        @elseif ($deTai->giangViens->count() == 1)
                            @php $giangVien = $deTai->giangVienHuongDans->first(); @endphp
                            <p><strong>Giảng viên hướng dẫn:</strong> {{ $giangVien->ho_ten }} - Email:
                                {{ $giangVien->email }} - Số điện thoại: {{ $giangVien->so_dien_thoai }}
                            @else
                            <p><strong>Giảng viên hướng dẫn:</strong></p>
                            <ul>
                                @foreach ($deTai->giangVienHuongDans as $giangVien)
                                    <li>{{ $giangVien->ho_ten }} - Email: {{ $giangVien->email }} - Số điện thoại:
                                        {{ $giangVien->so_dien_thoai }}
                                @endforeach
                            </ul>
                        @endif

                        @if ($deTai->sinhViens->first()->loai_sv == 'dang_ky')
                            @if ($deTai->giangViens->count() == 1)
                                @php $giangVien = $deTai->giangViens->first(); @endphp
                                <p><strong>Giảng viên ra đề tài:</strong> {{ $giangVien->ho_ten }} - Email:
                                    {{ $giangVien->email }} - Số điện thoại: {{ $giangVien->so_dien_thoai }}
                                @else
                                <p><strong>Giảng viên ra đề tài:</strong></p>
                                <ul>
                                    @foreach ($deTai->giangViens as $giangVien)
                                        <li>{{ $giangVien->ho_ten }} - Email: {{ $giangVien->email }} - Số điện thoại:
                                            {{ $giangVien->so_dien_thoai }}
                                    @endforeach
                                </ul>
                            @endif
                        @endif

                        <p><strong>Lĩnh vực:</strong> {{ $deTai->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {!! $deTai->mo_ta !!}</p>
                        <form id="form_cap_nhat">
                            <div class="d-flex align-items-center">
                                <label for="so_luong_giang_vien"><strong>Chọn số lượng giảng viên:</strong></label>
                                <select id="so_luong_giang_vien" class="form-select ms-2" style="width: 70px">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}"
                                            {{ $i == $deTai->giangVienHuongDans->count() ? 'selected' : '' }}>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div id="giang_vien_selects"></div>
                            <span class="error-message text-danger d-hidden error-giangvien m-0 mt-2"></span>

                            <input type="hidden" name="DeTai[ma_de_tai]" value="{{ $deTai->ma_de_tai }}">
                            <div class="text-center">
                                <a href="{{ route('phan_cong_huong_dan.danh_sach') }}"
                                    class="btn btn-secondary btn-lg">Quay
                                    lại</a>
                                <button type="submit" class="btn btn-primary btn-lg" id="capNhat">Xác nhận cập
                                    nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@php
    $giangVienMaGv = $deTai->giangVienHuongDans->pluck('ma_gv')->toArray();
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
                    var label = $('<label>').attr('for', 'giang_vien_' + i).text('Giảng viên ' + (i + 1) +
                        ':').css({
                        'width': '110px',
                        'font-weight': 'bold'
                    });
                    var select = $('<select>')
                        .attr({
                            name: 'DeTai[giang_vien][' + i + ']',
                        })
                        .addClass('form-select form-select-lg shadow-none')
                        .css('width', '280px');

                    select.append('<option value="" disabled hidden>Chọn giảng viên ' + (i + 1) +
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

                    selectWrapper.append(label).append(select);
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
            $('#so_luong_giang_vien').val(@json($deTai->giangVienHuongDans->count())).trigger('change');

            $("#capNhat").click(function(event) {
                event.preventDefault();

                let form = $("#form_cap_nhat").get(0);
                let formData = new FormData(form);

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");

                $.ajax({
                    url: "{{ route('phan_cong_huong_dan.xac_nhan_sua') }}",
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
                                    "{{ route('phan_cong_huong_dan.danh_sach') }}";
                            });
                        } else {
                            if (result.errors == 'phan_cong') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Cập nhật thất bại! Đề tài này đã được phân công phản biện',
                                    confirmButtonText: 'OK',
                                    timer: 1000,
                                    showConfirmButton: false
                                })
                            } else if (result.errors == 'cham_diem') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Cập nhật thất bại! Đề tài này đã được chấm điểm',
                                    confirmButtonText: 'OK',
                                    timer: 1000,
                                    showConfirmButton: false
                                })
                            } else {
                                $.each(result.errors, function(field, messages) {
                                    let inputField = $("[name='DeTai[" + field + "]']");
                                    $('.error-' + field).text(messages[0]).removeClass(
                                        "d-none").addClass("d-block");
                                    if (field.startsWith("giangvien.")) {
                                        let index = field.split('.')[1];
                                        $(".error-giangvien-" + index).text(messages[0])
                                            .removeClass("d-none").show();
                                        $("[name='DeTai[giang_vien][" + index + "]']")
                                            .addClass(
                                                "is-invalid");
                                    }
                                    inputField.addClass("is-invalid");
                                });
                            }
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
                    },
                });
            });
        });
    </script>
@endsection
