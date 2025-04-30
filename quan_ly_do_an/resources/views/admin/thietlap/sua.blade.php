@extends('layouts.app')
@section('title', 'Cập nhật thiết lập')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Cập nhật thiết lập</h2>
                    </div>
                    <div class="card-body">
                        <form id="form_cap_nhat">
                            <input type="hidden" name="ThietLap[ma_thiet_lap]" value="{{ $thietLap->ma_thiet_lap }}">
                            <div class="d-flex mb-3">
                                <label for="ThietLap[nam_hoc]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 200px">
                                    Năm học
                                </label>
                                <div class="ms-2">
                                    <div class="d-flex align-items-center">
                                        <input type="text" class="form-control form-control-lg shadow-none text-center"
                                            name="ThietLap[nam_hoc_dau]" maxlength="4" style="width: 90px"
                                            value={{ $namDau }} readonly>
                                        <span class="mx-2">-</span>
                                        <input type="text" class="form-control form-control-lg shadow-none text-center"
                                            name="ThietLap[nam_hoc_cuoi]" maxlength="4" style="width: 90px"
                                            value={{ $namCuoi }} readonly>
                                    </div>
                                    <span class="error-message text-danger d-none mt-2 error-nam_hoc"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="ThietLap[thoi_gian_dang_ky]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 200px">
                                    Thời gian đăng ký
                                </label>
                                <div class="ms-2">
                                    <div class="d-flex align-items-center">
                                        <div class="input-group" id="datetimepicker1" data-td-target-input="nearest"
                                            data-td-target="#ngayDangKyStart" style="width: 180px">
                                            <input type="text" class="form-control form-control-lg shadow-none"
                                                name="ThietLap[ngay_dang_ky]" id="ngayDangKyStart"
                                                data-td-target="#ngayDangKyStart" readonly value="{{ \Carbon\Carbon::parse($thietLap->ngay_dang_ky)->format('d-m-Y') }}" />
                                            <span class="input-group-text" data-td-toggle="datetimepicker1"
                                                data-td-target="#ngayDangKyStart">
                                                <i class="bi bi-calendar-event"></i>
                                            </span>
                                        </div>
                                        <span class="mx-2">-</span>
                                        <div class="input-group" id="datetimepicker2" data-td-target-input="nearest"
                                            data-td-target="#ngayDangKyEnd" style="width: 180px">
                                            <input type="text" class="form-control form-control-lg shadow-none"
                                                name="ThietLap[ngay_ket_thuc_dang_ky]" id="ngayDangKyEnd"
                                                data-td-target="#ngayDangKyEnd" readonly value="{{ \Carbon\Carbon::parse($thietLap->ngay_ket_thuc_dang_ky)->format('d-m-Y') }}"/>
                                            <span class="input-group-text" data-td-toggle="datetimepicker2"
                                                data-td-target="#ngayDangKyEnd">
                                                <i class="bi bi-calendar-event"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <span class="error-message text-danger d-none mt-2 error-thoi_gian_dang_ky"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="ThietLap[thoi_gian_thuc_hien]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 200px">
                                    Thời gian thực hiện
                                </label>
                                <div class="ms-2">
                                    <div class="d-flex align-items-center">
                                        <div class="input-group" id="datetimepicker3" data-td-target-input="nearest"
                                            data-td-target="#ngayThucHienStart" style="width: 180px">
                                            <input type="text" class="form-control form-control-lg shadow-none"
                                                name="ThietLap[ngay_thuc_hien]" id="ngayThucHienStart"
                                                data-td-target="#ngayThucHienStart" readonly value="{{ \Carbon\Carbon::parse($thietLap->ngay_thuc_hien)->format('d-m-Y') }}"/>
                                            <span class="input-group-text" data-td-toggle="datetimepicker3"
                                                data-td-target="#ngayThucHienStart">
                                                <i class="bi bi-calendar-event"></i>
                                            </span>
                                        </div>
                                        <span class="mx-2">-</span>
                                        <div class="d-flex align-items-center">
                                            <div class="input-group" id="datetimepicker4" data-td-target-input="nearest"
                                                data-td-target="#ngayThucHienEnd" style="width: 180px">
                                                <input type="text" class="form-control form-control-lg shadow-none"
                                                    name="ThietLap[ngay_ket_thuc_thuc_hien]" id="ngayThucHienEnd"
                                                    data-td-target="#ngayThucHienEnd" readonly value="{{ \Carbon\Carbon::parse($thietLap->ngay_ket_thuc_thuc_hien)->format('d-m-Y') }}"/>
                                                <span class="input-group-text" data-td-toggle="datetimepicker4"
                                                    data-td-target="#ngayThucHienEnd">
                                                    <i class="bi bi-calendar-event"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="error-message text-danger d-none mt-2 error-thoi_gian_thuc_hien"></span>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('thiet_lap.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                    lại</a>
                                <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal">
                                    Xác nhận cập nhật
                                </button>
                            </div>
                            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                aria-hidden="true" style="font-size: 16px">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 shadow-sm border-0">
                                        <div class="modal-header bg-light border-bottom-0">
                                            <h5 class="modal-title fw-semibold text-primary" id="confirmModalLabel">Xác nhận cập nhật</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center fs-5 text-secondary">Bạn có chắc muốn cập nhật thiết lập này không?
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

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#capNhat").click(function(event) {
                event.preventDefault();

                let form = $("#form_cap_nhat").get(0);
                let formData = new FormData(form);

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");

                $.ajax({
                    url: "{{ route('thiet_lap.xac_nhan_sua') }}",
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
                                    "{{ route('thiet_lap.danh_sach') }}";
                            });
                        } else {
                            $("#confirmModal").modal('hide');

                            $.each(result.errors, function(field, messages) {
                                let inputField = $("[name='ThietLap[" + field + "]']");
                                $('.error-' + field).text(messages[0]).removeClass(
                                    "d-none").addClass("d-block");
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

            const element1 = document.getElementById('datetimepicker1');
            if (element1) {
                new tempusDominus.TempusDominus(element1, {
                    display: {
                        components: {
                            calendar: true,
                            date: true,
                            month: true,
                            year: true,
                            decades: true,
                        },
                        icons: {
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
                        format: 'dd-MM-yyyy'
                    },
                    useCurrent: false,
                });
            }

            const element2 = document.getElementById('datetimepicker2');
            if (element2) {
                new tempusDominus.TempusDominus(element2, {
                    display: {
                        components: {
                            calendar: true,
                            date: true,
                            month: true,
                            year: true,
                            decades: true,
                        },
                        icons: {
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
                        format: 'dd-MM-yyyy'
                    },
                    useCurrent: false,
                });
            }

            const element3 = document.getElementById('datetimepicker3');
            if (element3) {
                new tempusDominus.TempusDominus(element3, {
                    display: {
                        components: {
                            calendar: true,
                            date: true,
                            month: true,
                            year: true,
                            decades: true,
                        },
                        icons: {
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
                        format: 'dd-MM-yyyy'
                    },
                    useCurrent: false,
                });
            }

            const element4 = document.getElementById('datetimepicker4');
            if (element4) {
                new tempusDominus.TempusDominus(element4, {
                    display: {
                        components: {
                            calendar: true,
                            date: true,
                            month: true,
                            year: true,
                            decades: true,
                        },
                        icons: {
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
                        format: 'dd-MM-yyyy'
                    },
                    useCurrent: false,
                });
            }
        });
    </script>
@endsection
