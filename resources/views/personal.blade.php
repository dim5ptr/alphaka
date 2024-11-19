@extends('layout.userlayout')

@section('head')
    <title>Profile</title>
@endsection

@section('content')
<style>
    /* CSS Anda disini */
    html, body {
        height: 70vh;
        margin: 0;
        padding: 0;
        background-color: #d5def7;
    }

    body {
        transition: margin-left 0.3s;
    }

    .main-content {
        width: 80%;
        height: 40vh;
        flex: 1;
        /* margin-top: 5%; */
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
        margin: 2% auto;
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

    .modal-image-footer {
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

    .alert-danger {
        color: #721c24;
        background-color: #f8 d7da;
        border-color: #f5c6cb;
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
        padding: 25px 20px;
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
        width: 250px; /* Adjust to desired size */
        height: 250px;
        position: relative;
        margin-left: 3%;
        margin-right: 3%;
        border-radius: 50%;
        overflow: hidden; /* Ensures the image stays within the circular shape */
    }

    .foto img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover; /* Ensures the image fills the container while keeping aspect ratio */
        border-radius: 50%;
    }

    .editfoto {
        width: 100%; /* Matches the width and height of .foto */
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease-out;
    }

    .editfoto button {
        background: none;
        border: none;
        font-size: 2rem;
        color: white;
    }

    .foto:hover .editfoto {
        opacity: 1; /* Show overlay on hover */
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
        margin: 10px 10px 5px 10px;
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
        .main-content {
            width: 100%;
            margin-left: 0;
        }

        .judul {
            width: 90%;
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

<section id="main-content" class="main-content">
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
                <div class="foto">
                    @if (session('profile_picture'))
                        <img id="profile_picture" src="{{ asset(session('profile_picture')) }}" alt="Foto Profil" class="img-fluid rounded-circle profile-picture">
                    @else
                        <img id="profile_picture" src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(session('email')))) }}?s=200&d=mp" alt="Foto Profil" class="profile-picture">
                    @endif

                    <div class="editfoto">
                        <button type="button" onclick="openImageModal()">
                            <i class="fas fa-image me-2"></i>
                        </button>
                    </div>
                </div>

                <div class="data">
                    <p><span class="text-bold"><strong>User Name:</strong> {{ $personalInfo['username'] ?? 'N/A' }}</span></p>
                    <p class="text-bold"><strong>Nama:</strong> {{ $personalInfo['fullname'] ?? 'N/A' }}</p>
                    <p class="text-bold"><strong>Birthday:</strong> {{ $personalInfo['dateofbirth'] ?? 'N/A'}}</p>
                    <p class="text-bold"><strong>Gender:</strong> {{ $personalInfo['gender'] == 0 ? 'Female' : 'Male' }}</p>
                    <p class="text-bold"><strong>Email:</strong> {{ session('email') ?? 'N/A'}}</p>
                    <p class="text-bold"><strong>Phone Number:</strong> {{ $personalInfo['phone']?? 'N/A' }}</p>
                    <div class="btn-container">
                        <button type="button" class="btn btn-light" onclick="openModal()">
                            <i class="fas fa-user-edit me-2"></i> Edit Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="updateUser Modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="updateUser ModalLabel">Ubah Profil</h1>
                    <button type="button" class="btn-close" onclick="closeModal()">×</button>
                </div>
                <form action="{{ route('editpersonal') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" name="fullname" id="name" class="form-control" placeholder="Enter your Full Name" value="{{ session('full_name') }}">
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Enter your Username" value="{{ session('username') }}">
                    </div>
                    <div class="form-group">
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
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>

        <div class="modal-image" id="updateImageModal">
            <div class="modal-image-content">
                <div class="modal-image-header">
                    <h1 class="modal-title" id="updateImageModalLabel">Change Profile Image</h1>
                    <button type="button" class="btn-close" onclick="closeImageModal()">×</button>
                </div>
                <form method="POST" action="{{ route('upload.profile.picture') }}" enctype="multipart/form-data">
                    @csrf
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
</section>

<script>
    function redirectToGravatar() {
        fetch('{{ route("gravatar") }}',
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email: '{{ session("email") }}'
                })
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else if (response.ok) {
                    return response.json();
                } else {
                    window.location.href = 'https://gravatar.com';
                }
            })
            .then(data => {
                if (data.gravatarUrl) {
                    window.location.href = data.gravatarUrl;
                } else {
                    window.location.href = 'https://gravatar.com';
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                window.location.href = 'https://gravatar.com';
            });
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
        document.getElementById('updateUser Modal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('updateUser Modal').style.display = 'none';
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
</script>
@endsection
