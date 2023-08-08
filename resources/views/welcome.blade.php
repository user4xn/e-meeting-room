<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css?family=Vibur" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <style>
        body {
            background-color: #151845;
            padding: 0;
            margin: 0;
        }

        .container {
            height: 370px;
            width: 370px;
            /* border: 1px solid #fff; */
            position: absolute;
            margin: auto;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
        }

        .moon {
            background-color: #39beff;
            height: 170px;
            width: 170px;
            border-radius: 50%;
            position: absolute;
            margin: auto;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            overflow: hidden;
        }

        .crater {
            background-color: #31b4ff;
            height: 30px;
            width: 30px;
            border-radius: 50%;
            position: relative;
        }

        .crater:before {
            content: "";
            position: absolute;
            height: 25px;
            width: 25px;
            border-radius: 50%;
            box-shadow: -5px 0 0 2px #1ca4f9;
            top: 2px;
            left: 7px;
        }

        .crater1 {
            top: 27px;
            left: 90px;
            transform: scale(0.9);
        }

        .crater2 {
            bottom: 15px;
            left: 61px;
            transform: scale(0.6);
        }

        .crater3 {
            left: 15px;
            transform: scale(0.75);
        }

        .crater4 {
            left: 107px;
            top: 32px;
            transform: scale(1.18);
        }

        .crater5 {
            left: 33px;
            bottom: 4px;
            transform: scale(0.65);
        }

        .shadow {
            height: 190px;
            width: 190px;
            box-shadow: 21px 0 0 5px rgba(0, 0, 0, 0.15);
            border-radius: 50%;
            position: relative;
            bottom: 157.5px;
            right: 46px;
        }

        .eye {
            background-color: #161616;
            height: 12px;
            width: 12px;
            position: relative;
            border-radius: 50%;
        }

        .eye-l {
            bottom: 255px;
            left: 59px;
        }

        .eye-r {
            bottom: 267px;
            left: 101px;
        }

        .mouth {
            height: 5px;
            width: 10px;
            border: 3px solid #161616;
            position: relative;
            bottom: 262px;
            left: 79px;
            border-top: none;
            border-radius: 0 0 10px 10px;
        }

        .blush {
            background-color: #1ca4f9;
            height: 7.5px;
            width: 7.5px;
            position: relative;
            border-radius: 50%;
        }

        .blush1 {
            bottom: 273px;
            left: 50px;
        }

        .blush2 {
            bottom: 281px;
            left: 115px;
        }

        .orbit {
            height: 280px;
            width: 280px;
            /* border: 1px solid #fff; */
            border-radius: 50%;
            position: absolute;
            margin: auto;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            animation: spin 10s infinite linear;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        .rocket {
            background-color: #fafcf7;
            height: 50px;
            width: 25px;
            border-radius: 50% 50% 0 0;
            position: relative;
            left: -11px;
            top: 115px;
        }

        .rocket:before {
            content: "";
            position: absolute;
            background-color: #39beff;
            height: 20px;
            width: 55px;
            border-radius: 50% 50% 0 0;
            z-index: -1;
            right: -15px;
            bottom: 0;
        }

        .rocket:after {
            content: "";
            position: absolute;
            background-color: #39beff;
            height: 4px;
            width: 15px;
            border-radius: 0 0 2px 2px;
            bottom: -4px;
            left: 4.3px;
        }

        .window {
            background-color: #151845;
            height: 10px;
            width: 10px;
            border: 2px solid #b8d2ec;
            border-radius: 50%;
            position: relative;
            top: 17px;
            left: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="moon">
            <div class="crater crater1"></div>
            <div class="crater crater2"></div>
            <div class="crater crater3"></div>
            <div class="crater crater4"></div>
            <div class="crater crater5"></div>
            <div class="shadow"></div>
            <div class="eye eye-l"></div>
            <div class="eye eye-r"></div>
            <div class="mouth"></div>
            <div class="blush blush1"></div>
            <div class="blush blush2"></div>
        </div>
        <div class="orbit">
            <div class="rocket">
                <div class="window"></div>
            </div>
        </div>
    </div>
</body>

</html>