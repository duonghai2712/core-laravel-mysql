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
        @if(@$type === \App\Models\Postgres\Admin\Device::BLOCK_DEVICE)
            <p>Thiết bị <a href="#">{{@$deivce_name}}</a> đã bị khóa, media hiện tại trên thiết bị {{@$status}}</p>
            <p>Tất cả các bên sẽ không thể đặt quảng cáo vào đây cho đến khi thiết bị được mở khóa trở lại!</p>
            <p>Vui lòng liên hệ admin hệ thống nếu muốn mở khóa thiết bị!</p>
        @elseif(@$type === \App\Models\Postgres\Admin\Device::OPEN_DEVICE)
            <p>Thiết bị <a href="#">{{@$deivce_name}}</a> đã được mở khóa thành công.</p>
            <p>Tất cả các bên có thể đặt quảng cáo vào đây, đồng thời cửa hàng cũng có thể thêm media trên thiết bị.</p>
        @endif
        <p>Cảm ơn bạn đã sử dụng hệ thống booking quảng cáo Ants Media.</p>
        <a class="enter" href="{{env('APP_DASHBOARD_STORE', '')}}" target="_blank">Tiếp tục</a>
    </div>
</div>
</body>

</html>
