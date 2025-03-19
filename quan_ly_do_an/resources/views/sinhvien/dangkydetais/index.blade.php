@extends('layouts.app')
@section('title', 'Đăng ký đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Danh sách đề tài</h2>
                    </div>
                    <div class="card-body">
                        @include('sinhvien.dangkydetais.pageAjax', ['deTais' => $deTais])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            let baseUrl = @json(route('dang_ky_de_tai.page_ajax'));
            let isLoading = false;

            function showTableLoading() {
                let colCount = $('#table-body').closest('table').find('thead tr th').length;
                $('#table-body').html(`
                    <tr>
                        <td colspan="${colCount}" class="text-center">
                            <div class="d-flex justify-content-center align-items-center">
                                <span class="spinner-border text-primary me-2" role="status"></span>
                                <span>Đang tải dữ liệu...</span>
                            </div>
                        </td>
                    </tr>
                `);
            }

            function fetchData(page = 1, limit = $('#recordsPerPage').val()) {
                if (isLoading) return;
                isLoading = true;

                showTableLoading();

                $.get(`${baseUrl}?page=${page}&limit=${limit}`, function(data) {
                    let newData = $(data);
                    $('#table-body').html(newData.find('#table-body').html());
                    $('#recordCount, #totalRecords').each(function() {
                        $(this).text(newData.find(`#${this.id}`).text());
                    });
                    $('#pagination-container').html(newData.find('#pagination-container').html());

                }).always(() => {
                    isLoading = false;
                }).fail(() => alert("Lỗi tải dữ liệu, vui lòng thử lại!"));
            }

            $(document).on('change', '#recordsPerPage', () => fetchData(1));

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).data('page');
                if (!$(this).parent().hasClass('disabled') && page) fetchData(page);
            });
        });
    </script>
@endsection
