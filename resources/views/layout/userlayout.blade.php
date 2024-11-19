<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STI | Sarastya Technology Integrata</title>
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lexend:wght@100..900&display=swap" rel="stylesheet">



    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>

    <style>
        /* CSS Enhancements */
        html, body {
            height: auto;
            margin: 0;
            padding: 0;
            font-family: "Lexend", sans-serif;
            background-color: #d5def7;
        }

        .navbar {
            position: fixed;
            background-color: white;
            padding: 0px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            width: 100%;
            top: 0;
            z-index: 3;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.2);
        }

        .navbar p {
            margin-right: 2%;
            margin-top: 1%;
            padding: 0;
            color: gray;
        }

        .navbar span {
            font-weight: 800;
            color: #365AC2;
            font-size: 16px;
        }

        .open-btn {
            cursor: pointer;
            color: #365AC2;
            font-size: 20px;
            font-weight: 600;
            border: none;
            background: none;
            margin-left: 3%;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            left: -270px;
            background-color: white;
            overflow-x: hidden;
            transition: 0.3s;
            padding-top: 7%;
            box-shadow: 1px 0 9px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .sidebar .sidebar-isi {
            padding: 0px;
        }

        .sidebar-isi .list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-link, .nav-link-act {
            margin-left: 6%;
            display: flex;
            align-items: center;
            padding: 14px 17px;
            margin-bottom: 2%;
            border-radius: 5px;
            text-decoration: none;
            width: calc(100% - 40px);
            box-sizing: border-box;
            transition: background-color 0.3s, color 0.3s;
        }

        .nav-link .link {
            font-size: 17px;
            color: #365AC2;
            font-weight: 400;
        }

        .nav-link-act {
            background-color: #365AC2;
            color: white;
        }

        .nav-link:hover {
            background-color: #365AC2;
        }

        .nav-link:hover .link {
            color: white;
        }

        .main-content {
            padding-top: 5%;
            transition: margin-left .3s;
        }

        .open-btn:hover {
            color: darkblue;
        }

        .logout-button {
            margin-left: 20px;
            padding: 8px 9px;
            margin-top: 7%;
            margin-bottom: 2%;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            width: 83%;
            box-sizing: border-box;
            background-color: white;
            color: #c23636;
            border: 2px solid #c23636;
            transition: background-color 0.3s, color 0.3s;
        }

        .logout-button:hover {
            background-color: #c23636;
            color: aliceblue;
        }

        @media (max-width: 768px) {
            .navbar p {
                font-size: 0.678rem;
                margin-right: 5%;
            }

            .open-btn {
                font-size: 0.990rem;
                width: 35%;
            }

            .sidebar {
                font-size: 100%;
                padding-top: 20%;
            }

            .logoutForm {
                height: 50%;
            }

            ul {
                font-size: 0.876rem;
            }

            .dot {
    width: 8px;
    height: 8px;
    background-color: red;
    border-radius: 100%;
    display: inline-block; /* pastikan display ini benar */
    position: relative; /* untuk memudahkan positioning */
}

}
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="open-btn" onclick="toggleSidebar()">
            &#9776;
            <span id="sidebarDot" class="dot" style="width: 8px; height: 8px; transform: translate(-100%, -120%);  border-radius: 50%;  background-color: red; display: none;"></span>
             {{ $currentPage }}
        </div>
        <p class="p1"><span>{{ \Carbon\Carbon::now()->format('l') }},</span><br>{{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
    </nav>

    <div id="sidebar" class="sidebar">
        <div class="sidebar-isi">
            <ul class="list">
                <li>
                    <a href="/" class="{{ request()->is('/*') ? 'nav-link-act' : 'nav-link' }}" onclick="updateNavbarText('Dashboard')">
                        <span class="link"><i class="fa-solid fa-house-chimney"></i>ㅤDashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/inbox" class="{{ request()->is('showinbox') ? 'nav-link-act' : 'nav-link' }}"  onclick="markAllAsRead(); updateNavbarText('Inbox');">
                        <span class="link"><i class="fa-solid fa-inbox"></i>ㅤInbox</span>
                        <span id="inboxDot" class="dot" style="width: 8px; height: 8px; border-radius: 50%; margin-left: 50%; background-color: red; display: none;"></span>
                    </a>
                </li>
                <li>
                    <a href="/organization" class="{{ request()->is('organization*') ? 'nav-link-act' : 'nav-link' }}" onclick="updateNavbarText('Organization')">
                        <span class="link"><i class="nav-icon fas fa-users"></i>ㅤOrganization</span>
                    </a>
                </li>
                <li>
                    <a href="/produk" class="{{ request()->is('produk*') ? 'nav-link-act' : 'nav-link' }}" onclick="updateNavbarText('Products')">
                        <span class="link"><i class="fa-solid fa-cube"></i>ㅤProducts</span>
                    </a>
                </li>
                <li>
                    <a href="/personal" class="{{ request()->is('personal*') ? 'nav-link-act' : 'nav-link' }}" onclick="updateNavbarText('Profile')">
                        <span class="link"><i class="fa-solid fa-id-card"></i>ㅤProfile</span>
                    </a>
                </li>
                <li>
                    <a href="/security" class="{{ request()->is('security*') ? 'nav-link-act' : 'nav-link' }}" onclick="updateNavbarText('Security')">
                        <span class="link"><i class="fa-solid fa-user-shield"></i>ㅤSecurity</span>
                    </a>
                </li>
                <li>
                    <a href="/transaction" class="{{ request()->is('transaction*') ? 'nav-link-act' : 'nav-link' }}" onclick="updateNavbarText('Transactions')">
                        <span class="link"><i class="fa-solid fa-money-bill-wave"></i>ㅤTransactions</span>
                    </a>
                </li>
                <li>
                    <a href="/ownedproduct" class="{{ request()->is('ownedproduct*') ? 'nav-link-act' : 'nav-link' }}" onclick="updateNavbarText('Owned Products')">
                        <span class="link"><i class="bi bi-cart"></i>ㅤOwned Products</span>
                    </a>
                </li>
            </ul>

            <form id="logoutForm" method="GET" class="logoutForm" action="{{ route('confirm-logout') }}">
                <button type="submit" class="logout-button"><i class="fa-solid fa-right-from-bracket"></i>ㅤLogout</button>
            </form>
        </div>
    </div>

    <div id="content-wrapper" class="content-wrapper">
        @yield('content') <!-- This is where the content from welcome.blade.php will be injected -->
    </div>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById("sidebar");
            if (sidebar.style.left === "0px") {
                sidebar.style.left = "-270px";
            } else {
                sidebar.style.left = "0px";
            }
        }

        function updateNavbarText(pageName) {
            document.querySelector('.open-btn').innerHTML = `&#9776; ${pageName}`;
        }
    </script>


    <script>
      document.addEventListener('DOMContentLoaded', function() {
    // Mengirim request ke server untuk mendapatkan unreadMessages
    fetch('/get-unread-messages', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Pastikan untuk mengirimkan token CSRF
        }
    })
    .then(response => response.json())  // Mengubah response menjadi format JSON
    .then(data => {
        const unreadMessages = data.unreadMessages;  // Mendapatkan status unreadMessages dari response
        console.log("Unread messages status:", unreadMessages);  // Debug log
        updateDotDisplay(unreadMessages);  // Memperbarui tampilan dot
    })
    .catch(error => console.error('Error fetching unread messages:', error));

    // Jika halaman ini adalah Inbox, sembunyikan dot notifikasi
    if (window.location.pathname === '/inbox') {
        updateDotDisplay(0);  // Menyembunyikan dot langsung jika halaman Inbox
    }
});

function updateDotDisplay(unreadMessages) {
    const inboxDot = document.getElementById('inboxDot');
    const sidebarDot = document.getElementById('sidebarDot');

    // Menampilkan atau menyembunyikan dot notifikasi
    if (unreadMessages > 0) {
        inboxDot.style.display = 'inline-block';  // Menampilkan dot
        sidebarDot.style.display = 'inline-block';  // Menampilkan dot
        console.log("Dot displayed");
    } else {
        inboxDot.style.display = 'none';  // Menyembunyikan dot
        sidebarDot.style.display = 'none';  // Menyembunyikan dot
        console.log("Dot hidden");
    }
}


        // Menambahkan fungsi untuk menandai semua pesan sebagai sudah dibaca
        function markAllAsRead() {
            fetch('/mark-messages-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Jika pesan berhasil ditandai sebagai sudah dibaca, sembunyikan dot
                    updateDotDisplay(0);
                } else {
                    console.error('Gagal menandai pesan sebagai sudah dibaca');
                }
            })
            .catch(error => console.error('Error:', error));
        }

    </script>

</body>
</html>
