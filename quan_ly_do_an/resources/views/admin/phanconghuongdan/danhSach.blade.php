@extends('layouts.app')
@section('title', 'Phân công hướng dẫn')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách đề tài</h2>
                        <a href="{{ route('phan_cong_huong_dan.tai_danh_sach_huong_dan') }}" class="btn btn-info btn-lg">Tải danh sách hướng dẫn</a>

                    </div>
                    <div class="card-body">

                        <form class="d-flex mb-3" id="form_tim_kiem">
                            <div class="d-flex flex-column" style="width: 400px">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="ten_de_tai">Tên đề tài:</label>
                                    <input type="text" name="ten_de_tai" class="form-control ms-2 shadow-none"
                                        placeholder="Tên đề tài" style="width: 240px">
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <label for="giang_vien">Giảng viên hướng dẫn:</label>
                                    <select class="form-select ms-2 shadow-none" name="giang_vien" style="width: 240px">
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
                            </div>
                            <div class="d-flex flex-column ms-3" style="width: 400px">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="sinh_vien">Sinh viên thực hiện:</label>
                                    <input type="text" name="sinh_vien" class="form-control ms-2 shadow-none"
                                        placeholder="Tên sinh viên" style="width: 240px">
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button id="clear" class="btn btn-secondary me-2">Tạo lại</button>
                                    <button id="timKiem" class="btn btn-primary" type="submit">Tìm kiếm</button>
                                </div>
                            </div>

                        </form>
                        @include('admin.phanconghuongdan.pageAjax', ['deTais' => $deTais])
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
                    url: "{{ route('phan_cong_huong_dan.page_ajax') }}",
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
