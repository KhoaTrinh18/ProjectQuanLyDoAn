@extends('layouts.app')
@section('title', 'Danh sách đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="modal fade" id="modalDoiMatKhau" tabindex="-1" aria-labelledby="modalLabel">
                    <div class="modal-dialog">
                        <form id="form_doi_mk">
                            <div class="modal-content">
                                <div class="modal-header bg-secondary">
                                    <h5 class="modal-title text-white" id="modalLabel">Đổi mật khẩu</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="mat_khau_cu" class="form-label">Mật khẩu cũ</label>
                                        <input type="password" name="MatKhau[mk_cu]" class="form-control shadow-none">
                                        <span class="error-message text-danger d-none mt-2 error-mk_cu"></span>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mat_khau_moi" class="form-label">Mật khẩu mới</label>
                                        <input type="password" name="MatKhau[mk_moi]" class="form-control shadow-none">
                                        <span class="error-message text-danger d-none mt-2 error-mk_moi"></span>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mat_khau_moi_confirmation" class="form-label">Xác nhận mật khẩu
                                            mới</label>
                                        <input type="password" name="MatKhau[mk_xac_nhan]" class="form-control shadow-none">
                                        <span class="error-message text-danger d-none mt-2 error-mk_xac_nhan"></span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success" id="luu">Lưu mật khẩu</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
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
                                        <option value="" selected disabled hidden>Chọn lĩnh vực</option>
                                        <option value="">Tất cả</option>
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
                                    <label for="trang_thai">Trạng thái:</label>
                                    <select class="form-select ms-2 w-75 shadow-none" name="trang_thai">
                                        <option value="" selected disabled hidden>Chọn trạng thái</option>
                                        <option value="">Tất cả</option>
                                        <option value="1">Có người đăng ký</option>
                                        <option value="0">Chưa có người đăng ký</option>
                                    </select>
                                </div>
                            </div>
                            <div class="ms-3">
                                <button id="clear" class="btn btn-secondary">Tạo lại</button>
                                <button id="timKiem" class="btn btn-primary" type="submit">Tìm kiếm</button>
                            </div>
                        </form>
                        @include('sinhvien.dangkydetai.pageAjax', ['deTais' => $deTais])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            const daDangNhap = @json($taiKhoan->da_dang_nhap);

            if (daDangNhap == 0) {
                $('#modalDoiMatKhau').modal({
                    backdrop: 'static',
                    keyboard: false,
                }).modal('show');
            }

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

            const deadline = new Date("{{ $ngayHetHan }}");

            const interval = setInterval(() => {
                const now = new Date();
                if (now >= deadline) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Hết thời gian!',
                        text: 'Bạn đã hết thời gian đăng ký.',
                        confirmButtonText: 'OK',
                        showConfirmButton: true
                    }).then(() => {
                        window.location.href =
                            "{{ route('thong_tin_de_tai.thong_tin') }}";
                    });
                    clearInterval(interval);
                }
            }, 10);
            $("#luu").click(function(event) {
                event.preventDefault();

                let form = $("#form_doi_mk").get(0);
                let formData = new FormData(form);

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");

                $.ajax({
                    url: "{{ route('doi_mat_khau') }}",
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
                                text: 'Đổi mật khẩu thành công!',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('dang_ky_de_tai.danh_sach') }}";
                            });
                        } else {
                            $.each(result.errors, function(field, messages) {
                                let inputField = $("[name='MatKhau[" + field + "]']");
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
                            text: 'Đổi mật khẩu thất bại! Vui lòng thử lại',
                            confirmButtonText: 'OK',
                            timer: 1000,
                            showConfirmButton: false
                        })
                    }
                });
            });
        });
    </script>
@endsection
