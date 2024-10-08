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
            display: inline;
            height: auto;
            z-index: 5;
            background: none;
        }

        .open-btn button {
            display: inline;
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

    /* Content Header */
        section {
            max-width: 100%;
            height: 100%;
            margin: 5% auto;
            padding: 3px;
            justify-content: center;
            align-items: center;
        }

        .container {
            margin-top: 3%;
            transition: 0.3s ease-out;
            position: relative;
        }

        .infoo {
            align-content: center;
            display: inline-flex;
            width: 20%;
            padding-right: 0.5%;
            justify-content:flex-end;
        }

        .filter {
            margin-top: 3.5%;
            align-content: center;
            padding-right: 10%;
            margin-right: 10%;
            height: 45px;
            border-right: 5px solid #365AC2;
        }

        #filterDropdown {
 padding: 10px 40px 10px 15px;
 font-size: 1rem;
    border: 2px solid #365AC2;
    border-radius: 5px;
    color: #333;
    outline: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

/* Hover and focus effect on dropdown */
#filterDropdown:hover,
#filterDropdown:focus {
    background-color: #ffffff;

}


    /* Button Styles */
    .btn-primary {
        padding: 10px 10px;
        background-color: #365AC2;
        color: white;
        border: none;
        border-radius: 20px;
        font-size: 0.890rem;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #2e4a8c;
        transform: scale(1.05);
    }

    .btn-primary i {
        margin-right: 2%;
        font-size: 2rem;
    }

    img {
        float: right;
        width: 50%;
    }

    .add {
        float: right;
        margin-right: 3%;/* Atur padding sesuai kebutuhan */
    }

    footer {
        position: fixed;
        bottom: 5%;
        left: 0;
        width: 100%;
        z-index: 900; /* Pastikan footer berada di bawah tombol "Buat Organisasi" */
    }

    .display {
    width: 90%;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-left: 5%;

}

/* Card styling */
.card {
    background: #ffffff;
    border: 1px solid #dddddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: calc(33.333% - 20px); /* Adjust width to fit the container with gaps */
    box-sizing: border-box;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

/* Card body styling */
.card-body {
    padding: 20px;
    background: #ffffff;
    border: 1px solid #dddddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: calc(33.333% - 20px); /* Adjust width to fit the container with gaps */
    box-sizing: border-box;
    transition: transform 0.3s ease, box-shadow 0.3s ease;

}

.card-id {
    display: flex;
    width: 100%;
}

.card-data {
    width: 80%;
    display: -webkit-box;
    -webkit-line-clamp: 3; /* Batasi hingga 3 baris */
    -webkit-box-orient: vertical;
    overflow: hidden;     /* Sembunyikan teks yang melebihi batas */
    text-overflow: ellipsis;
    margin-bottom: 5%;
    }

.card-id img {
    float: right;
    width: 15%;
    height: 15%;
}

.card-title {
    font-size: 1.2rem;
    margin-bottom: 10px;
    color: #333;
}

.card-description {
    font-size: 1rem;
    color: #666;
}

/* Card footer styling */
.card-footer {
    padding: 10px 20px;
    height: 25%;
    justify-content: flex-end;
    border-top: 1.5px solid #dddddd;
}

.members-count {
    margin-top: 2%;
   float: left;
    width: 30%;
    height: 70%;
    font-size: 0.790rem;
    text-align: center;
    border-radius: 10px;
    background-color: #d5d5d5ac;
    padding-top: 2%;
}

.members-count p {
    margin: 0;
    padding-top: 3%;
}

.members-count i{
    margin-right: 5%;
}

.card-footer .btn {
    text-decoration: none;
    margin-top: 2%;
    float: right;
    padding: 10px 20px;
    font-size: 1rem;
    color: #ffffff;
    background: #007bff;
    border-radius: 10px;
    transition: background 0.3s ease;
}

.card-footer .btn:hover {
    background: #0056b3;
}


.alert {
        padding: 15px 15px 20px;
        border: 1px solid transparent;
        text-align: center;
        position: relative;
        width: 40%;
        height: 3%;
        margin-left: 25%;
        font-size: 100%;
        justify-content: center;
        transition: 0.3s ease-out;

    }

    .alert-success {
        color: white;
        background-color: #1363DF;
        border-color: #c3e6cb;
        font-weight: bold;
        border-radius: 5px;
        z-index: 3;
    }

    .alert-info {
        position: relative;
        width: auto;
        margin: 10%;
        justify-content: center;
        align-items: center;
        color: #0c5460;
        font-weight: bolder;
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

    .alert button {
        width: auto;
        height: auto;
        font-size: 1rem;
        border: none;
        background: none;
        color: white;
    }

/* Responsive Adjustments */
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

    footer {
        width: 95%;
    }
    .main-content {
        position: relative;
        margin-left: 0;
        width: 80%;
        height: 90%;
    }

    .card {
        width: calc(50% - 20px); /* Adjust for smaller screens */
        height: 40%;
    }

    .card-body {
        width: calc(50% - 20px); /* Adjust for smaller screens */
    }

    .logout-button{
        top: 50%;
    }

    .alert {
        font-size: 70%;
    }
}

@media (max-width: 480px) {

    .main-content {
        margin-top: 15%;
        margin-left: 0;
        width: 100%;
    }

    .card {
        width: calc(100% - 20px); /* Adjust for very small screens */
    }

    .card-body {
        width: calc(100% - 20px); /* Adjust for very small screens */
        font-size: 0.890rem;
    }

}

/* Media Query untuk layar kecil (ponsel) */
@media (max-width: 768px) {
    .infoo {
        justify-content: center; /* Sentralisasi di layar kecil */
    }

    .filter {
        padding-right: 5%; /* Atur ulang padding agar sesuai dengan layar kecil */
        margin-right: 5%;
        border-right: 3px solid #365AC2; /* Kurangi ketebalan border */
    }

    #filterDropdown {
        padding: 10px 20px 10px 10px; /* Kurangi padding agar pas di layar kecil */
        font-size: 0.9rem; /* Sesuaikan font untuk layar kecil */
        border-radius: 3px;
    }
}

