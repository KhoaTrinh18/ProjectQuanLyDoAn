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
        <table class="table table-bordered table-striped table-hover" style="font-size: 12px">
            <thead style="background: #222e3c;">
                <tr>
                    <th scope="col" class="text-white">#</th>
                    <th scope="col" class="text-white">MSSV</th>
                    <th scope="col" class="text-white">Tên sinh viên</th>
                    <th scope="col" class="text-white">Lớp</th>
                    <th scope="col" class="text-white" width="18%">Tên đề tài</th>
                    <th scope="col" class="text-white">Giảng viên hướng dẫn</th>
                    <th scope="col" class="text-white">Điểm</th>
                    <th scope="col" class="text-white">Trạng thái (<a
                            href="{{ route('sinh_vien.cap_nhat_trang_thai') }}">Cập nhật</a>)</th>
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

                        @if ($sinhVien->dang_ky == 0)
                            <td><i>Chưa có</i></td>
                            <td><i>Chưa có</i></td>
                        @else
                            @php
                                if ($sinhVien->loai_sv == 'de_xuat') {
                                    $sinhVienDTSV = DB::table('sinh_vien_de_tai_sv')
                                        ->where('ma_sv', $sinhVien->ma_sv)
                                        ->where('trang_thai', '!=', 0)
                                        ->first();
                                    $deTai = \App\Models\DeTaiSinhVien::where([
                                        'ma_de_tai' => $sinhVienDTSV->ma_de_tai,
                                        'da_huy' => 0,
                                    ])->first();
                                } else {
                                    $phanCongSVDK = DB::table('bang_phan_cong_svdk')
                                        ->where('ma_sv', $sinhVien->ma_sv)
                                        ->first();
                                    $deTai = \App\Models\DeTaiGiangVien::where([
                                        'ma_de_tai' => $phanCongSVDK->ma_de_tai,
                                        'da_huy' => 0,
                                    ])->first();
                                }
                            @endphp
                            <td width="18%">{{ $deTai->ten_de_tai }}</td>
                            <td>
                                @php $giangVienHDs = $deTai->giangVienHuongDans()->wherePivot('ma_sv', $sinhVien->ma_sv)->get(); @endphp
                                @if ($giangVienHDs->count() == 0)
                                    <i>Chưa có</i>
                                @else
                                    {!! $giangVienHDs->pluck('ho_ten')->implode('<br>') !!}
                                @endif
                            </td>
                        @endif
                        </td>
                        <td> {!! $sinhVien->diem ?? '<em>Chưa có</em>' !!} </td>
                        <td>
                            @if ($sinhVien->trang_thai == 0)
                                <span class="text-danger">Không hoàn thành</span>
                            @elseif($sinhVien->trang_thai == 1)
                                <span class="text-warning">Đang thực hiện</span>
                            @elseif($sinhVien->trang_thai == 2)
                                <span class="text-success">Đã hoàn thành</span>
                            @else
                                <span class="text-danger">Nghỉ giữa chừng</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('sinh_vien.chi_tiet', ['ma_sv' => $sinhVien->ma_sv]) }}"
                                class="btn btn-secondary btn-sm">Xem</a>
                            @if ($sinhVien->trang_thai == 1)
                                <a href="{{ route('sinh_vien.huy', ['ma_sv' => $sinhVien->ma_sv]) }}"
                                    class="btn btn-danger btn-sm">Hủy</a>
                                <a href="{{ route('sinh_vien.sua', ['ma_sv' => $sinhVien->ma_sv]) }}"
                                    class="btn btn-primary btn-sm">Sửa</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                @if ($sinhViens->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center">Không có sinh viên</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
