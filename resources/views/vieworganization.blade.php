@extends('layout.userlayout')
@section('head')
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection


@section('content')

<div id="main-content" class="main-content">
        @if (session('success'))
            <div class="alert alert-success notification" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger notification" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <br>
        <nav class="bc-pr">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('organization') }}" style="color: #3200af;">Organization</a></li>
                <li class="breadcrumb-item" style="color: #3200af;">{{ $organization['organization_name'] }}</a></li>
            </ol>
        </nav>

        <!-- Content -->
        <div class="container">
            <!-- Organization Card -->
            <div class="card-organization">
                <div class="info">
                    <h1 class="m-0">{{ $organization['organization_name'] }}</h1>
                    <small>{{ $organization['description'] }}</small>
                </div>
                <a href="{{ route('showeditorganization', ['organization_name' => $organization['organization_name']]) }}"
                    class="btn-edit">
                    <i class="fa fa-pencil-alt"></i>
                </a>
            </div>

            <!-- Member List -->
            <div class="member">
                <!-- Buttons and Search Input -->
                <div class="mb-side">
                    <div class="group-btn">
                        <i class="fas fa-user"></i> <!-- Ikon pemilik -->
                        Owner by:

                    </div>

                    <!-- Search Input with Icon -->
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-search"></i> <!-- Search Icon -->
                            </span>
                        </div>
                        <input type="search" id="searchInput" class="form-control" placeholder="Search...">
                    </div>
                    <input type="hidden" id="organizationId" value="{{ $organization['organization_id'] }}">

                </div>

                <!-- Table -->
                <div class="table-container">
                    <table id="dataTable" class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Username</th>
                                <th scope="col">Email</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="dataBody">
                            <!-- Data akan diisi di sini -->
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <footer>
            <div class="add">
                  <button type="submit" class="btn btn-primary" onclick="openModal()">
                       <i class="fas fa-plus"></i>
                   </button>
               </form>
           </div>
       </footer>
    </div>

<!-- Main Modal for Searching Users -->
<div class="modal" id="addMemberModal">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title" id="addMemberModalLabel">Add Member</h1>
            <button type="button" class="btn-close" onclick="closeModal()">×</button>
        </div>



        <form id="searchUsers" action="{{ route('searchUsers') }}" method="POST" onsubmit="return handleSearch(event)">
            @csrf
            <div class="form-group">
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter someone's email" required>
                <div id="responseMessage" class=""></div> <!-- Display found users here -->
            </div>
            <button type="submit" class="btn btn-primary btn-block">Search</button>
        </form>
        <!-- Disable the Add Member button by default -->
        <button type="button" class="btn btn-primary btn-block" id="addMemberButton" onclick="openAddedUsersModal()" disabled>Add Member</button>
    </div>
</div>

<!-- Modal for Added Users -->
<div class="modal" id="addedUsersModal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title" id="addedUsersModalLabel">Added Members</h1>
        </div>
        <div class="form-group">
            <h3>Emails Already Added</h3>
            <div id="addedUsersList">
                <!-- Example of how emails will be added dynamically -->
            </div>
        </div>
        <button type="button" class="btn btn-primary btn-block" onclick="sendEmails()">Send Email</button>
        <button type="button" class="btn btn-secondary btn-block" onclick="showSearchModal()">Previous</button>
    </div>
</div>

<style>
    /* CSS Enhancements */

    /* css modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1001;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.7); /* Darker overlay */
}

.modal-content {
    background-color: #ffffff;
    margin: 4% auto; /* Higher margin to center more vertically */
    padding: 20px;
    border: none; /* Remove border */
    width: 90%;
    max-width: 500px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
    position: relative;
    top: 50%;
    transform: translateY(-50%);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background-color: #365AC2;
    color: white;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    margin-bottom: 20px; /* Menambah jarak antara header dan konten */
}

.modal-header h1 {
    margin: 0;
    font-size: 20px;
    font-weight: bold;
}

.btn-close {
    background: none;
    border: none;
    font-size: 22px;
    cursor: pointer;
    color: white;
}

.form-group {
    margin-bottom: 20px;
}

.form-group .form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.form-group .form-control:focus {
    border-color: #365AC2;
    outline: none;
    box-shadow: 0 0 5px rgba(54, 90, 194, 0.2);
}

#responseMessage {
    margin-top: 10px;
    font-size: 14px;
    color: #555;
}

