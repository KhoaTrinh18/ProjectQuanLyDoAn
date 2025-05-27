@extends('layouts.app')
@section('title', 'Nộp báo cáo')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Nộp báo cáo</h2>
                    </div>
                    <div class="card-body">
                        <form id="form_them">
                            <div class="d-flex mb-2">
                                <label for="file"
                                    class="p-2 d-flex align-items-center justify-content-center text-white rounded bg-secondary"
                                    style="width: 250px">
                                    Báo cáo đề tài (PDF, Word)
                                </label>
                                <div class="ms-2 w-100">
                                    <input type="file" class="form-control form-control-lg shadow-none" name="file"
                                        style="width: 280px">
                                    <span class="error-message text-danger d-none mt-2 error-file"></span>
                                    @if (!empty($tenFileCu))
                                        <small class="text-muted mt-1 d-block">
                                            File báo cáo hiện tại:
                                            <a href="{{ route('thong_tin_de_tai.tai_bao_cao') }}"
                                                download="{{ basename($tenFileCu) }}" title="Tải file báo cáo">{{ basename($tenFileCu) }}</a>
                                        </small>
                                    @else
                                        <small class="text-muted mt-1 d-block">Chưa có file báo cáo nào được nộp.</small>
                                    @endif
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-success btn-lg mt-2" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal">
                                    Xác nhận nộp
                                </button>
                            </div>
                            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 shadow-sm border-0">
                                        <div class="modal-header bg-light border-bottom-0">
                                            <h5 class="modal-title fw-semibold text-primary" id="confirmModalLabel">Xác
                                                nhận
                                                nộp báo cáo</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body text-center fs-5 text-secondary">
                                            Bạn có chắc chắn muốn nộp file báo cáo này ?
                                        </div>
                                        <div class="modal-footer bg-light border-top-0">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary" id="them">Xác
                                                nhận</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h5 class="text-center mt-3" style="font-weight: bold"><i>Nộp báo cáo trước thời gian kết thúc thực hiện đề tài!</i>
                            </h5>
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
            $("#them").click(function(event) {
                event.preventDefault();

                let form = $("#form_them").get(0);
                let formData = new FormData(form);

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");

                $.ajax({
                    url: "{{ route('thong_tin_de_tai.xac_nhan_nop') }}",
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
                                text: 'Nộp báo cáo thành công!',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            $('#confirmModal').modal('hide');
                            $.each(result.errors, function(field, messages) {
                                let inputField = $("[name='" + field + "'");
                                inputField.addClass("is-invalid");
                                $('.error-' + field).text(messages[0])
                                    .removeClass(
                                        "d-none").addClass("d-block");
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất bại!',
                            text: 'Nộp báo cáo thất bại! Vui lòng thử lại',
                            confirmButtonText: 'OK',
                            timer: 1000,
                            showConfirmButton: false
                        })
                    }
                });
            });

            const deadline = new Date("{{ $ngayHetHan }}");

            const interval = setInterval(() => {
                const now = new Date();
                if (now >= deadline) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Hết thời gian!',
                        text: 'Bạn đã hết thời gian nộp báo cáo.',
                        confirmButtonText: 'OK',
                        showConfirmButton: true
                    }).then(() => {
                        window.location.href =
                            "{{ route('thong_tin_de_tai.thong_tin') }}";
                    });
                    clearInterval(interval);
                }
            }, 10);
        });
    </script>
@endsection
