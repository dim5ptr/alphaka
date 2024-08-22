<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        img {
            width: 130px;
            height: 130px;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(to right, #3d38e8, #3498db);
            color: #2155CD;
            font-weight: medium;
            
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
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
        a button{
            color: #E8F9FD;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        h1 {
            font-weight: 800;
        }
        p {
            
            font-size: 20px;
        }

        .button-9 {
  appearance: button;
  backface-visibility: hidden;
  background-color: #405cf5;
  border-radius: 6px;
  border-width: 0;
  box-shadow: rgba(50, 50, 93, .1) 0 0 0 1px inset,rgba(50, 50, 93, .1) 0 2px 5px 0,rgba(0, 0, 0, .07) 0 1px 1px 0;
  box-sizing: border-box;
  color: #fff;
  cursor: pointer;

  font-size: 100%;
  height: 44px;
  line-height: 1.15;
  margin: 12px 0 0;
  outline: none;
  overflow: hidden;
  padding: 0 25px;
  position: relative;
  text-align: center;
  text-transform: none;
  transform: translateZ(0);
  transition: all .2s,box-shadow .08s ease-in;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  width: 100%;
}

.button-9:disabled {
  cursor: default;
}

.button-9:hover {
  background-color: #065dd8;
  transform: translateY(-2px);
}
    </style>
</head>
<body>
    <div class="container">
    <img src="{{ asset('img/arrow.png') }}" alt="Email img">
        <h1>Konfirmasi Email</h1>
        @if(session('success'))
            <p>{{ session('success') }}</p>
        @endif
        <p>Silakan cek email Anda untuk link konfirmasi. Jika Anda tidak menerima email, silakan periksa<br>folder spam atau coba daftarkan ulang.</p>
        <a href="{{ route('register') }}"><button class="button-9" role="button">Kembali ke halaman register</button></a>
    </div>
</body>
</html>