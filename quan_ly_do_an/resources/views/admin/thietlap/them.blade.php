@extends('layouts.app')
@section('title', 'Thêm mới thiết lập')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Thêm mới thiết lập</h2>
                    </div>
                    <div class="card-body">
                        <form id="form_thiet_lap">
                            <div class="d-flex mb-3">
                                <label for="ThietLap[nam_hoc]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 200px">
                                    Năm học
                                </label>
                                <div class="ms-2">
                                    <div class="d-flex align-items-center">
                                        <input type="text" class="form-control form-control-lg shadow-none"
                                            name="ThietLap[nam_hoc_dau]" maxlength="4" style="width: 70px">
                                        <span class="mx-2">-</span>
                                        <input type="text" class="form-control form-control-lg shadow-none"
                                            name="ThietLap[nam_hoc_cuoi]" maxlength="4" style="width: 70px">
                                    </div>
                                    <span class="error-message text-danger d-none mt-2 error-ten_de_tai"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="ThietLap[nam_hoc]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 200px">
                                    Ngày đăng ký
                                </label>
                                <div class="ms-2">
                                    <div class="d-flex align-items-center">
                                        <div class="input-group" id="datetimepicker1" data-td-target-input="nearest"
                                            data-td-target="#ngayDangKyStart" style="width: 170px">
                                            <input type="text" class="form-control form-control-lg shadow-none"
                                                name="ThietLap[ngay_dang_ky]" id="ngayDangKyStart"
                                                data-td-target="#ngayDangKyStart" readonly />
                                            <span class="input-group-text" data-td-toggle="datetimepicker1"
                                                data-td-target="#ngayDangKyStart">
                                                <i class="bi bi-calendar-event"></i>
                                            </span>
                                        </div>
                                        <span class="mx-2">-</span>
                                        <div class="input-group" id="datetimepicker2" data-td-target-input="nearest"
                                            data-td-target="#ngayDangKyEnd" style="width: 170px">
                                            <input type="text" class="form-control form-control-lg shadow-none"
                                                name="ThietLap[ngay_ket_thuc_dang_ky]" id="ngayDangKyEnd"
                                                data-td-target="#ngayDangKyEnd" readonly />
                                            <span class="input-group-text" data-td-toggle="datetimepicker2"
                                                data-td-target="#ngayDangKyEnd">
                                                <i class="bi bi-calendar-event"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <span class="error-message text-danger d-none mt-2 error-ten_de_tai"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <label for="ThietLap[nam_hoc]"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 200px">
                                    Ngày thực hiện
                                </label>
                                <div class="ms-2">
                                    <div class="d-flex align-items-center">
                                        <div class="input-group" id="datetimepicker3" data-td-target-input="nearest"
                                            data-td-target="#ngayThucHienStart" style="width: 170px">
                                            <input type="text" class="form-control form-control-lg shadow-none"
                                                name="ThietLap[ngay_thuc_hien]" id="ngayThucHienStart"
                                                data-td-target="#ngayThucHienStart" readonly />
                                            <span class="input-group-text" data-td-toggle="datetimepicker3"
                                                data-td-target="#ngayThucHienStart">
                                                <i class="bi bi-calendar-event"></i>
                                            </span>
                                        </div>
                                        <span class="mx-2">-</span>
                                        <div class="d-flex align-items-center">
                                            <div class="input-group" id="datetimepicker4" data-td-target-input="nearest"
                                                data-td-target="#ngayThucHienEnd" style="width: 170px">
                                                <input type="text" class="form-control form-control-lg shadow-none"
                                                    name="ThietLap[ngay_ket_thuc_thuc_hien]" id="ngayThucHienEnd"
                                                    data-td-target="#ngayThucHienEnd" readonly />
                                                <span class="input-group-text" data-td-toggle="datetimepicker4"
                                                    data-td-target="#ngayThucHienEnd">
                                                    <i class="bi bi-calendar-event"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <span class="error-message text-danger d-none mt-2 error-ten_de_tai"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('thiet_lap.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                    lại</a>
                                <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal">
                                    Thiết lập
                                </button>
                            </div>
                            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                aria-hidden="true" style="font-size: 16px">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmModalLabel">Xác nhận thiết lập</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">Bạn có chắc chắn muốn thiết lập thời gian này không?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary" id="thietLap">Xác
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
            $("#thietLap").click(function(event) {
                event.preventDefault();

                let form = $("#form_thiet_lap").get(0);
                let formData = new FormData(form);
                formData.set("DeTai[mo_ta]", $("#mo_ta").summernote("code"));

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");
                $(".note-editor").css("border", "");

                $.ajax({
                    url: "{{ route('de_xuat_de_tai.xac_nhan_de_xuat') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(result) {
                        if (result.success) {
                            alert("Đề xuất thành công!");
                            location.reload();
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
                    error: function(xhr, status, error) {
                        console.error("Lỗi khi gửi dữ liệu:", error);
                        alert("Lỗi khi gửi dữ liệu! Vui lòng thử lại.");
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
                        format: 'dd-MM-yyyy'
                    },
                    useCurrent: false,
                });
            }
        });
    </script>
@endsection
