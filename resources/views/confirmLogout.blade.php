<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Logout Confirmation</title>

    <style>
        /* CSS Anda disini */
        html, body {
            height: auto;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #d5def7;
        }

        body {
            transition: margin-left 0.3s;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: -270px;
            background-color: white;
            overflow-x: hidden;
            transition: 0.3s;
            padding-top: 100px;
            box-shadow: 1px 0 9px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sidebar .sidebar-isi {
            display: block;
            padding: 0px;
            height: 100%;
        }

        .sidebar-isi .list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .list .nav-link {
            margin-left: 6%;
            display: flex;
            align-items: center;
            padding: 14px 17px;
            margin-bottom: 2%;
            border-radius: 5px;
            text-decoration: none;
            width: calc(100% - 40px);
            box-sizing: border-box;
            position: relative;
            justify-content: flex-start;
            transition: background-color 0.3s, color 0.3s;
        }

        .nav-link .link {
            font-size: 17px;
            color: #365AC2;
            font-weight: 400;
            transition: color 0.3s;
        }

        .nav-link .nav-link-act i {
            padding-right: 10px;
            font-size: 20px;
            color: #365AC2;
            cursor: pointer;
            transition: color 0.3s;
        }

        .nav-link:hover {
            background-color: #365AC2;
        }

        .nav-link:hover i,
        .nav-link:hover .link {
            color: white;
        }

        .nav-link-act {
            margin-left: 6%;
            display: flex;
            align-items: center;
            padding: 14px 17px;
            margin-bottom: 2%;
            border-radius: 5px;
            text-decoration: none;
            width: calc(100% - 40px);
            box-sizing: border-box;
            position: relative;
            justify-content: flex-start;
            background-color: #365AC2;
            color: white;
        }


        .navbar {
            position: fixed;
            background-color: white;
            padding: 0px;
            display: flex;
            justify-content: flex-end;
            font-size: 14px;
            box-shadow: 0 2px 9px rgba(0, 0, 0, 0.2);
            width: 100%;
            top: 0;
            z-index: 900;
        }

        .navbar p {
            margin-right: 2%;
            padding: 0;
            color: gray;
        }

        .navbar span {
            font-weight: 800;
            color: #365AC2;
            font-size: 16px;
        }

        .open-btn {
            position: fixed;
            left: 2%;
            top: 2.5%;
            cursor: pointer;
            color: #365AC2;
            font-size: 20px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
            z-index: 1001;
            background: none;
        }

        .open-btn:hover {
            color: darkblue;
        }

        .main-content {
            width: 80%;
            height: auto;
            flex: 1;
            margin-top: 20vh;
            margin-left: 10%;
            transition: margin-left .3s;
        }

        .note {
            float: none; /* Remove float for better responsiveness */
            margin: 5% auto; /* Center horizontally and add vertical margin */
            width: 90%; /* Adjust width for better responsiveness */
            max-width: 600px; /* Optional: Limit maximum width */
            height: auto; /* Adjust height to content */
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px; /* Add padding inside the note */
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .note img {
            margin-left: 35% ;
            width: 30%;
        }

        .note h4 {
            font-size: 1.2rem;
            text-align: center;
            margin-bottom: 5%; /* Add spacing below the heading */
        }

        .logoutForm {
            display: flex;
            width: 100%;
            gap: 10px; /* Space between buttons */
        }

        .logout-button, .back-button {
            width: 30%; /* Full width of parent */
            padding: 10px; /* Add padding */
            border-radius: 5px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.987rem;
            box-sizing: border-box;
            border: 2px solid;
            background-color: #ffffff;
            transition: transform 0.3s ease-out;
            margin-bottom: 5%;
            justify-content: center;
            cursor: pointer;
        }

        .logout-button {
            color: #fff;
            background-color: #205abe;
            border-color: #365AC2;
        }

        .back-button {
            color: #365AC2;
            border-color: #365AC2;
            margin-inline-start: 18%;
        }

        .back-button:hover, .logout-button:hover {
            transform: scale(0.980);
        }

        @media (max-width: 768px) {

            .note{
                border-radius: 10px;
            }
            .note i {
                margin-left: 20vw;
                font-size: 5rem;
            }

            .note h4 {
            font-size: 0.990rem;
            }

            .logout-button, .back-button {
            font-size: 0.887rem;
            }

            .logout-button, .back-button {
            width: 30%; /* Full width of parent */
            padding: 5px; /* Add padding */
            border-radius: 2px;
            box-sizing: border-box;
            font-size: 0.790rem;
            margin-top: 5%;
        }


    </style>
</head>
<body>
    <nav class="navbar">
        <p class="p1"><span>{{ \Carbon\Carbon::now()->format('l') }} </span><br>{{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
    </nav>


    <div id="main-content" class="main-content">
        <div class="note">
            <div class="warning">
                <img src="img/H.jpg"><br/>
                <h4 class="card-text"> Hello,
                    <strong>
                        {{ session('username') ?? session('full_name') ?? session('email') }}
                    </strong>! Are you sure wanna log out?</h4>
                <form id="logoutForm" class="logoutForm" method="GET" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" class="back-button" onclick="handleBackButton()">Back</button>
                    <button type="submit" class="logout-button"> Logout</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function handleBackButton() {
            const referrer = document.referrer;
            const profileUrl = "{{ route('personal') }}"; // Ganti dengan rute profile Anda
            const dashboardUrl = "{{ url('/') }}"; // Ganti dengan rute dashboard Anda
            const securityUrl = "{{ route('showsecurity') }}"; // Ganti dengan rute security Anda

            if (referrer.includes(profileUrl)) {
                window.location.href = profileUrl;
            } else if (referrer.includes(dashboardUrl) || referrer.includes(securityUrl)) {
                window.location.href = dashboardUrl;
            } else {
                window.history.back();
            }
        }
    </script>


    <script>
        // Function to toggle sidebar visibility
        function toggleSidebar() {
            var sidebar = document.getElementById("sidebar");
            var mainContent = document.getElementById("main-content");
            if (sidebar.style.left === "0px") {
                sidebar.style.left = "-270px";
                mainContent.style.marginLeft = "10%";
            } else {
                sidebar.style.left = "0px";
                mainContent.style.marginLeft = "19%";
            }
        }
    </script>
</body>
</html>
