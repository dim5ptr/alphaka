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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

<style>
     /* CSS Anda disini */
     html, body {
            height: 70vh;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
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

.sidebar.open {
    left: 0;
}

.sidebar .sidebar-isi {
    display: block;
    padding: 0;
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
    border: none;
    transition: 0.3s;
    z-index: 1001;
    background: none;
}

.open-btn:hover {
    color: darkblue;
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

        .main-content {
            width: 80%;
            height: 40vh;
            flex: 1;
            margin-top: 5%;
            margin-left: 10%;
            transition: margin-left .3s;
        }

        .banner {
            float: right;
            margin-right: 10%;
            margin-top: 3%;
            width: 80%;
            height: 50vh;
            margin-left: 4%;
        }

        .btn-primary, .btn-danger, .btn-light {
        padding: 8px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 4px;
        border: none;
        transition: background-color 0.3s ease;
    }

    .btn-primary {
        background-color: #365AC2;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-light {
        background-color: #365AC2;
        color: white;
    }

    .btn-light:hover {
        background-color: #0e4988;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1001;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 2% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 10px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e5e5e5;
        background-color: #365AC2;
        color: white;
        padding: 10px 20px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        margin-bottom: 2.1%;
    }

    .modal-header h1 {
        margin: 0;
        font-size: 24px;
    }

    .btn-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: white;
    }

    .form-control {
        width: 96%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        font-size: 14px;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        border-top: 1px solid #e5e5e5;
        padding-top: 10px;
        margin-top: 20px;
    }

    .alert {
        padding: 15px;
        border: 1px solid transparent;
        text-align: left;
        position: absolute;
        width: 40%;
        height: 3%;
        margin-left: 20%;
        font-size: 0.890rem;
        justify-content: center;
        z-index: 1001;
    }

    .alert-success {
        color: white;
        background-color: #1363DF;
        border-color: #c3e6cb;
        font-weight: bold;
        border-radius: 5px;
    }

    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }

    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .modal-image {
        display: none;
        position: fixed;
        z-index: 1001;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-image-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 10px;
    }

    .modal-image-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e5e5e5;
        background-color: #365AC2;
        color: white;
        padding: 10px 20px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        margin-bottom: 2.1%;
    }

    .modal-image-header h1 {
        margin: 0;
        font-size: 24px;
    }

    .btn-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: white;
    }

    .container-flex {
        max-width: 100%;
        background-color: white;
        margin: 4% auto;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .judul {
        width: 95%;
        margin-top: 2%;
        padding: auto;
        padding-top: 25px;
        padding-bottom: 25px;
        padding-left: 20px;
        border-radius: 15px;
        background-color: #0056b3;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        color: white;
    }

    .judul h4 {
        margin: 0;
        font-size: 24px;
        text-align: left;
    }

    .profile-info {
        display: inline-flex;
        align-items: center;
        margin-left: 5%;
        width: 100%;
    }



    .foto {
        height: 30%;
        position: relative;
        margin-left: 3%;
        margin-right: 3%;
        border-radius: 100%;
    }

    .foto img {
        widows: 100%;
        display: block;
        margin: auto;
        border-radius: 100%;
    }

    .editfoto{
        margin-left: 2.5%;
        margin-top: 2%;
        width: 95%;
        height: 95%;
        position: absolute;
        top: 0;
        left: 0;
        border-radius: 50%;
        background: #00000039;
        display: flex;
        justify-content: center;
        opacity: 0;
       transition: 0.3ms ease-out;
    }

    .editfoto button{
       background: none;
       border: none;
       font-size: 2rem;
       color: white;
    }

    .editfoto:hover {
        opacity: 100%;
    }

    .editfoto > * {
        transform: translateY(25px);
        transition: transform 0.3s;
    }

    .editfoto:hover > * {
        transform: translateY(0px);
    }

    .profile-info .data {
        flex: 1;
        margin: 10px 10px 5px 10px
    }

    .profile-info .data span {
        font-size: 20px;
        color: #000000;
    }

    .profile-info .data p {
        font-size: 16px;
        color: rgb(92, 89, 89);
    }

    .btn-container {
        display: flex;
        justify-content: flex-start;
        margin-top: 10px;
        margin-bottom: 5%;
    }

   

    .is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 0.875em;
}

