<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Hóa đơn</title>
    <link rel="stylesheet" href="{{ $order->bootstrap }}"/>
    <style>
        * {
            font-family: DejaVu Sans;
        }
    </style>
</head>

<body>
<header style="padding-bottom: 0">
    <div style="display: flex;">
        <div style="width: 25%; position: relative; top: 40px">
            <img src="{{ $order->shop_logo_path }}" alt=""/>
        </div>
        <div style="position: relative; left: 25%; width: 85%">
            <p style="text-align: center; font-size: 2.5em"><b>HÓA ĐƠN</b></p>
            <div class="alert">
                    <span style="width: 33.3%; display: inline-block"><span class="alert-link">NGÀY:</span>
                        {{ date('d/m/Y') }}</span>
                <span style="width: 33.3%; display: inline-block" class="text-uppercase text-center"><span
                        class="alert-link">MÃ ĐƠN HÀNG:</span> {{ $order->code }}</span>
                <span style="width: max-content; display: inline-block; text-align:left"><span
                        class="alert-link">NGƯỜI GIAO HÀNG:</span> {{ $order->shippers->name }}</span>
            </div>
        </div>
    </div>
    <div style="display: flex; position: relative">
        <div style="width: 48%">
            <p><b>VẬN CHUYỂN TỪ:</b></p>
            <table class="table">
                <tbody>
                <tr class="alert">
                    <td style="border: none; padding-left: 0">TÊN CỬA HÀNG: <span
                            class="alert-link text-uppercase">{{ $order->shop_name }}</span>
                    </td>
                </tr>
                <tr class="alert">
                    <td style="border: none; padding-left: 0">LIÊN HỆ: <span
                            class="alert-link">{{ $order->shop_phone }}</span>
                    </td>
                </tr>
                <tr class="alert">
                    <td style="border: none; padding-left: 0">ĐỊA CHỈ: <span
                            class="alert-link">{{ $order->shop_address }}</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div style="width: 48%; position: absolute; top: 0; left: 52%">
            <p><b>VẬN CHUYỂN ĐẾN:</b></p>
            <table class="table table-borderless">
                <tbody>
                <tr class="alert">
                    <td style="border: none; padding-left: 0">KHÁCH HÀNG: <span
                            class="alert-link">{{ $order->users->name }}</span></td>
                </tr>
                <tr class="alert">
                    <td style="border: none; padding-left: 0">ĐIỆN THOẠI: <span
                            class="alert-link">{{ $order->phone }}</span></td>
                </tr>
                <tr class="alert">
                    <td style="border: none; padding-left: 0">ĐỊA CHỈ: <span
                            class="alert-link">{{ $order->address }}</span></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</header>
<main>
    <div>
        <div class="alert" style="padding: 0">
            <h3 class="text-center text-success alert-link">CHI TIẾT ĐƠN HÀNG</h3>
            <table class="table">
                <thead class="bg-info">
                <tr>
                    <th class="text-justify">Stt</th>
                    <th class="text-justify">Sản phẩm</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-center">Giá</th>
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
                        <td class="text-justify">{{ $key + 1 }}</td>
                        <td class="text-justify">{{ $bill->product_name }}</td>
                        <td>{{ $bill->quantity }}</td>
                        <td>{{ number_format($bill->product_price, 2, '.', ',') }}VNĐ</td>
                        <td>{{ number_format($total, 2, '.', ',') }}VNĐ</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                @php
                    $tax = $amount * 0.1;
                    $feeship = $order->fee_ship;
                    if (isset($order->coupons)) {
                        switch ($order->coupons->type) {
                            case '0':
                                $discount = $order->coupons->discount;
                                break;
                            case '1':
                                $discount = ($order->coupons->discount * $amount) / 100;
                                break;
                            default:
                                $discount = 0;
                        }
                    } else {
                        $discount = 0;
                    }
                @endphp
                <tr class="text-right">
                    <td colspan="5" style="padding-right: 0">Phí vận chuyển:
                        {{ number_format($feeship, 2, '.', ',') }}VNĐ</td>
                </tr>
                <tr class="table-borderless text-right">
                    <td colspan="5" style="border: none; padding-right: 0">Thuế:
                        {{ number_format($tax, 2, '.', ',') }}VNĐ</td>
                </tr>
                @if (!empty($order->coupon_id))
                    <tr class="table-borderless text-right">
                        <td colspan="5" style="border: none; padding-right: 0">Chiết khấu:
                            - {{ number_format($discount, 2, '.', ',') }}VNĐ
                        </td>
                    </tr>
                @endif
                <tr class="table-borderless text-right">
                    <td colspan="5" style="border: none; padding-right: 0">Thành tiền: <span
                            class="alert-link text-danger">{{ number_format($amount + $tax + $feeship - $discount, 2, '.', ',') }}VNĐ</span>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</main>
<footer>
    <table class="table">
        <tbody>
        <tr class="alert">
            <td class="text-center" style="border: none">
                <div>Người bán</div>
                <div><i>(Ký, Ghi rõ họ tên)</i></div>
            </td>
            <td class="text-center" style="border: none">
                <div>Người mua</div>
                <div><i>(Ký, Ghi rõ họ tên)</i></div>
            </td>
        </tr>
        </tbody>
    </table>
</footer>
</body>

</html>
