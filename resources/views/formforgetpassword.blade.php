<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reset Password</title>
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <!-- Include toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #365AC2, #AFC3FC);
            font-family: 'Poppins', sans-serif;
        }
        .container-fluid {
            max-width: 400px;
        }
        .reset-password-box {
            width: 100%;
            padding: 40px;
            background-color: #e9edf9;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .reset-password-box h2 {
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 20px;
            color: #365AC2;
        }
        .alert {
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
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
    </style>
</head>
<body>
    <div class="container-fluid d-flex justify-content-center align-items-center vh-100">
        <div class="reset-password-box">
            <h2>Reset Password</h2>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                    <br><a href="{{ route('login') }}" class="btn btn-primary mt-3">Go to Login</a>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="change_type" value="reset">
                <input type="hidden" id="email" name="email" class="form-control" value="{{session('email')}}" required>
                <input type="hidden" id="token" name="reset_token" class="form-control" value="{{session('reset_token')}}" required>
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_new_password" class="form-label">Confirm New Password</label>
                    <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-md px-5 rounded-pill text-light" style="background-color: #2f58cc;">
                    Save Password
                </button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Load jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Load toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @if (session('status'))
    <script>
        $(document).ready(function() {
            toastr.success('{{ session('status') }}');
        });
    </script>
    @endif
    @if ($errors->any())
    <script>
        $(document).ready(function() {
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        });
    </script>
    @endif
</body>
</html>
