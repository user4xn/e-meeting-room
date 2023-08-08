<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @import url('https://fonts.cdnfonts.com/css/roboto');
        * {
            font-family: helvetica;
            font-weight: 400;
            font-size: 14px;
            color: #000000;
        }
        .box-container{
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
        img{
            margin-bottom: 25px;
            width: 180px;
        }
        p{
            margin-top: 10px;
            display: block;
        }
        .btn-verification {
            width: 120px;
            display: block;
            line-height: 40px;
            text-align: center;
            background-color: #0068FF;
            text-decoration: none;
            color: #fff;
            font-size: 16px;
            border-radius: 4px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="box-container">
        <h1>Konfirmasi Email</h1>
        <p>verifikasi email kamu untuk mendapatkan akses ke e-meeting.</p>
        <a class="btn-verification">{{$otp}}</a>
        <p>abaikan email ini jika kamu tidak merasa dalam anggota e-meeting.</p>
    </div>
</body>

</html>