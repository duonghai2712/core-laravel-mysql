<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email template</title>
    <style>
        .email-template {
            width: 620px;
            margin: 0 auto;
            font-size: 14px;
            line-height: 150%;
            font-weight: 400;
            color: #505052;
            font-family: 'Roboto', sans-serif;
        }
        .email-template img {
            width: 620px;
            height: 267px;
        }
        .email-template .email-content {
            width: 526px;
            margin: 36px auto 40px;
        }
        .email-template .email-content h1 {
            font-size: 26px;
            line-height: 150%;
            font-weight: 600;
            color: #7186FF;
            text-align: center;
        }
        .email-template .email-content p {
            margin-top: 16px;
        }
        .hight-light {
            color: #F05B29;
        }
        .link {
            color: #334DE2;
        }
        .contact {
            color: #505052;
            text-decoration: none;
        }
        .enter {
            display: block;
            width: 240px;
            height: 40px;
            margin: 32px auto 0;
            background: linear-gradient(260.01deg, #FFC10E 0%, #FFA901 100.02%);
            border-radius: 10px;
            font-size: 16px;
            line-height: 40px;
            font-weight: 700;
            color: #FFFFFF;
            text-align: center;
            text-decoration: none;
        }
        .enter:hover {
            background: #FFA901;
        }
    </style>
</head>

<body>
<div class="email-template">
    <img src="{{asset('images/mail/email-template.png')}}" alt="Ants media">
    <div class="email-content">
        <h1>{{@$title}}</h1>
        <p>Xin chào {{@$username}}</p>
        @if(@$type === \App\Models\Postgres\Store\Order::CHANGE_ORDER)
            <p>Đơn hàng <a>{{@$order_code}}</a> có sự thay đổi nội dung phát quảng cáo!</p>
        @elseif(@$type === \App\Models\Postgres\Store\Order::NEW_ORDER)
            <p>Đơn hàng <a>{{@$order_code}}</a> mới đã được tạo thành công!</p>
        @endif
        <p>Vui lòng truy cập link sau để xét duyệt: <a href="{{env('APP_DASHBOARD_ADMIN', '') . @$url}}" target="_blank">Đây</a></p>
        <p>Cảm ơn bạn đã sử dụng hệ thống booking quảng cáo Ants Media.</p>
        <a class="enter" href="{{env('APP_DASHBOARD_ADMIN', '') . @$url}}" target="_blank">Tiếp tục</a>
    </div>
</div>
</body>

</html>
