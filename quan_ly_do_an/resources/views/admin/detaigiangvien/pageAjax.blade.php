<div id="data-container">
    <div class="d-flex justify-content-between">
        <div>
            <select id="recordsPerPage" class="form-select d-inline-block w-auto">
                <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100</option>
            </select>
            <p class="my-2">
                Hiển thị <span id="recordCount">{{ $deTaiGVs->count() }}</span> trên tổng
                <span id="totalRecords">{{ $deTaiGVs->total() }}</span>
            </p>
        </div>
        <div id="pagination-container">
            @if ($deTaiGVs->total() > $deTaiGVs->perPage())
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item {{ $deTaiGVs->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link pagination-link" data-page="1">Đầu</a>
                        </li>
                        <li class="page-item {{ $deTaiGVs->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link pagination-link"
                                data-page="{{ $deTaiGVs->currentPage() - 1 }}">Trước</a>
                        </li>
                        @for ($page = 1; $page <= $deTaiGVs->lastPage(); $page++)
                            <li class="page-item {{ $page == $deTaiGVs->currentPage() ? 'active' : '' }}">
                                <a class="page-link pagination-link"
                                    data-page="{{ $page }}">{{ $page }}</a>
                            </li>
                        @endfor
                        <li class="page-item {{ $deTaiGVs->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link pagination-link"
                                data-page="{{ $deTaiGVs->currentPage() + 1 }}">Tiếp</a>
                        </li>
                        <li class="page-item {{ $deTaiGVs->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link pagination-link" data-page="{{ $deTaiGVs->lastPage() }}">Cuối</a>
                        </li>
                    </ul>
                </nav>
            @endif
        </div>
    </div>
    <div>
        <table class="table table-bordered table-striped table-hover">
            <thead style="background: #222e3c;">
                <tr>
                    <th scope="col" class="text-white">#</th>
                    <th scope="col" class="text-white" style="width: 40%;">Tên đề tài</th>
                    <th scope="col" class="text-white">Giảng viên</th>
                    <th scope="col" class="text-white">Ngày đưa ra</th>
                    <th scope="col" class="text-white">Trạng thái</th>
                    <th scope="col" class="text-white"></th>
                </tr>
            </thead>
            <tbody id="table-body">
                @foreach ($deTaiGVs as $key => $deTaiGV)
                    <tr>
                        <td scope="row">
                            {{ $key + 1 }}</td>
                        <td
                            style="width: 40%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; word-break: break-word;">
                            {{ $deTaiGV->ten_de_tai }}
                        </td>
                        <td> {!! $deTaiGV->giangViens->pluck('ho_ten')->implode('<br>') !!} </td>
                        <td> {{ \Carbon\Carbon::parse($deTaiGV->ngayDuaRa->ngay_dua_ra)->format('d-m-Y') }} </td>
                        <td>
                            @if ($deTaiGV->trang_thai == 1)
                                <span class="text-warning">Chờ duyệt</span>
                            @elseif ($deTaiGV->trang_thai == 2)
                                <span class="text-success">Đã duyệt</span>
                            @else
                                <span class="text-danger">Không duyệt</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($deTaiGV->trang_thai == 1)
                                <a href="{{ route('de_tai_giang_vien.duyet', ['ma_de_tai' => $deTaiGV->ma_de_tai]) }}"
                                    class="btn btn-primary btn-sm">Duyệt</a>
                            @else
                                <a href="{{ route('de_tai_giang_vien.chi_tiet', ['ma_de_tai' => $deTaiGV->ma_de_tai]) }}"
                                    class="btn btn-secondary btn-sm">Xem</a>
                                <a href="{{ route('de_tai_giang_vien.huy', ['ma_de_tai' => $deTaiGV->ma_de_tai]) }}"
                                    class="btn btn-danger btn-sm">Hủy</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                @if ($deTaiGVs->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center">Không có thiết lập</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
