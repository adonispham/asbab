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
                        <li class="active">Chi tiết đơn hàng</li>
                    </ul>
                </div>
            </div>

            <section class="panel">
                <div class="panel-body">
                    <secction class="adv-table">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th colspan="5" class="text-center text-uppercase bg-success">Thông tin khách hàng</th>
                            </tr>
                            <tr>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Điện thoại</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{ isset($order->user_id) ? $order->users->name : $order->name }}</td>
                                <td>{{ isset($order->user_id) ? $order->users->email : $order->mail }}</td>
                                <td>{{ isset($order->user_id) ? $order->users->phone : $order->phone }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </secction>
                </div>
            </section>

            <section class="panel">
                <div class="panel-body">
                    <secction class="adv-table">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th colspan="6" class="text-center text-uppercase bg-success">Thông tin vận chuyển</th>
                            </tr>
                            <tr>
                                <th>Người giao hàng</th>
                                <th>Điện thoại</th>
                                <th>Email</th>
                                <th>Địa chỉ</th>
                                <th>Phương thức thanh toán</th>
                                <th>Ghi chú</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                switch ($order->paymethod) {
                                    case '0':
                                        $pay = 'Paypal';
                                        break;
                                    case '1':
                                        $pay = 'Khi nhận hàng';
                                        break;
                                    case '2':
                                        $pay = 'Chuyển khoản';
                                        break;
                                }
                            @endphp
                            <tr>
                                <td>{{ $order->shippers !== null ? $order->shippers->name : 'không có thông tin' }}</td>
                                <td>{{ $order->shippers !== null ? $order->shippers->email : 'không có thông tin' }}</td>
                                <td>{{ $order->shippers !== null ? $order->shippers->phone : 'không có thông tin' }}</td>
                                <td>{{ $order->address }}</td>
                                <td>{{ $pay }}</td>
                                <td class="note-delivery">{{ $order->note ? $order->note : 'không có thông tin' }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </secction>
                </div>
            </section>

            <section class="panel">
                <div class="panel-body">
                    <secction class="adv-table">
                        <table class="table table-striped table-middle">
                            <thead>
                            <tr>
                                <th colspan="5" class="text-center text-uppercase bg-success">Thông tin đơn hàng</th>
                            </tr>
                            <tr>
                                <th class="text-center">STT</th>
                                <th>Tên sản phẩm</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">Giá cả</th>
                                <th class="text-center">Tổng</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $amount = 0;
                            @endphp
                            @foreach ($order->bills as $key => $bill)
                                @php
                                    $total = $bill->product_price * $bill->quantity;
                                    $amount += $total;
                                @endphp
                                <tr class="text-center">
                                    <td>{{ $key }}</td>
                                    <td class="text-justify">{{ $bill->product_name }}</td>
                                    <td>{{ $bill->quantity }}</td>
                                    <td>{{ number_format($bill->product_price, 2, '.', ','). 'VNĐ' }}</td>
                                    <td>{{ number_format($total, 2, '.', ',').'VNĐ' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            @php
                                $feeship = $order->fee_ship;
                                $tax = $amount * 0.1;
                                if ($order->coupon_id !== null) {
                                    if ($order->coupons->type == 0) {
                                        $discount = $order->coupons->discount;
                                    } else {
                                        $discount = ($amount * $order->coupons->discount) / 100;
                                    }
                                } else {
                                    $discount = 0;
                                }
                                $totalamount = $amount - $discount + $tax + $feeship;
                            @endphp
                            <tfoot>
                            <tr>
                                <td>Phí vận chuyển: <span>{{ number_format($feeship, 2, '.', ',') }}VNĐ</span></td>
                                <td>Thuế: <span>{{ number_format($tax, 2, '.', ',') }}VNĐ</span></td>
                                <td colspan="3" rowspan="2" class="text-right">
                                    <span>Thành tiền: </span><span
                                        class="text-primary ml-10">{{ number_format($totalamount, 2, '.', ',') }}VNĐ</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">Chiết khấu:
                                    <span>-{{ number_format($discount, 2, '.', ',') }}VNĐ</span>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </secction>
                </div>
                @can('in đơn hàng')
                    @if ($order->status == 1)
                        <div class="flex-center">
                            <a href="{{ route('admin.order.print', ['id' => $order->id]) }}"
                               class="btn btn-info mb-15">In</a>
                        </div>
                    @endif
                @endcan
            </section>
        </section>
    </section>
@endsection
