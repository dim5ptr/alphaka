@extends('layout.userlayout')

@section('content')
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
    <style>

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



    <!-- jQuery and Slick Carousel JS -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.promotion-slider').slick({
                autoplay: true,
                autoplaySpeed: 3000,
                dots: true,
                infinite: true,
                speed:   500,
                slidesToShow: 1,
                slidesToScroll: 1
            });
        });
    </script>
@endsection

