@extends('layouts.app')
@section('title', 'Hủy đề tài sinh viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Hủy đề tài sinh viên</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTaiSV->ten_de_tai }}</h3>

                        @if ($deTaiSV->sinhViens->count() == 1)
                            @php $sinhVien = $deTaiSV->sinhViens->first(); @endphp
                            <p><strong>Sinh viên ra đề tài:</strong> {{ $sinhVien->ho_ten }} - MSSV: {{ $sinhVien->mssv }}
                            @else
                            <p><strong>Sinh viên ra đề tài:</strong></p>
                            <ul>
                                @foreach ($deTaiSV->sinhViens as $sinhVien)
                                    <li>{{ $sinhVien->ho_ten }} - MSSV: {{ $sinhVien->mssv }}
                                @endforeach
                            </ul>
                        @endif

                        <p><strong>Ngày đề xuất:</strong> {{ $deTaiSV->ngayDeXuat->ngay_de_xuat }}</p>
                        <p><strong>Lĩnh vực:</strong> {{ $deTaiSV->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {!! $deTaiSV->mo_ta !!}</p>

                        <form id="form_huy">
                            <input type="hidden" name="DeTai[ma_de_tai]" value="{{ $deTaiSV->ma_de_tai }}">
                            <div class="text-center">
                                <a href="{{ route('de_tai_sinh_vien.danh_sach') }}" class="btn btn-secondary btn-lg">Quay
                                    lại</a>
                                <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal">Xác nhận hủy</button>
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
                                        <div class="modal-body">Bạn có chắc chắn muốn hủy đề tài này không?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary" id="huy">Xác
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
            $("#huy").click(function(event) {
                event.preventDefault();

                let form = $("#form_huy").get(0);
                let formData = new FormData(form);

                $.ajax({
                    url: "{{ route('de_tai_sinh_vien.xac_nhan_huy') }}",
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
                            window.location.href =
                                "{{ route('de_tai_sinh_vien.danh_sach') }}";
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
