<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Failed Reset</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <style>
        body {
            background-color: #ffffff;
            justify-content: center;
            width: 100vh;
        }

        .container {
            display: flex;
            margin-top: 15%;
            margin-left: 50%;
            max-width: 100%;
        }

        .text {
            width: 50%;
            margin-left: 5%;
        }

        img {
            width: 50%;
            height: auto;
        }

        h1{
            font-weight: bold;
            color: #365AC2;
            font-size: 10vh;
            margin-top: 25%;
        }

        p, a{
            font-weight: 500;
            color: #030d2a8e;
            font-size: 2.3vh;
            padding-top: 8%;
            transform: 0.3s color;
        }

        a:hover {
            color: #365AC2;
        }


    </style>
</head>
<body>
    <div class="container">
            <img src="img/H.jpg">
        <div class="text">
            <h1>Oops!</h1>
            @if(session('error'))
                <p>{{ session('error') }}</p>
            @endif
            <p>Token tidak aktif, </br> <a href="{{ route('password.request') }}">kirim ulang email</a> untuk mendapat token baru.</p>
        </div>
    </div>
</body>
</html>
