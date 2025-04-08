@extends('layouts.app')
@section('title', 'Danh sách đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    @if ($hetHan == 1)
                        <div class="card-header d-flex justify-content-center align-items-center flex-column">
                            <h2 style="font-weight: bold"><i>Đã hết hạn thời gian đăng ký!</i></h2>
                        </div>
                    @else
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h2 style="font-weight: bold">Danh sách đề tài</h2>
                        </div>
                        <div class="card-body">
                            <form class="d-flex mb-3" id="form_tim_kiem">
                                <div class="d-flex flex-column" style="width: 350px">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label for="ten_de_tai">Tên đề tài:</label>
                                        <input type="text" name="ten_de_tai" class="form-control ms-2 w-75 shadow-none"
                                            placeholder="Tên đề tài">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mt-2">
                                        <label for="ma_linh_vuc">Lĩnh vực:</label>
                                        <select class="form-select ms-2 w-75 shadow-none" name="ma_linh_vuc">
                                            <option value="" selected>Chọn lĩnh vực</option>
                                            @foreach ($linhVucs as $linhVuc)
                                                <option value="{{ $linhVuc->ma_linh_vuc }}">{{ $linhVuc->ten_linh_vuc }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex flex-column ms-3" style="width: 350px">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label for="giang_vien">Giảng viên:</label>
                                        <input type="text" name="giang_vien" class="form-control ms-2 w-75 shadow-none"
                                            placeholder="Giảng viên">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mt-2">
                                        <label for="trang_thai">Trạng thái:</label>
                                        <select class="form-select ms-2 w-75 shadow-none" name="trang_thai">
                                            <option value="" selected>Chọn trạng thái</option>
                                            <option value="1">Có người đăng ký</option>
                                            <option value="0">Chưa có người đăng ký</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <button id="clear" class="btn btn-secondary">Clear</button>
                                    <button id="timKiem" class="btn btn-primary" type="submit">Tìm kiếm</button>
                                </div>
                            </form>
                            @include('sinhvien.dangkydetai.pageAjax', ['deTais' => $deTais])
                        </div>
                    @endif
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
                    url: "{{ route('dang_ky_de_tai.page_ajax') }}",
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
