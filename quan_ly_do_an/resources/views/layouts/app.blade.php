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
    @yield('style')
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="index.html">
                    <span class="align-middle">AdminKit</span>
                </a>
                @if (session('ten_sinh_vien') != null)
                    <ul class="sidebar-nav">
                        <li class="sidebar-header">
                            Thực hiện
                        </li>
                        <li class="sidebar-item {{ request()->is('dang-ky-de-tai*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('dang_ky_de_tai.danh_sach') }}">
                                <i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">Đăng
                                    ký
                                    đề tài</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->is('de-xuat-de-tai*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('de_xuat_de_tai.de_xuat') }}">
                                <i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">Đề
                                    xuất
                                    đề tài</span>
                            </a>
                        </li>
                        <li class="sidebar-header">
                            Thông tin
                        </li>
                        <li
                            class="sidebar-item {{ request()->is('thong-tin-de-tai/thong-tin') || request()->is('thong-tin-de-tai/chi-tiet') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('thong_tin_de_tai.thong_tin') }}">
                                <i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">Đề
                                    tài
                                    của tôi</span>
                            </a>

                        </li>
                        <li
                            class="sidebar-item {{ request()->is('thong-tin-de-tai/danh-sach-de-tai-huy') || request()->is('thong-tin-de-tai/chi-tiet-de-tai-huy*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('thong_tin_de_tai.danh_sach_de_tai_huy') }}">
                                <i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">Đề
                                    tài
                                    đã hủy</span>
                            </a>
                        </li>
                    </ul>
                @else
                    <ul class="sidebar-nav">
                        <li class="sidebar-header">
                            Thực hiện
                        </li>
                        <li class="sidebar-item {{ request()->is('dua-ra-de-tai*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('dua_ra_de_tai.danh_sach') }}">
                                <i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">Đưa
                                    ra đề tài</span>
                            </a>
                        </li>
                        <li class="sidebar-header">
                            Thông tin
                        </li>
                        <li class="sidebar-item {{ request()->is('thong-tin-de-tai*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('thong_tin_de_tai.danh_sach_duyet') }}">
                                <i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">Đề
                                    tài đã duyệt</span>
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
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item d-flex align-items-center">
                            @if (session('ten_sinh_vien') != null)
                                {{ session('ten_sinh_vien') }}
                            @else
                                @if (session('ten_giang_vien') != null)
                                    {{ session('ten_giang_vien') }}
                                @endif
                            @endif
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
        });
    </script>
    @yield('scripts')
</body>

</html>
