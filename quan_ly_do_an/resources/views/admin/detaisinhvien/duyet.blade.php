@extends('layouts.app')
@section('title', 'Duyệt đề tài sinh viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Duyệt đề tài sinh viên</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTaiSV->ten_de_tai }}</h3>

                        @if ($deTaiSV->sinhViens->count() == 1)
                            @php $sinhVien = $deTaiSV->sinhViens->first(); @endphp
                            <p><strong>Sinh viên ra đề tài:</strong> {{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }}) -
                                Email: {{ $sinhVien->email }} - Số điện thoại: {{ $sinhVien->so_dien_thoai }}
                            @else
                            <p><strong>Sinh viên ra đề tài:</strong></p>
                            <ul>
                                @foreach ($deTaiSV->sinhViens as $sinhVien)
                                    <li>{{ $sinhVien->ho_ten }} ({{ $sinhVien->mssv }}) - Email: {{ $sinhVien->email }} - Số
                                        điện thoại: {{ $sinhVien->so_dien_thoai }}
                                @endforeach
                            </ul>
                        @endif

                        <p><strong>Ngày đề xuất:</strong>
                            {{ \Carbon\Carbon::parse($deTaiSV->ngayDeXuat->ngay_de_xuat)->format('d-m-Y') }}</p>
                        @if ($deTaiSV->giangVienDuKiens->count() == 1)
                            @php $giangVien = $deTaiSV->giangVienDuKiens->first(); @endphp
                            <p><strong>Giảng viên hướng dẫn (dự kiến):</strong>
                                {{ $giangVien->ho_ten }} - Email:
                                {{ $giangVien->email }} - Số điện thoại: {{ $giangVien->so_dien_thoai }}</p>
                        @else
                            <p><strong>Giảng viên hướng dẫn (dự kiến):</strong></p>
                            <ul>
                                @foreach ($deTaiSV->giangVienDuKiens as $giangVien)
                                    <li>{{ $giangVien->ho_ten }} - Email: {{ $giangVien->email }} - SĐT:
                                        {{ $giangVien->so_dien_thoai }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <p><strong>Lĩnh vực:</strong> {{ $deTaiSV->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {!! $deTaiSV->mo_ta !!}</p>

                        <form id="form_duyet">
                            <input type="hidden" name="DeTai[ma_de_tai]" value="{{ $deTaiSV->ma_de_tai }}">
                            <div class="text-center">
                                <a href="{{ route('de_tai_sinh_vien.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                    lại</a>
                                @if ($deTaiSV->trang_thai == 3)
                                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                        data-bs-target="#confirmModal">Xác nhận duyệt hoàn toàn</button>
                                @else
                                    <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal"
                                        data-bs-target="#cancelModal">Xác nhận không duyệt</button>
                                    <button type="button" class="btn btn-info btn-lg" data-bs-toggle="modal"
                                        data-bs-target="#editModal">Xác nhận duyệt cần chỉnh sửa</button>
                                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                        data-bs-target="#confirmModal">Xác nhận duyệt</button>
                                @endif
                            </div>
                            <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 shadow-sm border-0">
                                        <div class="modal-header bg-light border-bottom-0">
                                            <h5 class="modal-title fw-semibold text-primary" id="cancelModalLabel">Xác nhận
                                                không duyệt</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body text-center fs-5 text-secondary">
                                            <p>Bạn có chắc chắn không muốn duyệt đề tài này?</p>
                                            <div class="mt-3 text-start">
                                                <label class="form-label fw-semibold">Chọn lý do từ chối:</label>
                                                <select class="form-select" id="lyDoTuChoi" name="lyDoTuChoi">
                                                    <option value="">-- Chọn lý do --</option>
                                                    <option value="Trùng nội dung với đề tài khác">Trùng nội dung với đề tài
                                                        khác</option>
                                                    <option value="Không phù hợp với định hướng nghiên cứu">Không phù hợp
                                                        với định hướng nghiên cứu</option>
                                                    <option value="Thiếu tính thực tiễn">Thiếu tính thực tiễn</option>
                                                    <option value="khac">Lý do khác</option>
                                                </select>

                                                <textarea class="form-control mt-3" id="lyDoKhac" rows="3" placeholder="Nhập lý do cụ thể..."
                                                    style="display: none;"></textarea>
                                                <span class="error-message text-danger d-none mt-2 error-lyDoTuChoi"></span>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light border-top-0">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary" id="khongDuyet">Xác nhận</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 shadow-sm border-0">
                                        <div class="modal-header bg-light border-bottom-0">
                                            <h5 class="modal-title fw-semibold text-primary" id="editModalLabel">Xác nhận
                                                duyệt cần chỉnh sửa</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body text-center fs-5 text-secondary">
                                            <div class="text-start">
                                                <label class="form-label fw-semibold">Nội dung cần chỉnh sửa:</label>
                                                <textarea class="form-control mt-3" name="noiDungSua" rows="3" placeholder="Nhập nội dung cần chỉnh sửa..."></textarea>
                                                <span class="error-message text-danger d-none mt-2 error-noiDungSua"></span>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light border-top-0">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary" id="duyetSua">Xác nhận</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 shadow-sm border-0">
                                        <div class="modal-header bg-light border-bottom-0">
                                            <h5 class="modal-title fw-semibold text-primary" id="confirmModalLabel">Xác
                                                nhận
                                                duyệt</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body fs-5 text-secondary">
                                            @if (!empty($trungLap))
                                                <div>
                                                    <p><strong>Cảnh báo:</strong> Đề tài này có thể trùng với các đề tài
                                                        sau:</p>
                                                    <ul>
                                                        @foreach ($trungLap as $item)
                                                            <li>{{ $item['de_tai'] }} ({{ $item['percent'] }}%)</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <p class="text-center">Bạn có chắc chắn muốn duyệt đề tài này không?</p>
                                        </div>
                                        <div class="modal-footer bg-light border-top-0">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary" id="duyet">Xác
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
            $("#duyet").click(function(event) {
                event.preventDefault();

                let form = $("#form_duyet").get(0);
                let formData = new FormData(form);

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");

                $.ajax({
                    url: "{{ route('de_tai_sinh_vien.xac_nhan_duyet') }}",
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
                                text: 'Duyệt thành công!',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('de_tai_sinh_vien.danh_sach') }}";
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất bại!',
                            text: 'Duyệt thất bại! Vui lòng thử lại',
                            confirmButtonText: 'OK',
                            timer: 1000,
                            showConfirmButton: false
                        })
                    },
                });
            });

            $('#lyDoTuChoi').on('change', function() {
                $('#lyDoKhac').toggle(this.value === 'khac');
            });

            $("#khongDuyet").click(function(event) {
                event.preventDefault();

                let form = $("#form_duyet").get(0);
                let formData = new FormData(form);

                let selectedLyDo = $('#lyDoTuChoi').val();
                let lyDoFinal = (selectedLyDo === 'khac') ? $('#lyDoKhac').val().trim() : selectedLyDo;

                formData.append('lyDoTuChoi', lyDoFinal);

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");

                $.ajax({
                    url: "{{ route('de_tai_sinh_vien.xac_nhan_khong_duyet') }}",
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
                                text: 'Không duyệt thành công!',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('de_tai_sinh_vien.danh_sach') }}";
                            });
                        } else {
                            $.each(result.errors, function(field, messages) {
                                $('.error-' + field).text(messages[0]).removeClass(
                                    "d-none").addClass("d-block");
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất bại!',
                            text: 'Không duyệt thất bại! Vui lòng thử lại',
                            confirmButtonText: 'OK',
                            timer: 1000,
                            showConfirmButton: false
                        })
                    },
                });
            });

            $("#duyetSua").click(function(event) {
                event.preventDefault();

                let form = $("#form_duyet").get(0);
                let formData = new FormData(form);

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");

                $.ajax({
                    url: "{{ route('de_tai_sinh_vien.xac_nhan_duyet_sua') }}",
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
                                text: 'Duyệt nhưng cần chỉnh sửa thành công!',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('de_tai_sinh_vien.danh_sach') }}";
                            });

                        } else {
                            $.each(result.errors, function(field, messages) {
                                $('.error-' + field).text(messages[0]).removeClass(
                                    "d-none").addClass("d-block");
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất bại!',
                            text: 'Không duyệt thất bại! Vui lòng thử lại',
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
