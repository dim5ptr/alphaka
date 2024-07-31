
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Include Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
         body {
            margin: 0;
            padding: 0;
        }
        .container-fluid {
            margin: 0;
            padding: 0;
        }
        #google {
            width: 60px;
        }   
    </style>
</head>
<body>
    <div class="container-fluid p-0 vh-100"> <!-- Ensure container fills viewport height -->
        <div class="row g-0 h-100"> <!-- Ensure row fills height -->
            <div class="col-md-6 d-flex justify-content-center align-items-center" style="background-color: #c1bff4;">
                <img src="images/register2.png" alt="" class="img-fluid"  style="padding-left: 100px;">
            </div>
            <div class="col-md-6 d-flex justify-content-center align-items-center" style="background-color: #c1bff4;">
                <div class="card p-5 form-container bg-white"> <!-- Add bg-white class here -->
                    <h2 class="text-center mb-4">SIGN UP!</h2>
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" name="email" class="form-control rounded-5 shadow" id="email" aria-describedby="emailHelp"/>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control rounded-5 shadow" id="password"/>
                        </div>
                        <div class="mb-4">
                            <label for="confirmpassword" class="form-label">Confirm Password</label>
                            <input type="password" name="confirmpassword" class="form-control rounded-5 shadow" id="confirmpassword"/>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                    <div class="text-center mt-4">
                        <p>Already have an account? <a href="/login" class="text-decoration-none">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Load jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Load Toastr.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@if (session('success_message'))
    <script>
        $(document).ready(function() {
            toastr.success('{{ session('success_message') }}');
        });
    </script>
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            $(document).ready(function() {
                toastr.error('{{ $error }}');
            });
        </script>
    @endforeach
@endif

</body>
</html>