.btn-block {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    background-color: #365AC2;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-block:hover {
    background-color: #2a48a0;
}

.btn-secondary {
    background-color: #777;
    border: none;
}

.btn-secondary:hover {
    background-color: #666;
}

h3 {
    margin-bottom: 20px;
    color: #365AC2;
    font-size: 18px;
}

/* Added Users Modal */
#addedUsersModal .modal-header {
    padding-bottom: 10px;
}

#addedUsersModal .form-group {
    margin-bottom: 25px;
}

#addedUsersList {
    padding: 10px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}

.btn-remove {
    background-color: #6c757d; /* Abu-abu */
    color: white;
    border: none;
    padding: 8px 16px;
    font-size: 14px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-left: 10px; /* Jarak dari teks email */
}

.btn-remove:hover {
    background-color: #5a6268; /* Abu-abu lebih gelap saat di-hover */
}

.btn-remove:active {
    background-color: #4e555b; /* Warna saat tombol di-klik */
}

.main-content {
    position: relative;
    box-sizing: border-box;
    width: auto;
    height: auto;
    flex: 1;
    margin-left: 10%;
    transition: margin-left .3s;
    padding: 2%;
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

/* Main Content */
.container {
    padding-top: 1%;
    width: 80%;
    height: 100%;
    margin-left: 5%;
}

/* Organization Card */
.card-organization {
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px 30px 30px;
    background-color: #ffffff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    position: relative;
    justify-content: space-between;
    display: flex;
    flex-wrap: wrap; /* Untuk memastikan layout tetap rapi di layar kecil */
    width: 100%;
    margin-bottom: 2%;
    box-sizing: border-box;
}

.info {
    width: 100%; /* Default 100% untuk layar kecil */
    margin-bottom: 1rem; /* Memberikan ruang antara teks dan tombol di layar kecil */
}

.info h1 {
    font-size: 2rem;
    font-weight: bold;
    color: #3200af;
    margin-bottom: 0.5rem;
}

.info small {
    font-size: 1rem;
    color: #3200af;
}

.btn-edit {
    background-color: #365AC2;
    color: #fff;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease, transform 0.3s ease;
    position: absolute;
    bottom: 20px;
    right: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    line-height: 1;
}

.btn-edit i {
    font-size: 1rem;
}

.btn-edit:hover {
    background-color: #5a5bd4;
    transform: translateY(-2px);
}

.member {
    width: 100%;
}

/* Buttons and Search Input */
.mb-side {
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
}

.group-btn {
    width: 30%;
    padding: 15px; /* Ruang dalam */
    background-color: #f8f9fa; /* Warna latar belakang cerah */
    color: #333; /* Warna teks gelap */
    font-size: 18px; /* Ukuran font lebih besar */
    text-align: center; /* Pusatkan teks */
    border: 1px solid #dee2e6; /* Border ringan */
    border-radius: 8px; /* Sudut melengkung */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Efek bayangan */
    display: flex; /* Menggunakan Flexbox */
    align-items: center; /* Pusatkan item secara vertikal */
    justify-content: center; /* Pusatkan item secara horizontal */
}

.group-btn i {
    margin-right: 8px; /* Ruang antara ikon dan teks */
    color: #007bff; /* Warna ikon */
}


.d-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn {
    font-size: 0.875rem;
    padding: 0.5rem 1.5rem;
    border-radius: 0.5rem;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    display: inline-block;
    margin-top: 3%;
}

#anggotaBtn, #pengurusBtn {
    font-size: 0.875rem;
    padding: 0.5rem 1.5rem;
}

#anggotaBtn {
    background-color: #365AC2;
    color: #fff;
}

#anggotaBtn:hover {
    background-color: #2e4a8c;
}

#pengurusBtn {
    background-color: #7773d4;
    color: #fff;
}

#pengurusBtn:hover {
    background-color: #5a5bd4;
}

.input-group {
    width: 100%;
    display: flex;
}

.input-group-prepend {
    flex-shrink: 0;
}

.input-group-prepend .input-group-text {
    background-color: #365AC2; /* Blue background */
    border: none;
    color: #fff; /* White icon color */
    border-radius: 0.5rem 0 0 0.5rem;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    box-sizing: border-box;
}

.input-group-text i {
    color: #ffffff;
}

