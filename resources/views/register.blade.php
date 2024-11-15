<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Register</title>

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
            background: linear-gradient(135deg, #AFC3FC, #365AC2);
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
            background-color: white;
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
            color: white;
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
            background: #ffffff;
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

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background: white;
            color: #365AC2;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 10px;
            transition: background 0.3s;
        }

        button[type="submit"]:hover {
            background: #AFC3FC;
            color: rgb(0, 0, 0);
        }

        .google-form{
            margin-top: 5%;
            padding: 5px;
        }

        .google-form span{
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

        button[type="submit"]:hover {
            background: #AFC3FC;
            color: rgb(0, 0, 0);
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
        <div class="right">
            <h2>Register</h2>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if (session('success_message'))
            <div class="alert alert-success">
                {{ session('success_message') }}
            </div>
            @endif

            <form action="{{ route('register') }}" method="POST" id="registerForm">
                @csrf
                <div class="input-group">
                    <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                    <span class="icon"><i class="fas fa-envelope"></i></span>
                    <div id="email-error" class="error"></></div>
                </div>
                <div class="input-group">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <span class="icon"><i class="fas fa-lock"></i></span>
                        <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                            <i class="fa fa-eye"></i>
                        </button>
                    <div id="password-error" class="error"></div>
                </div>
                <div class="input-group">
                    <input type="password" id="password_confirmation" name="confirmpassword" placeholder="Confirm Password" required>
                    <span class="icon"><i class="fa fa-lock"></i></span>
                        <button type="button" class="btn" id="toggle-confirm-password">
                            <i class="fa fa-eye"></i>
                        </button>
                    <div id="password-confirmation-error" class="error"></div>
                </div>

                <button type="submit" class="btn btn-primary">Register</button>

                <div class="google-form">
                    <a class="google" href="{{ route('auth.google') }}">
                        <i class="fab fa-google"></i>
                        <span class="google-login">Login with Google</span>
                    </a>
                </div>
            </form>

            <br>
            <div class="register-link">
                <p class="form-text">Sudah punya akun? <a href="{{ route('login') }}">Login disini</a></p>
            </div>
        </div>
        <div class="left">
            <img src="{{ asset('img/A.jpg') }}" alt="Welcome Image">
        </div>
    </div>
</div>

<script>
    document.getElementById('toggle-password').addEventListener('click', function () {
    const passwordField = document.getElementById('password');
    const passwordIcon = this.querySelector('i');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
    }
});

document.getElementById('toggle-confirm-password').addEventListener('click', function () {
    const confirmPasswordField = document.getElementById('password_confirmation');
    const confirmPasswordIcon = this.querySelector('i');

    if (confirmPasswordField.type === 'password') {
        confirmPasswordField.type = 'text';
        confirmPasswordIcon.classList.remove('fa-eye');
        confirmPasswordIcon.classList.add('fa-eye-slash');
    } else {
        confirmPasswordField.type = 'password';
        confirmPasswordIcon.classList.remove('fa-eye-slash');
        confirmPasswordIcon.classList.add('fa-eye');
    }
});
</script>

<script>
document.getElementById('email').addEventListener('input', function () {
    const email = this.value;
    const errorElement = document.getElementById('email-error');

    // Simple email validation regex
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(email)) {
        errorElement.textContent = 'Please enter a valid email address!';
    } else {
        errorElement.textContent = '';
    }
});

document.getElementById('password').addEventListener('input', function () {
    const password = this.value;
    const errorElement = document.getElementById('password-error');

    if (password.length < 8) {
        errorElement.textContent = 'Password must be at least 8 characters long!';
    } else {
        errorElement.textContent = '';
    }
});

document.getElementById('password_confirmation').addEventListener('input', function () {
    const password = document.getElementById('password').value;
    const passwordConfirmation = this.value;
    const errorElement = document.getElementById('password-confirmation-error');

    if (password !== passwordConfirmation) {
        errorElement.textContent = 'Passwords do not match!';
    } else {
        errorElement.textContent = '';
    }
});

document.getElementById('registerForm').addEventListener('submit', function (event) {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;

    const emailErrorElement = document.getElementById('email-error');
    const passwordErrorElement = document.getElementById('password-error');
    const passwordConfirmationErrorElement = document.getElementById('password-confirmation-error');

    // Simple email validation regex
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(email)) {
        emailErrorElement.textContent = 'Please enter a valid email address!';
        event.preventDefault();
    } else {
        emailErrorElement.textContent = '';
    }

    if (password.length < 8) {
        passwordErrorElement.textContent = 'Password must be at least 8 characters long!';
        event.preventDefault();
    } else {
        passwordErrorElement.textContent = '';
    }

    if (password !== passwordConfirmation) {
        passwordConfirmationErrorElement.textContent = 'Passwords do not match!';
        event.preventDefault();
    } else {
        passwordConfirmationErrorElement.textContent = '';
    }
});
</script>
</body>
</html>
