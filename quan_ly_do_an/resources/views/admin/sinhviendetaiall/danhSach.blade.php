@extends('layouts.app')
@section('title', 'Danh sách sinh viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách sinh viên</h2>
                    </div>
                    <div class="card-body">
                        <form class="d-flex mb-3" id="form_tim_kiem">
                            <div class="d-flex flex-column" style="width: 420px">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="sinh_vien" style="width: 200px">Sinh viên:</label>
                                    <input type="text" name="sinh_vien" class="form-control ms-2 w-75 shadow-none"
                                        placeholder="Tên sinh viên">
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <label for="ten_de_tai" style="width: 200px">Tên đề tài:</label>
                                    <input type="text" name="ten_de_tai" class="form-control ms-2 w-75 shadow-none"
                                        placeholder="Tên đề tài">
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <label for="giang_vien" style="width: 205px">Giảng viên hướng dẫn:</label>
                                    <select class="form-select ms-2 w-75 shadow-none" name="giang_vien">
                                        <option value="" selected disabled hidden>Chọn giảng viên</option>
                                        <option value="">Tất cả</option>
                                        @foreach ($chuyenNganhs as $chuyenNganh)
                                            <optgroup label="{{ $chuyenNganh->ten_bo_mon }}">
                                                @foreach ($chuyenNganh->giangViens as $giangVien)
                                                    <option value="{{ $giangVien->ma_gv }}">
                                                        {{ $giangVien->ho_ten }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <label for="nam_hoc" style="width: 205px">Năm học:</label>
                                    <select class="form-select ms-2 w-75 shadow-none" name="nam_hoc">
                                        <option value="" selected hidden disabled>Chọn năm học</option>
                                        <option value="">Tất cả</option>
                                        @foreach ($thietLaps as $thietLap)
                                            <option value="{{ $thietLap->nam_hoc }}">{{ $thietLap->nam_hoc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex flex-column ms-3" style="width: 300px">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="mssv" style="width: 150px">MSSV:</label>
                                    <input type="text" name="mssv" class="form-control ms-2 w-100 shadow-none"
                                        placeholder="MSSV">
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <label for="lop" style="width: 150px">Lớp:</label>
                                    <input type="text" name="lop" class="form-control ms-2 w-100 shadow-none"
                                        placeholder="Tên lớp">
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <label for="trang_thai" style="width: 156px">Trạng thái:</label>
                                    <select class="form-select ms-2 w-100 shadow-none" name="trang_thai">
                                        <option value="" selected hidden disabled>Chọn trạng thái</option>
                                        <option value="">Tất cả</option>
                                        <option value="0">Không hoàn thành</option>
                                        <option value="2">Đã hoàn thành</option>
                                        <option value="3">Nghỉ giữa chừng</option>
                                    </select>
                                </div>
                                <div class="ms-3 d-flex justify-content-end mt-2">
                                    <button id="clear" class="btn btn-secondary me-2">Tạo lại</button>
                                    <button id="timKiem" class="btn btn-primary" type="submit">Tìm kiếm</button>
                                </div>
                            </div>
                        </form>
                        @include('admin.sinhviendetaiall.pageAjax', ['sinhviens' => $sinhViens])
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
                    url: "{{ route('sinh_vien_de_tai_all.page_ajax') }}",
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
        });
    </script>
@endsection