.form-control {
    border: 2px solid #ffffff;
   border-top-right-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
    padding: 0.75rem;
    font-size: 0.875rem;
    box-shadow: none;
    border-left: none;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    width: 100%; /* Default to 100% of the parent container */
    height: 100%;
    box-sizing: border-box; /* Ensure padding and border are included in the total width */
}

.form-control:focus {
    border-color: #7773d4;
    box-shadow: 0 0 0 0.2rem rgba(119, 115, 212, 0.25);
    outline: none;
}
/* Table Styling */
.table-container {
    width: 100%;
    box-sizing: border-box;
    overflow-x: auto; /* Tambahkan scroll horizontal jika diperlukan */
    -webkit-overflow-scrolling: touch; /* Untuk smooth scrolling di perangkat iOS */
    margin-bottom: 10%;
}


.table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.table thead {
    background-color: #365AC2;
    color: #ffffff;
}

.table th, .table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #f0f0f0;
    width: auto; /* Pastikan kolom tidak meluas */
}


/* Gaya baris */
.table tbody tr {
    transition: background-color 0.2s;
}

.table tbody tr:hover {
    background-color: #eef5ff;
}

.table tbody tr.selected {
    background-color: #0066ff;
    color: white;
}

.table tbody tr.selected td {
    color: white;
}

/* CSS Responsive Styles */

