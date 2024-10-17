<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Security</title>

<style>
        /* CSS Anda disini */
        html, body {
            height: 100vh;
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
            z-index: 4;
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

        .logoutForm {
            list-style: none;
            height: 50%;
            top: 50%;

        }
        .logout-button {
            margin-left: 15%;
            display: flex;
            text-align : center;
            padding: 8px 9px;
            margin-bottom: 2%;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            width: calc(80% - 40px);
            box-sizing: border-box;
            position: relative;
            top: 100%;
            background-color: white;
            color: #c23636;
            border: 2px solid #c23636;
            transition: background-color 0.3s, color 0.3s;
        }

        .logout-button i{
            font-weight: 700;
            font-size: 20px;
            color: #c23636;
        }

        .logout-button:hover i,
        .logout-button:hover {
            background-color: #c23636;;
            color: aliceblue;
            font-weight: 700;
        }

        .navbar {
            position: fixed;
            background-color: white;
            padding: 0px;
            margin-bottom: 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            /* box-shadow: 0 2px 3px rgba(0, 0, 0, 0.2); */
            width: 100%;
            top: 0;
            z-index: 5;

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
            position: relative;
            justify-content: center;
            align-items: center;
            float: left;
            margin-left: 2%;
            width: 20%;
            height: auto;
            z-index: 5;
            background: none;
        }

        .open-btn button {
            border: none;
            background: none;
            cursor: pointer;
            color: #365AC2;
            font-size: 20px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }

        .open-btn:hover {
            color: darkblue;
        }

        .main-content {
            width: 80%;
            height: 100vh;
            flex: 1;
            margin-top: 5%;
            margin-left: 10%;
            transition: margin-left .3s;
        }

        .menu {
            display: flex;
            float: right;
            margin-right: 10%;
            margin-top: 3%;
            width: 80%;
            height: 70vh;
            margin-left: 4%;
        }

        .list2 {
            background-color: white;
            width: 18%;
            height: 98%;
            margin-top: 0.6%;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
            box-shadow: 0 2px 5px 2px rgba(0, 0, 0, 0.1);
        }

        .list2 ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .list2 ul li {
            margin: 2 autopx, 0;
            margin-bottom: 3%;
        }
        .nav-mini {
            display: block;
            padding: 10px;
            text-decoration: none;
            font-weight: 400;
            font-size: 0.890rem;
            color: #365AC2;
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.3s;
            box-shadow: 0 1px 4px rgba(98, 98, 98, 0.107);

        }

        .nav-mini:hover::before {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0%;
            width: 100%;
            height: 2px;
            background-color: #365AC2;
        }

        .nav-mini:hover::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0%;
            width: 100%;
            height: 2px;
            background-color: #365AC2;
        }



        .nav-mini:hover {
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
        }
        .page {
            background-color: white;
            width: 80%;
            height: 100%;
            border-radius: 5px;
            box-shadow: 0 2px 5px 2px rgba(0, 0, 0, 0.1);
            z-index: 3;
            display: table-column;
            gap: 0px;
        }

        .inpage {
            margin-left: 5%;
            font-size: 15px;
        }

        .inpage h3 {
            margin-bottom: 10px;
            font-size: 2rem;
            color: #333;
        }

        .inpage p {
            font-size: 1rem;
            margin-bottom: 5%;
        }

        .inpage label {
            margin-top: 50px;
            margin-bottom: 10px;
        }

        .inpage input[type="password"] {
            width: 60%;
            padding: 15px;
            margin: 8px 0;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .inpage button[type="submit"] {
            background-color: #365AC5;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        .inpage button[type="submit"]:hover {
            background-color: #365AA3;
        }

        .error-message {
        color: #536bc1;
        font-size: 12px;
        margin-top: 5px;
        margin-bottom: 15px;
        display: block;
        }
        .error {
            border: 1px solid #536bc1;
        }

        /* In your CSS file */
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #5b0d0d;
            border-color: #f5c6cb;
        }

            @media (max-width: 768px) {

                .navbar p {
                    font-size: 0.678rem;
                    margin-right: 5%;
                }

                .open-btn button{
                    font-size: 0.990rem;
                    width: 100%;
                    display: inline;
                }

                .open-btn {
                    width: 35%;
                    display: inline;
                }
                .menu {
                    flex-direction: column; /* Stack menu items vertically */
                    margin-top: 10vh;
                    margin-right: 0;
                    margin-left: auto;
                    width: auto;
                    height: auto; /* Adjust height based on content */
                }

                .list2 {
                    display: flex;
                    width: 100%; /* Full width on mobile */
                    height: auto; /* Adjust height based on content */
                    margin: 0 auto; /* Center horizontally */
                    margin-bottom: 5%;
                    font-size: 0.876rem;
                }

                .list2 ul {
                    width: 100%;
                }
                .page {
                    display: flex;
                    flex-direction: column;
                    width: 95%; /* Full width on mobile */
                    margin: 0 auto; /* Center horizontally */
                    padding: 10px; /* Add padding inside page */
                }

                .input-group input[type="password"] {
                    width: 100%; /* Full width on mobile */
                    padding-bottom: 3%; /* Add space at the bottom of input fields */
                    font-size: 0.890rem; /* Adjust the font size of input fields */
                    border: 1px solid #ccc; /* Add border for input fields */
                    border-radius: 4px; /* Rounded corners */
                    padding: 10px; /* Add some internal padding */
                    margin-bottom: 10px; /* Space between input fields */
                }


                .inpage button {
                    float: right;
                    margin-top: 0%;
                    margin-right: 2%; /* Right margin for submit button */
                    font-size: 0.850rem; /* Adjust font size for submit button */
                    padding: 10px 20px; /* Add padding for submit button */
                    background-color: #007bff; /* Set background color */
                    color: white; /* White text */
                    border: none; /* Remove border */
                    border-radius: 4px; /* Rounded corners */
                    cursor: pointer; /* Pointer cursor */
                }

                .inpage h3 {
                    margin-bottom: 5px;
                    font-size: 1.3rem;
                    color: #333;
                }

                .inpage p {
                    margin-bottom: 10%;
                    font-size: 0.837rem;
                }

                .inpage label {
                    font-size: 0.870rem;
                    font-weight: 500;
                }

                .error-message {
                    font-size: 12px;
                    margin-bottom: 15px;
                    margin-top: 2px;
                    color: #365AC2; /* Set error message color */
                }

                .custom-modal {
                    width: 100%;
                }


                /* Optional: Add responsive styling for smaller screens */
                @media screen and (max-width: 768px) {
                    .inpage input[type="password"] {
                        width: 100%; /* Full width on smaller screens */
                    }

                    .inpage button[type="submit"] {
                        width: 60%; /* Full width for submit button on smaller screens */
                        margin-right: 0; /* Reset margin */

                    }
                }
            }

            .form-input {
                margin-bottom: 3%;
            }

             .input-group {
                position: relative;
                width: 90%;
                display: flex;
            }

            .inpage .input-group input {
                width: calc(100% - 20%); /* Adjust the width to account for the button */
                padding-right: 10%; /* Ensure input text doesn't overlap the button */
                padding: 10px; /* Uniform padding */
                border: 2px solid #c5c4c4; /* Border styling */
                border-radius: 4px; /* Rounded corners */
                margin-top: 2%; /* Space between input fields */
                font-size: 1rem;
                box-sizing: border-box; /* Ensure padding doesn't affect width */
                height: 40px; /* Ensure input has a fixed height */
            }

            .inpage .input-group .btn {
                position: absolute;
                right: 20%; /* Position button inside the input */
                top: 20%; /* Align to the top of the input */
                bottom: 0; /* Align to the bottom of the input */
                margin: auto; /* Center vertically */
                background: none;
                border: none;
                cursor: pointer;
                color: #a2a3a7;
                font-size: 1rem;
                padding: 0 10px; /* Add padding for click area */
                height: 100%; /* Match the button's height to the input's height */
                align-items: center; /* Vertically center the icon */
                justify-content: center; /* Horizontally center the icon */
            }

  /* The Modal (background) */
  .custom-modal {
            display: none;
            position: relative;
            z-index: 6;
            padding: 0px;
            width: 90%;
            height: 90%;
            overflow: auto;
            background-color: white;
        }

        /* Modal Content/Box */
        .custom-modal-content {
           margin-left: 0%;
            width: 100%;
            border-radius: 8px;
            padding-bottom: 5%;
        }

        /* Close Button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            cursor: pointer;
        }

        .data p {
            padding: 6px 12px;
            border-radius: 50px;
            background-color: #afd6ffc8;
            font-size: 0.870rem;
            width: 40%;
        }

        .acc p{
            font-size: 0.90rem;
            line-height: 25px;
        }

        .acc span{
            font-size: 1.2rem;
            font-weight: bolder;
            color: #365AA3;
        }

        .session-list p{
            font-size: 0.90rem;
            line-height: 25px;
        }

        .session-list span{
            font-size: 1.2rem;
            font-weight: bolder;
            color: #365AA3;
        }

        .greet {
            margin-left: 3%;
            position: relative;
            z-index: 10;
        }

        .greet h4{
            padding: 10px 15px;
            border-radius: 40px;
            background-color: #2a69accd;
            color: white;
            font-size: 0.870rem;
            width: 40%;
        }
    </style>
</head>
<body>
     <nav class="navbar">
        <div class="open-btn">
            <button onclick="toggleSidebar()">&#9776; Security</button>
        </div>
        <p class="p1"><span>{{ \Carbon\Carbon::now()->format('l') }},</span><br>{{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
    </nav>

    <div id="sidebar" class="sidebar">
        <div class="sidebar-isi">
            <ul class="list">
                <li>
                    <a href="/" class="nav-link">
                        <span class="link"><i class="fa-solid fa-house-chimney"></i>ㅤDashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/organization" class="nav-link">
                        <span class="link"><i class="nav-icon fas fa-users"></i>ㅤOrganization</span>
                    </a>
                </li>
                <li>
                    <a href="/personal" class="nav-link">
                        <span class="link"><i class="fa-solid fa-id-card"></i>ㅤProfile</span>
                    </a>
                </li>
                <li>
                    <a href="/security" class="nav-link-act">
                        <span class="link"><i class="fa-solid fa-user-shield"></i>ㅤSecurity</span>
                    </a>
                </li>
            </ul>
            <form id="logoutForm" method="GET" class="logoutForm" action="{{ route('confirm-logout') }}">
                <button type="submit" class="logout-button">ㅤ<i class="fa-solid fa-right-from-bracket"></i>ㅤLogout</button>
            </form>
        </div>
    </div>

    <div id="main-content" class="main-content">
        <div class="banner">
            <div class="menu">
                <div class="list2">
                <ul>
                    <li>
                        <a href="#" class="nav-mini" data-target="password">Password</a>
                    </li>
                    <li>
                        <a href="#" class="nav-mini" data-target="sessions">Account Sessions</a>
                    </li>
                    <li>
                        <a href="#" class="nav-mini" data-target="help">Help Center</a>
                    </li>
                </ul>
            </div>

        <!-- Modal for Password -->
        <div class="page">
            <div class="greet">
                <h4>Hi, {{ session('username') ?? session('full_name') ?? session('email') }}!</h4>
            </div>
            <div class="inpage">
                <div id="password-modal" class="custom-modal">
                    <div class="custom-modal-content">
                        <h3>Manage Your Password</h3>
                        <p>Your new password must be different from your previous used password.</p>
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <form id="change-password-form" method="POST" action="{{ route('editpassword') }}">
                            @csrf
                            <input type="hidden" name="request_type" value="change">
                            <div class="form-input">
                                <label for="new-password">New Password:</label><br>
                                <div class="input-group">
                                    <input type="password" id="new-password" name="new_password" required>
                                    <button type="button" class="btn" id="toggle-new-password"><i class="fa fa-eye"></i></button>
                                </div>
                                <span id="new-password-error" class="error-message"></span>
                            </div>
                            <div class="form-input">
                                <label for="confirm-password">Confirm New Password:</label><br>
                                <div class="input-group">
                                    <input type="password" id="confirm-password" name="confirm_new_password" required>
                                    <button type="button" class="btn" id="toggle-confirm-password"><i class="fa fa-eye"></i></button>
                                </div>
                                <span id="confirm-password-error" class="error-message"></span>
                            </div>
                            <button type="submit">Change Password</button>
                        </form>
                    </div>
                </div>

           <!-- Modal for Account Sessions -->
           <div id="sessions-modal" class="custom-modal">
            <div class="custom-modal-content">
                <h3>Account Sessions</h3>
                <p>Know your last activity here! Make sure the activity session is by yourself</p>
                <div class="acc">
                    <p>loged as</br><span>{{session('email') }}</span> </p>
                </div>
                <div id="session-list" class="session-list">

                </div>
            </div>
        </div>

                <!-- Modal for Login Activity -->
                <div id="help-modal" class="custom-modal">
                    <div class="custom-modal-content">
                        <h3>Help Center</h3>
                        <p>View your recent login activity.</p>
                    </div>
                </div>
             </div>
            </div>
         </div>
        </div>
    </div>
    <script>
        const baseUrl = "http://192.168.1.24:14041/api"; // Replace with your actual base URL
        const apiKey = "5af97cb7eed7a5a4cff3ed91698d2ffb"; // Replace with your actual API key
        const accessToken = "{{ session('access_token') }}"; // Server-side token

        async function fetchUserActivity() {
            try {
                const response = await fetch(`${baseUrl}/sso/user_activity.json`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `${accessToken}`,
                        'x-api-key': apiKey
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const result = await response.json();
                if (result.success) {
                    displayUserActivity(result.data);
                } else {
                    displayNoData();
                }
            } catch (error) {
                console.error('There has been a problem with your fetch operation:', error);
            }
        }

        function displayUserActivity(data) {
            const sessionListDiv = document.getElementById('session-list');
            sessionListDiv.innerHTML = `
                <div>
                    <p>last login activity</br><span>${new Date(data.created_date).toLocaleString()}</span> </p>
                </div>
            `;
        }

        function displayNoData() {
            const sessionListDiv = document.getElementById('session-list');
            sessionListDiv.innerHTML = '<p>No session data available.</p>';
        }

        // Fetch user activity when the page loads
        fetchUserActivity();
    </script>
    <script>
        // Function to close all modals
    function closeAllModals() {
        document.querySelectorAll('.custom-modal').forEach(modal => {
            modal.style.display = "none";
        });
    }

    // Show Change Password modal when page loads
    document.addEventListener('DOMContentLoaded', function() {
        closeAllModals(); // Close any open modals
        const passwordModal = document.getElementById('password-modal');
        if (passwordModal) {
            passwordModal.style.display = 'block'; // Open Change Password modal
        }
    });

    // Handle menu clicks to show corresponding modals
    document.querySelectorAll('.nav-mini').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            const modalId = targetId + '-modal';

            closeAllModals(); // Close all modals before opening a new one
            const targetModal = document.getElementById(modalId);
            if (targetModal) {
                targetModal.style.display = "block";
            }
        });
    });

    // Toggle visibility for New Password
    document.getElementById('toggle-new-password').addEventListener('click', function () {
        const passwordField = document.getElementById('new-password');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            this.innerHTML = '<i class="fa fa-eye-slash"></i>';
        } else {
            passwordField.type = 'password';
            this.innerHTML = '<i class="fa fa-eye"></i>';
        }
    });

    // Toggle visibility for Confirm New Password
    document.getElementById('toggle-confirm-password').addEventListener('click', function () {
        const confirmPasswordField = document.getElementById('confirm-password');
        if (confirmPasswordField.type === 'password') {
            confirmPasswordField.type = 'text';
            this.innerHTML = '<i class="fa fa-eye-slash"></i>';
        } else {
            confirmPasswordField.type = 'password';
            this.innerHTML = '<i class="fa fa-eye"></i>';
        }
    });


    document.addEventListener('DOMContentLoaded', function() {
        var newPasswordInput = document.getElementById('new-password');
        var confirmPasswordInput = document.getElementById('confirm-password');
        var newPasswordError = document.getElementById('new-password-error');
        var confirmPasswordError = document.getElementById('confirm-password-error');

        // Real-time validation function
        function validatePasswords() {
            var newPasswordValue = newPasswordInput.value;
            var confirmPasswordValue = confirmPasswordInput.value;

            // Clear previous error messages
            newPasswordError.textContent = '';
            confirmPasswordError.textContent = '';

            // Password length check
            if (newPasswordValue.length < 8) {
                newPasswordError.textContent = 'Password must be at least 8 characters.';
            }

            // Match check
            if (newPasswordValue !== confirmPasswordValue) {
                confirmPasswordError.textContent = 'Passwords do not match.';
            }
        }

        newPasswordInput.addEventListener('input', validatePasswords);
        confirmPasswordInput.addEventListener('input', validatePasswords);
    });


</script>


<script>
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
