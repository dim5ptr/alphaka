<!DOCTYPE html>
<html>
<head>
    <style>
        /* Custom styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #AFC3FC;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .text-center {
            text-align: center;
        }
        .bg-blue {
            background-color: #AFC3FC;
            padding: 20px 0;
        }
        .inner-body {
            background-color: #ffffff;
            border-radius: 2px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015);
            padding: 20px;
            margin-top: 20px;
        }
        .footer {
            font-size: 12px;
            padding: 20px 0;
            color: #555;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        img {
            max-width: 100px;
            height: auto;
        }
        p {
            line-height: 1.6;
            color: #333;
        }
        h3 {
            color: #333;
        }
    </style>
    <title>Reset Password</title>
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
</head>
<body>
    <div class="bg-blue">
        <div class="container text-center">
            <img src="{{ asset('https://avatars.githubusercontent.com/u/156770999?s=200&v=4') }}" alt="{{ config('app.name') }}">
        </div>
        <div class="container">
            <div class="inner-body">
                <h2>Halo!</h2>
                <p>Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi untuk akun Anda.<br>
                Tekan tombol di bawah ini untuk mengatur ulang kata sandi.</p>
                <p class="text-center">
                    <a href="{{ env('APP_URL') }}/password/reset/{{ $token }}$request->email" class="btn">Reset Password</a>
                </p>
                <p>Tautan Pengaturan Ulang Kata Sandi ini akan kedaluwarsa: {{ session('expired_date') }} <br>
                Jika Anda tidak meminta pengaturan ulang kata sandi, abaikan saja pesan ini.</p>
                <p>Salam,<br>
                Sarastya Technology</p>
                <br>
                <hr>
                <p>Jika Anda kesulitan mengakses tombol "Reset Password", salin dan tempel URL di bawah ini ke browser web Anda: <a href="{{ env('APP_URL') }}/password/reset/{{ $token }}">{{ env('APP_URL') }}/password/reset/{{ $token }}</a></p>

            </div>
        </div>
        <div class="container text-center footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
