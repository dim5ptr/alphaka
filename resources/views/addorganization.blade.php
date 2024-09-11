<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STI | Sarastya Technology Integrata</title>
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <style>
        /* CSS Enhancements */

        .banner {
            display: flex;
            background-color: #ffffff;
            background-size: cover;
            border-radius: 20px;
            margin: 5px 5%;
            padding: 40px;
            box-shadow: 0 4px 8px 4px rgba(0, 0, 0, 0.1);
        }

        section {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }


        html, body {
            height: 100%;
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
            margin-bottom: 10%;
            display: flex;
            justify-content: flex-end;
            font-size: 14px;
            box-shadow: 0 2px 9px rgba(0, 0, 0, 0.2);
            width: 100%;
            top: 0;
            z-index: 900;
        }

        .inbox {
            padding-left: 5%;
            border-right: 5px solid #365AC2;
            width: 3%;
            height: 40px;
            margin-top: 0.9%;
            margin-right: 1.5%;
            font-size: 1.5rem;
            justify-content: center;
            align-content: center;
            color: #365AC2;
        }

        .inbox1{
            transition: color ease-out .3s;
        }

        .inbox1:hover{
            color: #626981;
        }
        .inbox i {
            cursor: pointer;
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
        .open-btn span {
           color: #2d4da3;
        }
        .main-content {
            width: calc(100% - 270px);
            height: 100%;
            flex: 1;
            margin-top: 5%;
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
            text-align : center;
            padding: 8px 9px;
            margin-bottom: 2%;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            width: calc(80% - 40px);
            box-sizing: border-box;
            position: fixed;
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
/* General Styles */

.row {
  display: flex;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
  width: 900px;
  height: auto;
  justify-content: center;
  margin-left: 16%;
}

.stepper {
  display: flex;
  flex-direction: column;
  padding-top: 3%;
  width: 60%;
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
  background-color: #365AC2;
}

.stepper h2 {
    color: white;
    margin-top: 2%;
    margin-left: 5%;
    font-size: 1.7rem;
}

.steps {
    margin-top: 0%;
    margin-left: 5%;
}
.step {
  display: flex;
  align-items: center;
  margin-left: 3%;
  margin-top: 1%;
  margin-bottom: 20px;
  position: relative;
}

.step p{
    color: #ffffff;
    margin-right: 5%;

}

.step-number {
  width: 40px;
  height: 40px;
  background-color: #ffffff;
  color: #365AC2;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 1.2rem;
  margin-right: 10px;
  line-height: 1; /* Pastikan line-height tidak mengganggu */
  flex-shrink: 0; /* Agar ukuran lingkaran tidak berubah */
}

.step-instruction {
  font-size: 1rem;
  color: #333;
}

.step:not(:last-child)::after {
  content: '';
  position: absolute;
  width: 2px;
  height: 80%;
  background-color: #ddd;
  left: 5%;
  top: 57px;
}

.form-container1 {
    padding-left: 20px;
  width: 50%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  background-color: #f9f9f9;
  border-radius: 20px;
}

label {
  font-size: 1rem;
  color: #333;
  margin-bottom: 5px;
}

input {
  width: 100%;
  padding: 10px;
  margin-bottom: 20px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 1rem;
}

.form-container {
    width: 55%;
    height: 50%;
    border-radius: 20px;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Shadow untuk isi form */
    margin-top: 2%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding-left: 5%;
    padding-top: 5%;
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
    height: 90px;
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


</style>
</head>
<body>
    <nav class="navbar">
        <div class="inbox"><a href="/inbox" class="inbox1"><i class="fa-solid fa-inbox"></i></a></div>
        <p class="p1"><span>{{ \Carbon\Carbon::now()->format('l') }},</span><br>{{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
    </nav>

    <button class="open-btn" onclick="toggleSidebar()">&#9776; Organization <span> > Create Organization</span></button>

    <div id="sidebar" class="sidebar">
        <div class="sidebar-isi">
            <ul class="list">
                <li>
                    <a href="/" class="nav-link">
                        <span class="link"><i class="fa-solid fa-house-chimney"></i>ㅤDashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/organization" class="nav-link-act">
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
        <!-- Content Header (Page header) -->

                <!-- Form Section -->
                <section class="form-section">
                    <div class="row justify-center">
                        <div class="stepper">
                            <h2>New Organization</h2>

                             <div class="steps">
                                <div class="step">
                                    <div class="step-number">1</div>
                                    <p class="step-instruction">Isi nama dan deskripsi organisasi yang akan dibuat, klik "create"</p>
                                </div>
                                <div class="step">
                                    <div class="step-number">2</div>
                                    <p class="step-instruction">Cek kode verivikasi di "inbox", klik button "verivikasi"</p>
                                </div>
                                <div class="step">
                                    <div class="step-number">3</div>
                                    <p class="step-instruction">Kembali ke "Organization" dan refresh. Organisasi baru berhasil dibuat!</p>
                                </div>
                             </div>
                        </div>

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
                                <div class="form-group">
                                    <button type="submit" class="btn-submit">Create</button>
                                </div>
                            </form>
                        </div>

                </section>

            </div>
    </div>

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

