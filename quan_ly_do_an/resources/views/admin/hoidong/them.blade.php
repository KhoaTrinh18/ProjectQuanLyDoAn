@extends('layouts.app')
@section('title', 'Thêm mới hội đồng')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Thêm mới hội đồng</h2>
                    </div>
                    <div class="card-body">
                        <form id="form_them">
                            <div class="d-flex mb-3">
                                <label for="HoiDong[ten_hoi_dong]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Tên hội đồng
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="text" class="form-control form-control-lg shadow-none"
                                        placeholder="Nhập tên hội đồng" name="HoiDong[ten_hoi_dong]" style="width: 280px">
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
                                        style="width: 280px">
                                        <option value="" selected hidden disabled>Chọn chuyên ngành</option>
                                        @foreach ($chuyenNganhs as $chuyenNganh)
                                            <option value="{{ $chuyenNganh->ma_bo_mon }}">{{ $chuyenNganh->ten_bo_mon }}
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
                                        placeholder="Nhập phòng" name="HoiDong[phong]" style="width: 280px">
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
                                        data-td-target="#ngayToChucInput" style="width: 280px">
                                        <input type="text" class="form-control form-control-lg shadow-none"
                                            name="HoiDong[ngay]" id="ngayToChucInput" data-td-target="#ngayToChucInput"
                                            readonly />
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
                                        style="width: 280px">
                                        <option value="" selected hidden disabled>Chọn giảng viên</option>
                                        @foreach ($chuyenNganhs as $chuyenNganh)
                                            <optgroup label="{{ $chuyenNganh->ten_bo_mon }}">
                                                @foreach ($chuyenNganh->giangViens as $giangVien)
                                                    <option value="{{ $giangVien->ma_gv }}">
                                                        {{ $giangVien->ho_ten }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
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
                                        style="width: 280px">
                                        <option value="" selected hidden disabled>Chọn giảng viên</option>
                                        @foreach ($chuyenNganhs as $chuyenNganh)
                                            <optgroup label="{{ $chuyenNganh->ten_bo_mon }}">
                                                @foreach ($chuyenNganh->giangViens as $giangVien)
                                                    <option value="{{ $giangVien->ma_gv }}">
                                                        {{ $giangVien->ho_ten }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
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
                                        @for ($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>
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
                                <button type="submit" class="btn btn-success btn-lg" id="them">Xác nhận thêm
                                    mới</button>
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
                            name: 'HoiDong[uy_vien][' + i + ']',
                        })
                        .addClass('form-select form-select-lg shadow-none')
                        .css('width', '280px');

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

            $("#them").click(function(event) {
                event.preventDefault();

                let form = $("#form_them").get(0);
                let formData = new FormData(form);

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");

                $.ajax({
                    url: "{{ route('hoi_dong.xac_nhan_them') }}",
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
                                text: 'Thêm mới thành công!',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('hoi_dong.danh_sach') }}";
                            });
                        } else {
                            $.each(result.errors, function(field, messages) {
                                let inputField = $("[name='HoiDong[" + field + "]']");
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
                            text: 'Thêm mới thất bại! Vui lòng thử lại',
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
