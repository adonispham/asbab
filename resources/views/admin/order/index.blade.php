@extends('admin.layout.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.css') }}"/>
@endsection

@section('js')
    <script src="{{ asset('administrator/assets/dataTables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('administrator/plugins.js') }}"></script>
    <script src="{{ asset('administrator/common.js') }}"></script>
    <script src="{{ asset('administrator/order/order.js') }}"></script>
@endsection

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class="breadcumb-item"><a href="{{ route('admin') }}"><i class="fa fa-home"></i> Trang
                                chủ</a>
                        </li>
                        <li class="active">Đơn hàng</li>
                    </ul>
                </div>
            </div>

            <section class="panel">
                <div class="panel-body">
                    <form class="adv-table">
                        @can('cập nhật đơn hàng')
                            <div class="flex-end center mb-15">
                                <div class="btn-group text-right">
                                    <a class="btn btn-info btn-update-order-status btn-open-select-shipper mr-10"
                                       data-href="{{ route('admin.order.update', ['status' => 2]) }}">
                                        <i class="fa fa-truck"> Đang giao hàng</i>
                                    </a>
                                    <a class="btn btn-success btn-update-order-status"
                                       data-href="{{ route('admin.order.update', ['status' => 3]) }}">
                                        <i class="fa fa-smile-o"> Đã giao</i>
                                    </a>
                                </div>
                            </div>
                        @endcan

                        <table class="table table-bordered table-striped" id="orders-table"
                               @if (auth()->user()->can('cập nhật đơn hàng'))
                               data-update="1"
                               @if (auth()->user()->can('hiển thị đơn hàng'))
                               data-url="{{ route('admin.order.data', ['show' => 1, 'update' => 1]) }}"
                               @else
                               data-url="{{ route('admin.order.data', ['show' => 0, 'update' => 1]) }}"
                               @endif
                               @else
                               data-update="0"
                               @if (auth()->user()->can('hiển thị đơn hàng'))
                               data-url="{{ route('admin.order.data', ['show' => 1, 'update' => 1]) }}"
                               @else
                               data-url="{{ route('admin.order.data', ['show' => 0, 'update' => 1]) }}"
                            @endif
                            @endif>
                            <thead>
                            <tr>
                                @can('cập nhật đơn hàng')
                                    <th class="text-center"><input type="checkbox" name="check" data-toggle="checkall"
                                                                   data-target=".item"/></th>
                                @endcan
                                <th>Mã đơn hàng</th>
                                <th>Khách hàng</th>
                                <th>Điện thoại</th>
                                <th>Địa chỉ</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Tổng</th>
                            </tr>
                            </thead>
                        </table>
                    </form>


                    <div class="modal fade full-width-modal-right" id="select_shipper" tabindex="-1" role="dialog"
                         aria-labelledby="select_shipperLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content-wrap">
                                <div class="modal-content">
                                    <div class="modal-header mb-15">
                                        <h4 class="modal-title text-center">Chọn người giao hàng</h4>
                                    </div>
                                    <form class="modal-body shipper-body">
                                        <div class="form-group mlr">
                                            <label>Người giao hàng:</label>
                                            <select name="ship_id" class="form-control">
                                                <option value="">Chọn người giao hàng</option>
                                                @foreach ($shippers as $ship)
                                                    <option value="{{ $ship->id }}">{{ $ship->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mlr">
                                            <label>Ghi chú:</label>
                                            <textarea type="text" name="note" rows="5" class="form-control"></textarea>
                                        </div>
                                        <div class="flex-end mlr">
                                            <button data-dismiss="modal" class="btn btn-shadow btn-default"
                                                    type="button">Đóng
                                            </button>
                                            <button class="btn btn-shadow btn-success ml-10" type="submit">Thêm</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </section>
@endsection
