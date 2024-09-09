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

        .wlc h2 {
            margin-top: 3%;
            font-size: 2rem;
            font-weight: 800;
            color: rgba(20, 19, 19, 0.923);
            margin-bottom: 1%;
        }

        .wlc span {
            color: #365AC2;
            font-weight: bolder;
        }

        .wlc {
            margin-left: 10%;
            max-width: 40%;
        }

        .wlc p {
            color: #666;
            font-size: 1.2rem;
            line-height: 1.5;
        }

        .pict {
            max-width: 40%;
            margin-left: 50px;
        }

        .pict img {
            width: 70%;
            margin-left: 20%;
        }

        section {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(5%, 4fr));
            height: 100%;
            gap: 20px;
            margin: 0px 2%;
            padding: 17px;
        }

        .card {
            background-color: #fff;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            position: relative;
        }

        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .card-text {
            position: absolute;
            top: 80%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: left;
            border-radius: 15px;
            border: 2px solid white;
            width: 93%;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.849);
            color: #020202;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .card:hover .card-overlay {
            opacity: 1;
        }

        .card:hover .card-text {
            opacity: 1;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .card-content {
            align-items: center;
        }

        .card-icon {
            width: 97%;
            height: 0.5%;
            border: 5px solid white;
            border-radius: 15px;
        }

        .card p {
            font-size: 1rem;
            margin-bottom: 25%;
            padding: 10px;
        }

        .card span {
            font-weight: bold;
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

        .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
    }

    .row {
        display: flex;
        justify-content: center;
    }

    /* Content Header */
    .content-header {
        padding: 20px 0;
        background-color: #f8f9fa;
        margin-bottom: 30px;
    }

    .breadcrumb {
        list-style: none;
        padding: 0;
        display: flex;
        background-color: transparent;
        margin-bottom: 20px;
    }

    .breadcrumb-item {
        margin-right: 10px;
        font-size: 16px;
    }

    .breadcrumb-link {
        text-decoration: none;
        color: #7773d4;
    }

    .breadcrumb-item.active {
        color: #365AC2;
        font-weight: bold;
    }

    .page-title {
        color: #365AC2;
        text-align: center;
        font-size: 2.5rem;
    }

    /* Form Styles */
    .form-container {
        background-color: #bac1ef;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 600px;
    }

    .card {
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 15px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        color: #365AC2;
        font-size: 16px;
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 16px;
        background-color: #fff;
    }

    /* Button Styles */
    .btn-primary {
        background-color: #365AC2;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #2e4a8c;
        transform: scale(1.05);
    }

    /* Optional styles for other buttons */
    .btn-secondary {
        background-color: #AFC3FC;
        color: black;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 16px;
        text-decoration: none;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #8cb1e2;
        transform: scale(1.05);
    }

    </style>
</head>
<body>
    <nav class="navbar">
        <p class="p1"><span>{{ \Carbon\Carbon::now()->format('l') }},</span><br>{{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
    </nav>

    <button class="open-btn" onclick="toggleSidebar()">&#9776; Organization</button>

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
        <header class="content-header">
            <div class="container">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-12 d-flex justify-content-end">
                        <form action="{{ route('showcreateorganization') }}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-primary rounded">
                                <i class="fas fa-plus"></i>
                                Tambah Organisasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <section class="content">
            <div class="container">
                @if(isset($organizations))
                    <div class="row">
                        @foreach($organizations as $organization)
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm">
                                    <img src="{{ $organization['logo'] }}" alt="Logo" class="card-img-top">
                                    <div class="card-body">
                                        <h3 class="card-title">{{ $organization['organization_name'] }}</h3>
                                        <p class="card-description">{{ $organization['description'] }}</p>
                                        <div class="members-count">
                                            Anggota: {{ $organization['members_count'] }}
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="{{ route('showvieworganization', ['organization_name' => $organization['organization_name']]) }}" class="btn btn-secondary">Lihat</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif(isset($data['success']) && !$data['success'])
                    <div class="alert alert-danger" role="alert">
                        Terjadi kesalahan saat mengambil data organisasi. Silakan coba lagi nanti.
                    </div>
                @else
                    <div class="alert alert-info" role="alert">
                        Tidak ada data organisasi yang tersedia.
                    </div>
                @endif
            </div>
        </section>
    </div>

    <div class="container">
        <header>
            <div class="logo">
                <i class="fas fa-sitemap"></i>
            </div>
            <h1>Organization Hub</h1>
            <button class="add-department-btn"><i class="fas fa-plus"></i> Add new department</button>
        </header>

        <main>
            <section class="departments">
                <h2><i class="fas fa-chart-line"></i> Department Chart</h2>
                <div class="department-chart">
                    <div class="department-section">
                        <h3>Board of directors <span>1</span></h3>
                        <div class="department-card">
                            <i class="fas fa-key"></i> 1001
                            <p>Board of directors</p>
                        </div>
                    </div>

                    <div class="department-section">
                        <h3>Project Management <span>3</span></h3>
                        <div class="department-card">
                            <i class="fas fa-user-tie"></i> 2001
                            <p>Head of Project Management</p>
                        </div>
                        <div class="department-card">
                            <i class="fas fa-users"></i> 2002
                            <p>Project Team #1</p>
                        </div>
                        <div class="department-card">
                            <i class="fas fa-users"></i> 2003
                            <p>Project Team #2</p>
                        </div>
                    </div>

                    <!-- More department sections... -->
                </div>
            </section>

            <section class="department-list">
                <h2><i class="fas fa-list"></i> Department List</h2>
                <div class="list-item">
                    <i class="fas fa-key"></i> 1001
                    <p>Board of directors</p>
                </div>
                <div class="list-item">
                    <i class="fas fa-user-tie"></i> 2001
                    <p>Head of Project Management</p>
                </div>
                <!-- More department list items... -->
            </section>
        </main>
    </div>

    <section class="content">
        <div class="container">
            @if(isset($organizations))
                <div class="row">
                    @foreach($organizations as $organization)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <img src="{{ $organization['logo'] }}" alt="Logo" class="card-img-top">
                                <div class="card-body">
                                    <h3 class="card-title">{{ $organization['organization_name'] }}</h3>
                                    <p class="card-description">{{ $organization['description'] }}</p>
                                    <div class="members-count">
                                        Anggota: {{ $organization['members_count'] }}
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="{{ route('showvieworganization', ['organization_name' => $organization['organization_name']]) }}" class="btn btn-secondary">Lihat</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif(isset($data['success']) && !$data['success'])
                <div class="alert alert-danger" role="alert">
                    Terjadi kesalahan saat mengambil data organisasi. Silakan coba lagi nanti.
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    Tidak ada data organisasi yang tersedia.
                </div>
            @endif
        </div>
    </section>


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
{{--
<script>
    document.getElementById('logoutForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Mencegah form submit secara default

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const headers = new Headers();
        headers.append('X-CSRF-TOKEN', token);
        headers.append('Content-Type', 'application/json');

        fetch(this.action, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Logout berhasil!');
                window.location.href = data.redirect; // Redirect ke halaman login setelah logout
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat logout!');
        });
    });
</script> --}}
</div>


</body>
</html>