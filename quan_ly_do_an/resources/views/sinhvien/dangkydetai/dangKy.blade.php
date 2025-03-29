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
                        @if ($deTai->so_luong_sv < 1)
                            <p><strong>Sinh viên thực hiện:
                                </strong>chưa có</p>
                        @else
                            @if ($deTai->so_luong_sv == 1)
                                <p><strong>Sinh viên đã đăng ký:
                                    </strong>{{ implode(', ', $sinhViens->map(fn($sv) => "{$sv->ho_ten} ({$sv->mssv})")->toArray()) }}
                                </p>
                            @else
                                <p><strong>Sinh viên đã đăng ký:</strong></p>
                                <ul>
                                    @foreach ($sinhViens as $sv)
                                        <li>{{ $sv->ho_ten }} ({{ $sv->mssv }})</li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif
                        <form id="form_dang_ky">
                            <input type="hidden" name="DeTai[ma_de_tai]" value="{{ $deTai->ma_de_tai }}">
                            <div class="text-center">
                                <a href="{{ route('dang_ky_de_tai.index') }}" class="btn btn-secondary btn-lg">Quay
                                    lại</a>
                                @if ($deTai->so_luong_sv < $deTai->so_luong_sv_toi_da && session('co_de_tai') == 0)
                                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                        data-bs-target="#confirmModal">Xác nhận đăng
                                        ký</button>
                                @endif
                            </div>
                            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmModalLabel">Xác nhận đăng ký</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Khi bạn đã đăng ký 1 đề tài thì không thể đăng ký đề tài có trong danh sách
                                            đề tài hoặc tự đề xuất đề tài. Nếu bạn muốn hủy thì phải liên hệ với giảng viên
                                            ra đề tài để hủy. Bạn có chắc chắn muốn đăng ký đề tài này không?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary" id="dangKy">Xác
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
