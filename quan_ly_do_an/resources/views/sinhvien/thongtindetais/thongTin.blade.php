@extends('layouts.app')
@section('title', 'Chi tiết đề tài')

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    @if (session('co_de_tai') == 0)
                        <div class="card-header d-flex justify-content-center align-items-center flex-column">
                            <h2 style="font-weight: bold"><i>Bạn chưa có đề tài</i></h2>
                            <h5 style="font-weight: bold"><i>(Vui lòng đề xuất hoặc đăng ký!)</i></h5>
                        </div>
                    @else
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h2 style="font-weight: bold">Thông tin đề tài</h2>
                        </div>
                        <div class="card-body">

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
