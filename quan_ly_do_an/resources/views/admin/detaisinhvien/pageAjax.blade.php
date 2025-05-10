<div id="data-container">
    <div class="d-flex justify-content-between">
        <div>
            <select id="recordsPerPage" class="form-select d-inline-block w-auto">
                <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100</option>
            </select>
            <p class="my-2">
                Hiển thị <span id="recordCount">{{ $deTaiSVs->count() }}</span> trên tổng
                <span id="totalRecords">{{ $deTaiSVs->total() }}</span>
            </p>
        </div>
        <div id="pagination-container">
            @if ($deTaiSVs->total() > $deTaiSVs->perPage())
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item {{ $deTaiSVs->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link pagination-link" data-page="1">Đầu</a>
                        </li>
                        <li class="page-item {{ $deTaiSVs->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link pagination-link"
                                data-page="{{ $deTaiSVs->currentPage() - 1 }}">Trước</a>
                        </li>
                        @for ($page = 1; $page <= $deTaiSVs->lastPage(); $page++)
                            <li class="page-item {{ $page == $deTaiSVs->currentPage() ? 'active' : '' }}">
                                <a class="page-link pagination-link"
                                    data-page="{{ $page }}">{{ $page }}</a>
                            </li>
                        @endfor
                        <li class="page-item {{ $deTaiSVs->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link pagination-link"
                                data-page="{{ $deTaiSVs->currentPage() + 1 }}">Tiếp</a>
                        </li>
                        <li class="page-item {{ $deTaiSVs->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link pagination-link" data-page="{{ $deTaiSVs->lastPage() }}">Cuối</a>
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
                    <th scope="col" class="text-white" style="width: 40%;">Tên đề tài</th>
                    <th scope="col" class="text-white">Lĩnh vực</th>
                    <th scope="col" class="text-white">Sinh viên</th>
                    <th scope="col" class="text-white">Ngày đề xuất</th>
                    <th scope="col" class="text-white">Trạng thái</th>
                    <th scope="col" class="text-white"></th>
                </tr>
            </thead>
            <tbody id="table-body">
                @foreach ($deTaiSVs as $key => $deTaiSV)
                    <tr>
                        <td scope="row">
                            {{ $key + 1 }}</td>
                        <td
                            style="width: 40%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; word-break: break-word;">
                            {{ $deTaiSV->ten_de_tai }}
                        </td>
                        <td> {{ $deTaiSV->linhVuc->ten_linh_vuc }} </td>
                        <td> {!! $deTaiSV->sinhViens->pluck('ho_ten')->implode('<br>') !!} </td>
                        <td> {{ \Carbon\Carbon::parse($deTaiSV->ngayDeXuat->ngay_de_xuat)->format('d-m-Y') }} </td>
                        <td>
                            @if ($deTaiSV->trang_thai == 1)
                                <span class="text-warning">Chờ duyệt</span>
                            @elseif ($deTaiSV->trang_thai == 2)
                                <span class="text-success">Đã duyệt</span>
                            @else
                                <span class="text-danger">Không duyệt</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($deTaiSV->trang_thai == 1)
                                <a href="{{ route('de_tai_sinh_vien.duyet', ['ma_de_tai' => $deTaiSV->ma_de_tai]) }}"
                                    class="btn btn-primary btn-sm">Duyệt</a>
                            @else
                                <a href="{{ route('de_tai_sinh_vien.chi_tiet', ['ma_de_tai' => $deTaiSV->ma_de_tai]) }}"
                                    class="btn btn-secondary btn-sm">Xem</a>
                                <a href="{{ route('de_tai_sinh_vien.huy', ['ma_de_tai' => $deTaiSV->ma_de_tai]) }}"
                                    class="btn btn-danger btn-sm">Hủy</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                @if ($deTaiSVs->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center">Không có thiết lập</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
