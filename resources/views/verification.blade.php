<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(to right, #3d38e8, #3498db);
            color: white;
        }
        .container {
            background: rgba(0, 0, 0, 0.8);
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
        }
        .loader {
            border: 8px solid #f3f3f3;
            border-radius: 50%;
            border-top: 8px solid #3498db;
            width: 60px;
            height: 60px;
            animation: spin 2s linear infinite;
            margin: auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Konfirmasi Email</h1>
        @if(session('success'))
            <p>{{ session('success') }}</p>
        @endif
        <p>Silakan cek email Anda untuk link konfirmasi. Jika Anda tidak menerima email, silakan periksa folder spam atau coba daftarkan ulang.</p>
        <p>Kembali ke <a href="{{ route('register') }}">halaman register</a>.</p>
    </div>
</body>
</html>