.profile-upload-link {
            display: block;
            text-align: center;
            outline: none;
            position: relative;
        }

        .upload-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            display: none;
            z-index: 1;
        }

        .profile-upload-link:hover .upload-text {
            display: block;
        }

        .profile-upload-link:hover .profile-icon {
            color: #3200af;
        }

        .profile-icon {
            transition: color 0.3s ease;
        }

        .profile-picture {
            width: 200px; /* Tentukan ukuran yang diinginkan untuk foto profil */
            height: 200px; /* Tentukan ukuran yang diinginkan untuk foto profil */
        }

        /* Mengubah warna ikon menjadi abu-abu */
        .btn-link .fas.fa-user-circle {
            color: #808080; /* Kode warna abu-abu */
        }

        .profile-icon {
            font-size: 280px; /* Mengatur ukuran ikon */
        }

        .breadcrumb {
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
    padding: 0;
    margin: 2%;
    margin-top: 20%;
    list-style: none;
    width: 100%;
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

        @media (max-width: 768px) {

.profile-info img {
    /* Tambahkan jarak bawah antara gambar profil dan teks */
}

.profile-icon {
    font-size: 200px;
    margin-bottom: 20px; /* Jarak bawah antara ikon profil dan konten lainnya */
}

.modal-content, .modal-image-content {
    width: 100%;
    max-width: 80%;
    margin: 20px auto; /* Tambahkan margin agar modal tidak terlalu dekat dengan tepi viewport */
}

.btn-primary, .btn-light, .btn-danger {
    margin: 10px 0; /* Tambahkan margin vertikal pada tombol agar tidak berdempetan dengan elemen lain */
}

.main-content{
    justify-content: center;
    align-content: center;
    align-items: center;
}
.banner {
    width: 100%;
    justify-content: center;
    align-items: center;
    margin-right: 0;
}

.container-flex {
    margin-top: 50px;
    width: 100%;
    }

.editfoto{
    margin-left: 1%;
    margin-top: 15%;
    width: 98%;
    height: 80%;
}

    .judul{
        width: 85%;
        margin-bottom: 5%;
        margin-top: 4%;
    }

    .judul h4{
    margin: 0;
    font-size: 20px;
    text-align: left;
}

    .profile-info {
        flex-direction: column;
        align-items: center;
    }

    .profile-info img {
        width: 130px;
        height: auto;
        margin-bottom: 10px;
        margin-top: 20px;
    }

    .profile-info .data span, .profile-info .data p {
        text-align: left;
    }

}

        @media (max-width: 768px) {

.profile-info img {
    /* Tambahkan jarak bawah antara gambar profil dan teks */
}

.profile-icon {
    font-size: 200px;
    margin-bottom: 20px; /* Jarak bawah antara ikon profil dan konten lainnya */
}

.modal-content, .modal-image-content {
    width: 100%;
    max-width: 80%;
    margin: 20px auto; /* Tambahkan margin agar modal tidak terlalu dekat dengan tepi viewport */
}

.btn-primary, .btn-light, .btn-danger {
    margin: 10px 0; /* Tambahkan margin vertikal pada tombol agar tidak berdempetan dengan elemen lain */
}

.main-content{
    justify-content: center;
    align-content: center;
    align-items: center;
}
.banner {
    width: 100%;
    justify-content: center;
    align-items: center;
    margin-right: 0;
}

.container-flex {
    margin-top: 50px;
    width: 100%;
    }

.editfoto{
    margin-left: 1%;
    margin-top: 15%;
    width: 98%;
    height: 80%;
}

    .judul{
        width: 85%;
        margin-bottom: 5%;
        margin-top: 4%;
    }

    .judul h4{
    margin: 0;
    font-size: 20px;
    text-align: left;
}

    .profile-info {
        flex-direction: column;
        align-items: center;
    }

    .profile-info img {
        width: 130px;
        height: auto;
        margin-bottom: 10px;
        margin-top: 20px;
    }

    .profile-info .data span, .profile-info .data p {
        text-align: left;
    }

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
        <br>
        <nav class="bc-pr">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('organization') }}" style="color: #3200af;">Organization</a></li>
                <li class="breadcrumb-item" style="color: #3200af;"><a href="{{ route('showvieworganization', ['organization_name' => $organization['organization_name']]) }}" style="color: #3200af;">{{ $organization['organization_name'] }}</a></li>
                <li class="breadcrumb-item" aria-current="page" style="color: #3200af;">More Details</li>
            </ol>
        </nav>
        <section class="content">
            <div class="container-flex">
                <div class="judul">
                    <h4>More Details</h4>
                </div>
                <div class="profile-info">
                    <div class="foto">
                        @if(session('profile_picture'))
                            <img id="profile_picture" src="{{ asset(session('profile_picture')) }}" alt="Profile Picture" class="img-fluid profile-picture">
                        @else
                        <img id="profile_picture" src="{{ asset('img/user.png') }}"  alt="Foto Profil" class="profile-picture">
                        @endif
                    </div>
                    <div class="data">
                        <p><span class="text-bold">Name:</span> {{ session('fullname') ?? 'N/A' }}</p>
                        <p><span class="text-bold">Birthday:</span> {{ session('dateofbirth') ?? 'N/A' }}</p>
                        <p><span class="text-bold">Gender:</span> {{ session('gender') === 0 ? 'Female' : 'Male' }}</p>
                        <p><span class="text-bold">Email:</span> {{ session('email') ?? 'N/A' }}</p>
                        <p><span class="text-bold">Phone Number:</span> {{ session('phone') ?? 'N/A' }}</p>
                        <p><span class="text-bold">User Role:</span> {{ session('user_role') ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </section>

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

{{-- @extends('layout.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <!-- Breadcrumbs for navigation history -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: transparent;">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" style="color: #7773d4;">Dashboard</a> <!-- Kembali ke Dashboard -->
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('organization') }}" style="color: #7773d4;">Organization</a> <!-- Kembali ke Organization -->
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('showvieworganization', ['organization_name' => $organization['organization_name']]) }}" style="color: #3200af;">{{ $organization['organization_name'] }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #3200af;">More Details</li> <!-- Halaman saat ini -->
                </ol>
            </nav>

            <!-- Title for the page -->
            <div class="row">
                <div class="col-10 offset-1">
                    <h1 class="m-0 text-center" style="color: #3200af;">More Details</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Konten Utama -->
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <!-- Menampilkan foto profil -->
                    <div class="card border border-0 shadow-none">
                        <div class="card-body" style="background-color: #e1e5f8;">
                            @if(session('profile_picture'))
                                <img id="profile_picture" src="{{ asset(session('profile_picture')) }}" alt="Foto Profil" class="img-fluid rounded-circle profile-picture">
                            @else
                                <i class="fas fa-user-circle fa-5x rounded-circle profile-icon"></i>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-7" style="color: #3200af;">
                    <div class="card">
                        <div class="card-body">
                            <p><span class="text-bold">Nama:</span> {{ session('fullname') ?? 'N/A' }}</p>
                            <p><span class="text-bold">Birthday:</span> {{ session('dateofbirth') ?? 'N/A' }}</p>
                            <p><span class="text-bold">Gender:</span> {{ session('gender') === 0 ? 'Female' : 'Male' }}</p>
                            <p><span class="text-bold">Email:</span> {{ session('email') ?? 'N/A' }}</p>
                            <p><span class="text-bold">Phone Number:</span> {{ session('phone') ?? 'N/A' }}</p>
                            <p><span class="text-bold">User Role:</span> {{ session('user_role') ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('script')
    @parent {{-- Tambahkan script yang ada di parent

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <script>
        $(document).ready(function() {
            @if(session('success'))
                toastr.success('{{ session('success') }}');
            @endif
            @if(session('error'))
                toastr.error('{{ session('error') }}');
            @endif
        });
    </script>

    <style>
        .profile-picture {
            width: 280px; /* Ukuran foto profil */
            height: 280px;
        }

        .profile-icon {
            font-size: 240px; /* Ukuran ikon */
        }
    </style>
@endsection --}}
