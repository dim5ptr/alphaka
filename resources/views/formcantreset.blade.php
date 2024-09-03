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
            height: 100%;
            margin: 0;
            justify-content: center;
            align-content: center;
        }

        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .container img {
            max-width: 25%;
            height: auto;
            flex: 1 1 70%;
            margin-right: 20px;
        }

        .text {
            flex: 1 1 30%;
            max-width: 35%;
            box-sizing: border-box;
            align-items: center;
        }

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

        a:hover {
            color: #365AC2;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .container img {
                max-width: 50%;
                margin-right: 0;
                margin-bottom: 20px;
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
            <p>Token tidak aktif, </br> 
                <span id="countdown"></span>
                <a href="{{ route('password.request') }}" id="resetLink" style="display: none;"><b>kirim ulang email</b></a> untuk mendapatkan token baru.
            </p>
        </div>
    </div>

    <script>
        let countdownTime = 60; // 60 seconds countdown
        let countdownElement = document.getElementById('countdown');
        let resetLink = document.getElementById('resetLink');

        // Reset the countdown start time whenever the page is loaded
        let countdownStart = Date.now();
        localStorage.setItem('countdownStart', countdownStart);

        function updateCountdown() {
            // Calculate the time difference
            let elapsed = Math.floor((Date.now() - countdownStart) / 1000);
            let remainingTime = countdownTime - elapsed;

            if (remainingTime > 0) {
                countdownElement.innerText = `Coba lagi dalam ${remainingTime} detik.`;
            } else {
                clearInterval(countdownInterval);
                countdownElement.style.display = 'none';
                resetLink.style.display = 'inline';
                localStorage.removeItem('countdownStart'); // Remove the countdown start time when done
            }
        }

        // Update the countdown every second
        let countdownInterval = setInterval(updateCountdown, 1000);

        // Run the function initially to set the correct countdown time
        updateCountdown();
    </script>
</body>
</html>