/* General Responsive Styles */
html, body {
    font-size: 16px;
}

 /* Button Styles */
 .btn-primary {
        padding: 10px 10px;
        background-color: #365AC2;
        color: white;
        border: none;
        border-radius: 12px;
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
        z-index: 998; /* Pastikan footer berada di bawah tombol "Buat Organisasi" */
    }

    .bc-pr {
        width: 100%;
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

/* notification add member style */
.notification {
    position: fixed; /* Fix position on screen */
    top: 20px;      /* Distance from the top */
    right: 20px;    /* Distance from the right */
    z-index: 1050;  /* Ensure it's above other content */
    transition: opacity 0.5s ease; /* Smooth fade out */
    opacity: 1;     /* Start fully visible */
}

.notification.fade-out {
    opacity: 0;     /* Fade out effect */
}

.alert {
        padding: 15px;
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

    .alert-success, .alert-danger{
        color: white;
        background-color: #1363DF;
        border-color: #c3e6cb;
        font-weight: bold;
        border-radius: 15px;
        z-index: 3;
        font-size: 20px;
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

/* Responsif untuk layar tablet (max-width: 768px) */
@media (max-width: 800px) {

    .main-content{
        width:  auto;
        margin-top: 10%;
    }
    .card-organization {
        padding: 15px 20px; /* Mengurangi padding untuk layar kecil */
        width: auto; /* Pastikan card menyesuaikan lebar layar */
    }

    .info {
        width: 100%; /* Info mengambil seluruh lebar di layar kecil */
        margin-bottom: 1rem; /* Memberikan ruang lebih banyak untuk konten */
    }

    .btn-edit {
        bottom: 10px; /* Adjust tombol edit lebih ke atas agar tidak terlalu ke tepi */
        right: 10px; /* Sama untuk kanan */
    }
    .table-container {
        width: auto;
    }

    .mb-side {
        flex-direction: column; /* Stack buttons and search input vertically */

    }

    .group-btn {
        width: 100%; /* Full width for buttons */
        display: flex;
        justify-content: space-between;
    }

    .input-group {
        width: auto; /* Full width for search input */
        margin-top: 1rem; /* Add some space between buttons and search input */
    }

    /* Ensure the form input and buttons are well-aligned */
    .form-control, .input-group-text {
        height: auto; /* Ensure height adapts properly */
    }
}

/* Responsif untuk layar mobile (max-width: 480px) */
@media (max-width: 500px) {
    .card-organization {
        padding: 10px 15px; /* Padding lebih kecil untuk layar mobile */
        width: auto;
    }
    .table-container {
        width: auto;
    }
    .input-group {
        width: auto; /* Full width for search input */
        margin-top: 1rem; /* Add some space between buttons and search input */
    }
    .btn-edit {
        bottom: 8px; /* Adjust posisi tombol edit lebih ke atas */
        right: 8px;  /* Sama untuk kanan */
        width: 35px; /* Lebar tombol edit lebih kecil */
        height: 35px; /* Tinggi tombol edit lebih kecil */
    }

    .btn-edit i {
        font-size: 0.875rem; /* Ukuran ikon lebih kecil untuk menyesuaikan ukuran tombol */
    }

    .info h1 {
        font-size: 1.5rem; /* Ukuran font lebih kecil di layar mobile */
    }

    .info small {
        font-size: 0.875rem; /* Ukuran font kecil di mobile */
    }
}

    </style>

<script>
    // Function to handle search and add users dynamically
    function handleSearch(event) {
        event.preventDefault(); // Prevent the form from submitting
        const emailInput = document.getElementById('email');
        const email = emailInput.value.trim();
        const responseMessageDiv = document.getElementById('responseMessage');
        const addMemberButton = document.getElementById('addMemberButton');

        // Show loading indicator
        const loadingIndicator = document.createElement('p');
        loadingIndicator.innerText = 'Searching...';
        responseMessageDiv.appendChild(loadingIndicator);

        // Check if the email is already added
        if (isEmailAlreadyAdded(email)) {
            displayTemporaryMessage('This email is already added.');
            loadingIndicator.remove(); // Remove loading indicator
            return;
        }

        // Make the API call to search users
        fetch('{{ route('searchUsers') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            loadingIndicator.remove(); // Remove loading indicator
            if (data.success && data.data) {
                data.data.forEach(user => {
                    const userDiv = document.createElement('div');
                    userDiv.className = 'user-entry';
                    userDiv.innerHTML = `
                        <p>${user.email} <button class="btn-remove" onclick="removeUser(this)">Remove</button></p>
                    `;
                    responseMessageDiv.appendChild(userDiv);
                });
                addMemberButton.disabled = false;
            } else {
                displayTemporaryMessage('No users found for this email.');
            }
        })
        .catch(error => {
            loadingIndicator.remove(); // Remove loading indicator
            console.error('Error:', error);
            displayTemporaryMessage('An error occurred while searching for users.');
        });

        emailInput.value = '';
    }


    // Function to check if the email is already added
    function isEmailAlreadyAdded(email) {
        const userEntries = document.querySelectorAll('.user-entry p');
        for (const entry of userEntries) {
            if (entry.textContent.includes(email)) {
                return true; // Return true if email is already added
            }
        }
        return false; // Return false if email is not found
    }

    // Function to display a temporary message that disappears after 5 seconds
    function displayTemporaryMessage(message) {
        const responseMessageDiv = document.getElementById('responseMessage');
        const tempMessage = document.createElement('p');
        tempMessage.innerText = message;
        responseMessageDiv.appendChild(tempMessage);

        // Remove the message after 5 seconds
        setTimeout(() => {
            tempMessage.remove();
        }, 5000);
    }

    // Function to remove a user entry
    function removeUser(button) {
        button.parentElement.parentElement.remove(); // Remove the user entry

        // Disable the Add Member button if no users are left
        const userEntries = document.querySelectorAll('.user-entry p');
        const addMemberButton = document.getElementById('addMemberButton');
        if (userEntries.length === 0) {
            addMemberButton.disabled = true;
        }
    }

    // Function to open the modal for added users
    let emailsToTokenize = []; // Store emails for token generation

    function openAddedUsersModal() {
        // Close the current modal
        document.getElementById('addMemberModal').style.display = 'none';

        // Show the added users modal
        document.getElementById('addedUsersModal').style.display = 'block';

        // Populate the added emails into the modal
        const addedUsersList = document.getElementById('addedUsersList');
        const userEntries = document.querySelectorAll('.user-entry p');

        addedUsersList.innerHTML = ''; // Clear previous list
        emailsToTokenize = []; // Clear previous emails for token generation
        userEntries.forEach(entry => {
            const email = entry.textContent.split(' ')[0]; // Get the email from the entry
            addedUsersList.innerHTML += `<p>${email}</p>`; // Append the email to the modal
            emailsToTokenize.push(email); // Store email for later use
        });
    }

    // Function to show the search modal again
    function showSearchModal() {
        // Close the added users modal
        document.getElementById('addedUsersModal').style.display = 'none';

        // Show the original search modal
        document.getElementById('addMemberModal').style.display = 'block';
    }

    // Function to close both modals
    function closeModal() {
        document.getElementById('addMemberModal').style.display = 'none';
        document.getElementById('addedUsersModal').style.display = 'none';
    }

    // Function to send the emails
    function sendEmails() {
        const addedUsersList = document.getElementById('addedUsersList');
        const emails = [];
        const organizationId = "{{ $organization['organization_id'] }}"; // Ensure organization_id is passed correctly

        // Loop through each email in the list and collect the text content
        addedUsersList.querySelectorAll('p').forEach(emailElement => {
            emails.push(emailElement.textContent.trim()); // Collect emails
        });

        console.log('Emails collected for sending:', emails); // Log the collected emails for debugging

        // Check if there are any emails before proceeding
        if (emails.length === 0) {
            alert('No emails added.');
            return;
        }

        // Body of the POST request
        const body = JSON.stringify({
            organization_id: organizationId,  // Use organizationId from the template
            emails: emails                    // Pass collected emails
        });

        // Make an API call to send emails
        fetch('{{ route('sendAddMemberEmail') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: body
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Emails sent successfully!');
            } else {
                alert('Failed to send emails: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error sending emails:', error);
        });
    }
    </script>



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

            // Function to open the second modal (Add Member Details)
            function openAddMemberDetailsModal() {
                document.getElementById('addMemberDetailsModal').style.display = 'block';
            }

            // Function to close the second modal
            function closeMemberDetailsModal() {
                document.getElementById('addMemberDetailsModal').style.display = 'none';
            }

            function openModal() {
            document.getElementById('addMemberModal').style.display = 'block';
            }

             function closeModal() {
            document.getElementById('addMemberModal').style.display = 'none';
             }

        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            // Fetch data from external API
            function fetchDataFromAPI() {
                // API URL
                const apiUrl = 'http://192.168.1.24:14041/api/sso/list_user_by_organization.json';

                // Access token from Laravel session (injected using Blade)
                const accessToken = '{{ session('access_token') }}';
                console.log("Access Token: ", accessToken); // Log access token
                const organizationId = "{{ $organization['organization_id'] }}"; // Ensure organization_id is passed correctly

                // Request headers
                const headers = {
                    'Authorization': accessToken, // Use token from session
                    'x-api-key': '5af97cb7eed7a5a4cff3ed91698d2ffb',
                    'Content-Type': 'application/json'
                };

                // Body of the POST request
                const body = JSON.stringify({
                    organization_id: organizationId,
                    find: ""
                });
                console.log("Request Body: ", body); // Log body request

                // Fetch data using fetch API
                fetch(apiUrl, {
                    method: 'POST',
                    headers: headers,
                    body: body
                })
                .then(response => {
                    console.log("Raw Response: ", response); // Log raw response
                    return response.json();
                }) // Convert response to JSON
                .then(data => {
                    console.log("Response Data: ", data); // Log response data

                    if (data.success && data.data && data.data.length > 0) {
                        populateTable(data.data); // Populate table with the users array
                    } else {
                        populateTable([]); // Pass empty array if no users are found
                    }
                })
                .catch(error => {
                    console.error('Error:', error); // Log any errors
                });
            }

            function populateTable(data) {
        var tbody = document.getElementById('dataBody');
        tbody.innerHTML = ''; // Clear the table body

        if (data.length === 0) {
            var row = document.createElement('tr');
            var noDataCell = `<td colspan="4" class="text-center">No data found</td>`;
            row.innerHTML = noDataCell;
            tbody.appendChild(row);
        } else {
            data.forEach(function(item, index) {
                var row = document.createElement('tr');
                row.innerHTML = `
                    <th scope="row">${index + 1}</th>
                    <td>${item.username ? item.username : 'N/A'}</td>
                    <td>${item.email}</td>
                    <td>
                        <form action="{{ route('showmoredetails', ['organization_name' => $organization['organization_name']]) }}" method="GET">
                            @csrf
                            <input type="hidden" name="email" value="${item.email}">
                            <button type="submit" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="More Details" style="background: none; border: none; padding: 0;">
                                <i class="bi bi-info-circle" style="font-size: 1.8rem; color: #007bff;"></i>
                            </button>
                        </form>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }
    }

            // Call fetchDataFromAPI on page load
            document.addEventListener('DOMContentLoaded', function () {
                fetchDataFromAPI(); // Fetch and populate table on page load

                // Search functionality
                document.getElementById('searchInput').addEventListener('input', function () {
                    var searchValue = this.value.toLowerCase();
                    var rows = document.querySelectorAll('#dataTable tbody tr');

                    rows.forEach(function (row) {
                        var cells = row.querySelectorAll('td');
                        var found = Array.from(cells).some(function (cell) {
                            return cell.textContent.toLowerCase().includes(searchValue);
                        });

                        row.style.display = found ? '' : 'none';
                    });
                });
            });

        </script>
@endsection

