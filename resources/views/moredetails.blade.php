@extends('layout.layout')

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
    @parent {{-- Tambahkan script yang ada di parent --}}

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
@endsection
