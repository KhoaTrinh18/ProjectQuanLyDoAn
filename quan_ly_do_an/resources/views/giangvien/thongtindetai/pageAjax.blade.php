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
        <table class="table table-bordered table-striped table-hover" style="font-size: 13px">
            <thead style="background: #222e3c;">
                <tr>
                    <th scope="col" class="text-white">#</th>
                    <th scope="col" class="text-white" style="width: 35%;">Tên đề tài</th>
                    <th scope="col" class="text-white">Lĩnh vực</th>
                    <th scope="col" class="text-white">Sinh viên đăng ký (Ngày đăng ký)</th>
                    <th scope="col" class="text-white">Năm học</th>
                    <th scope="col" class="text-white"></th>
                </tr>
            </thead>
            <tbody id="table-body">
                @foreach ($deTais as $key => $deTai)
                    <tr>
                        <td scope="row">
                            {{ $key + 1 }}</td>
                        <td
                            style="width: 35%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; word-break: break-word;">
                            {{ $deTai->ten_de_tai }}
                        </td>
                        <td>{{ $deTai->linhVuc->ten_linh_vuc }}</td>
                        <td>
                            @if ($deTai->sinhViens->count() == 0)
                                <i>Chưa có</i>
                            @else
                                @foreach ($deTai->sinhViens as $sinhVien)
                                    @php
                                        $phanCongSVDK = DB::Table('bang_phan_cong_svdk')
                                            ->where('ma_sv', $sinhVien->ma_sv)
                                            ->first();
                                        $ngayDangKy = \Carbon\Carbon::create($phanCongSVDK->ngay_dang_ky)->format(
                                            'd-m-Y',
                                        );
                                    @endphp
                                    {{ $sinhVien->ho_ten }} ({{ $ngayDangKy }})</br>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            {{ $deTai->nam_hoc }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('thong_tin_de_tai.chi_tiet_huong_dan', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                class="btn btn-secondary btn-sm">Xem</a>
                        </td>
                    </tr>
                @endforeach
                @if ($deTais->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center">Không có đề tài</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
