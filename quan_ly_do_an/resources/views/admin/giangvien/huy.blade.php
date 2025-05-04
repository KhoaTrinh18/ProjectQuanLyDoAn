@extends('layouts.app')
@section('title', 'Hủy giảng viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Hủy giảng viên</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <p><strong>Tên giảng viên:</strong> {{ $giangVien->ho_ten }}</p>
                        <p><strong>Email:</strong> {{ $giangVien->email }}</p>
                        <p><strong>Số điện thoại:</strong> {{ $giangVien->so_dien_thoai }}</p>
                        <p><strong>Bộ môn:</strong> {{ $giangVien->boMon->ten_bo_mon }}</p>
                        <p><strong>Học vị:</strong> {{ $giangVien->hocVi->ten_hoc_vi }}</p>
                        <p><strong>Tài khoản:</strong> {{ $giangVien->taiKhoan->ten_tk }}</p>
                        <p><strong>Mật khẩu:</strong> {{ $giangVien->taiKhoan->mat_khau }}</p>

                        <form id="form_huy">
                            <input type="hidden" name="ma_gv" value="{{ $giangVien->ma_gv }}">
                            <div class="text-center">
                                <a href="{{ route('giang_vien.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                    lại</a>
                                <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal">Xác nhận hủy</button>
                            </div>
                            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 shadow-sm border-0">
                                        <div class="modal-header bg-light border-bottom-0">
                                            <h5 class="modal-title fw-semibold text-primary" id="confirmModalLabel">Xác nhận
                                                hủy</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body text-center fs-5 text-secondary">
                                            Bạn có chắc chắn muốn hủy giảng viên này không?
                                        </div>
                                        <div class="modal-footer bg-light border-top-0">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary" id="huy">Xác nhận</button>
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
            $("#huy").click(function(event) {
                event.preventDefault();

                let form = $("#form_huy").get(0);
                let formData = new FormData(form);

                $.ajax({
                    url: "{{ route('giang_vien.xac_nhan_huy') }}",
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
                                text: 'Hủy thành công!',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('giang_vien.danh_sach') }}";
                            });
                        } else {
                            if (result.error == 'dua_ra') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Hủy thất bại! Vì giảng viên này đưa ra đề tài',
                                    confirmButtonText: 'OK',
                                    timer: 2000,
                                    showConfirmButton: false
                                })
                            } else if(result.error == 'phan_cong_huong_dan') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Hủy thất bại! Vì giảng viên này đã được phân công hướng dẫn',
                                    confirmButtonText: 'OK',
                                    timer: 2000,
                                    showConfirmButton: false
                                })
                            } else if(result.error == 'phan_cong_phan_bien') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Hủy thất bại! Vì giảng viên này đã được phân công phản biện',
                                    confirmButtonText: 'OK',
                                    timer: 2000,
                                    showConfirmButton: false
                                })
                            } else if(result.error == 'phan_cong_hoi_dong') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Thất bại!',
                                    text: 'Hủy thất bại! Vì giảng viên này đã được phân công hội đồng',
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
                            text: 'Hủy thất bại! Vui lòng thử lại',
                            confirmButtonText: 'OK',
                            timer: 1000,
                            showConfirmButton: false
                        })
                    },
                });
            });
        });
    </script>
@endsection
