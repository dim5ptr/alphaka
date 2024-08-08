<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
           * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #365AC2, #AFC3FC);
            padding: 20px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 1100px;
        }

        .reset-password-box {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background-color: #e9edf9;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .reset-password-box h2 {
            font-size: 2rem;
            font-weight: 900;
            margin-bottom: 20px;
            color: #365AC2;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
            width: 100%;
        }

        .input-group input {
            outline: none;
            width: 100%;
            height: 50px;
            padding: 18px;
            padding-left: 50px;
            border: 1px solid #ccc;
            background: #fff;
            border-radius: 30px;
            font-size: 1rem;
        }

        .input-group .icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #365AC2;
        }

        input[type="submit"] {
            width: 100%;
            padding: 18px;
            background: #365AC2;
            color: #e9edf9;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 10px;
            transition: background 0.3s;
        }

        input[type="submit"]:hover {
            background: #2e4d91;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #365AC2;
        }

        .form-group input {
            width: 100%;
            padding: 15px 20px;
            border-radius: 20px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: #365AC2;
            color: #e9edf9;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #2e4d91;
        }

        .back-to-login {
            display: inline-block;
            margin-top: 20px;
            color: #365AC2;
            text-decoration: none;
            font-weight: 600;
        }

        .back-to-login:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .reset-password-box {
                padding: 20px;
            }

            .reset-password-box h2 {
                font-size: 1.8rem;
            }

            .form-group input {
                padding: 12px 15px;
            }

            input[type="submit"] {
                padding: 15px;
            }

            .btn {
                padding: 15px;
            }
        }

        @media (max-width: 480px) {
            .reset-password-box h2 {
                font-size: 1.5rem;
            }

            .form-group input {
                padding: 10px 12px;
            }

            input[type="submit"] {
                padding: 12px;
            }

            .btn {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">

        <form method="POST" action="{{ route('forgetpassword') }}">
            @csrf

            <div class="reset-password-box">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif


                    <div id="alertE" class="alert" style="display: none;"></div>


                <h2>Lupa Password</h2>

                <div class="form-group">
                    <label for="user_email">Alamat Email</label>
                    <div class="input-group">
                        <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required />
                    </div>
                </div>

                <button type="submit" class="btn">
                    Kirim Tautan Reset Password
                </button>

                <a href="{{ route('login') }}" class="back-to-login">Kembali ke Login</a>
            </div>
        </form>
    </div>

    <script>
       document.addEventListener("DOMContentLoaded", function() {
            var alertDiv = document.getElementById('alertE');

            // Menampilkan pesan error jika ada
            @if ($errors->any())
                var errors = @json($errors->all());
                if (errors.length > 0) {
                    alertDiv.innerHTML = '<ul>' + errors.map(function(error) {
                        return '<li>' + "Alamat email tidak tersedia, soriee" + '</li>';
                    }).join('') + '</ul>';
                    alertDiv.classList.add('alert-danger');
                    alertDiv.style.display = 'block';
                }
            @endif

            @if (session('error'))
                var sessionError = @json(session('error'));
                if (sessionError) {
                    alertDiv.textContent = "Alamat tidak ada, soriee";
                    alertDiv.classList.add('alert-danger');
                    alertDiv.style.display = 'block';
                }
            @endif
        });
    </script>


</body>
</html>

