@extends('admin.layoutadm.layoutadm')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-10 offset-1">
                    <h1 class="m-0 text-center" style="color: #3200af;">More Details</h1>
                </div>
            </div>
        </div>
    </div>
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
                <div class="card border border-0 shadow-none">
                </div>
                    <div class="col-md-7" style="color: #3200af;">
                        <div class="card">
                            <div class="card-body">
                                <p><span class="text-bold">Nama:</span>{{ session('fullname') ?? 'N/A' }}</p>
                                <p><span class="text-bold">User Name:</span>{{ session('Username') ?? 'N/A' }}</p>
                                <p><span class="text-bold">Birthday:</span>{{session('dateofbirth') ?? 'N/A' }}</p>
                                <p><span class="text-bold">Gender:</span>{{ session('gender') === 0 ? 'Female' : 'Male' }}</p>
                                <p><span class="text-bold">Email:</span>{{ session('email') ?? 'N/A' }}</p>
                                <p><span class="text-bold">Phone Number:</span>{{  session('phone') ?? 'N/A' }}</p>
                                <p><span class="text-bold">User Role:</span> {{ session('user_role') ?? 'N/A' }}</p>
                                <a href="{{ route('showedituseradm') }}">
                                    <button type="submit" class="btn rounded text-light" style="background-color: #7773d4;">
                                        edit
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    @parent {{-- Tambahkan script yang ada di parent --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var menuMoreDetails = document.getElementById('menu-more-details');

            // Tambahkan title pada hover
            menuMoreDetails.addEventListener('mouseover', function() {
                menuMoreDetails.setAttribute('title', 'Click to view more details');
            });

            // Hapus title saat mouse leave
            menuMoreDetails.addEventListener('mouseleave', function() {
                menuMoreDetails.removeAttribute('title');
            });

            // Menambahkan keterangan tooltip saat menu diklik
            menuMoreDetails.addEventListener('click', function() {
                if (!menuMoreDetails.classList.contains('menu-active')) {
                    menuMoreDetails.classList.add('menu-active');
                    menuMoreDetails.setAttribute('title', 'Details are visible');
                } else {
                    menuMoreDetails.classList.remove('menu-active');
                    menuMoreDetails.removeAttribute('title');
                }
            });

            // Tambahkan event listener untuk setiap menu action
            var actionMenus = document.querySelectorAll('.action-menu');
            actionMenus.forEach(function(menu) {
                menu.addEventListener('mouseover', function() {
                    var menuAction = menu.getAttribute('data-menu-action');
                    menu.setAttribute('title', menuAction);
                });

                menu.addEventListener('mouseleave', function() {
                    menu.removeAttribute('title');
                });

                menu.addEventListener('click', function() {
                    if (!menu.classList.contains('menu-active')) {
                        menu.classList.add('menu-active');
                        var menuAction = menu.getAttribute('data-menu-action');
                        menu.setAttribute('title', menuAction);
                    } else {
                        menu.classList.remove('menu-active');
                        menu.removeAttribute('title');
                    }
                });
            });
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

        /* Gaya untuk hover pada menu */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        /* Nama menu saat diklik */
        .menu-name {
            color: #3200af;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            cursor: pointer;
        }

        /* Efek hover saat menu diklik */
        .menu-active {
            background-color: #f0f0f0;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }
    </style>
@endsection
