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
                    <th scope="col" class="text-white" style="width: 30%;">Tên đề tài</th>
                    <th scope="col" class="text-white">Sinh viên thực hiện</th>
                    <th scope="col" class="text-white">Giảng viên hướng dẫn</th>
                    <th scope="col" class="text-white">Hành động</th>
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
                            style="width: 30%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; word-break: break-word;">
                            {{ $deTai->ten_de_tai }}
                        </td>
                        <td> {!! $deTai->sinhViens->pluck('ho_ten')->implode('<br>') !!} </td>
                        <td>
                            @if ($deTai->giangViens->count() != 0)
                                {!! $deTai->giangViens->pluck('ho_ten')->implode('<br>') !!}
                            @else
                                <i>Chưa có</i>
                            @endif
                        </td>
                        <td>
                            @php
                                $sinhVien = $deTai->sinhViens->first();
                            @endphp

                            @if ($sinhVien->loai_sv == 'de_xuat')
                                Đề xuất
                            @else
                                Đăng ký
                            @endif
                        </td>
                        <td>
                            @if ($deTai->giangViens->count() != 0)
                                <span class="text-success">Đã phân công</span>
                            @else
                                <span class="text-warning">Chưa phân công</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($deTai->giangViens->count() != 0)
                                <a href="{{ route('phan_cong_huong_dan.chi_tiet', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                    class="btn btn-secondary btn-sm">Xem</a>
                                @if ($sinhVien->loai_sv == 'de_xuat')
                                    <a href="{{ route('phan_cong_huong_dan.sua', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                        class="btn btn-primary btn-sm">Sửa</a>
                                @endif
                            @else
                                <a href="{{ route('phan_cong_huong_dan.phan_cong', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                    class="btn btn-primary btn-sm">Phân công hướng dẫn</a>
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
