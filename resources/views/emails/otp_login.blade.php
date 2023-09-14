<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verifikasi OTP - Sirupat</title>
    <style>
        * {
            font-family: Helvetica, sans-serif;
            font-weight: 400;
            font-size: 14px;
            color: #000;
        }

        h3 {
            font-weight: 700;
            font-size: 20px;
            margin: 10px 0;
        }

        #container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        #header-email {
            background-color: #f4f4f4;
            text-align: center;
            padding: 2.5% 0;
        }

        .logo-wrapper {
            width: 50%;
            justify-content: center;
            align-items: center;
            margin: auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .brand-logo{
			display: block;
            margin: auto;
        }

        #content {
            padding: 0 5%;
            text-align: justify;
        }

        .badge {
            height: 30px;
            margin-top: 20px;
            text-align: center;
        }

        .badge-primary {
            background-color: #f06767;
            padding: 10px 25px;
            color: #fff;
            font-weight: 700;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0;
        }

        #footer {
            background-color: #f4f4f4;
            padding: 30px 0;
            text-align: center;
        }

        #footer span {
            display: block;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div id="container">
        <div id="header-email">
            <div class="logo-wrapper">
                <div class="brand-logo">
                    <img src="{{ asset('images/logo-sirupat-email.png') }}" style="width: 120px;" alt="">
                </div>
            </div>
        </div>
        <div id="content">
            <h3>Halo, {{ $name }}</h3>
            <p>
                Harap gunakan kode ini untuk verifikasi akun Anda. Jangan berikan kode ini kepada siapa pun. Ini adalah tindakan keamanan kami.
            </p>
            <div class="badge">
                <span class="badge-primary">{{ $otp }}</span>
            </div>
            <p>Kode ini akan hangus dalam 5 menit setelah email dikirimkan.</p>
        </div>
        <div id="footer">
            <span>SIRUPAT&nbsp;&copy;&nbsp;Copyright 2023</span>
        </div>
    </div>
</body>

</html>