@extends('admin.layoutadm.layoutadm')

@section('content')
<div id="main-content" class="main-content">
    <section class="content">
        <div class="container-flex">
            <div class="judul">
                <h4>More Details</h4>
            </div>
            <div class="profile-info">
                <div class="foto">
                    @if (session('profile_picture'))
                        <img id="profile_picture" src="{{ asset(session('profile_picture')) }}" alt="Profile Picture" class="img-fluid rounded-circle profile-picture">
                    @else
                        <img id="profile_picture" src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(session('email')))) }}?s=200&d=mp" alt="Profile Picture" class="profile-picture">
                    @endif
                </div>
                <div class="data">
                    <p><span class="text-bold">Name:</span> {{ session('fullname') ?? 'N/A' }}</p>
                    <p><span class="text-bold">Birthday:</span> {{ session('dateofbirth') ?? 'N/A' }}</p>
                    <p><span class="text-bold">Gender:</span> {{ session('gender') === 0 ? 'Female' : 'Male' }}</p>
                    <p><span class="text-bold">Email:</span> {{ session('email') ?? 'N/A' }}</p>
                    <p><span class="text-bold">Phone Number:</span> {{ session('phone') ?? 'N/A' }}</p>
                    <p><span class="text-bold">User Role:</span>
                        {{ (int) session('user_role') === 1 ? 'PENGGUNA' : ((int) session('user_role') === 2 ? 'ADMIN' : 'PENGGUNA') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
html, body {
    font-family: Arial, sans-serif;
    color: #333;
}

.main-content {
    width: 80%;
    margin: 5% auto;
    transition: margin-left .3s;
}

.container-flex {
    max-width: 100%;
    background-color: white;
    margin: 2% auto;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 2%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.judul {
    width: 100%;
    background-color: #0056b3;
    color: white;
    padding: 20px;
    border-radius: 10px 10px 0 0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.judul h4 {
    font-size: 24px;
    margin: 0;
}

.profile-info {
    display: flex;
    flex-direction: row;
    align-items: center;
    padding: 2% 5%;
    width: 100%;
}

.foto {
    flex-shrink: 0;
    margin-right: 20px;
}

.profile-picture {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 3px solid #7773d4;
}

.data {
    flex: 1;
}

.data p {
    font-size: 16px;
    margin: 8px 0;
    color: #555;
}

.text-bold {
    font-weight: bold;
}

@media (max-width: 768px) {
    .main-content {
        width: 95%;
    }

    .profile-info {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .foto {
        margin-right: 0;
        margin-bottom: 15px;
    }

    .profile-picture {
        width: 120px;
        height: 120px;
    }

    .data p {
        font-size: 14px;
    }
}
</style>
@endsection
