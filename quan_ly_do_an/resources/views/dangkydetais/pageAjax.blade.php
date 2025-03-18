<div class="d-flex justify-content-between">
    <div>
        <select id="recordsPerPage" class="form-select d-inline-block w-auto">
            <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
            <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100</option>
        </select>
        <p class="my-2">
            Hiển thị <span id="recordCount">{{ $deTais->count() }}</span> trên tổng
            <span id="totalRecords">{{ $deTais->total() }}</span> 
        </p>
    </div>
    <div id="pagination-container">
        @if ($deTais->total() > $deTais->perPage())
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $deTais->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link pagination-link" data-page="1">Đầu</a>
                    </li>
                    <li class="page-item {{ $deTais->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link pagination-link" data-page="{{ $deTais->currentPage() - 1 }}">Trước</a>
                    </li>
                    @for ($page = 1; $page <= $deTais->lastPage(); $page++)
                        <li class="page-item {{ $page == $deTais->currentPage() ? 'active' : '' }}">
                            <a class="page-link pagination-link" data-page="{{ $page }}">{{ $page }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $deTais->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link pagination-link" data-page="{{ $deTais->currentPage() + 1 }}">Tiếp</a>
                    </li>
                    <li class="page-item {{ $deTais->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link pagination-link" data-page="{{ $deTais->lastPage() }}">Cuối</a>
                    </li>
                </ul>
            </nav>
        @endif
    </div>
</div>

<div id="data-container">
    <table class="table table-bordered table-striped table-hover">
        <thead style="background-color: #222e3c;">
            <tr>
                <th scope="col" class="text-white">#</th>
                <th scope="col" class="text-white">Tên đề tài</th>
                <th scope="col" class="text-white">Lĩnh vực</th>
                <th scope="col" class="text-white">Giảng viên</th>
                <th scope="col" class="text-white"></th>
            </tr>
        </thead>
        <tbody id="table-body">
            @foreach ($deTais as $key => $deTai)
                <tr>
                    <th scope="row">{{ ($deTais->currentPage() - 1) * $deTais->perPage() + $key + 1 }}</th>
                    <td>{{ $deTai->ten_de_tai }}</td>
                    <td>{{ $deTai->linhVuc->ten_linh_vuc ?? 'Chưa có' }}</td>
                    <td>{{ $deTai->giang_vien }}</td>
                    <td class="text-center">
                        <a href="#" class="btn btn-primary btn-sm">Đăng ký</a>
                    </td>
                </tr>
            @endforeach
            @if ($deTais->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">Chưa có đề tài nào</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
