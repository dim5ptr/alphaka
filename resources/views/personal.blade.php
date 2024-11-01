<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Profile</title>

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


        .logout-Form {
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

    </style>
</head>
<body>
    <nav class="navbar">
        <div class="open-btn">
            <button onclick="toggleSidebar()">&#9776; Personal</button>
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
                    <a href="/personal" class="nav-link-act">
                        <span class="link"><i class="fa-solid fa-id-card"></i>ㅤProfile</span>
                    </a>
                </li>
                <li>
                    <a href="/security" class="nav-link">
                        <span class="link"><i class="fa-solid fa-user-shield"></i>ㅤSecurity</span>
                    </a>
                </li>
            </ul>
            <form id="logoutForm" class="logout-Form" method="GET" action="{{ route('confirm-logout') }}">
                @csrf
                <button type="submit" class="logout-button">ㅤ <i class="fa-solid fa-right-from-bracket"></i>ㅤLogout</button>
                </form>
        </div>
    </div>

    <div id="main-content" class="main-content">
        @if (session('success'))
        <div class="alert alert-success" id="alert-success">
            {{ session('success') }}
        </div>
        @elseif (session('error'))
        <div class="alert alert-danger" id="alert-danger">
            {{ session('error') }}
        </div>
        @endif
        <div class="banner">
            <div class="container-flex">
            <div class="judul">
                <h4>Akun Pengguna</h4>
            </div>

            <div class="profile-info">
                {{-- <div class="foto">
                @if (session('profile_picture') === '' || session('profile_picture') === null)
                    <img id="profile_picture" src="{{ asset('img/user.png') }}"  alt="Foto Profil" class="profile-picture">
                @else
                <img id="profile_picture" src="{{ session('profile_picture') ? asset(session('profile_picture')) : ''  }}"  alt="Foto Profil" class="img-fluid rounded-circle profile-picture">
                @endif
                <div class="editfoto">
                <button type="button" onclick="openImageModal()">
                    <i class="fas fa-image me-2"></i>
                </button>
                </div>
                </div> --}}
                <div class="foto">
                    @if (session('profile_picture'))
                        <img id="profile_picture" src="{{ asset(session('profile_picture')) }}" alt="Foto Profil" class="img-fluid rounded-circle profile-picture">
                    @else
                        <img id="profile_picture" src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(session('email')))) }}?s=200&d=mp" alt="Foto Profil" class="profile-picture">
                    @endif

                    <div class="editfoto">
                        <button type="button" onclick="redirectToGravatar()">
                            <i class="fas fa-image me-2"></i>
                        </button>
                    </div>
                </div>

                <div class="data">
                    <p>
                        <p><span class="text-bold"><strong>User Name:</strong> {{ $personalInfo['username'] }}</span></p>
                        <p  class="text-bold"><strong>Nama:</strong> {{ $personalInfo['fullname'] }}</p>
                        <p class="text-bold"><strong>Birthday:</strong> {{ $personalInfo['dateofbirth'] }}</p>
                        <p class="text-bold"><strong>Gender:</strong> {{ $personalInfo['gender'] == 0 ? 'Female' : 'Male' }}</p>
                        <p class="text-bold"><strong>Email:</strong> {{ session('email') }}</p>
                        <p class="text-bold"><strong>Phone Number:</strong> {{ $personalInfo['phone'] }}</p>
                    </p>
                    <div class="btn-container">
                        <button type="button" class="btn btn-light" onclick="openModal()">
                            <i class="fas fa-user-edit me-2"></i> Edit Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="updateUserModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="updateUserModalLabel">Ubah Profil</h1>
                    <button type="button" class="btn-close" onclick="closeModal()">×</button>
                </div>
                <form action="{{ route('editpersonal') }}" method="POST">
                    @csrf
                    <!-- Personal information fields -->
                    <div class="form-group" class="form-label">
                        <label for="name">Full Name</label>
                        <input type="text" name="fullname" id="name" class="form-control" placeholder="Enter your Full Name" value="{{ session('full_name') }}">
                    </div>
                    <div class="form-group" class="form-label">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Enter your Username" value="{{ session('username') }}">
                    </div>
                    <div class="form-group" class="form-label">
                        <label for="dateofbirth">Date of Birth</label>
                        <input type="date" name="dateofbirth" id="dateofbirth" class="form-control" value="{{ session('dateofbirth') }}">
                    </div>
                    <div class="form-group">
                        <label for="gender" class="form-label">Gender</label>
                        <select id="gender" name="gender" class="form-control">
                            <option value="0" {{ session('gender') == 0 ? 'selected' : '' }}>Female</option>
                            <option value="1" {{ session('gender') == 1 ? 'selected' : '' }}>Male</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" id="phone" placeholder="Enter your Phone Number" class="form-control" value="{{ session('phone') }}">
                    </div>

                    <button type="submit" class="btn btn-primary"">Save</button>
                </form>
            </div>
        </div>

        <!-- Profile Image Update Modal -->
        <div class="modal-image" id="updateImageModal">
            <div class="modal-image-content">
                <div class="modal-image-header">
                    <h1 class="modal-title" id="updateImageModalLabel">Change Profile Image</h1>
                    <button type="button" class="btn-close" onclick="closeImageModal()">×</button>
                </div>
                <form method="POST" action="{{ route('upload.profile.picture') }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Profile Image</label>
                        <input type="file" name="profile_picture" class="form-control" id="profile_picture">
                    </div>
                    <div class="modal-image-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    </div>

    <div id="updateUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h1>Update Profile Picture via Gravatar</h1>
                <button class="btn-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <iframe id="gravatarFrame" src="" width="100%" height="500px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        function redirectToGravatar() {
            fetch('{{ route("gravatar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
                },
                body: JSON.stringify({
                    email: '{{ session("email") }}' // Example of sending email from session
                })
            })
            .then(response => {
                if (response.redirected) {
                    // Handle redirect
                    window.location.href = response.url;
                } else if (response.ok) {
                    return response.json();
                } else {
                    // Redirect to Gravatar if there's any error
                    window.location.href = 'https://gravatar.com';
                }
            })
            .then(data => {
                if (data.gravatarUrl) {
                    window.location.href = data.gravatarUrl; // Redirect to Gravatar URL
                } else if (data.redirect) {
                    window.location.href = data.redirect; // Redirect to Gravatar if no profile picture
                } else {
                    window.location.href = 'https://gravatar.com'; // Fallback redirect
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                // Always redirect to Gravatar on any fetch error
                window.location.href = 'https://gravatar.com';
            });
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

        const successAlert = document.getElementById('alert-success');
            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.opacity = 0;
                    setTimeout(function() {
                        successAlert.style.display = 'none';
                    }, 500);
                }, 5000);
            }

            // Hide error alert after 5 seconds
            const errorAlert = document.getElementById('alert-danger');
            if (errorAlert) {
                setTimeout(function() {
                    errorAlert.style.opacity = 0;
                    setTimeout(function() {
                        errorAlert.style.display = 'none';
                    }, 500);
                }, 5000);
            }
        function openModal() {
        document.getElementById('updateUserModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('updateUserModal').style.display = 'none';
    }

    function openImageModal() {
        document.getElementById('updateImageModal').style.display = 'block';
    }

    function closeImageModal() {
        document.getElementById('updateImageModal').style.display = 'none';
    }

    document.querySelector('form').addEventListener('submit', function() {
        closeModal();
        closeImageModal();
    });
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const usernameInput = document.getElementById('username');
        const fullnameInput = document.getElementById('fullname');
        const phoneInput = document.getElementById('phone');
        const birthdayInput = document.getElementById('dateofbirth');
        const genderSelect = document.getElementById('gender');

        function validateUsername() {
            const username = usernameInput.value;
            if (username.length < 3) {
                showError(usernameInput, 'Username must be at least 3 characters long');
            } else {
                clearError(usernameInput);
            }
        }

        function validateFullname() {
            const fullname = fullnameInput.value;
            if (fullname.length < 3) {
                showError(fullnameInput, 'Full Name must be at least 3 characters long');
            } else {
                clearError(fullnameInput);
            }
        }

        function validatePhone() {
    const phone = phoneInput.value;
    const phonePattern = /^[0-9]{10,15}$/;

    // Check if the input is not empty and contains only digits with the correct length
    if (!phonePattern.test(phone)) {
        showError(phoneInput, 'Phone number must be between 10 and 15 digits and contain only numbers');
    } else {
        clearError(phoneInput);
    }
}


        function validateBirthday() {
            const birthday = new Date(birthdayInput.value);
            const today = new Date();
            if (birthday > today) {
                showError(birthdayInput, 'Date of Birth cannot be in the future');
            } else {
                clearError(birthdayInput);
            }
        }

        function showError(input, message) {
            input.classList.add('is-invalid');
            let error = input.nextElementSibling;
            if (!error || !error.classList.contains('invalid-feedback')) {
                error = document.createElement('div');
                error.className = 'invalid-feedback';
                input.parentElement.appendChild(error);
            }
            error.textContent = message;
        }

        function clearError(input) {
            input.classList.remove('is-invalid');
            let error = input.nextElementSibling;
            if (error && error.classList.contains('invalid-feedback')) {
                error.remove();
            }
        }

        usernameInput.addEventListener('input', validateUsername);
        fullnameInput.addEventListener('input', validateFullname);
        phoneInput.addEventListener('input', validatePhone);
        birthdayInput.addEventListener('input', validateBirthday);
    });
    </script>
</body>
</html>
