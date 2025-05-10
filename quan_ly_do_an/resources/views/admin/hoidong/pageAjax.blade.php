<div id="data-container">
    <div class="d-flex justify-content-between">
        <div>
            <select id="recordsPerPage" class="form-select d-inline-block w-auto">
                <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100</option>
            </select>
            <p class="my-2">
                Hiển thị <span id="recordCount">{{ $hoiDongs->count() }}</span> trên tổng
                <span id="totalRecords">{{ $hoiDongs->total() }}</span>
            </p>
        </div>
        <div id="pagination-container">
            @if ($hoiDongs->total() > $hoiDongs->perPage())
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item {{ $hoiDongs->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link pagination-link" data-page="1">Đầu</a>
                        </li>
                        <li class="page-item {{ $hoiDongs->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link pagination-link"
                                data-page="{{ $hoiDongs->currentPage() - 1 }}">Trước</a>
                        </li>
                        @for ($page = 1; $page <= $hoiDongs->lastPage(); $page++)
                            <li class="page-item {{ $page == $hoiDongs->currentPage() ? 'active' : '' }}">
                                <a class="page-link pagination-link"
                                    data-page="{{ $page }}">{{ $page }}</a>
                            </li>
                        @endfor
                        <li class="page-item {{ $hoiDongs->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link pagination-link"
                                data-page="{{ $hoiDongs->currentPage() + 1 }}">Tiếp</a>
                        </li>
                        <li class="page-item {{ $hoiDongs->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link pagination-link" data-page="{{ $hoiDongs->lastPage() }}">Cuối</a>
                        </li>
                    </ul>
                </nav>
            @endif
        </div>
    </div>
    <div>
        <table class="table table-bordered table-striped table-hover" style="font-size: 13px">
            <thead style="background: #222e3c;">
                <tr>
                    <th scope="col" class="text-white">#</th>
                    <th scope="col" class="text-white" style="width: 20%">Tên hội đồng</th>
                    <th scope="col" class="text-white">Chuyên ngành</th>
                    <th scope="col" class="text-white">Phòng</th>
                    <th scope="col" class="text-white">Ngày tổ chức</th>
                    <th scope="col" class="text-white">Năm học</th>
                    <th scope="col" class="text-white" style="width: 15%"></th>
                </tr>
            </thead>
            <tbody id="table-body">
                @foreach ($hoiDongs as $key => $hoiDong)
                    <tr>
                        <td scope="row"> {{ $key + 1 }} </td>
                        <td> {{ $hoiDong->ten_hoi_dong }} </td>
                        <td> {{ $hoiDong->chuyenNganh->ten_bo_mon }} </td>
                        <td> {{ $hoiDong->phong }} </td>
                        <td> {{ \Carbon\Carbon::parse($hoiDong->ngay)->format('H:i d-m-Y') }} </td>
                        <td> {{ $hoiDong->nam_hoc }} </td>
                        <td class="text-center">
                            <a href="{{ route('hoi_dong.chi_tiet', ['ma_hoi_dong' => $hoiDong->ma_hoi_dong]) }}"
                                class="btn btn-secondary btn-sm">Xem</a>
                            <a href="{{ route('hoi_dong.huy', ['ma_hoi_dong' => $hoiDong->ma_hoi_dong]) }}"
                                class="btn btn-danger btn-sm">Hủy</a>
                            <a href="{{ route('hoi_dong.sua', ['ma_hoi_dong' => $hoiDong->ma_hoi_dong]) }}"
                                class="btn btn-primary btn-sm">Sửa</a>
                        </td>
                    </tr>
                @endforeach
                @if ($hoiDongs->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center">Không có hội đồng</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
