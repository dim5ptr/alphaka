<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .form-container {
            max-width: 400px; 
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0 vh-100">
        <div class="row g-0 h-100">
            <div class="col-md-6 d-flex justify-content-center align-items-center" style="background-color: #c1bff4;">
                <img src="images/loginnn2.svg" alt="" class="img-fluid" style="padding-left: 100px;">
            </div>
            <div class="col-md-6 d-flex justify-content-center align-items-center" style="background-color: #c1bff4;">
                <div class="card p-5 form-container bg-white">
                    <h2 class="text-center mb-4">SIGN IN!</h2>
                    <form id="loginForm" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required />
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="password" required />
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                    <div class="text-center mt-4">
                        <p><a href="{{ route('forgetpassword') }}" class="text-decoration-none">Forgot your password?</a></p>
                        <p>Don't have an account yet? <a href="{{ route('register') }}" class="text-decoration-none">Register</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
