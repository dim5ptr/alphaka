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
    <title>Email Verification</title>
</head>
<body>
    <div class="bg-blue">
        <div class="container text-center">
            <img src="{{ asset('https://avatars.githubusercontent.com/u/156770999?s=200&v=4') }}" alt="{{ config('app.name') }}">
        </div>
        <div class="container">
            <div class="inner-body">
                <h2>Halo!</h2>
                <p>Anda menerima email ini untuk memverifikasi email Anda.</p>
                <p class="text-center">
                <a href="{{ url('active/' . $token) }}" class="btn">Activation</a>
                </p>
                <p>Tautan Verifikasi email ini akan kedaluwarsa dalam waktu 60 menit.<br>
                Jika Anda tidak merasa membutuhkan verifikasi email, abaikan saja pesan ini.</p>
                <p>Salam,<br>
                Sarastya Technology</p>
                <br>
                <hr>
                <p>Jika Anda kesulitan mengakses tombol "Verifikasi Email", salin dan tempel URL di bawah ini ke browser web Anda: <a href="{{ url('active/' . $token) }}" class=""></a></p>
            </div>
        </div>
        <div class="container text-center footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
