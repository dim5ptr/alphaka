<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #AFC3FC, #365AC2);
            height: 100vh;
        }
        .container {
            padding-top: 5%;
            max-width: 75%;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            margin: 50px auto;
        }
        .form-container {
            background: #365AC2;
            width: 50%;
            height: 100%;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: white;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 35px;
            font-weight: 900;
        }
        .form-group {
            margin-left: 20%;
            margin-bottom: 15px;
        }
        .form-group .input-group {
            position: relative;
            width: 100%;
        }
        .form-group .input-group i {
            position: absolute;
            left: 20px;
            top: 47%;
            transform: translateY(-50%);
            color: #365AC2;
        }
        .form-group .input-group input {
            outline: none;
            font-size: medium;
            width: calc(80% - 20px);
            height: 50px;
            padding: 10px;
            padding-left: 50px;
            border: 1px solid #ccc;
            border-radius: 30px;
            box-sizing: border-box;
            color: #090909;
        }
        .form-group .input-group input:hover {
            box-shadow: 0 0 5px #AFC3FC;
        }

        .input-group .btn {
            position: absolute;
            top: 50%;
            right: 35%;
            background: none;
            border: none;
            cursor: pointer;
            color: #365AC2;
            font-size: 1rem;
        }

        .input-group .btn i {
            font-size: 1rem;
        }


        .btn-primary {
            font-weight: bolder;
            width: 60%;
            height: 40px;
            margin-left: 20%;
            margin-top: 10px;
            padding: 10px;
            background-color: #c4d3ff;
            font-size: medium;
            color: #365AC2;
            border: none;
            border-radius: 30px;
            cursor: pointer;
        }
        .btn-primary:hover {
            color: #0e0e0e;
            background-color: #90b3d8;
        }
        .form-text {
            text-align: center;
            margin-top: 10px;
        }
        .form-text a {
            font-weight: bolder;
            color: #AFC3FC;
            text-decoration: none;
        }
        .form-text a:hover {
            text-decoration: underline;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
        }
        .alert-danger ul {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }
        .pict {
            background-color: white;
            width: 50%;
            display: flex;
            justify-content: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            align-items: center;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .pict img {
            max-width: 70%;
        }
        .error {
            color: rgb(255, 255, 255);
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

    .pict {
        display: none;
    }

    .container{
        max-width: 100%;
    }

    .form-container {
        border-radius: 15px;
        margin: 10px;
        width: 500px;
        padding: 5px;
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
        <div class="form-container">
            <h2>Daftar</h2>
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
                <div class="form-group">
                    <div class="input-group">
                        <i class="fa fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                    </div>
                    <span id="email-error" class="error"></span>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <i class="fa fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <button type="button" class="btn" id="toggle-password">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <span id="password-error" class="error"></span>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <i class="fa fa-lock"></i>
                        <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required>
                        <button type="button" class="btn" id="toggle-confirm-password">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <span id="password-confirmation-error" class="error"></span>
                </div>

                <button type="submit" class="btn btn-primary">Daftar</button>
            </form>
            <p class="form-text">Sudah punya akun? <a href="{{ route('login') }}">Login disini</a></p>
        </div>
        <div class="pict">
            <img src="{{ asset('img/A.jpg') }}" alt="">
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
            const confirmPasswordField = document.getElementById('confirmpassword');
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
