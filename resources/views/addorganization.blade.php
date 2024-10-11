<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STI | Sarastya Technology Integrata</title>
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* CSS Enhancements */

        section {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }


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
            z-index: 2;
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
    padding: 0;
    margin-bottom: 10%;
    display: flex;
    justify-content: flex-end;
    font-size: 14px;
    /* box-shadow: 0 2px 9px rgba(0, 0, 0, 0.2); */
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
    transition: 0.3s;
    z-index: 1001;
    border: none;
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
.logoutForm {
    list-style: none;
    height: 50%;
    top: 50%;
}

.logout-button {
    margin-left: 15%;
    display: flex;
    text-align: center;
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

.logout-button i {
    font-weight: 700;
    font-size: 20px;
    color: #c23636;
}

.logout-button:hover i,
.logout-button:hover {
    background-color: #c23636;
    color: aliceblue;
    font-weight: 700;
}

label {
  font-size: 1rem;
  font-weight: bold;
  color: #365AC2;
  margin-bottom: 5px;
}

input {
  width: 100%;
  padding: 10px;
  margin-bottom: 5%;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 1rem;
}

.form-container {
    width: 40%;
    border-radius: 20px;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-top: 2%;
    margin-left: 27%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding-left: 3%;
    padding-top: 3%;
}

.form-group {
    margin-top: 0px;
}


.form-group input {
    width: 83%;
    padding: 15px;
    margin-top: 10px;
    border-radius: 8px;
    border: 2px solid #365AC2; /* Menambahkan border */
}

.form-group textarea {
    width: 83%;
    height: 70px;
    padding: 15px;
    margin-top: 10px;
    margin-bottom: 2%;
    border-radius: 8px;
    border: 2px solid #365AC2;
    font-family: 'Poppins', sans-serif;
    font-size: 1rem;
}


.form-container button[type="submit"]{
    float: right;
    margin-right: 11%;
    margin-top: 2%;
    margin-bottom: 7%; /* Right margin for submit button */
    font-size: 0.850rem; /* Adjust font size for submit button */
    padding: 10px 20px; /* Add padding for submit button */
    background-color: #365AC2; /* Set background color */
    color: white; /* White text */
    border: none; /* Remove border */
    border-radius: 4px; /* Rounded corners */
    cursor: pointer; /* Pointer cursor */
}

.how {
    display: inline-flex;
    align-items: center;
    width: 50%;
}

.how a{
    text-decoration: underline;
    cursor: pointer;
    width: 100%;
    color: #365AC2;
}

.how a:hover {
    color: #2a4494;
}

/* Modal container */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: #25419750;
}

/* Modal content */
.modal-content {
    background-color: #fff;
    margin: 9% auto;
    padding: 30px;
    width: 65%;
    max-width: 30%;
    max-height: 55%;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(27, 24, 124, 0.1);

}

/* Close button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #365AC2;
    text-decoration: none;
    cursor: pointer;
}

h3{
    font-size: 2rem;
    color: #365AC2;
    margin-bottom: 6%;
    padding-left: 5%;

}

/* Flexbox layout for step and instruction */
.steps {
    margin-top: 5%;
    margin-bottom: 5%;
    padding-left: 5%;
    padding-right: 5%;
}

.step {
    display: flex;
    align-items: flex-start;
    margin-bottom: 15px;
}

.step-number {
    background-color: #365AC2;
    color: #fff;
    font-size: 1rem;
    font-weight: bold;
    width: 45px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-right: 15px;
}

.step-instruction {
    margin: 0;
    line-height: 1.5;
    font-size: 1rem;
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

    .sidebar {
       font-size: 100%;
       padding-top: 10%;
    }

    .sidebar-isi {
        margin-top: 15%;
    }

    .logoutForm {
        height: 50%;
    }

    ul {
        font-size: 0.876rem;
    }


        }

    </style>
</head>
<body>
    <nav class="navbar">
        <div class="open-btn">
            <button onclick="toggleSidebar()">&#9776; Dashboard</button>
        </div>
        <p class="p1"><span>{{ \Carbon\Carbon::now()->format('l') }},</span><br>{{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
    </nav>

    <div id="sidebar" class="sidebar">
        <div class="sidebar-isi">
            <ul class="list">
                <li>
                    <a href="/" class="nav-link-act">
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
                    <a href="/security" class="nav-link">
                        <span class="link"><i class="fa-solid fa-user-shield"></i>ㅤSecurity</span>
                    </a>
                </li>
            </ul>

                <form id="logoutForm" method="GET" class="logoutForm" action="{{ route('confirm-logout') }}">
                <button type="submit" class="logout-button">ㅤ <i class="fa-solid fa-right-from-bracket"></i>ㅤLogout</button>
                </form>
            </ul>
        </div>
    </div>

    <div id="main-content" class="main-content">
          <!-- Form Section -->
          <div class="form-container">
            <!-- Form for creating organization -->
            <form action="{{ route('addorganization') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="organization_name" class="form-label">Organization Name</label>
                    <input type="text" name="organization_name" id="organization_name" class="form-input" placeholder="Enter Organization Name" required>
                </div>
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea type="text" name="description" id="description" class="form-input" placeholder="Enter Organization Description" required></textarea>
                </div>

                <input type="hidden" name="access_token_status" value="{{ session('access_token')}}">

                <div class="form-group">
                    <button type="submit" class="btn-submit">Create</button>
                </div>

                <div class="how">
                   <a onclick="openModal()">  need a help?</a>
                </div>

            </form>
        </div>

        <!-- Modal Structure -->
        <div id="stepModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <div class="modal-body">
                    <h3>How to create new organization?</h3>
                    <div class="steps">
                        <div class="step">
                            <div class="step-number">1</div>
                            <p class="step-instruction">Insert name and description for your organization, and click "create" button.</p>
                        </div>
                        <div class="step">
                            <div class="step-number">2</div>
                            <p class="step-instruction">We will send an verification email for your organization, check your inbox!</p>
                        </div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <p class="step-instruction">Verification your organization, and new organization is created successful!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        // Get the modal
        var modal = document.getElementById("stepModal");

        // Open the modal
        function openModal() {
            modal.style.display = "block";
        }

        // Close the modal
        function closeModal() {
            modal.style.display = "none";
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
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

</div>


</body>
</html>
