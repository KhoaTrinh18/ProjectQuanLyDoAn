@extends('layouts.app')
@section('title', 'Hủy hội đồng')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Hủy hội đồng</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $hoiDong->ten_hoi_dong }}</h3>
                        <p><strong>Chuyên nghành:</strong> {{ $hoiDong->chuyenNganh->ten_bo_mon }}</p>
                        <p><strong>Phòng:</strong> {{ $hoiDong->phong }}</p>
                        <p><strong>Ngày tổ chức:</strong> {{ \Carbon\Carbon::parse($hoiDong->ngay)->format('H:i d-m-Y') }}</p>
                        <p><strong>Năm học:</strong> {{ $hoiDong->nam_hoc }}</p>

                        @php $chuTich = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Chủ tịch')->first(); @endphp
                        <p><strong>Chủ tịch:</strong> {{ $chuTich->ho_ten }} - Email:
                            {{ $chuTich->email }} - Số điện thoại: {{ $chuTich->so_dien_thoai }}</p>

                        @php $thuKy = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Thư ký')->first(); @endphp
                        <p><strong>Thư ký:</strong> {{ $thuKy->ho_ten }} - Email:
                            {{ $thuKy->email }} - Số điện thoại: {{ $thuKy->so_dien_thoai }}</p>

                        @if ($hoiDong->giangViens()->wherePivot('chuc_vu', 'Ủy viên')->count() == 1)
                            @php $uyVien = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Ủy viên')->first(); @endphp
                            <p><strong>Ủy viên:</strong> {{ $uyVien->ho_ten }} - Email:
                                {{ $uyVien->email }} - Số điện thoại: {{ $uyVien->so_dien_thoai }}
                            @else
                                @php $uyViens = $hoiDong->giangViens()->wherePivot('chuc_vu', 'Ủy viên')->get(); @endphp
                            <p><strong>Ủy viên:</strong></p>
                            <ul>
                                @foreach ($uyViens as $uyVien)
                                    <li>{{ $uyVien->ho_ten }} - Email: {{ $uyVien->email }} - Số điện thoại:
                                        {{ $uyVien->so_dien_thoai }}
                                @endforeach
                            </ul>
                        @endif

                        <form id="form_huy">
                            <input type="hidden" name="ma_hoi_dong" value="{{ $hoiDong->ma_hoi_dong }}">
                            <div class="text-center">
                                <a href="{{ route('hoi_dong.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
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
                                            Bạn có chắc chắn muốn hủy hội đồng này không?
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
                    url: "{{ route('hoi_dong.xac_nhan_huy') }}",
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
                                    "{{ route('hoi_dong.danh_sach') }}";
                            });
                        } else {
                            Swal.fire({
                            icon: 'error',
                            title: 'Thất bại!',
                            text: 'Hủy thất bại! Vì hội đồng này đã được phân công',
                            confirmButtonText: 'OK',
                            timer: 2000,
                            showConfirmButton: false
                        })
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