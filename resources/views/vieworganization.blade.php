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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

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

        /* Pengaturan posisi konten modal */
        position: relative;
        top: 30%;
        transform: translateY(-50%); /* Moves the modal up by 50% of its own height */
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

    .form-group .form-control{
    width: 100%; /* Ensures the form group takes full width */
    box-sizing: border-box; /* Includes padding and border in the element's total width */
    margin-bottom: 2%;
    }

    .btn-block {
    width: 100%; /* Makes the button full width */
    box-sizing: border-box; /* Includes padding and border in the element's total width */
    }


html, body {
    height: auto;
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
    z-index: 1000;
}

.sidebar.open {
    left: 0;
}

.sidebar .sidebar-isi {
    display: block;
    padding: 0;
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
    padding: 0;
    margin-bottom: 10%;
    display: flex;
    justify-content: flex-end;
    font-size: 14px;
    box-shadow: 0 2px 9px rgba(0, 0, 0, 0.2);
    width: 100%;
    top: 0;
    z-index: 900;
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
    position: fixed;
    left: 2%;
    top: 2.5%;
    cursor: pointer;
    color: #365AC2;
    font-size: 20px;
    font-weight: 600;
    border: none;
    transition: 0.3s;
    z-index: 1001;
    background: none;
}

.open-btn:hover {
    color: darkblue;
}

