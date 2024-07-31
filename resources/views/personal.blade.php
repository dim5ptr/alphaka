@extends('layout.settings')

@section('content')
    <!-- Header Konten (Header Halaman) -->
    <div class="content-header">
        <div class="container-fluid">
            <a href="{{ route('dashboard') }}">
                <i class="fa-solid fa-house fa-xl" style="color: #7773d4;"></i>
            </a>

            <h1 class="m-0 text-center" style="color: #3200af;">Your Profile</h1>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Konten Utama -->
    <section class="content">
        <div class="container" style="margin-right: 20px;">
            <div class="row">
                <div class="col-md-5">
                    <!-- Menampilkan foto profil -->
                    <div class="card border border-0 shadow-none" >
                        <div class="card-body"  style="background-color: #e1e5f8;">
                            @if(session('profile_picture'))
                                <form method="GET" action="{{ route('show.upload.profile.picture') }}" enctype="multipart/form-data" class="profile-upload-link">
                                    @csrf
                                    <button type="submit" class="btn btn-link">
                                        <img id="profile_picture" src="{{ session('profile_picture') ? asset(session('profile_picture')) : '' }}" alt="Foto Profil" class="img-fluid rounded-circle profile-picture">
                                        <div class="upload-text">Upload New Profile</div>
                                    </button>
                                </form>
                            @else
                                <form method="GET" action="{{ route('show.upload.profile.picture') }}" enctype="multipart/form-data" class="profile-upload-link">
                                    @csrf
                                    <button type="submit" class="btn btn-link">
                                        <i class="fas fa-user-circle fa-5x rounded-circle profile-icon"></i>
                                        <div class="upload-text"><small>Upload New Profile</small></div>
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="col-md-7" style="color: #3200af;">
                    <div class="card">
                        <div class="card-body">
                            <p><span class="text-bold">Nama:</span> {{ $personalInfo['fullname'] }}</p>
                            <p><span class="text-bold">User Name:</span> {{ $personalInfo['username'] }}</p>
                            <p><span class="text-bold">Birthday:</span> {{ $personalInfo['dateofbirth'] }}</p>
                            <p><span class="text-bold">Gender:</span> {{ $personalInfo['gender'] == 0 ? 'Female' : 'Male' }}</p>
                            <p><span class="text-bold">Email:</span> {{ session('email') }}</p>
                            <p><span class="text-bold">Phone Number:</span> {{ $personalInfo['phone'] }}</p>
                            <a href="{{ route('showeditpersonal') }}">
                                <button type="submit" class="btn rounded text-light" style="background-color: #7773d4;">
                                    edit
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('script')
    @parent {{-- Ini akan menambahkan script yang ada di parent --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <script>
        // Check if there's a success message in the session, then show Toastr
        $(document).ready(function() {
            @if(session('success'))
                toastr.success('{{ session('success') }}');
            @endif
            @if(session('error'))
                toastr.success('{{ session('error') }}');
            @endif

            var profilePictureFilename = localStorage.getItem('profile_picture');
            // Jika ada nama file gambar, tambahkan path ke gambar dan atur sebagai sumber gambar
            if (profilePictureFilename) {
                var profilePicturePath = '{{ asset('profile_pictures') }}/' + profilePictureFilename;
                document.getElementById('profile_picture').src = profilePicturePath;
            }
        });
    </script>

    <style>
        .profile-upload-link {
            display: block;
            text-align: center;
            color: #808080;
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
            width: 240px; /* Tentukan ukuran yang diinginkan untuk foto profil */
            height: 240px; /* Tentukan ukuran yang diinginkan untuk foto profil */
        }

        /* Mengubah warna ikon menjadi abu-abu */
        .btn-link .fas.fa-user-circle {
            color: #808080; /* Kode warna abu-abu */
        }
        
        .profile-icon {
            font-size: 280px; /* Mengatur ukuran ikon */
        }

    </style>
@endsection
