<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
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
        h2 {
            color: #333;
        }
    </style>
    <title>Your License Key</title>
</head>
<body>
    <div class="bg-blue">
        <div class="container text-center">
            <img src="{{ asset('https://avatars.githubusercontent.com/u/156770999?s=200&v=4') }}" alt="{{ config('app.name') }}">
        </div>
        <div class="container">
            <div class="inner-body">
                <h2>Halo!</h2>
                <p>Yth. {{ $user_email ?? 'Pelanggan Terhormat' }}</p>
                <p>Terima kasih telah melakukan pembelian. Berikut adalah informasi lisensi Anda:</p>
                <p><strong>License Key:</strong> {{ $license_key }}</p>
                <p><strong>Order Key:</strong> {{ $order_key }}</p>
                <p>Jika Anda memiliki pertanyaan atau membutuhkan bantuan lebih lanjut, jangan ragu untuk menghubungi kami.</p>
                <p>Salam hangat,<br>
                Sarastya Technology</p>
                <br>
            </div>
        </div>
        <div class="container text-center footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
