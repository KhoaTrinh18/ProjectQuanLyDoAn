@extends('layouts.app')
@section('title', 'Danh sách sinh viên')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12 col-lg-6" style="padding-right: 6px">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Đề tài giảng viên</h2>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="chart-de-tai"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6" style="padding-left: 6px">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Sinh viên</h2>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="chart-sinh-vien"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 style="font-weight: bold">Sinh viên hoàn thành</h2>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="chart-sinh-vien-all"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const dataSinhVienTheoNam = @json($dataSinhVienTheoNam);
        const thongKeDeTai = @json($thongKeDeTai);
        const thongKeSinhVien = @json($thongKeSinhVien);

        $(document).ready(function() {

            console.log(dataSinhVienTheoNam);

            function veBieuDo(idCanvas, dataNguon) {
                const labels = Object.keys(dataNguon);
                const data = Object.values(dataNguon);

                new Chart(document.getElementById(idCanvas), {
                    type: "doughnut",
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                window.theme.danger,
                                window.theme.warning,
                                window.theme.success
                            ],
                            borderColor: "transparent"
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        cutoutPercentage: 65,
                        legend: {
                            display: true
                        }
                    }
                });
            }

            veBieuDo("chart-de-tai", thongKeDeTai);
            veBieuDo("chart-sinh-vien", thongKeSinhVien);
        });

        const labels = Object.keys(dataSinhVienTheoNam).sort((a, b) => {
            return parseInt(a.split('-')[0]) - parseInt(b.split('-')[0]);
        });
        const thamGiaData = labels.map(year => dataSinhVienTheoNam[year]?.tham_gia || 0);
        const hoanThanhData = labels.map(year => dataSinhVienTheoNam[year]?.hoan_thanh || 0);

        new Chart(document.getElementById("chart-sinh-vien-all"), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Số sinh viên tham gia',
                        data: thamGiaData,
                        backgroundColor: window.theme.primary
                    },
                    {
                        label: 'Số sinh viên hoàn thành',
                        data: hoanThanhData,
                        backgroundColor: window.theme.success
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
