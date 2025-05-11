@extends('layouts.app')
@section('title', 'Danh sách bộ môn')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách bộ môn</h2>
                        <div>
                            <a href="{{ route('bo_mon.them') }}" class="btn btn-success btn-lg">Thêm mới</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover" style="font-size: 13px">
                            <thead style="background: #222e3c;">
                                <tr>
                                    <th scope="col" class="text-white">#</th>
                                    <th scope="col" class="text-white">Tên bộ môn</th>
                                    <th scope="col" class="text-white"></th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach ($boMons as $key => $boMon)
                                    <tr>
                                        <td scope="row"> {{ $key + 1 }} </td>
                                        <td> {{ $boMon->ten_bo_mon }} </td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-huy-{{ $boMon->ma_bo_mon }}">
                                                Hủy
                                            </button>

                                            <div class="modal fade" id="modal-huy-{{ $boMon->ma_bo_mon }}" tabindex="-1"
                                                aria-labelledby="modalLabel-{{ $boMon->ma_bo_mon }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content rounded-4 shadow-sm border-0">
                                                        <div class="modal-header bg-light border-bottom-0">
                                                            <h5 class="modal-title fw-semibold text-primary"
                                                                id="modalLabel-{{ $boMon->ma_bo_mon }}">
                                                                Xác nhận hủy bộ môn</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body fs-5 text-secondary">
                                                            Bạn có chắc muốn hủy bộ môn
                                                            <strong>{{ $boMon->ten_bo_mon }}</strong>?
                                                        </div>
                                                        <div class="modal-footer bg-light border-top-0">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Hủy</button>
                                                            <form class="form-huy" data-ma="{{ $boMon->ma_bo_mon }}">
                                                                <input type="hidden" name="ma_bo_mon"
                                                                    value="{{ $boMon->ma_bo_mon }}">
                                                                <button type="submit" class="huy btn btn-primary">Xác
                                                                    nhận</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{ route('bo_mon.sua', ['ma_bo_mon' => $boMon->ma_bo_mon]) }}"
                                                class="btn btn-primary btn-sm">Sửa</a>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($boMons->isEmpty())
                                    <tr>
                                        <td colspan="3" class="text-center">Không có bộ môn</td>
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
                    url: "{{ route('bo_mon.huy') }}",
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
                        } else if (result.error == 'giang_vien') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Thất bại!',
                                text: 'Hủy thất bại! Vì bộ môn này đã được gán cho giảng viên',
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
                                text: 'Hủy thất bại! Vì bộ môn này đã được gán cho hội đồng',
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
