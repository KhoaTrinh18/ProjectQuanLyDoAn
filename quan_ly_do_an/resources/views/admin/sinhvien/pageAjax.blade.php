<div id="data-container">
    <div class="d-flex justify-content-between">
        <div>
            <select id="recordsPerPage" class="form-select d-inline-block w-auto">
                <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100</option>
            </select>
            <p class="my-2">
                Hiển thị <span id="recordCount">{{ $sinhViens->count() }}</span> trên tổng
                <span id="totalRecords">{{ $sinhViens->total() }}</span>
            </p>
        </div>
        <div id="pagination-container">
            @if ($sinhViens->total() > $sinhViens->perPage())
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item {{ $sinhViens->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link pagination-link" data-page="1">Đầu</a>
                        </li>
                        <li class="page-item {{ $sinhViens->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link pagination-link"
                                data-page="{{ $sinhViens->currentPage() - 1 }}">Trước</a>
                        </li>
                        @for ($page = 1; $page <= $sinhViens->lastPage(); $page++)
                            <li class="page-item {{ $page == $sinhViens->currentPage() ? 'active' : '' }}">
                                <a class="page-link pagination-link"
                                    data-page="{{ $page }}">{{ $page }}</a>
                            </li>
                        @endfor
                        <li class="page-item {{ $sinhViens->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link pagination-link"
                                data-page="{{ $sinhViens->currentPage() + 1 }}">Tiếp</a>
                        </li>
                        <li class="page-item {{ $sinhViens->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link pagination-link" data-page="{{ $sinhViens->lastPage() }}">Cuối</a>
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
                    <th scope="col" class="text-white">MSSV</th>
                    <th scope="col" class="text-white">Tên sinh viên</th>
                    <th scope="col" class="text-white">Lớp</th>
                    <th scope="col" class="text-white" width="28%">Tên đề tài</th>
                    <th scope="col" class="text-white">Tài khoản</th>
                    <th scope="col" class="text-white">Trạng thái</th>
                    <th scope="col" class="text-white"></th>
                </tr>
            </thead>
            <tbody id="table-body">
                @foreach ($sinhViens as $key => $sinhVien)
                    <tr>
                        <td scope="row"> {{ $key + 1 }} </td>
                        <td> {{ $sinhVien->mssv }} </td>
                        <td> {{ $sinhVien->ho_ten }} </td>
                        <td> {{ $sinhVien->lop }} </td>
                        <td> Chưa có </td>
                        <td>
                            @if (isset($sinhVien->taiKhoan))
                                Tài khoản: {{$sinhVien->taiKhoan->ten_tk}}</br>
                                Mật khẩu: {{$sinhVien->taiKhoan->mat_khau}}
                            @else
                                Chưa có
                            @endif
                        </td>
                        <td>
                            @if ($sinhVien->trang_thai == 0)
                                <span class="text-danger">Không hoàn thành</span>
                            @elseif($sinhVien->trang_thai == 1)
                                <span class="text-warning">Đang thực hiện</span>
                            @else
                                <span class="text-success">Đã hoàn thành</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('sinh_vien.chi_tiet', ['ma_sv' => $sinhVien->ma_sv]) }}"
                                class="btn btn-secondary btn-sm">Xem</a>
                            <a href="{{ route('sinh_vien.huy', ['ma_sv' => $sinhVien->ma_sv]) }}"
                                class="btn btn-danger btn-sm">Hủy</a>
                            <a href="{{ route('sinh_vien.sua', ['ma_sv' => $sinhVien->ma_sv]) }}"
                                class="btn btn-primary btn-sm">Sửa</a>
                        </td>
                    </tr>
                @endforeach
                @if ($sinhViens->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center">Không có sinh viên</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
