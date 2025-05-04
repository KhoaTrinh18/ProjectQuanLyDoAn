<div id="data-container">
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
                                <a class="page-link pagination-link"
                                    data-page="{{ $page }}">{{ $page }}</a>
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
    <div>
        <table class="table table-bordered table-striped table-hover">
            <thead style="background: #222e3c;">
                <tr>
                    <th scope="col" class="text-white">#</th>
                    <th scope="col" class="text-white" style="width: 25%;">Tên đề tài</th>
                    <th scope="col" class="text-white">Sinh viên thực hiện</th>
                    <th scope="col" class="text-white">Giảng viên hướng dẫn</th>
                    <th scope="col" class="text-white">Hội đồng</th>
                    <th scope="col" class="text-white">Trạng thái</th>
                    <th scope="col" class="text-white"></th>
                </tr>
            </thead>
            <tbody id="table-body">
                @foreach ($deTais as $key => $deTai)
                    <tr>
                        <td scope="row">
                            {{ $key + 1 }}</td>
                        <td
                            style="width: 25%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; word-break: break-word;">
                            {{ $deTai->ten_de_tai }}
                        </td>
                        <td> {!! $deTai->sinhViens->pluck('ho_ten')->implode('<br>') !!} </td>
                        <td>
                            @if ($deTai->giangViens->count() != 0)
                                {!! $deTai->giangViens->pluck('ho_ten')->implode('<br>') !!}
                            @else
                                Chưa có
                            @endif
                        </td>
                        <td>
                            @if ($deTai->hoiDongs->count() != 0)
                                {!! $deTai->hoiDongs->pluck('ten_hoi_dong')->implode('<br>') !!}
                            @else
                                Chưa có
                            @endif
                        </td>
                        <td>
                            @if ($deTai->hoiDongs->count() != 0)
                                <span class="text-success">Đã phân công</span>
                            @else
                                <span class="text-warning">Chưa phân công</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($deTai->hoiDongs->count() != 0)
                                <a href="{{ route('phan_cong_hoi_dong.chi_tiet', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                    class="btn btn-secondary btn-sm">Xem</a>
                                <a href="{{ route('phan_cong_hoi_dong.huy', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                    class="btn btn-danger btn-sm">Hủy</a>
                                <a href="{{ route('phan_cong_hoi_dong.sua', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                    class="btn btn-primary btn-sm">Sửa</a>
                            @else
                                <a href="{{ route('phan_cong_hoi_dong.phan_cong', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                    class="btn btn-primary btn-sm">Phân công hội dồng</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                @if ($deTais->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center">Không có thiết lập</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
