<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif
        }

        .coupon {
            border: 5px dotted #bbb;
            width: 80%;
            border-radius: 15px;
            margin: 0 auto;
            max-width: 600px;
        }

        .container {
            padding: 2px 16px;
            background-color: #f1f1f1
        }

        .promo {
            background-color: #ccc;
            font-weight: 900;
            padding: 3px
        }

        a,
        .expire {
            color: red;
            text-align: center
        }

        p.code {
            font-size: 20px;
            text-align: center
        }

        p {
            line-height: 1.5;
            text-align: justify
        }

        h2.note {
            text-align: center;
            font-size: large;
            text-decoration: underline
        }
    </style>
</head>

<body>
<div class="coupon">
    <div class="container">
        <h4 style="text-align: center">Phiếu giảm giá từ <a style="color: blue" target="_blank"
                                                                  href="{{ route('asbab.home') }}">www.asbab.dev.com</a>
        </h4>
    </div>
    <div class="container" style="background-color: #fff">
        <h2 class="note">
            @switch($coupon->typr)
                @case(0)
                {{ 'Giảm đến ' . number_format($coupon->discount, 2, '.', ',') .'VNĐ' }}
                @break
                @case(1)
                {{ 'Giảm đến ' . $coupon->discount . '%' }}
                @break
            @endswitch
            cho tổng đơn hàng.
        </h2>
        <p>Bạn có thể mua hàng tại <a target="_blank" href="{{ route('asbab.home') }}">www.asbab.dev.com</a>!
            Nếu bạn đã có tài khoản, hãy <a target="_blank" href="{{ route('asbab.home').'#login_account' }}">đăng
                nhập</a> để mua hàng và sử dụng mã giảm giá ở dưới để nhận được chiết khấu. Cảm ơn! Mong bạn có thật
            nhiều sức khỏe và niềm vui trong cuộc sống.</p>
    </div>
    <div class="container">
        <p class="code">Sử dụng mã: <span class="promo">{{ $coupon->code }}</span> chỉ {{ $coupon->quantity  }} phiếu</p>
        <p class="expire">Ngày hết hạn: {{ date('d/m/Y', strtotime($coupon->time_out_of)) }}</p>
    </div>
</div>
</body>

</html>
