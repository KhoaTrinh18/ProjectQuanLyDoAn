@extends('layouts.app')
@section('title', 'Danh sách học vị')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách học vị</h2>
                        <div>
                            <a href="{{ route('hoc_vi.them') }}" class="btn btn-success btn-lg">Thêm mới</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover" style="font-size: 13px">
                            <thead style="background: #222e3c;">
                                <tr>
                                    <th scope="col" class="text-white">#</th>
                                    <th scope="col" class="text-white">Tên học vị</th>
                                    <th scope="col" class="text-white">Số lượng đề tài hướng dẫn</th>
                                    <th scope="col" class="text-white"></th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach ($hocVis as $key => $hocVi)
                                    <tr>
                                        <td scope="row"> {{ $key + 1 }} </td>
                                        <td> {{ $hocVi->ten_hoc_vi }} </td>
                                        <td> {{ $hocVi->sl_de_tai_huong_dan }} </td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-huy-{{ $hocVi->ma_hoc_vi }}">
                                                Hủy
                                            </button>

                                            <div class="modal fade" id="modal-huy-{{ $hocVi->ma_hoc_vi }}" tabindex="-1"
                                                aria-labelledby="modalLabel-{{ $hocVi->ma_hoc_vi }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content rounded-4 shadow-sm border-0">
                                                        <div class="modal-header bg-light border-bottom-0">
                                                            <h5 class="modal-title fw-semibold text-primary"
                                                                id="modalLabel-{{ $hocVi->ma_hoc_vi }}">
                                                                Xác nhận hủy học vị</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body fs-5 text-secondary">
                                                            Bạn có chắc muốn hủy học vị
                                                            <strong>{{ $hocVi->ten_hoc_vi }}</strong>?
                                                        </div>
                                                        <div class="modal-footer bg-light border-top-0">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Hủy</button>
                                                            <form class="form-huy" data-ma="{{ $hocVi->ma_hoc_vi }}">
                                                                <input type="hidden" name="ma_hoc_vi"
                                                                    value="{{ $hocVi->ma_hoc_vi }}">
                                                                <button type="submit" class="huy btn btn-primary">Xác
                                                                    nhận</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{ route('hoc_vi.sua', ['ma_hoc_vi' => $hocVi->ma_hoc_vi]) }}"
                                                class="btn btn-primary btn-sm">Sửa</a>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($hocVis->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center">Không có học vị</td>
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
                    url: "{{ route('hoc_vi.huy') }}",
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
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Thất bại!',
                                text: 'Hủy thất bại! Vì học vị này đã được gán cho giảng viên',
                                confirmButtonText: 'OK',
                                timer: 2000,
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
