@extends('layouts.app')
@section('title', 'Đề xuất đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Đề xuất đề tài</h2>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <label for="DeTai[ten_de_tai]"
                                class="p-2 d-flex align-items-center justify-content-center text-white rounded col-2 bg-secondary">
                                Tên đề tài
                            </label>
                            <div class="col-10">
                                <input type="text" class="form-control form-control-lg shadow-none"
                                    placeholder="Nhập tên đề tài" name="DeTai[ten_de_tai]">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="DeTai[ma_linh_vuc]"
                                class="p-2 d-flex align-items-center justify-content-center text-white rounded col-2 bg-secondary">
                                Lĩnh vực
                            </label>
                            <div class="col-10">
                                <select class="form-select form-select-lg shadow-none" name="DeTai[ma_linh_vuc]">
                                    <option selected>Chọn lĩnh vực</option>
                                    @foreach ($linhVucs as $linhVuc)
                                        <option value="{{ $linhVuc->ma_linh_vuc }}">{{ $linhVuc->ten_linh_vuc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="DeTai[mo_ta]"
                                class="p-2 d-flex align-items-center justify-content-center text-white rounded col-2 bg-secondary">
                                Mô tả
                            </label>
                            <div class="col-10">
                                <textarea class="form-control form-control-lg shadow-none" name="DeTai[mo_ta]" id="mo_ta"></textarea>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-primary btn-lg w-25" data-bs-toggle="modal"
                                data-bs-target="#confirmModal">
                                Đề xuất
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Xác nhận đề xuất</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Khi bạn đã đề xuất 1 đề tài thì không thể đăng ký đề tài có trong danh sách đề tài. Bạn có chắc chắn muốn
                    đề xuất đề tài này không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            $('#mo_ta').summernote({
                height: 400,
                minHeight: 400,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['insert', ['picture', 'link']],
                    ['view', ['fullscreen', 'codeview']]
                ],
                callbacks: {
                    onInit: function() {
                        $('.note-editor').addClass('m-0');
                    }
                }
            });
        });
    </script>
@endsection