/* Media Query untuk layar sangat kecil (di bawah 480px) */
@media (max-width: 480px) {
    .infoo {
        /* Stack elemen secara vertikal */
        align-items: center; /* Sentralisasi konten */
        width: 40%;
        font-size: 0.780rem;
        margin-right: 4%;
    }

    .infoo p{
        width: 50%;
    }

    .filter {
        padding-left: 15%;
        margin-top: 5%; /* Tambahkan margin atas */
        margin-right: 0;
        padding-right: 0;
        width: 30%; /* Ambil lebar penuh */
        border-right: none; /* Hapus border untuk tampilan lebih sederhana */
    }

    #filterDropdown {
        width: 50%; /* Ambil lebar penuh pada layar kecil */
        padding: 8px 10px;
        font-size: 0.8rem; /* Font lebih kecil */
    }
}



    </style>
</head>
<body>
    <nav class="navbar">
        <div class="open-btn">
            <button onclick="toggleSidebar()">&#9776; Organization</button>
        </div>
        <div class="infoo">
        <div class="filter">
            <select id="filterDropdown" onchange="filterOrganizations(this.value)">
                <option value="all">All</option>
                <option value="owner">Mine</option>
                <option value="member">Added By</option>
            </select>
        </div>
        <p class="p1"><span>{{ \Carbon\Carbon::now()->format('l') }},</span><br>{{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
        </div>
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
        <section class="content">
            @if (session('success_message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success">
                    {{ session('success_message') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" id="alert-success"></button>
                </div>
            @endif

            <div class="container">

                @if(isset($organizations))
                    <div class="display" id="organization-list">
                        @foreach($organizations as $organization)
                            <div class="card-body organization-card" data-type="{{ $organization['type'] }}">
                                <div class="card-id">
                                    <div class="card-data">
                                        <h3 class="card-title">{{ $organization['organization_name'] }}</h3>
                                        <p class="card-description">{{ $organization['description'] }}</p>
                                    </div>
                                    <img id="profile_picture" src="{{ asset('img/user.png') }}" alt="Foto Profil" class="profile-picture">
                                </div>
                                <div class="card-footer text-center">
                                    <div class="members-count">
                                        <p><i class="fa-solid fa-user-group"></i>{{ $organization['members_count'] ?? 0 }}</p>
                                    </div>
                                    <a href="{{ route('showvieworganization', ['organization_name' => $organization['organization_name']]) }}" class="btn btn-secondary">Lihat</a>
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

        <footer>
            <div class="add">
                <form action="{{ route('showcreateorganization') }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-primary rounded">
                        <i class="fas fa-plus"></i>
                    </button>
                </form>
            </div>
        </footer>
    </div>

    <script>
    // JavaScript function to filter organizations based on type and update the selected filter label
    function filterOrganizations(type) {
        const cards = document.querySelectorAll('.organization-card');
        const selectedFilter = document.getElementById('selectedFilter');

        // Filter logic
        cards.forEach(card => {
            if (type === 'all') {
                card.style.display = 'block';
            } else if (card.dataset.type === type) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
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
