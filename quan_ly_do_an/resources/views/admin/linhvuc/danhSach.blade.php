@extends('layouts.app')
@section('title', 'Danh sách lĩnh vục')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách lĩnh vực</h2>
                        <div>
                            <a href="{{ route('linh_vuc.them') }}" class="btn btn-success btn-lg">Thêm mới</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover" style="font-size: 13px">
                            <thead style="background: #222e3c;">
                                <tr>
                                    <th scope="col" class="text-white">#</th>
                                    <th scope="col" class="text-white">Tên lĩnh vực</th>
                                    <th scope="col" class="text-white"></th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach ($linhVucs as $key => $linhVuc)
                                    <tr>
                                        <td scope="row"> {{ $key + 1 }} </td>
                                        <td> {{ $linhVuc->ten_linh_vuc }} </td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-huy-{{ $linhVuc->ma_linh_vuc }}">
                                                Hủy
                                            </button>

                                            <div class="modal fade" id="modal-huy-{{ $linhVuc->ma_linh_vuc }}" tabindex="-1"
                                                aria-labelledby="modalLabel-{{ $linhVuc->ma_linh_vuc }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content rounded-4 shadow-sm border-0">
                                                        <div class="modal-header bg-light border-bottom-0">
                                                            <h5 class="modal-title fw-semibold text-primary"
                                                                id="modalLabel-{{ $linhVuc->ma_linh_vuc }}">
                                                                Xác nhận hủy lĩnh vực</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body fs-5 text-secondary">
                                                            Bạn có chắc muốn hủy lĩnh vực
                                                            <strong>{{ $linhVuc->ten_linh_vuc }}</strong>?
                                                        </div>
                                                        <div class="modal-footer bg-light border-top-0">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Hủy</button>
                                                            <form class="form-huy" data-ma="{{ $linhVuc->ma_linh_vuc }}">
                                                                <input type="hidden" name="ma_linh_vuc"
                                                                    value="{{ $linhVuc->ma_linh_vuc }}">
                                                                <button type="submit" class="huy btn btn-primary">Xác
                                                                    nhận</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{ route('linh_vuc.sua', ['ma_linh_vuc' => $linhVuc->ma_linh_vuc]) }}"
                                                class="btn btn-primary btn-sm">Sửa</a>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($linhVucs->isEmpty())
                                    <tr>
                                        <td colspan="3" class="text-center">Không có lĩnh vực</td>
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
                    url: "{{ route('linh_vuc.huy') }}",
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
                        } else if (result.error == 'de_tai_gv') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Thất bại!',
                                text: 'Hủy thất bại! Vì lĩnh vực này đã được gán cho đề tài giảng viên',
                                confirmButtonText: 'OK',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Thất bại!',
                                text: 'Hủy thất bại! Vì lĩnh vực này đã được gán cho đề tài sinh viên',
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
