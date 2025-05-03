<div id="data-container">
    <div class="d-flex justify-content-between">
        <div>
            <select id="recordsPerPage" class="form-select d-inline-block w-auto">
                <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100</option>
            </select>
            <p class="my-2">
                Hiển thị <span id="recordCount">{{ $giangViens->count() }}</span> trên tổng
                <span id="totalRecords">{{ $giangViens->total() }}</span>
            </p>
        </div>
        <div id="pagination-container">
            @if ($giangViens->total() > $giangViens->perPage())
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item {{ $giangViens->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link pagination-link" data-page="1">Đầu</a>
                        </li>
                        <li class="page-item {{ $giangViens->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link pagination-link"
                                data-page="{{ $giangViens->currentPage() - 1 }}">Trước</a>
                        </li>
                        @for ($page = 1; $page <= $giangViens->lastPage(); $page++)
                            <li class="page-item {{ $page == $giangViens->currentPage() ? 'active' : '' }}">
                                <a class="page-link pagination-link"
                                    data-page="{{ $page }}">{{ $page }}</a>
                            </li>
                        @endfor
                        <li class="page-item {{ $giangViens->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link pagination-link"
                                data-page="{{ $giangViens->currentPage() + 1 }}">Tiếp</a>
                        </li>
                        <li class="page-item {{ $giangViens->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link pagination-link" data-page="{{ $giangViens->lastPage() }}">Cuối</a>
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
                    <th scope="col" class="text-white">Tên giảng viên</th>
                    <th scope="col" class="text-white">Email</th>
                    <th scope="col" class="text-white">Số điện thoại</th>
                    <th scope="col" class="text-white">Bộ môn</th>
                    <th scope="col" class="text-white">Tài khoản</th>
                    <th scope="col" class="text-white" style="width: 13%"></th>
                </tr>
            </thead>
            <tbody id="table-body">
                @foreach ($giangViens as $key => $giangVien)
                    <tr>
                        <td scope="row"> {{ $key + 1 }} </td>
                        <td> {{ $giangVien->ho_ten }} </td>
                        <td> {{ $giangVien->email }} </td>
                        <td> {{ $giangVien->so_dien_thoai }} </td>
                        <td> {{ $giangVien->boMon->ten_bo_mon }} </td>
                        <td>
                            @if (isset($giangVien->taiKhoan))
                                Tài khoản: {{ $giangVien->taiKhoan->ten_tk }}</br>
                                Mật khẩu: {{ $giangVien->taiKhoan->mat_khau }}
                            @else
                                Chưa có
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('giang_vien.chi_tiet', ['ma_gv' => $giangVien->ma_gv]) }}"
                                class="btn btn-secondary btn-sm">Xem</a>
                            <a href="{{ route('giang_vien.huy', ['ma_gv' => $giangVien->ma_gv]) }}"
                                class="btn btn-danger btn-sm">Hủy</a>
                            <a href="{{ route('giang_vien.sua', ['ma_gv' => $giangVien->ma_gv]) }}"
                                class="btn btn-primary btn-sm">Sửa</a>
                        </td>
                    </tr>
                @endforeach
                @if ($giangViens->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center">Không có giảng viên</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
