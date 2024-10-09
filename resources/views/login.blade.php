<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>login</title>

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
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100%;
            max-width: 1100px;
        }

        .login-box {
            display: flex;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            margin: 50px;
        }

        .left, .right {
            flex: 1;
            padding: 40px;
        }

        .left {
            background-color: #e9edf9;
            color: #365AC2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .left img {
            width: 100%;
            max-width: 400px;
            height: auto;
            margin-bottom: 20px;
        }

        .right {
            background: #365AC2;
            color: #e9edf9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .right h2 {
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 20px;
            color: #e9edf9;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
            width: 100%;
        }

        .input-group input {
            outline: none;
            width: 400px;
            height: 50px;
            padding: 18px;
            padding-left: 50px;
            border: 1px solid #ccc;
            background: #e9edf9;
            border-radius: 30px;
            font-size: 1rem;
        }

        /* Style untuk toggle button dan ikon */
.input-group .btn {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #365AC2;
    font-size: 1.2rem;
}

.input-group .btn i {
    font-size: 1rem;
}

/* Saat tombol ditekan */
.input-group .btn:focus {
    outline: none;
}

/* Style untuk input di mobile */
@media (max-width: 480px) {
    .input-group .btn {
        right: 10px;
        font-size: 1rem;
    }
}

        .input-group .icon  {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #365AC2;
        }

        .forgot-password {
            text-align: right;
            width: 100%;
            margin-top: -10px;
            margin-bottom: 20px;
        }

        .forgot-password a {
            color: #fff;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        input[type="submit"] {
            width: 100%;
            padding: 15px;
            background: #e9edf9;
            color: #365AC2;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 10px;
            transition: background 0.3s;
        }

        input[type="submit"]:hover {
            background: #AFC3FC;
            color: rgb(0, 0, 0);
        }

        .google-form{
            margin-top: 5%;
            padding: 5px;
        }

        .google-form a{
            margin-left: 2%;
            color: #c3e6cb;
            text-decoration: none;
        }
      .google {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 12px;
            color: #e9edf9;
            border: 2px solid #e9edf9;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: background 0.3s;
            text-decoration: none;
        }

      .google:hover {
            background: #9fb7ff7f;
        }

        .register-link {
            text-align: center;
            margin-top: 10px;
        }

        .register-link a {
            color: #afc3fc;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            width: 100%;
            text-align: left;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-size: 0.9rem;
            position: relative;
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

        .alert .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            color: inherit;
        }

        /* Media Queries for Responsiveness */
@media (max-width: 768px) {
    .login-box {
        flex-direction: grid;
        margin: 50px;
    }


    .left, .right {
        padding: 20px;
    }

    .input-group input {
        width: 100%;
    }



}

@media (max-width: 480px) {

    .left{
        display: none;
    }

    .right h2 {
        font-size: 2rem;
    }

    .input-group input {
        padding: 15px;
        font-size: 0.9rem;
    }

    .input-group .icon {
        font-size: 0.8rem;
        left: 10px;
    }

    .input-group input {
    padding-left: 30px; /* Menambah jarak antara ikon dan teks input */
}

}

    </style>
</head>
<body>
<div class="container">
    <div class="login-box">
        <div class="left">
            <img src="{{ asset('img/B.png') }}" alt="Welcome Image">
        </div>
        <div class="right">
            <h2>Login</h2>
            @if (session('success'))
            <div class="alert alert-success" id="alert-success">
                {{ session('success') }}
                <span class="close-btn" onclick="closeAlert('alert-success')">&times;</span>
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger" id="alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <span class="close-btn" onclick="closeAlert('alert-danger')">&times;</span>
            </div>
            @endif

            <form id="loginForm" action="{{ route('login') }}" method="post">
                @csrf
                <div class="input-group">
                    <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                    <span class="icon"><i class="fas fa-envelope"></i></span>
                    <div id="emailError" class="error-message"></div>
                </div>
                <div class="input-group">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <span class="icon"><i class="fas fa-lock"></i></span>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                            <i class="fa fa-eye"></i>
                        </button>
                    <div id="passwordError" class="error-message"></div>
                </div>

                <div class="forgot-password">
                    <a href="{{ route('password.request') }}">Lupa Password?</a>
                </div>
                <input type="submit" value="login" class="btn-login">
            </form>

            <br>
            <div class="register-link">
                <p>Belum punya akun? <a href="{{ route('register') }}">Register Sekarang</a></p>
            </div>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        // Toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // Toggle icon
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        emailInput.addEventListener('input', validateEmail);
        passwordInput.addEventListener('input', validatePassword);

        function validateEmail() {
            const emailError = document.getElementById('emailError');
            const emailValue = emailInput.value.trim();
            if (!emailValue) {
                emailError.textContent = 'Email is required.';
            } else if (!validateEmailFormat(emailValue)) {
                emailError.textContent = 'Please enter a valid email address.';
            } else {
                emailError.textContent = '';
            }
        }

        function validatePassword() {
            const passwordError = document.getElementById('passwordError');
            const passwordValue = passwordInput.value.trim();
            if (!passwordValue) {
                passwordError.textContent = 'Password is required.';
            } else {
                passwordError.textContent = '';
            }
        }

        function validateEmailFormat(email) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailPattern.test(email);
        }
        // Hide success alert after 5 seconds
        const successAlert = document.getElementById('alert-success');
        if (successAlert) {
            setTimeout(function() {
                successAlert.style.opacity = 0;
                setTimeout(function() {
                    successAlert.style.display = 'none';
                }, 500);
            }, 5000);
        }

        // Hide error alert after 5 seconds
        const errorAlert = document.getElementById('alert-danger');
        if (errorAlert) {
            setTimeout(function() {
                errorAlert.style.opacity = 0;
                setTimeout(function() {
                    errorAlert.style.display = 'none';
                }, 500);
            }, 5000);
        }
    });

    function closeAlert(alertId) {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.style.opacity = 0;
            setTimeout(function() {
                alert.style.display = 'none';
            }, 500);
        }
    }
</script>
</body>
</html>
