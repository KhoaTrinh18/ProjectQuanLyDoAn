<div class="modal-header bg-secondary">
    <h5 class="mb-0 modal-title text-white">Thông tin cá nhân</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    @php
        $maTaiKhoan = session()->get('ma_tai_khoan');
        $giangVien = \App\Models\GiangVien::where('ma_tk', $maTaiKhoan)->first();
    @endphp

    <div class="mb-2 d-flex">
        <div class="fw-bold me-2" style="width: 130px;">Tên tài khoản:</div>
        <div>{{ $giangVien->taiKhoan->ten_tk }}</div>
    </div>
    <div class="mb-2 d-flex">
        <div class="fw-bold me-2" style="width: 130px;">Họ tên:</div>
        <div>{{ $giangVien->ho_ten }}</div>
    </div>
    <div class="mb-2 d-flex">
        <div class="fw-bold me-2" style="width: 130px;">Email:</div>
        <div>{{ $giangVien->email }}</div>
    </div>
    <div class="mb-2 d-flex">
        <div class="fw-bold me-2" style="width: 130px;">Số điện thoại:</div>
        <div>{{ $giangVien->so_dien_thoai }}</div>
    </div>
    <div class="mb-2 d-flex">
        <div class="fw-bold me-2" style="width: 130px;">Bộ môn:</div>
        <div>{{ $giangVien->boMon->ten_bo_mon }}</div>
    </div>
    <div class="mb-2 d-flex">
        <div class="fw-bold me-2" style="width: 130px;">Học vị:</div>
        <div>{{ $giangVien->hocVi->ten_hoc_vi }}</div>
    </div>
    <div class="mb-2 d-flex">
        <div class="fw-bold me-2" style="width: 130px;">Số lượng sinh viên:</div>
        <div>{{ $giangVien->hocVi->sl_sinh_vien_huong_dan }}</div>
    </div>
</div>

<div class="modal-header bg-secondary rounded-0">
    <h5 class="modal-title text-white" id="modalLabel">Đổi mật khẩu</h5>
</div>
<div class="modal-body">
    <form id="form_doi_mk">
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
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success" id="luu">Lưu mật khẩu</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $("#luu").click(function(event) {
            event.preventDefault();

            let form = $("#form_doi_mk").get(0);
            let formData = new FormData(form);

            $(".error-message").text('').removeClass(
                "d-block").addClass("d-none");
            $(".is-invalid").removeClass("is-invalid");

            $.ajax({
                url: "{{ route('doi_mat_khau_gv') }}",
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
                            window.location.reload();
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
