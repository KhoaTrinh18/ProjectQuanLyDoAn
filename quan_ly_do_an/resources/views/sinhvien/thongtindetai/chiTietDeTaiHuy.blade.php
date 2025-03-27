@extends('layouts.app')
@section('title', 'Chi tiết đề tài đã hủy')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Chi tiết đề tài đã hủy</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTai->ten_de_tai }}</h3>
                        @if ($deTai->giangViens)
                            <p><strong>Giảng viên ra đề tài:</strong>
                                {{ $deTai->giangViens->pluck('ho_ten')->implode(', ') }}
                            </p>
                        @endif
                        <p><strong>Lĩnh vực:</strong> {{ $deTai->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {!! $deTai->mo_ta !!}</p>

                        <form id="form_de_xuat">
                            <input type="hidden" name="ma_de_tai" value="{{ $deTai->ma_de_tai }}">
                            <div class="text-center">
                                <a href="{{ route('thong_tin_de_tai.danh_sach_de_tai_huy') }}"
                                    class="btn btn-secondary btn-lg">Quay lại</a>
                                @if (session('co_de_tai') == 0)
                                    <button type="submit" class="btn btn-primary btn-lg" id="deXuat">Xác nhận đề
                                        xuất</button>
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
            $("#deXuat").click(function(event) {
                event.preventDefault();

                let form = $("#form_de_xuat").get(0);
                let formData = new FormData(form);

                $.ajax({
                    url: "{{ route('thong_tin_de_tai.xac_nhan_de_xuat') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(result) {
                        if (result.success) {
                            alert("Đề xuất thành công!");
                            window.location.href = "{{ route('thong_tin_de_tai.danh_sach_de_tai_huy') }}";
                        }
                    },
                    error: function(xhr) {
                        alert("Đề xuất thất bại! Vui lòng thử lại.");
                    },
                });
            });
        });
    </script>
@endsection
