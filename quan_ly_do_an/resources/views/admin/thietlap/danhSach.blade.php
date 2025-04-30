@extends('layouts.app')
@section('title', 'Danh sách thiết lập')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách thiết lập</h2>
                        <div>
                            <a href="{{ route('thiet_lap.them') }}" class="btn btn-success btn-lg">Thêm mới</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover">
                            <thead style="background: #222e3c;">
                                <tr>
                                    <th scope="col" class="text-white">#</th>
                                    <th scope="col" class="text-white">Năm học</th>
                                    <th scope="col" class="text-white">Thời gian đăng ký</th>
                                    <th scope="col" class="text-white">Thời gian thực hiện</th>
                                    <th scope="col" class="text-white">Trạng thái</th>
                                    <th scope="col" class="text-white"></th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach ($thietLaps as $key => $thietLap)
                                    <tr>
                                        <td scope="row">
                                            {{ $key + 1 }}</td>
                                        <td> {{ $thietLap->nam_hoc }} </td>
                                        <td>Từ {{ Carbon\Carbon::parse($thietLap->ngay_dang_ky)->format('d-m-Y') }} đến
                                            {{ Carbon\Carbon::parse($thietLap->ngay_ket_thuc_dang_ky)->format('d-m-Y') }}
                                        </td>
                                        <td>Từ {{ Carbon\Carbon::parse($thietLap->ngay_thuc_hien)->format('d-m-Y') }} đến
                                            {{ Carbon\Carbon::parse($thietLap->ngay_ket_thuc_thuc_hien)->format('d-m-Y') }}
                                        </td>
                                        <td>
                                            @if ($thietLap->trang_thai == 1)
                                                <span class="text-warning">Đang hoạt động</span>
                                            @else
                                                <span class="text-success">Đã hoàn thành</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($thietLap->trang_thai == 1)
                                                <a href="{{ route('thiet_lap.sua', ['ma_thiet_lap' => $thietLap->ma_thiet_lap]) }}"
                                                    class="btn btn-primary btn-sm">Sửa</a>
                                            @else
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-huy-{{ $thietLap->ma_thiet_lap }}">
                                                    Hủy
                                                </button>

                                                <div class="modal fade" id="modal-huy-{{ $thietLap->ma_thiet_lap }}"
                                                    tabindex="-1" aria-labelledby="modalLabel-{{ $thietLap->ma_thiet_lap }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content rounded-4 shadow-sm border-0">
                                                            <div class="modal-header bg-light border-bottom-0">
                                                                <h5 class="modal-title fw-semibold text-primary"
                                                                    id="modalLabel-{{ $thietLap->ma_thiet_lap }}">
                                                                    Xác nhận hủy thiết lập</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body fs-5 text-secondary">
                                                                Bạn có chắc muốn hủy thiết lập năm
                                                                <strong>{{ $thietLap->nam_hoc }}</strong>?
                                                            </div>
                                                            <div class="modal-footer bg-light border-top-0">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Hủy</button>
                                                                <form class="form-huy" data-ma="{{ $thietLap->ma_thiet_lap }}">
                                                                    <input type="hidden" name="ma_thiet_lap"
                                                                        value="{{ $thietLap->ma_thiet_lap }}">
                                                                    <button type="submit" class="huy btn btn-primary">Xác
                                                                        nhận</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($thietLaps->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">Không có thiết lập</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".huy").click(function(event) {
                event.preventDefault();

                let form = $(this).closest(".form-huy").get(0);
                let formData = new FormData(form);

                $.ajax({
                    url: "{{ route('thiet_lap.huy') }}",
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
                                location.reload();
                            });
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
