@extends('layouts.app')
@section('title', 'Đăng ký đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Đăng ký đề tài</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">

                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTai->ten_de_tai }}</h3>
                        <p><strong>Giảng viên ra đề tài:</strong>
                            {{ $deTai->giangViens->pluck('ho_ten')->implode(', ') }}
                        </p>
                        <p><strong>Lĩnh vực:</strong> {{ $deTai->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {!! $deTai->mo_ta !!}</p>

                        <form id="form_dang_ky">
                            <input type="hidden" name="DeTai[ma_de_tai]" value="{{ $deTai->ma_de_tai }}">
                            @if ($deTai->da_dang_ky == 0 && session('co_de_tai') == 0)
                            <div>
                                <label for="DeTai[mssv][]"><strong>Sinh viên làm chung (nếu có):</strong></label>
                                <div class="w-100">
                                    <div class="d-flex mt-2 align-items-center">
                                        <input type="text" class="form-control form-control-lg shadow-none me-2"
                                            placeholder="Nhập MSSV" name="DeTai[mssv][0]" style="width: 150px">
                                        <span class="error-message text-danger d-none error-mssv-0"></span>
                                    </div>
                                    <div class="d-flex mt-2 align-items-center">
                                        <input type="text" class="form-control form-control-lg shadow-none me-2"
                                            placeholder="Nhập MSSV" name="DeTai[mssv][1]" style="width: 150px">
                                        <span class="error-message text-danger d-none error-mssv-1 "></span>
                                    </div>
                                    <div class="d-flex mt-2 align-items-center">
                                        <input type="text" class="form-control form-control-lg shadow-none me-2"
                                            placeholder="Nhập MSSV" name="DeTai[mssv][2]" style="width: 150px">
                                        <span class="error-message text-danger d-none error-mssv-2"></span>
                                    </div>
                                    <div class="d-flex mt-2 align-items-center">
                                        <input type="text" class="form-control form-control-lg shadow-none me-2"
                                            placeholder="Nhập MSSV" name="DeTai[mssv][3]" style="width: 150px">
                                        <span class="error-message text-danger d-none error-mssv-3"></span>
                                    </div>
                                    <div class="d-flex mt-2 align-items-center">
                                        <input type="text" class="form-control form-control-lg shadow-none me-2"
                                            placeholder="Nhập MSSV" name="DeTai[mssv][4]" style="width: 150px">
                                        <span class="error-message text-danger d-none error-mssv-4"></span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="text-center">
                                <a href="{{ route('dang_ky_de_tai.index') }}" class="btn btn-secondary btn-lg">Quay lại</a>
                                @if ($deTai->da_dang_ky == 0 && session('co_de_tai') == 0)
                                    <button type="submit" class="btn btn-primary btn-lg" id="dangKy">Xác nhận đăng
                                        ký</button>
                                @endif
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
            $("#dangKy").click(function(event) {
                event.preventDefault();

                let form = $("#form_dang_ky").get(0);
                let formData = new FormData(form);

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");

                $.ajax({
                    url: "{{ route('dang_ky_de_tai.xac_nhan_dang_ky') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(result) {
                        if (result.success) {
                            alert("Đăng ký thành công!");
                            window.location.href = "{{ route('dang_ky_de_tai.index') }}";
                        } else {
                            $.each(result.errors, function(field, messages) {
                                $('.error-' + field).text(messages[0]).removeClass(
                                    "d-none").addClass("d-block");
                                if (field.startsWith("mssv.")) {
                                    let index = field.split('.')[1];
                                    $(".error-mssv-" + index).text(messages[0])
                                        .removeClass("d-none").show();
                                    $("[name='DeTai[mssv][" + index + "]']").addClass(
                                        "is-invalid");
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        alert("Đăng ký thất bại! Vui lòng thử lại.");
                    },
                });
            });
        });
    </script>
@endsection
