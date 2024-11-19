@extends('layout.userlayout')
@section('head')
    <title>Organization</title>
@endsection

@section('content')
<style>
    /* CSS Enhancements */

    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        background-color: #d5def7;
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
    z-index: 1; /* Pastikan footer berada di bawah tombol "Buat Organisasi" */
}

    .filter-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-left: 3%;
    }

    #filterDropdown, #sortDropdown {
        padding: 10px 15px;
        font-size: 1rem;
        border: 2px solid #365AC2;
        border-radius: 5px;
        color: #333;
        outline: none;
        cursor: pointer;
        transition: background-color 0.3s ease, border-color 0.3s ease;
        background-color: #fff;
    }

    #filterDropdown:hover,
    #filterDropdown:focus,
    #sortDropdown:hover,
    #sortDropdown:focus {
        background-color: #f0f0f0;
        border-color: #2e4a8c;
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
    padding-top: 15%;
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

.filter {
    margin-left: 0px;
}
.infoo {
    justify-content: center; /* Sentralisasi di layar kecil */
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
}

</style>
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

                <div class="filter-container">
                    <div class="filter" style="margin-left: 2%;">
                        <select id="filterDropdown" onchange="filterOrganizations(this.value)">
                            <option value="all">All</option>
                            <option value="owner">Mine</option>
                            <option value="member">Added By</option>
                        </select>
                    </div>
                </div>
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
                                        <p><i class="fa-solid fa-user-group"></i>{{ $organization['member_count'] }}</p>
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

@endsection
