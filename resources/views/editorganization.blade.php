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
            z-index: 1;
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
            margin-bottom: 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            /* box-shadow: 0 2px 3px rgba(0, 0, 0, 0.2); */
            width: 100%;
            top: 0;
            z-index: 3;

        }

        .navbar p {
            margin-right: 2%;
            padding: 0;
            color: gray;
        }

        .navbar a {
            font-weight: 600;
            color: #365AC2;
            font-size: 1.2rem;
            cursor: pointer;
            text-decoration: underline;
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
            width: 40%;
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
            width: calc(100% - 270px);
            height: 100%;
            flex: 1;
            margin-top: 5%;
            margin-left: 5%;
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
     /* Shadow untuk isi form */
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

.breadcrumb {
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
    padding: 0;
    margin: 0;
    list-style: none;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "/";
    margin: 0 8px; /* Jarak antara elemen dan garis miring */
    color: #3200af;
}

.breadcrumb a {
    text-decoration: none;
    color: #3200af;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.breadcrumb-item.active {
    color: #3200af;
}


</style>
</head>
<body>
    <nav class="navbar">
        <div class="open-btn">
            <button onclick="toggleSidebar()">&#9776; Organization</button>
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
        <br>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color: transparent;">
                <li class="breadcrumb-item"><a href="{{ route('organization') }}" style="color: #3200af;">Organization</a></li>
                <li class="breadcrumb-item"><a href="{{ route('showvieworganization', ['organization_name' => $organization['organization_name']]) }}" style="color: #3200af;">{{ $organization['organization_name'] }}</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #3200af;">Edit Organization</li>
            </ol>
        </nav>
                <!-- Form Section -->
                <section class="form-section">
                    <div class="row justify-center">
                        <div class="stepper">
                            <h2>Edit Organization</h2>

<div class="steps">
    <div class="step">
        <div class="step-number">1</div>
        <p class="step-instruction">Ubah nama dan deskripsi organisasi sesuai kebutuhan, lalu klik "Save Changes".</p>
    </div>
    <div class="step">
        <div class="step-number">2</div>
        <p class="step-instruction">Akan ada alert jika data berhasil diganti</p>
    </div>
    <div class="step">
        <div class="step-number">3</div>
        <p class="step-instruction">Kembali ke halaman "Organization" dan refresh. Perubahan organisasi berhasil disimpan!</p>
    </div>
</div>

                        </div>

                          <!-- Form Section -->
                        <div class="form-container">
                            <!-- Form for creating organization -->
                                <form action="{{ route('editorganization', ['organization_name' => $organization['organization_name']]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="organization_id" value="{{ $organization['organization_id'] }}">
                                    <div class="form-group">
                                        <label for="organization_name" class="form-label">Organization Name</label>
                                        <input type="text" name="organization_name" class="form-control" value="{{ $organization['organization_name'] }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="form-label">Description</label>
                                        <input type="text" name="description" class="form-control" value="{{ $organization['description'] }}">
                                    </div>

                                    <input type="hidden" name="access_token_status" value="{{ session('access_token')}}">

                                    <div class="form-group">
                                        <button type="submit" class="btn-submit">Save</button>
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


{{-- @extends('layout.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color: transparent;">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #3200af;">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('organization') }}" style="color: #3200af;">Organization</a></li>
                <li class="breadcrumb-item"><a href="{{ route('showvieworganization', ['organization_name' => $organization['organization_name']]) }}" style="color: #3200af;">{{ $organization['organization_name'] }}</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #3200af;">Edit Organization</li>
            </ol>
        </nav>
        <div class="container-fluid">
            <div class="row">
                <div class="col-10 offset-1"> <!-- Adjusted column class -->
                    <!-- Breadcrumbs for navigation history -->


                    <!-- Title for the page -->
                    <h1 class="m-0 text-center" style="color: #3200af;">Edit Organization</h1> <!-- Centered text -->
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row justify-content-center"> <!-- Centered form -->
                <div class="col-md-8 p-5 shadow-sm" style="background-color: #bac1ef;">
                    <div class="card shadow-none" style="background-color: #bac1ef;">
                        <div class="card-body">
                            <!-- Form for editing organization details -->
                            <form action="{{ route('editorganization', ['organization_name' => $organization['organization_name']]) }}" method="POST">
                                @csrf
                                <div class="mb-3" style="color: #3200af;">
                                    <label for="organization_name" class="form-label">Organization Name</label>
                                    <input type="text" name="organization_name" class="form-control" value="{{ $organization['organization_name'] }}">
                                </div>
                                <div class="mb-3" style="color: #3200af;">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" name="description" class="form-control" value="{{ $organization['description'] }}">
                                </div>
                                <button type="submit" class="btn btn-primary btn-block" style="background-color: #7773d4;"> <!-- Adjusted button class -->
                                    Save
                                </button>
                            </form>
                            <!-- End of form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection --}}
