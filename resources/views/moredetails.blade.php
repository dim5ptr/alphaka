@extends('layout.userlayout')

@section('content')

        <section class="content">
            <nav class="bc-pr">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('organization') }}" style="color: #3200af;">Organization</a></li>
                    <li class="breadcrumb-item" style="color: #3200af;"><a href="{{ route('showvieworganization', ['organization_name' => $organization['organization_name']]) }}" style="color: #3200af;">{{ $organization['organization_name'] }}</a></li>
                    <li class="breadcrumb-item" aria-current="page" style="color: #3200af;">More Details</li>
                </ol>
            </nav>
            <div class="container-flex">
                <div class="judul">
                    <h4>More Details</h4>
                </div>
                <div class="profile-info">
                    <div class="foto">
                        @if (session('profile_picture'))
                        <img id="profile_picture" src="{{ asset(session('profile_picture')) }}" alt="Foto Profil" class="img-fluid rounded-circle profile-picture">
                        @else
                            <img id="profile_picture" src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(session('email')))) }}?s=200&d=mp" alt="Foto Profil" class="profile-picture">
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

<style>
     /* CSS Anda disini */

    section {
        padding: 8%;
    }

        .main-content {
            width: 80%;
            height: 40vh;
            flex: 1;
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

            section {
        padding-top: 20%;
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
@endsection
