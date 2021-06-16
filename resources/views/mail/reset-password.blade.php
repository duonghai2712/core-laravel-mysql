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
        <h1>Đặt lại mật khẩu truy cập Ants Media</h1>
        <p>Xin chào {{@$username}}</p>
        <p>
            Ants Media nhận được thông báo quên mật khẩu từ bạn.<br>
            Vui lòng truy cập vào <a class="link" href="{{@$type === \App\Models\Postgres\Admin\Account::ADMIN ? env('APP_DASHBOARD_ADMIN', '') . '/reset-password?token=' . @$token : env('APP_DASHBOARD_STORE', '') . '/reset-password?token=' . @$token}}" target="_blank">đây</a> để đặt lại mật khẩu mới.<br>
            (Link hoạt động tối đa 5 phút kể từ khi tạo yêu cầu)
        </p>
        <p>Cảm ơn bạn đã sử dụng hệ thống booking quảng cáo Ants Media</p>
        <p>Mọi thắc mắc và góp ý, quý khách vui lòng liên hệ với ADT Creative qua email: <a class="contact" href="mailto:hello@adtgroup.vn" target="_blank">hello@adtgroup.vn</a> hoặc Hotline: <a class="contact" href="tel:0366370916" target="_blank">0366.370.916</a>. Đội ngũ ADT Creative luôn sẵn sàng hỗ trợ bạn</p>
        <a class="enter" href="{{@$type === \App\Models\Postgres\Admin\Account::ADMIN ? env('APP_DASHBOARD_ADMIN', '') . '/reset-password?token=' . @$token : env('APP_DASHBOARD_STORE', '') . '/reset-password?token=' . @$token}}" target="_blank">Tiếp tục</a>
    </div>
</div>
</body>

</html>
