@extends('layouts.app')
@section('title', 'Phân công giảng viên hướng dẫn')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Phân công giảng viên hướng dẫn</h2>
                    </div>
                    <div class="card-body" style="font-size: 16px">
                        <h3 class="text-center mb-4" style="font-weight: bold">{{ $deTai->ten_de_tai }}</h3>

                        @if ($deTai->sinhViens->count() == 1)
                            @php $sinhVien = $deTai->sinhViens->first(); @endphp
                            <p><strong>Sinh viên thực hiện:</strong> {{ $sinhVien->ho_ten }} - MSSV: {{ $sinhVien->mssv }}
                            @else
                            <p><strong>Sinh viên thực hiện:</strong></p>
                            <ul>
                                @foreach ($deTai->sinhViens as $sinhVien)
                                    <li>{{ $sinhVien->ho_ten }} - MSSV: {{ $sinhVien->mssv }}
                                @endforeach
                            </ul>
                        @endif

                        @if ($deTai->giangViens->count() == 0)
                            <p><strong>Giảng viên hướng dẫn:</strong> Chưa có</p>
                        @elseif ($deTai->giangViens->count() == 1)
                            @php $giangVien = $deTai->giangViens->first(); @endphp
                            <p><strong>Giảng viên hướng dẫn:</strong> {{ $giangVien->ho_ten }} - Email:
                                {{ $giangVien->email }} - Số điện thoại: {{ $giangVien->so_dien_thoai }}
                            @else
                            <p><strong>Giảng viên hướng dẫn:</strong></p>
                            <ul>
                                @foreach ($deTai->giangViens as $giangVien)
                                    <li>{{ $giangVien->ho_ten }} - Email: {{ $giangVien->email }} - Số điện thoại:
                                        {{ $giangVien->so_dien_thoai }}
                                @endforeach
                            </ul>
                        @endif

                        @if ($deTai->sinhViens->first()->loai_sv == 'dang_ky')
                            @if ($deTai->giangViens->count() == 1)
                                @php $giangVien = $deTai->giangViens->first(); @endphp
                                <p><strong>Giảng viên ra đề tài:</strong> {{ $giangVien->ho_ten }} - Email:
                                    {{ $giangVien->email }} - Số điện thoại: {{ $giangVien->so_dien_thoai }}
                                @else
                                <p><strong>Giảng viên ra đề tài:</strong></p>
                                <ul>
                                    @foreach ($deTai->giangViens as $giangVien)
                                        <li>{{ $giangVien->ho_ten }} - Email: {{ $giangVien->email }} - Số điện thoại:
                                            {{ $giangVien->so_dien_thoai }}
                                    @endforeach
                                </ul>
                            @endif
                        @endif

                        <p><strong>Lĩnh vực:</strong> {{ $deTai->linhVuc->ten_linh_vuc }}</p>
                        <p><strong>Mô tả:</strong> {!! $deTai->mo_ta !!}</p>
                        <form id="form_phan_cong">
                            <div class="d-flex align-items-center">
                                <label for="so_luong_giang_vien"><strong>Chọn số lượng giảng viên:</strong></label>
                                <select id="so_luong_giang_vien" class="form-select ms-2" style="width: 70px">
                                    <option value="1" selected>1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>

                            <div id="giang_vien_selects"></div>
                            <span class="error-message text-danger d-hidden error-giangvien m-0 mt-2"></span>

                            <input type="hidden" name="DeTai[ma_de_tai]" value="{{ $deTai->ma_de_tai }}">
                            <div class="text-center">
                                <a href="{{ route('phan_cong_huong_dan.danh_sach') }}"
                                    class="btn btn-secondary btn-lg">Quay
                                    lại</a>
                                <button type="submit" class="btn btn-primary btn-lg" id="phanCong">Xác nhận phân công</button>
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
            var selectedGiangViens = [];

            $('#so_luong_giang_vien').change(function() {
                var soLuong = $(this).val();
                var giangVienOptions = @json($giangViens);
                var giangVienSelects = $('#giang_vien_selects').empty();

                for (var i = 0; i < soLuong; i++) {
                    var selectWrapper = $(
                        '<div class="mt-2 d-flex align-items-center">'
                    );
                    var label = $('<label>').attr('for', 'giang_vien_' + i).text('Giảng viên ' + (i + 1) +
                        ':').css({
                        'width': '110px',
                        'font-weight': 'bold'
                    });
                    var select = $('<select>')
                        .attr({
                            name: 'DeTai[giang_vien][' + i + ']',
                        })
                        .addClass('form-select ms-2')
                        .css('width', '260px');

                    select.append('<option value="">Chọn giảng viên</option>');

                    giangVienOptions.forEach(function(giangVien) {
                        var option = $('<option>')
                            .val(giangVien.ma_gv)
                            .text(giangVien.ho_ten)
                            .prop('disabled', selectedGiangViens.includes(giangVien.ma_gv));

                        select.append(option);
                    });

                    select.change(function() {
                        updateSelectedGiangViens();
                        updateGiangViensSelects();
                    });

                    selectWrapper.append(label).append(select)
                    selectWrapper.append(
                        '<span class="error-message text-danger d-hidden error-giangvien-[' + i +
                        '] ms-2"></span>');
                    giangVienSelects.append(selectWrapper);
                }
            });

            function updateSelectedGiangViens() {
                selectedGiangViens = [];
                $('#giang_vien_selects select').each(function() {
                    var selectedValue = $(this).val();
                    if (selectedValue) {
                        selectedGiangViens.push(selectedValue);
                    }
                });
            }

            function updateGiangViensSelects() {
                $('#giang_vien_selects select').each(function() {
                    var currentSelect = $(this);
                    var currentValue = currentSelect.val();

                    currentSelect.find('option').prop('disabled', false);

                    selectedGiangViens.forEach(function(giangVienId) {
                        currentSelect.find('option[value="' + giangVienId + '"]').prop('disabled',
                            true);
                    });

                    if (currentValue) {
                        currentSelect.find('option[value="' + currentValue + '"]').prop('disabled', false);
                    }
                });
            }

            $("#phanCong").click(function(event) {
                event.preventDefault();

                let form = $("#form_phan_cong").get(0);
                let formData = new FormData(form);

                $(".error-message").text('').removeClass(
                    "d-block").addClass("d-none");
                $(".is-invalid").removeClass("is-invalid");

                $.ajax({
                    url: "{{ route('phan_cong_huong_dan.xac_nhan_phan_cong') }}",
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
                                text: 'Phân công thành công!',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('phan_cong_huong_dan.danh_sach') }}";
                            });
                        } else {
                            $.each(result.errors, function(field, messages) {
                                let inputField = $("[name='DeTai[" + field + "]']");
                                $('.error-' + field).text(messages[0]).removeClass(
                                    "d-none").addClass("d-block");
                                if (field.startsWith("giangvien.")) {
                                    let index = field.split('.')[1];
                                    $(".error-giangvien-" + index).text(messages[0])
                                        .removeClass("d-none").show();
                                    $("[name='DeTai[giang_vien][" + index + "]']")
                                        .addClass(
                                            "is-invalid");
                                }
                                inputField.addClass("is-invalid");
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất bại!',
                            text: 'Phân công thất bại! Vui lòng thử lại',
                            confirmButtonText: 'OK',
                            timer: 1000,
                            showConfirmButton: false
                        })
                    },
                });
            });

            $('#so_luong_giang_vien').val(1).trigger('change');
        });
    </script>
@endsection
