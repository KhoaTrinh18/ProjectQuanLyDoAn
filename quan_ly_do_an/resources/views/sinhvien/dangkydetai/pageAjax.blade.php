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
                    <th scope="col" class="text-white" style="width: 28%;">Tên đề tài</th>
                    <th scope="col" class="text-white">Lĩnh vực</th>
                    <th scope="col" class="text-white">Giảng viên</th>
                    <th scope="col" class="text-white">Sinh viên đã đăng ký</th>
                    <th scope="col" class="text-white">Số lượng sinh viên đăng ký</th>
                    <th scope="col" class="text-white"></th>
                </tr>
            </thead>
            <tbody id="table-body">
                @foreach ($deTais as $key => $deTai)
                    <tr>
                        <td scope="row">{{ ($deTais->currentPage() - 1) * $deTais->perPage() + $key + 1 }}</td>
                        <td
                            style="width: 28%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; word-break: break-word;">
                            {{ $deTai->ten_de_tai }}
                        </td>
                        <td>{{ $deTai->linhVuc->ten_linh_vuc ?? 'Chưa có' }}</td>
                        <td>
                            {!! $deTai->giangViens->pluck('ho_ten')->implode('<br>') !!}
                        </td>
                        <td>
                            @if ($deTai->sinhViens->count() >= 1)
                                {!! $deTai->sinhViens->pluck('ho_ten')->implode('<br>') !!}
                            @else
                                <i>Chưa có</i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($deTai->so_luong_sv_dang_ky >= $deTai->so_luong_sv_toi_da)
                                <span
                                    class="text-danger">{{ $deTai->so_luong_sv_dang_ky . '/' . $deTai->so_luong_sv_toi_da }}</span>
                            @else
                                <span
                                    class="text-success">{{ $deTai->so_luong_sv_dang_ky . '/' . $deTai->so_luong_sv_toi_da }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                $thietLap = DB::table('thiet_lap')->where('trang_thai', 1)->first();
                                $duDeTai = 0;

                                foreach ($deTai->giangViens as $giangVien) {
                                    if (
                                        $giangVien->deTaiDangKys->where('nam_hoc', $thietLap->nam_hoc)->count() >=
                                        $giangVien->hocVi->sl_de_tai_huong_dan
                                    ) {
                                        $duDeTai = 1;
                                        break;
                                    }
                                }
                            @endphp
                            @if ($deTai->so_luong_sv_dang_ky < $deTai->so_luong_sv_toi_da && !$daDangKy && !$duDeTai)
                                <a href="{{ route('dang_ky_de_tai.dang_ky', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                    class="btn btn-primary btn-sm">Đăng ký</a>
                            @else
                                <a href="{{ route('dang_ky_de_tai.dang_ky', ['ma_de_tai' => $deTai->ma_de_tai]) }}"
                                    class="btn btn-secondary btn-sm">Xem</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                @if ($deTais->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center">Không có đề tài</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
