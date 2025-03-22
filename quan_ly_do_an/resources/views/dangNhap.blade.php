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

    <link rel="canonical" href="https://demo-basic.adminkit.io/pages-sign-in.html" />

    <title>Sign In | AdminKit Demo</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <h1 class="h2">Đăng nhập</h1>
                                </div>
                                <div class="m-sm-3">
                                    <form id="form_dang_nhap">
                                        <div class="mb-3">
                                            <label class="form-label">Tên tài khoản</label>
                                            <input class="form-control form-control-lg" type="text"
                                                name="ten_tai_khoan" placeholder="Nhập tên tài khoản" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Mật khẩu</label>
                                            <input class="form-control form-control-lg" type="password" name="mat_khau"
                                                placeholder="Nhập mật khẩu" />
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary" id="dangNhap">Đăng
                                                nhập</button>
                                        </div>
                                        <div class="text-center mt-2" id="error_block">
                                            <span class="text-danger"><i></i></span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#dangNhap").click(function(event) {
                event.preventDefault();

                let form = $("#form_dang_nhap").get(0);
                let formData = new FormData(form);

                $.ajax({
                    url: "{{ route('xac_nhan_dang_nhap') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(result) {
                        if (result.success) {
                            window.location.href = result.route;
                        } else {
                            $("#error_block span").html(
                                '<i class="fas fa-exclamation-circle"></i> ' + result.error
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Lỗi khi gửi dữ liệu:", error);
                        alert("Lỗi khi gửi dữ liệu! Vui lòng thử lại.");
                    }
                });
            });

        });
    </script>
</body>

</html>
