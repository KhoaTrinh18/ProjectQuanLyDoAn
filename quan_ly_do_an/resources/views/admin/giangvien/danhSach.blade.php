@extends('layouts.app')
@section('title', 'Danh sách giảng viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách giảng viên</h2>
                        <div>
                            <a href="{{ route('giang_vien.them') }}" class="btn btn-success btn-lg">Thêm mới</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="d-flex mb-3" id="form_tim_kiem">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="giang_vien" style="width: 120px">Tên giảng viên:</label>
                                    <input type="text" name="giang_vien" class="form-control ms-2 w-75 shadow-none"
                                        placeholder="Tên giảng viên">
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <label for="bo_mon" style="width: 120px">Bộ môn:</label>
                                    <select class="form-select ms-2 w-75 shadow-none" name="bo_mon">
                                        <option value="" selected disabled hidden>Chọn bộ môn</option>
                                        <option value="">Tất cả</option>
                                        @foreach ($boMons as $boMon)
                                            <option value="{{ $boMon->ma_bo_mon }}">{{ $boMon->ten_bo_mon }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex flex-column ms-3">
                                <div class="d-flex align-items-center justify-content-between" style="width: 220px">
                                    <label for="hoc_vi">Học vị:</label>
                                    <select class="form-select ms-2 w-75 shadow-none" name="hoc_vi">
                                        <option value="" selected disabled hidden>Chọn học vị</option>
                                        <option value="">Tất cả</option>
                                        @foreach ($hocVis as $hocVi)
                                            <option value="{{ $hocVi->ma_hoc_vi }}">{{ $hocVi->ten_hoc_vi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button id="clear" class="btn btn-secondary me-2">Tạo lại</button>
                                    <button id="timKiem" class="btn btn-primary" type="submit">Tìm kiếm</button>
                                </div>
                            </div>
                        </form>
                        @include('admin.giangvien.pageAjax', ['giangViens' => $giangViens])
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
                    url: "{{ route('giang_vien.page_ajax') }}",
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
