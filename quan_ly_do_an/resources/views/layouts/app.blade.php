<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords"
        content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />
    <link rel="canonical" href="https://demo-basic.adminkit.io/pages-blank.html" />
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.7.10/dist/css/tempus-dominus.min.css"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    @yield('style')
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="index.html">
                    <span class="align-middle">Quản lý đề tài</span>
                </a>
                @if (session('ten_sinh_vien') != null)
                    <ul class="sidebar-nav">
                        <li class="sidebar-header">
                            Thực hiện
                        </li>
                        <li class="sidebar-item {{ request()->is('dang-ky-de-tai*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('dang_ky_de_tai.danh_sach') }}">
                                <i class="align-middle bi bi-pencil-square"></i> <span class="align-middle">Đăng
                                    ký
                                    đề tài</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->is('de-xuat-de-tai*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('de_xuat_de_tai.de_xuat') }}">
                                <i class="align-middle bi bi-plus-square"></i> <span class="align-middle">Đề
                                    xuất
                                    đề tài</span>
                            </a>
                        </li>
                        <li class="sidebar-header">
                            Thông tin
                        </li>
                        <li
                            class="sidebar-item {{ request()->is('thong-tin-de-tai/thong-tin') || request()->is('thong-tin-de-tai/chi-tiet') || request()->is('thong-tin-de-tai/sua*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('thong_tin_de_tai.thong_tin') }}">
                                <i class="align-middle bi bi-info-square"></i> <span class="align-middle">Đề
                                    tài
                                    của tôi</span>
                            </a>

                        </li>
                        <li
                            class="sidebar-item {{ request()->is('thong-tin-de-tai/danh-sach-khong-duyet') || request()->is('thong-tin-de-tai/chi-tiet-khong-duyet*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('thong_tin_de_tai.danh_sach_khong_duyet') }}">
                                <i class="align-middle bi bi-list-task"></i> <span class="align-middle">Danh
                                    sách không duyệt</span>
                            </a>
                        </li>
                    </ul>
                @elseif (session('ten_giang_vien') != null)
                    <ul class="sidebar-nav">
                        <li class="sidebar-header">
                            Thông tin
                        </li>
                        <li class="sidebar-item {{ request()->is('thong-tin-de-tai*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('thong_tin_de_tai.danh_sach_duyet') }}">
                                <i class="align-middle bi bi-list-task"></i> <span class="align-middle">Đề
                                    tài đã duyệt</span>
                            </a>
                        </li>
                        <li class="sidebar-header">
                            Thực hiện
                        </li>
                        <li class="sidebar-item {{ request()->is('dua-ra-de-tai*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('dua_ra_de_tai.danh_sach') }}">
                                <i class="align-middle bi bi-plus-square"></i> <span class="align-middle">Đưa
                                    ra đề tài</span>
                            </a>
                        </li>
                        <li class="sidebar-header">
                            Chấm điểm
                        </li>
                        <li class="sidebar-item {{ request()->is('cham-diem-huong-dan*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('cham_diem_huong_dan.danh_sach') }}">
                                <i class="align-middle bi bi-check-square"></i> <span class="align-middle">Đề
                                    tài hướng dẫn</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('cham-diem-phan-bien*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('cham_diem_phan_bien.danh_sach') }}">
                                <i class="align-middle bi bi-check-square"></i> <span class="align-middle">Đề
                                    tài phản biện</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('cham-diem-hoi-dong*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('cham_diem_hoi_dong.danh_sach') }}">
                                <i class="align-middle bi bi-check-square"></i> <span class="align-middle">Đề
                                    tài hội đồng</span>
                            </a>
                        </li>
                    </ul>
                @else
                    <ul class="sidebar-nav">
                        <li class="sidebar-header">
                            Thông tin
                        </li>
                         <li class="sidebar-item {{ request()->is('sinh-vien-de-tai-all*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('sinh_vien_de_tai_all.danh_sach') }}">
                                <i class="align-middle bi bi-list-task"></i> <span
                                    class="align-middle">Sinh viên - Đề tài (Tất cả)</span>
                            </a>
                        </li>
                        <li class="sidebar-header">
                            Thực hiện
                        </li>
                        <li class="sidebar-item {{ request()->is('de-tai-giang-vien*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('de_tai_giang_vien.danh_sach') }}">
                                <i class="align-middle bi bi-clipboard-check"></i> <span class="align-middle">Đề
                                    tài giảng viên</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('de-tai-sinh-vien*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('de_tai_sinh_vien.danh_sach') }}">
                                <i class="align-middle bi bi-clipboard-check"></i> <span class="align-middle">Đề
                                    tài sinh viên</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('phan-cong-huong-dan*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('phan_cong_huong_dan.danh_sach') }}">
                                <i class="align-middle bi bi-ui-checks-grid"></i> <span
                                    class="align-middle">Phân công hướng dẫn</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('phan-cong-phan-bien*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('phan_cong_phan_bien.danh_sach') }}">
                                <i class="align-middle bi bi-ui-checks-grid"></i> <span
                                    class="align-middle">Phân công phản biện</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('phan-cong-hoi-dong*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('phan_cong_hoi_dong.danh_sach') }}">
                                <i class="align-middle bi bi-ui-checks-grid"></i> <span
                                    class="align-middle">Phân công hội đồng</span>
                            </a>
                        </li>
                        <li class="sidebar-header">
                            Hệ thống
                        </li>
                        <li class="sidebar-item {{ request()->is('thiet-lap*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('thiet_lap.danh_sach') }}">
                                <i class="align-middle bi bi-gear-wide-connected"></i> <span
                                    class="align-middle">Thiết
                                    lập</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('hoi-dong*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('hoi_dong.danh_sach') }}">
                                <i class="align-middle bi bi-collection-fill"></i> <span
                                    class="align-middle">Hội đồng</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->is('bo-mon*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('bo_mon.danh_sach') }}">
                                <i class="align-middle bi bi-journal"></i> <span class="align-middle">Bộ
                                    môn</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('hoc-vi*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('hoc_vi.danh_sach') }}">
                                <i class="align-middle bi bi-briefcase"></i> <span
                                    class="align-middle">Học vị</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('linh-vuc*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('linh_vuc.danh_sach') }}">
                                <i class="align-middle bi bi-grid-1x2"></i> <span
                                    class="align-middle">Lĩnh vực</span>
                            </a>
                        </li>
                        <li class="sidebar-header">
                            Người dùng
                        </li>
                        <li class="sidebar-item {{ request()->is('giang-vien*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('giang_vien.danh_sach') }}">
                                <i class="align-middle bi bi-person-fill"></i> <span class="align-middle">Giảng
                                    viên</span>
                            </a>
                        </li>
                          <li class="sidebar-item {{ request()->is('sinh-vien/*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('sinh_vien.danh_sach') }}">
                                <i class="align-middle bi bi-person-fill"></i> <span
                                    class="align-middle">Sinh viên</span>
                            </a>
                        </li>
                      
                       
                    </ul>
                @endif
            </div>
        </nav>
        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>
                <div class="navbar-collapse collapse">
                    <div class="nav-item" style="font-size: 18px">
                        @php
                            $thietLap = DB::table('thiet_lap')->where('trang_thai', 1)->first();
                            use Carbon\Carbon;

                            $homNay = Carbon::now()->toDateString();
                            $ngayKetThucDK = Carbon::parse($thietLap->ngay_ket_thuc_dang_ky)->toDateString();
                            $ngayDKFormatted = Carbon::parse($thietLap->ngay_dang_ky)->format('d-m-Y');
                            $ngayKT_DK_Formatted = Carbon::parse($thietLap->ngay_ket_thuc_dang_ky)->format('d-m-Y');
                            $ngayTHFormatted = Carbon::parse($thietLap->ngay_thuc_hien)->format('d-m-Y');
                            $ngayKT_TH_Formatted = Carbon::parse($thietLap->ngay_ket_thuc_thuc_hien)->format('d-m-Y');
                        @endphp
                        <strong>Năm học: {{ $thietLap->nam_hoc }}</strong>
                        @if ($homNay <= $ngayKetThucDK)
                            <span style="font-size: 13px" class="m-0">
                                Thời gian đăng ký: {{ $ngayDKFormatted }} đến {{ $ngayKT_DK_Formatted }}
                            </span>
                        @else
                            <span style="font-size: 13px" class="m-0">
                                Thời gian thực hiện: {{ $ngayTHFormatted }} đến {{ $ngayKT_TH_Formatted }}
                            </span>
                        @endif
                    </div>
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item d-flex align-items-center">
                            @if (session('ten_sinh_vien') != null)
                                <a href="{{ route('thong_tin_sinh_vien') }}" class="open-modal"
                                    data-bs-toggle="modal" data-bs-target="#myModal"
                                    style="color: inherit; text-decoration: underline;"><strong>{{ session('ten_sinh_vien') }}</strong></a>
                            @elseif (session('ten_giang_vien') != null)
                                {{ session('ten_giang_vien') }}
                            @else
                                {{ session('ten_admin') }}
                            @endif
                            <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel">
                                <div class="modal-dialog">
                                    <div class="modal-content" id="modal-content">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item ms-2">
                            <button type="submit" class="btn btn-danger" id="dangXuatBtn">Đăng xuất</button>
                        </li>
                    </ul>

                </div>
            </nav>
            <main class="content p-2">
                @yield('content')
            </main>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.7.10/dist/js/tempus-dominus.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#dangXuatBtn").click(function() {
                $.ajax({
                    url: "{{ route('dang_xuat') }}",
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response
                                .route;
                        } else {
                            alert("Lỗi khi đăng xuất. Vui lòng thử lại!");
                        }
                    },
                    error: function() {
                        alert("Có lỗi xảy ra khi gửi yêu cầu. Vui lòng thử lại!");
                    }
                });
            });

            $('.open-modal').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                $.get(url, function(data) {
                    $('#modal-content').html(data);
                });
            });
        });
    </script>
    @yield('scripts')
</body>

</html>
