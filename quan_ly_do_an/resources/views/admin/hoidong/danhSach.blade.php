@extends('layouts.app')
@section('title', 'Danh sách hội đồng')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách hội đồng</h2>
                        <div>
                            <a href="{{ route('hoi_dong.them') }}" class="btn btn-success btn-lg">Thêm mới</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="d-flex mb-3" id="form_tim_kiem">
                            <div class="d-flex flex-column me-3" style="width: 370px">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <label for="nam_hoc" style="width: 150px">Năm học:</label>
                                    <select class="form-select ms-2 w-75 shadow-none" name="nam_hoc">
                                        <option value="" selected>Chọn năm học</option>
                                        @foreach ($thietLaps as $thietLap)
                                            <option value="{{ $thietLap->nam_hoc }}">{{ $thietLap->nam_hoc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="chuyen_nghanh" style="width: 150px">Chuyên ngành:</label>
                                    <select class="form-select ms-2 w-75 shadow-none" name="chuyen_nghanh">
                                        <option value="" selected>Chọn chuyên ngành</option>
                                        @foreach ($chuyenNganhs as $chuyenNganh)
                                            <option value="{{ $chuyenNganh->ma_bo_mon }}">{{ $chuyenNganh->ten_bo_mon }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex flex-column" style="width: 480px">
                                <div class="d-flex align-items-center">
                                    <label for="ngay_to_chuc" style="width: 150px">Ngày tổ chức:</label>
                                    <div class="d-flex align-items-center ms-2 w-100">
                                        <div class="input-group" id="datetimepicker1" data-td-target-input="nearest"
                                            data-td-target="#ngayToChucDau">
                                            <input type="text" class="form-control form-control-lg shadow-none"
                                                name="ngay_to_chuc_dau" id="ngayToChucDau" data-td-target="#ngayToChucDau"
                                                readonly />
                                            <span class="input-group-text" data-td-toggle="datetimepicker1"
                                                data-td-target="#ngayToChucDau">
                                                <i class="bi bi-calendar-event"></i>
                                            </span>
                                        </div>
                                        <span class="mx-2">-</span>
                                        <div class="input-group" id="datetimepicker2" data-td-target-input="nearest"
                                            data-td-target="#ngayToChucCuoi">
                                            <input type="text" class="form-control form-control-lg shadow-none"
                                                name="ngay_to_chuc_cuoi" id="ngayToChucCuoi"
                                                data-td-target="#ngayToChucCuoi" readonly />
                                            <span class="input-group-text" data-td-toggle="datetimepicker2"
                                                data-td-target="#ngayToChucCuoi">
                                                <i class="bi bi-calendar-event"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button id="clear" class="btn btn-secondary me-2">Clear</button>
                                    <button id="timKiem" class="btn btn-primary" type="submit">Tìm kiếm</button>
                                </div>
                            </div>
                        </form>
                        @include('admin.hoidong.pageAjax', ['hoiDongs' => $hoiDongs])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            let isLoading = false;
            let lastSearchParams = {};

            function showTableLoading() {
                let colCount = $('#table-body').closest('table').find('thead tr th').length;
                $('#table-body').html(`
                    <tr>
                        <td colspan="${colCount}" class="text-center">
                            <div class="d-flex justify-content-center align-items-center">
                                <span class="spinner-border text-primary me-2" role="status"></span>
                                <span>Đang tải dữ liệu...</span>
                            </div>
                        </td>
                    </tr>
                `);
            }

            function fetchData(page = 1, searchParams = null) {
                if (isLoading) return;
                isLoading = true;

                let limit = $('#recordsPerPage').val();

                if (searchParams !== null) {
                    lastSearchParams = searchParams;
                } else {
                    searchParams = lastSearchParams;
                }

                let requestData = Object.assign({
                    page,
                    limit
                }, searchParams);

                showTableLoading();

                $.ajax({
                    url: "{{ route('hoi_dong.page_ajax') }}",
                    type: "GET",
                    data: requestData,
                    dataType: 'json',
                    success: function(result) {
                        $("#data-container").html(result.html);
                    },
                    error: function() {
                        alert("Lỗi tải dữ liệu, vui lòng thử lại!");
                    },
                    complete: function() {
                        isLoading = false;
                    }
                });
            }

            $(document).on('change', '#recordsPerPage', function() {
                fetchData(1);
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).data('page');
                if (!$(this).parent().hasClass('disabled') && page) fetchData(page);
            });

            $("#timKiem").click(function(event) {
                event.preventDefault();

                let formData = $("#form_tim_kiem").serializeArray();
                let searchParams = {};

                $.each(formData, function(i, field) {
                    if (field.value.trim() !== "") {
                        searchParams[field.name] = field.value;
                    }
                });

                fetchData(1, searchParams);
            });

            $("#clear").click(function(event) {
                event.preventDefault();

                $("#form_tim_kiem").find("input, select").val("");
                $("#recordsPerPage").val("10");

                fetchData(1, {});
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
                            clock: false,
                            hours: false,
                            minutes: false,
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
                            clock: false,
                            hours: false,
                            minutes: false,
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
                        format: 'dd-MM-yyyy'
                    },
                    useCurrent: false,
                });
            }
        });
    </script>
@endsection
