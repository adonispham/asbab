@extends('admin.layout.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.css') }}"/>
@endsection

@section('js')
    <script src="{{ asset('administrator/assets/dataTables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('administrator/plugins.js') }}"></script>
    <script src="{{ asset('administrator/common.js') }}"></script>
    <script src="{{ asset('administrator/coupon/coupon.js') }}"></script>
@endsection

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class="breadcumb-item"><a href="{{ route('admin') }}"><i class="fa fa-home"></i> Trang
                                chủ</a></li>
                        <li class="active">Phiếu giảm giá</li>
                    </ul>
                </div>
            </div>

            <section class="panel">
                <div class="panel-body">
                    <div class="adv-table">
                        @can('thêm phiếu giảm giá')
                            <div class="flex-end center mb-15">
                                <div class="btn-group text-right">
                                    <a class="btn btn-success" href="{{ route('admin.coupon.create') }}">
                                        Thêm phiếu giảm giá <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        @endcan

                        <table class="table table-bordered table-striped" id="coupons-table"
                               @if(auth()->user()->can('sửa phiếu giảm giá') || auth()->user()->can('xóa phiếu giảm giá') || auth()->user()->can('gửi phiếu giảm giá'))
                               @if (auth()->user()->can('sửa phiếu giảm giá') && auth()->user()->can('xóa phiếu giảm giá') && auth()->user()->can('gửi phiếu giảm giá'))
                               data-url="{{ route('admin.coupon.data', ['permission' => 1]) }}"
                               @elseif(auth()->user()->can('sửa phiếu giảm giá'))
                               data-url="{{ route('admin.coupon.data', ['permission' => 2]) }}"
                               @elseif(auth()->user()->can('xóa phiếu giảm giá'))
                               data-url="{{ route('admin.coupon.data', ['permission' => 3]) }}"
                               @elseif(auth()->user()->can('gửi phiếu giảm giá'))
                               data-url="{{ route('admin.coupon.data', ['permission' => 4]) }}"
                               @endif
                               @else
                               data-url="{{ route('admin.coupon.data', ['permission' => 0]) }}"
                            @endif>
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Chương trình</th>
                                <th class="text-center">Mã giảm giá</th>
                                <th class="text-center">Chiết khấu</th>
                                <th class="text-center">Số lượng</th>
                                @if(auth()->user()->can('sửa phiếu giảm giá') || auth()->user()->can('xóa phiếu giảm giá'))
                                    <th class="text-center">Hành động</th>
                                @endif
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </section>
        </section>
    </section>
@endsection
