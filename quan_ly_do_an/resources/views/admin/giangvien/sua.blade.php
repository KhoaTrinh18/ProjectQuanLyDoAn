@extends('layouts.app')
@section('title', 'Cập nhật hội đồng')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Cập nhật hội đồng</h2>
                    </div>
                    <div class="card-body">
                        <form id="form_cap_nhat">
                            <input type="hidden" name="HoiDong[ma_hoi_dong]" value="{{ $hoiDong->ma_hoi_dong }}">
                            <div class="d-flex mb-3">
                                <label for="HoiDong[ten_hoi_dong]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Tên hội đồng
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="text" class="form-control form-control-lg shadow-none"
                                        placeholder="Nhập tên hội đồng" name="HoiDong[ten_hoi_dong]" style="width: 250px"
                                        value="{{ $hoiDong->ten_hoi_dong }}">
                                    <span class="error-message text-danger d-none mt-2 error-ten_hoi_dong"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="HoiDong[chuyen_nganh]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Chuyên nghành
                                </label>
                                <div class="ms-2 w-100">
                                    <select class="form-select form-select-lg shadow-none" name="HoiDong[chuyen_nganh]"
                                        style="width: 250px">
                                        <option value="" selected>Chọn chuyên ngành</option>
                                        @foreach ($chuyenNganhs as $chuyenNganh)
                                            <option value="{{ $chuyenNganh->ma_bo_mon }}"
                                                {{ $chuyenNganh->ma_bo_mon == $hoiDong->chuyenNganh->ma_bo_mon ? 'selected' : '' }}>
                                                {{ $chuyenNganh->ten_bo_mon }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error-message text-danger d-none mt-2 error-chuyen_nganh"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="HoiDong[phong]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Phòng
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="text" class="form-control form-control-lg shadow-none"
                                        placeholder="Nhập phòng" name="HoiDong[phong]" style="width: 250px"
                                        value="{{ $hoiDong->phong }}">
                                    <span class="error-message text-danger d-none mt-2 error-phong"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="HoiDong[ngay]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Ngày tổ chức
                                </label>
                                <div class="ms-2 w-100">
                                    <div class="input-group" id="datetimepicker" data-td-target-input="nearest"
                                        data-td-target="#ngayToChucInput" style="width: 250px">
                                        <input type="text" class="form-control form-control-lg shadow-none"
                                            name="HoiDong[ngay]" id="ngayToChucInput" data-td-target="#ngayToChucInput"
                                            readonly
                                            value="{{ \Carbon\Carbon::parse($hoiDong->ngay)->format('H:i d-m-Y') }}" />
                                        <span class="input-group-text" data-td-toggle="datetimepicker"
                                            data-td-target="#ngayToChucInput">
                                            <i class="bi bi-calendar-event"></i>
                                        </span>
                                    </div>
                                    <span class="error-message text-danger d-none mt-2 error-ngay"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="HoiDong[chu_tich]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Chủ tịch
                                </label>
                                <div class="ms-2 w-100">
                                    <select class="form-select form-select-lg shadow-none" name="HoiDong[chu_tich]"
                                        style="width: 260px">
                                        <option value="">Chọn giảng viên</option>
                                        @foreach ($giangViens as $giangVien)
                                            @php $chuTich = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Chủ tịch')->first(); @endphp
                                            <option value="{{ $giangVien->ma_gv }}"
                                                {{ $giangVien->ma_gv == $chuTich->ma_gv ? 'selected' : '' }}>
                                                {{ $giangVien->ho_ten }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error-message text-danger d-none mt-2 error-chu_tich"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="HoiDong[thu_ky]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Thư ký
                                </label>
                                <div class="ms-2 w-100">
                                    <select class="form-select form-select-lg shadow-none" name="HoiDong[thu_ky]"
                                        style="width: 260px">
                                        <option value="" selected>Chọn giảng viên</option>
                                        @foreach ($giangViens as $giangVien)
                                            @php $thuKy = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Thư ký')->first(); @endphp
                                            <option value="{{ $giangVien->ma_gv }}"
                                                {{ $giangVien->ma_gv == $thuKy->ma_gv ? 'selected' : '' }}>
                                                {{ $giangVien->ho_ten }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error-message text-danger d-none mt-2 error-thu_ky"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="HoiDong[uy_vien][]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Ủy viên
                                </label>
                                <div class="ms-2 w-100">
                                    <select id="so_luong_giang_vien" class="form-select form-select-lg shadow-none"
                                        style="width: 70px">
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                {{ $i == $hoiDong->giangViens()->wherePivot('chuc_vu', 'Ủy viên')->count() ? 'selected' : '' }}>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                    <div id="giang_vien_selects"></div>
                                    <span class="error-message text-danger d-none mt-2 error-uy_vien"></span>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('hoi_dong.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
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
    $uyViensMaGv = $hoiDong
        ->giangViens()
        ->wherePivot('chuc_vu', 'Ủy viên')
        ->pluck('hoi_dong_giang_vien.ma_gv')
        ->toArray();
@endphp

@section('scripts')
    <script>
        $(document).ready(function() {
            var selectedGiangViens = [];

            $('#so_luong_giang_vien').change(function() {
                var soLuong = $(this).val();
                var giangVienOptions = @json($giangViens);
                var giangVienSelects = $('#giang_vien_selects').empty();
                var uyViensMaGv = @json($uyViensMaGv);

                for (var i = 0; i < soLuong; i++) {
                    var selectWrapper = $(
                        '<div class="mt-2 d-flex align-items-center">'
                    );
                    var select = $('<select>')
                        .attr({
                            name: 'HoiDong[uy_vien][' + i + ']',
                        })
                        .addClass('form-select form-select-lg shadow-none')
                        .css('width', '260px');

                    select.append('<option value="">Chọn giảng viên ' + (i + 1) + '</option>');

                    giangVienOptions.forEach(function(giangVien) {
                        var option = $('<option>')
                            .val(giangVien.ma_gv)
                            .text(giangVien.ho_ten)
                        if (uyViensMaGv[i] == giangVien.ma_gv) {
                            option.prop('selected', true);
                        }
                        select.append(option);
                    });

                    selectWrapper.append(select)
                    selectWrapper.append(
                        '<span class="error-message text-danger d-hidden error-giangvien-[' + i +
                        '] ms-2"></span>');
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
                var allSelects = $(
                    'select[name="HoiDong[chu_tich]"], select[name="HoiDong[thu_ky]"], #giang_vien_selects select'
                );

                allSelects.each(function() {
                    var currentSelect = $(this);
                    var currentValue = currentSelect.val();

                    currentSelect.find('option').prop('disabled', false);

                    selectedGiangViens.forEach(function(id) {
                        if (id !== currentValue) {
                            currentSelect.find('option[value="' + id + '"]').prop('disabled', true);
                        }
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

            $('#so_luong_giang_vien').val(@json($hoiDong->giangViens()->wherePivot('chuc_vu', 'Ủy viên')->count())).trigger('change');

            $("#capNhat").click(function(event) {
                event.preventDefault();

                let form = $("#form_cap_nhat").get(0);
                let formData = new FormData(form);

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");

                $.ajax({
                    url: "{{ route('hoi_dong.xac_nhan_sua') }}",
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
                                    "{{ route('hoi_dong.danh_sach') }}";
                            });
                        } else {
                            if (result.error != null) {
                                $.each(result.errors, function(field, messages) {
                                    console.log(result);
                                    let inputField = $("[name='HoiDong[" + field +
                                        "]']");
                                    inputField.addClass("is-invalid");
                                    $('.error-' + field).text(messages[0]).removeClass(
                                        "d-none").addClass("d-block");
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Cập nhật thất bại! Vì hội đồng này đã được phân công',
                                    confirmButtonText: 'OK',
                                    timer: 2000,
                                    showConfirmButton: false
                                })
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
                    }
                });
            });

            const element = document.getElementById('datetimepicker');
            if (element) {
                new tempusDominus.TempusDominus(element, {
                    display: {
                        components: {
                            calendar: true,
                            date: true,
                            month: true,
                            year: true,
                            decades: true,
                            clock: true,
                            hours: true,
                            minutes: true,
                            seconds: false
                        },
                        icons: {
                            time: 'bi bi-clock',
                            date: 'bi bi-calendar',
                            up: 'bi bi-chevron-up',
                            down: 'bi bi-chevron-down',
                            previous: 'bi bi-chevron-left',
                            next: 'bi bi-chevron-right',
                            today: 'bi bi-calendar-check',
                            clear: 'bi bi-trash',
                            close: 'bi bi-x-circle'
                        },
                        buttons: {
                            today: true,
                            clear: true,
                            close: true
                        },
                    },
                    localization: {
                        locale: 'vi',
                        format: 'HH:mm dd-MM-yyyy'
                    },
                    useCurrent: false,
                });
            }

        });
    </script>
@endsection
