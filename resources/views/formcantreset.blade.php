<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Failed Reset</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <style>
       html, body {
        height: 100%; /* Full height for body and html */
        margin: 0; /* Remove default margin */
        justify-content: center;
        align-content: center;
       }

        .wrapper {
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            height: 100vh; /* Full viewport height */
        }


       /* Style untuk kontainer utama */
       .container {
            display: flex;
            flex-wrap: wrap; /* Allow wrapping to new lines */
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
        }

        /* Style untuk gambar */
        .container img {
            max-width: 25%;
            height: auto; /* Maintain aspect ratio */
            flex: 1 1 70%; /* Grow and shrink, take 50% width */
            margin-right: 20px; /* Spacing between image and text */
        }

        /* Style untuk teks */
        .text {
            flex: 1 1 30%; /* Grow and shrink, take 50% width */
            max-width: 35%;
            box-sizing: border-box;
            align-items: center;
        }

        /* Gaya untuk heading dan paragraf */
        .text h1 {
            font-size: 3rem;
            margin-bottom: 12px;
            font-weight: bold;
            color: #365AC2;
        }

        .text p, a {
            font-size: 1.2rem;
            line-height: 1.5;
            margin-bottom: 20px;
            text-align: left;
            font-weight: 500;
            color: #030d2a8e;
            transform: 0.3s ease color;
        }

        /* Link styling */
        a:hover {
            color: #365AC2;
        }

        /* Media Query untuk perangkat mobile */
        @media (max-width: 768px) {
            .container {
                flex-direction: column; /* Stack items vertically */
            }

            .container img {
                max-width: 50%; /* Full width on mobile */
                margin-right: 0; /* Remove margin on mobile */
                margin-bottom: 20px; /* Add spacing below image */
            }

            .text {
                max-width: 100%;
                text-align: center;
            }

            .text h1 {
                font-size: 1.5rem;
            }

            .text p, a {
                font-size: 0.875rem;
                text-align: center;

            }
}

    </style>
</head>
<body>
    <div class="container">
        <img src="img/H.jpg" alt="Image">
        <div class="text">
            <h1>Oops!</h1>
            @if(session('error'))
                <p>{{ session('error') }}</p>
            @endif
            <p>Token tidak aktif, </br> <a href="{{ route('password.request') }}">kirim ulang email</a> untuk mendapat token baru.</p>
        </div>
    </div>
</body>
</html>
