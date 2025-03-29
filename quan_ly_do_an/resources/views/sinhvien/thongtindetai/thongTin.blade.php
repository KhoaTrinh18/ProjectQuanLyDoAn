@extends('layouts.app')
@section('title', 'Thông tin đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    @if (session('co_de_tai') == 0)
                        <div class="card-header d-flex justify-content-center align-items-center flex-column">
                            <h2 style="font-weight: bold"><i>Bạn chưa có đề tài</i></h2>
                            <h5 style="font-weight: bold"><i>(Vui lòng đề xuất hoặc đăng ký!)</i></h5>
                        </div>
                    @else
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h2 style="font-weight: bold">Thông tin đề tài</h2>
                        </div>
                        <div class="card-body" style="font-size: 16px">
                            <p><strong>Đề tài: </strong>{{ $deTai->ten_de_tai }} (<a href="{{ route('thong_tin_de_tai.chi_tiet')}}">Chi tiết</a>)</p>
                            <p><strong>Sinh viên thực hiện: </strong>{{ implode(', ', $sinhViens->pluck('ho_ten')->toArray()) }}</p>
                            <p><strong>Trạng thái: </strong>
                                @if ($deTai->trang_thai == 1)
                                    <span class="text-warning">Đang xử lý</span>
                                @elseif($deTai->trang_thai == 2)
                                    <span class="text-success">Đã duyệt</span>
                                @elseif($deTai->trang_thai == 3)
                                    <span class="text-danger">Không được duyệt</span>
                                @endif
                            </p>
                            @if ($deTai->trang_thai != 1 && $deTai->trang_thai != null)
                                <p><strong>Điểm demo: </strong>{{ $deTai->diem_demo ? $deTai->diem_demo : 'chưa có' }}</p>
                                <p><strong>Điểm báo cáo: </strong>{{ $deTai->diem_demo ? $deTai->diem_bao_cao : 'chưa có' }}
                                </p>
                                <p><strong>Điểm bảo vệ: </strong></p>
                                <p><strong>Điểm giảng viên hướng dẫn: </strong></p>
                            @else
                                <form id="form_huy">
                                    <input type="hidden" name="ma_de_tai" value="{{ $deTai->ma_de_tai }}">
                                    <div class="text-center">
                                        @if ($deTai->trang_thai == 1)
                                            <button type="submit" class="btn btn-danger btn-lg"
                                                id="huy">Hủy</button>
                                        @endif
                                    </div>
                                </form>
                            @endif
                        </div>
                    @endif
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
                    url: "{{ route('thong_tin_de_tai.huy') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(result) {
                        if (result.success) {
                            alert("Hủy thành công!");
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert("Hủy thất bại! Vui lòng thử lại.");
                    },
                });
            });
        });
    </script>
@endsection