.main-content {
    padding-top: 30px; /* Adjust this value based on the height of your navbar */
    position: relative;
    box-sizing: border-box;
    width: calc(100% - 270px);
    height: auto;
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
    text-align: center;
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

.logout-button i {
    font-weight: 700;
    font-size: 20px;
    color: #c23636;
}

.logout-button:hover i,
.logout-button:hover {
    background-color: #c23636;
    color: aliceblue;
    font-weight: 700;
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
    margin: 1rem auto; /* Margin otomatis di samping agar responsif */
    position: relative;
    justify-content: space-between;
    display: flex;
    flex-wrap: wrap; /* Untuk memastikan layout tetap rapi di layar kecil */
    width: 100%;
    max-width: 1000px; /* Membatasi lebar maksimal pada layar besar */
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

/* Responsif untuk layar tablet (max-width: 768px) */
@media (max-width: 768px) {
    .card-organization {
        padding: 15px 20px; /* Mengurangi padding untuk layar kecil */
        margin: 1rem; /* Mengurangi margin untuk lebih pas di layar kecil */
        width: 100%; /* Pastikan card menyesuaikan lebar layar */
    }

    .info {
        width: 100%; /* Info mengambil seluruh lebar di layar kecil */
        margin-bottom: 1rem; /* Memberikan ruang lebih banyak untuk konten */
    }

    .btn-edit {
        bottom: 10px; /* Adjust tombol edit lebih ke atas agar tidak terlalu ke tepi */
        right: 10px; /* Sama untuk kanan */
    }
}

/* Responsif untuk layar mobile (max-width: 480px) */
@media (max-width: 480px) {
    .card-organization {
        padding: 10px 15px; /* Padding lebih kecil untuk layar mobile */
        margin: 0.5rem; /* Lebih kecil lagi untuk layar sempit */
        width: 450%;
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

.member {
    width: 100%;
}

/* Buttons and Search Input */
.mb-side {
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
}

.group-btn  {
    width: 30%;
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
    width: 30%;
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
}

.input-group-text i {
    color: #ffffff;
}

.form-control {
    border: 1px solid #ddd;
    border-radius: 0.5rem;
    padding: 0.75rem;
    font-size: 0.875rem;
    box-shadow: none;
    border-left: none;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;

    /* Responsive width */
    width: 800px; /* Default to 100% of the parent container */
    max-width: 100%; /* Set a max width for larger screens */
    box-sizing: border-box; /* Ensure padding and border are included in the total width */
}

.form-control:focus {
    border-color: #7773d4;
    box-shadow: 0 0 0 0.2rem rgba(119, 115, 212, 0.25);
    outline: none;
}

/* Table Styling */
.table-container {
    width: 450%;
    box-sizing: border-box;
    overflow-x: auto; /* Tambahkan scroll horizontal jika diperlukan */
    -webkit-overflow-scrolling: touch; /* Untuk smooth scrolling di perangkat iOS */
    margin: 0 auto; /* Pusatkan kontainer */
}

/* Atur max-width hanya untuk tampilan desktop */
@media (min-width: 768px) {
.table-container {
    width: 100%;    }
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

/* Media Queries untuk layar kecil */
@media (max-width: 768px) {
    .table th, .table td {
        padding: 10px;
    }
    .table-container {
        margin-bottom: 15px; /* Tambahkan margin bawah untuk spasi di layar kecil */
    }
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
        z-index: 998; /* Pastikan footer berada di bawah tombol "Buat Organisasi" */
    }

    .breadcrumb {
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
    padding: 0;
    margin: 2%;
    list-style: none;
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

    </style>
</head>
<body>
    <nav class="navbar">
        <p class="p1"><span>{{ \Carbon\Carbon::now()->format('l') }},</span><br>{{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
    </nav>

    <button class="open-btn" onclick="toggleSidebar()">&#9776; Organization</button>

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
        <br>
        <nav>
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
                        <button type="button" class="btn" id="anggotaBtn">Member</button>
                        <button type="button" class="btn" id="pengurusBtn">Administrator</button>
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
                </div>

                <!-- Table -->
                <div class="table-container">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Handle</th>
                                <th scope="col">Email</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="dataBody">
                            <!-- Data will be populated by JavaScript -->
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

    <div class="modal" id="addMemberModal">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title" id="addMemberModalLabel">Add Member</h1>
            <button type="button" class="btn-close" onclick="closeModal()">×</button>
        </div>
        <form id="searchUsers" action="{{ route('searchUsers') }}" method="POST" onsubmit="return handleSearch(event)">
            @csrf
            <div class="form-group">
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter someone email" required>
                <div id="responseMessage" class=""></div> <!-- Display found users here -->
            </div>
            <button type="submit" class="btn btn-primary btn-block">Search</button>
        </form>
        <button type="submit" class="btn btn-primary btn-block">Add Member</button>
    </div>
</div>


    <!-- ajax section untuk modal add member-->
<script>
   function handleSearch(event) {
    event.preventDefault(); // Prevent the form from submitting

    const emailInput = document.getElementById('email');
    const email = emailInput.value;
    const responseMessageDiv = document.getElementById('responseMessage');

    // Make the API call to search users (replace with your actual API call)
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
        // Check if users are found
        if (data.success && data.data) {
            // Loop through each user found
            data.data.forEach(user => {
                // Create a new user div
                const userDiv = document.createElement('div');
                userDiv.className = 'user-entry'; // Optional: Add a class for styling
                userDiv.innerHTML = `
                    <p>${user.email} <button onclick="removeUser(this)">Remove</button></p>
                `;
                responseMessageDiv.appendChild(userDiv); // Append the user div to the responseMessage div
            });
        } else {
            responseMessageDiv.innerHTML += '<p>No users found for this email.</p>'; // Message if no users found
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });

    // Clear the input field
    emailInput.value = '';
}

function removeUser(button) {
    button.parentElement.parentElement.remove(); // Remove the user entry
}
</script>
        
    </div>
</div>


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
        var anggotaData = [
            { id: 1, first: 'John', last: 'Doe', handle: '@john_doe', email: 'john@gmail.com' },
            { id: 2, first: 'Jane', last: 'Smith', handle: '@jane_smith', email: 'jane@gmail.com' }
        ];

        var pengurusData = [
            { id: 1, first: 'Alice', last: 'Johnson', handle: '@alice_johnson', email: 'alice@gmail.com' },
            { id: 2, first: 'Bob', last: 'Brown', handle: '@bob_brown', email: 'bob@gmail.com' }
        ];

        function populateTable(data) {
            var tbody = document.getElementById('dataBody');
            tbody.innerHTML = '';

            data.forEach(function (item) {
                var row = document.createElement('tr');
                row.innerHTML = `
                    <th scope="row">${item.id}</th>
                    <td>${item.first}</td>
                    <td>${item.last}</td>
                    <td>${item.handle}</td>
                    <td>${item.email}</td>
                    <td>
                        <form action="{{ route('showmoredetails', ['organization_name' => $organization['organization_name']]) }}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="More Details" style="background: none; border: none; padding: 0;">
                                  ㅤ<i class="bi bi-info-circle" style="font-size: 1.8rem; color: #007bff;"></i>
                            </button>
                        </form>
                    </td>

                `;
                tbody.appendChild(row);
            });
        }

        document.getElementById('anggotaBtn').addEventListener('click', function () {
            populateTable(anggotaData); // Isi data anggota
        });

        document.getElementById('pengurusBtn').addEventListener('click', function () {
            populateTable(pengurusData); // Isi data pengurus
        });

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

        populateTable(anggotaData); // Default load data anggota saat halaman dimuat
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

    document.getElementById('addMemberForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission

    const emailInput = document.getElementById('email');
    const email = emailInput.value.trim(); // Get the email input value

    // Call your userSearch function here, passing the email or search value
    userSearch(email);

    // Optionally, you can also submit the form if needed:
    // this.submit(); 
});

function userSearch(email) {
    // Implement the user search logic, e.g., fetch from the API and populate the table
    console.log('Searching for:', email);
    
    // Add your search logic here, for example:
    fetch(`http://192.168.1.24:14041/api/search-users?find=${email}`)
        .then(response => response.json())
        .then(data => {
            // Handle the data returned from the search
            console.log(data); // or update the table with the search results
        })
        .catch(error => console.error('Error fetching user:', error));
}


</script> --}}
</div>



</body>
</html>

{{-- @section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection

@section('breadcrumbs')
<!-- Tambahkan breadcrumbs ke bagian atas -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb" style="background-color: transparent;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('organization') }}">Organization</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $organization['organization_name'] }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    @yield('breadcrumbs') <!-- Tambahkan breadcrumbs ke tampilan -->

    <h1 class="m-0" style="color: #3200af;">{{ $organization['organization_name'] }}</h1>
    <div class="row mt-3 pb-3 border-bottom">
        <div class="col-md-8" style="color: #3200af;">
            <small>{{ $organization['description'] }}</small>
        </div>
        <div class="col-md-4 text-center">
            <a href="{{ route('showeditorganization', ['organization_name' => $organization['organization_name']]) }}"
                class="btn btn-md px-5 rounded-pill text-light btn-pengurus" style="background-color: #7773d4;">
                Edit Organization
            </a>
        </div>
    </div>

    @include('listmember') <!-- Menampilkan daftar anggota organisasi -->
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif

@endsection --}}
