@extends('admin.layoutadm.settingsadm')
@section('content')
<div id="main-content" class="main-content">
    <section class="content">
        <div class="container-flex">
            <div class="judul">
                <h4>Account Details</h4>
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
                    <p><span class="text-bold">Name:</span> {{ session('full_name') ?? 'N/A' }}</p>
                    <p><span class="text-bold">Birthday:</span> {{ session('dateofbirth') ?? 'N/A' }}</p>
                    <p><span class="text-bold">Gender:</span> {{ session('gender') === 0 ? 'Female' : 'Male' }}</p>
                    <p><span class="text-bold">Email:</span> {{ session('email') ?? 'N/A' }}</p>
                    <p><span class="text-bold">Phone Number:</span> {{ session('phone') ?? 'N/A' }}</p>
                    <p><span class="text-bold">User Role:</span>
                        {{ (int) session('user_role') === 1 ? 'PENGGUNA' : ((int) session('user_role') === 2 ? 'ADMIN' : 'PENGGUNA') }}
                    </p>
                </div>
                <div class="button-group">
                    <a href="{{ route('showeditpersonaladm') }}" class="btn btn-primary custom-btn edit-btn" title="Edit Data">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button class="btn btn-danger custom-btn logout-btn" onclick="confirmLogout()">
                        <i class="fa fa-sign-out-alt"></i>
                    </button>
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
    margin: 10% auto;
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
    align-items: flex-start;
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
    position: relative;
}

.foto {
    flex-shrink: 0;
    margin-right: 20px;
}

.profile-picture {
    width: 150px;
    height: 150px;
    border-radius: 50%;
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

.button-group {
    position: absolute; /* Position absolute to place the button */
    bottom: 10px; /* Distance from the bottom */
    right: 10px; /* Distance from the right */
    display: flex;
    gap: 10px; /* Add space between buttons */
    margin-top: 15px; /* Add margin above buttons */
}

.custom-btn {
    padding: 10px 15px; /* Consistent padding */
    font-size: 14px; /* Adjust font size */
    border-radius: 5px; /* Rounded corners */
    display: inline-flex; /* Align icon and text properly */
    align-items: center; /* Center icon vertically */
    transition: background-color 0.3s, transform 0.2s; /* Smooth hover effect */
}

.edit-btn {
    background-color: #007bff; /* Primary color */
    color: white;
}

.deactivate-btn {
    background-color: #dc3545; /* Danger color */
    color: white;
}

.edit-btn:hover {
    background-color: #0056b3; /* Darker shade on hover */
}

.deactivate-btn:hover {
    background-color: #c82333; /* Darker shade on hover */
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
<script>
function confirmLogout() {
    Swal.fire({
        title: 'Logout',
        text: 'Are you sure you want to log out?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, log out',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("{{ route('logout') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Logged out successfully!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "{{ route('login') }}"; // Redirect to login page
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Logout failed',
                        text: 'An error occurred during logout.'
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
}
</script>
@endsection
