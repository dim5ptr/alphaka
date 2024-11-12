<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STI | Sarastya Technology Integrata</title>
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* CSS Enhancements */

        section {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }


        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #d5def7;
        }

        body {
            transition: margin-left 0.3s;
        }

        .navbar {
            position: fixed;
            background-color: white;
            padding: 0px;
            margin-bottom: 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            /* box-shadow: 0 2px 3px rgba(0, 0, 0, 0.2); */
            width: 100%;
            top: 0;
            z-index: 3;

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
            position: relative;
            justify-content: center;
            align-items: center;
            float: left;
            /* margin-left: 2%; */
            width: 20%;
            height: auto;
            z-index: 5;
            background: none;
        }

        .open-btn button {
            border: none;
            background: none;
            cursor: pointer;
            color: #365AC2;
            font-size: 20px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }

        .open-btn:hover {
            color: darkblue;
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
            display: block;
            padding: 0px;
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
            padding: 0px;
            margin-bottom: 10%;
            display: flex;
            justify-content: flex-end;
            font-size: 14px;
            /* box-shadow: 0 2px 9px rgba(0, 0, 0, 0.2); */
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
            width: calc(100% - 270px);
            height: 100%;
            flex: 1;
            padding-top: 10%;
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
            text-align : center;
            padding: 8px 9px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            width: calc(80% - 40px);
            box-sizing: border-box;
            position: absolute;
            top: 70%;
            background-color: white;
            color: #c23636;
            border: 2px solid #c23636;
            transition: background-color 0.3s, color 0.3s;
        }

        .logout-button i{
            font-weight: 700;
            font-size: 20px;
            color: #c23636;
        }

        .logout-button:hover i,
        .logout-button:hover {
            background-color: #c23636;;
            color: aliceblue;
            font-weight: 700;
        }


        .product-detail-container {
            display: flex;
            gap: 40px;
            padding: 20px;
            max-width: 1200px;
            margin: auto;
            background-color: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .product-image-gallery {
            flex: 1;
        }

        .main-image {
            width: 100%;
            border-radius: 8px;
        }

        .thumbnail-gallery {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .thumbnail-gallery img {
            width: 50px;
            height: 50px;
            border-radius: 4px;
            cursor: pointer;
            border: 1px solid #AFC3FC;
        }

        .product-info {
            flex: 1;
        }

        .product-title {
            font-size: 24px;
            color: #333333;
            font-weight: bold;
        }

        .product-description {
            font-size: 16px;
            color: #666666;
            margin: 10px 0;
        }

        .product-rating {
            font-size: 14px;
            color: #FFC107;
        }

        .product-price {
            font-size: 28px;
            color: #365AC2;
            font-weight: bold;
            margin: 20px 0 5px;
        }

        .product-subtext {
            font-size: 12px;
            color: #999999;
        }

        .color-selection {
            margin: 15px 0;
        }

        .color-options {
            display: flex;
            gap: 10px;
        }

        .color-circle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            cursor: pointer;
            border: 1px solid #AFC3FC;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 15px 0;
        }

        .quantity-btn {
            background-color: #AFC3FC;
            color: #FFFFFF;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        .quantity-input {
            width: 30px;
            text-align: center;
            border: 1px solid #AFC3FC;
            border-radius: 4px;
        }

        .stock-info {
            color: #E74C3C;
            font-weight: bold;
            margin: 15px 0;
        }

        .purchase-buttons {
            display: flex;
            gap: 10px;
        }

        .buy-now-btn, .add-to-cart-btn {
            padding: 10px 20px;
            border-radius: 4px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .buy-now-btn {
            background-color: #365AC2;
            color: #ffffff;
        }

        .add-to-cart-btn {
            background-color: #ffffff;
            color: #365AC2;
            border: 2px solid #365AC2;
        }

        .buy-now-btn:hover {
            background-color: #AFC3FC;
        }

        .add-to-cart-btn:hover {
            background-color: #AFC3FC;
            color: #ffffff;
        }

        .delivery-info {
            margin-top: 20px;
            border-top: 1px solid #AFC3FC;
            padding-top: 10px;
        }

        .delivery-option, .return-option {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #333333;
            margin-top: 10px;
        }

        @media (max-width: 768px) {

            .navbar p {
        font-size: 0.678rem;
        margin-right: 5%;
    }

    .open-btn button{
        font-size: 0.990rem;
        width: 100%;
        display: inline;
    }

    .open-btn {
        width: 35%;
        display: inline;
    }

    .sidebar {
       font-size: 100%;
       padding-top: 10%;
    }

    .sidebar-isi {
        margin-top: 15%;
    }

    .logoutForm {
        height: 50%;
    }

    ul {
        font-size: 0.876rem;
    }


        }

    </style>
</head>
<body>
    <nav class="navbar">
        <div class="open-btn">
            <button onclick="toggleSidebar()">&#9776; Product</button>
        </div>
        <p class="p1"><span>{{ \Carbon\Carbon::now()->format('l') }},</span><br>{{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
    </nav>

    <div id="sidebar" class="sidebar">
        <div class="sidebar-isi">
            <ul class="list">
                <li>
                    <a href="/" class="nav-link">
                        <span class="link"><i class="fa-solid fa-house-chimney"></i>ㅤDashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/organization" class="nav-link">
                        <span class="link"><i class="nav-icon fas fa-users"></i>ㅤOrganization</span>
                    </a>
                </li>
                <li>
                    <a href="/produk" class="nav-link-act">
                        <span class="link"><i class="fa-solid fa-cube"></i>ㅤProduct</span>
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
        <div class="product-detail-container">
            <div class="product-image-gallery">
                <!-- Replace with the actual product image URL -->
                <img src="{{ asset('img/App.gif') }}" alt="{{ $product['product_name'] }}" class="main-image">
                <div class="thumbnail-gallery">
                    <!-- Add actual thumbnail images if available -->
                    <img src="{{ asset('img/App.gif') }}" alt="Thumbnail 1">
                    <img src="{{ asset('img/App.gif') }}" alt="Thumbnail 2">
                    <img src="{{ asset('img/App.gif') }}" alt="Thumbnail 3">
                    <img src="{{ asset('img/App.gif') }}" alt="Thumbnail 4">
                </div>
            </div>

            <div class="product-info">
                <!-- Dynamic product name -->
                <h1 class="product-title">{{ $product['product_name'] }}</h1>
                <p class="product-description">
                    {{ $product['description'] }}
                </p>

                <div class="product-rating">
                    <!-- Add dynamic rating if available, here using a placeholder rating -->
                    ⭐⭐⭐⭐⭐ (121)
                </div>

                <div class="product-price">
                    <!-- Display dynamic price -->
                    Rp.{{ ($product['price']) ?? '0' }}
                    <span class="product-subtext">Suggested payments with 6 months special financing</span>
                </div>

                <div class="color-selection">
                    <label>Choose a Color</label>
                    <div class="color-options">
                        <!-- Add color options dynamically if needed -->
                        <span class="color-circle" style="background-color: #FF9999;"></span>
                        <span class="color-circle" style="background-color: #000000;"></span>
                        <span class="color-circle" style="background-color: #C0C0C0;"></span>
                        <span class="color-circle" style="background-color: #365AC2;"></span>
                        <span class="color-circle" style="background-color: #AFC3FC;"></span>
                    </div>
                </div>

                <div class="quantity-selector">
                    <button class="quantity-btn">-</button>
                    <input type="text" value="1" class="quantity-input">
                    <button class="quantity-btn">+</button>
                </div>

                <div class="stock-info">
                    <span class="stock-warning">Only 12 Items Left!</span> Don’t miss it
                </div>

                <div class="purchase-buttons">
                    <button class="buy-now-btn">Buy Now</button>
                    <button class="add-to-cart-btn">Add to Cart</button>
                </div>

                <div class="delivery-info">
                    <div class="delivery-option">
                        <span>🚚</span> Free Delivery
                        <p>Enter your Postal code for Delivery Availability</p>
                    </div>
                    <div class="return-option">
                        <span>🔄</span> Return Delivery
                        <p>Free 30days Delivery Returns. <a href="#">Details</a></p>
                    </div>
                </div>
            </div>
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
</script> --}}
</div>


</body>
</html>
