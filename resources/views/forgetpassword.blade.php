<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .form-container {
            max-width: 400px; 
        }
    </style>
</head>
<body>
    <div class="container-fluid d-flex justify-content-center align-items-center vh-100" style="background-color: #c1bff4;">
        <div class="card p-5 form-container bg-white">
            <h2 class="text-center mb-4">FORGOT PASSWORD</h2>
            <form id="forgetPasswordForm" action="{{ route('forgetpassword') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required />
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
                </div>
            </form>
            <div class="text-center mt-4">
                <p>Remembered your password? <a href="{{ route('login') }}" class="text-decoration-none">Sign In</a></p>
                <p>Don't have an account yet? <a href="{{ route('register') }}" class="text-decoration-none">Register</a></p>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
