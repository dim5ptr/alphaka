<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Verify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <!-- Include toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .form-container {
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-md-6 d-flex justify-content-center align-items-center" style="background-color: #c1bff4;">
                <img src="images/userverify.svg" alt="Verify Image" class="img-fluid">
            </div>
            <div class="col-md-6 d-flex justify-content-center align-items-center" style="background-color: #c1bff4;">
                <div class="card p-5 form-container">
                    <h2 class="text-center mb-4">FOR YOUR VERIFICATION. PLEASE, CHECK YOUR E-MAIL</h2>
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}" class="btn btn-primary">Back To Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Load jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Load toastr JS -->
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
