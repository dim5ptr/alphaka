@extends('layout.userlayout')

@section('content')
    <div class="homepage">
        <header class="homepage-header">
            <h1>Welcome to Sarastya Technology Integrata</h1>
            <p>Your one-stop solution for digital products.</p>
        </header>

        <section class="promotions">
            <h2>Current Promotions</h2>
            <div class="promotion-slider">
                <div><img src="{{ asset('img/SLIDE1.png') }}" alt="Promotion 1"></div>
                <div><img src="{{ asset('img/SLIDE2.png') }}" alt="Promotion 2"></div>
                <div><img src="{{ asset('img/SLIDE3.png') }}" alt="Promotion 3"></div>
            </div>
        </section>

        <section class="product-section">
            <h2>Featured Products</h2>
            <div class="product-list">
                @foreach($products as $product)
                    <div class="product-card">
                        <img width="100px" height="100px"src="{{ asset('img/B.png') }}" alt="{{ $product['product_name'] }}">
                        <h3>{{ $product['product_name'] }}</h3>
                        <p>{{ $product['description'] ?? 'No description available.' }}</p>
                        <p class="price">${{ $product['price'] ?? 'N/A' }}</p>
                        <a href="/purchase/{{ $product['product_id'] }}" class="btn">Buy Now</a>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <style>
        .homepage {
            padding: 20px;
            background-color: #f9f9f9; /* Light background for better contrast */
        }

        .homepage-header {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .promotions {
            margin-bottom: 30px;
        }

        .promotion-slider {
            width: 100%;
        }

        .promotion-slider img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .product-section {
            margin-bottom: 30px;
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 0 10px; /* Padding for grid */
        }

        .product-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff; /* Highlight price */
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .about-us {
            text-align: center;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .product-list {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }

            .homepage-header h1 {
                font-size: 1.5rem;
            }

            .homepage-header p {
                font-size: 1rem;
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
                speed: 500,
                slidesToShow: 1,
                slidesToScroll: 1
            });
        });
    </script>
@endsection