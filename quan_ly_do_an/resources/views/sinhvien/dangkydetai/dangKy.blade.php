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
                            <input type="hidden" name="ma_de_tai" value="{{ $deTai->ma_de_tai }}">
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
