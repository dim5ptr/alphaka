@extends('layout.userlayout')

@section('content')
    <div class="homepage">

        <!-- Search Bar -->
        <div class="search-container">
            <div class="search-wrapper">
                <input type="text" id="product-search" class="search-bar" placeholder="Search products by name..." onkeyup="filterProducts()">
                <i class="fas fa-search search-icon"></i>
            </div>
        </div>

        <section class="promotions" id="promotions-section">
            <h2>Current Promotions</h2>
            <div class="promotion-slider">
                <div><img src="{{ asset('img/SLIDE1.png') }}" alt="Promotion 1"></div>
                <div><img src="{{ asset('img/SLIDE2.png') }}" alt="Promotion 2"></div>
                <div><img src="{{ asset('img/SLIDE3.png') }}" alt="Promotion 3"></div>
            </div>
        </section>

        <section style="margin-top: 5%;" class="product-section">
            <h2 id="search-results-heading">Featured Products</h2>
            <div class="product-list" id="product-list">
                @foreach($products as $product)
                    <div class="product-card" data-name="{{ strtolower($product['product_name']) }}">
                        <a href="{{ route('showDetailProductu', ['id' => $product['product_id']]) }}" class="overlay-link"></a>
                        <img src="{{ asset('img/B.png') }}" alt="{{ $product['product_name'] }}">
                        <h3>{{ $product['product_name'] }}</h3>
                        <p class="description">{{ $product['description'] ?? 'No description available.' }}</p>
                        <p class="price">Rp. {{ $product['price'] ?? '0' }}</p>
                        <a href="/purchase/{{ $product['product_id'] }}" class="btn">Buy Now</a>
                    </div>
                @endforeach
            </div>
            <div id="no-results-message" class="no-results" style="display: none;">
                <p>No products found matching your search criteria.</p>
            </div>
        </section>
    </div>

    <style>
        .homepage {
            padding: 10%;
            background-color: #f9f9f9; /* Light background for better contrast */
            height: 1500px;
        }

        .search-container {
            margin-bottom: 20px;
            width: 100%; /* Full width of the container */
        }

        .search-wrapper {
            position: relative;
            width: 100%; /* Full width of the promotions image */
        }

        .search-bar {
            padding: 15px 40px 15px 20px; /* Add padding for the icon */
            border-radius: 25px;
            border: 1px solid #ccc;
            width: 94%;
            background-color: #f9f9f9; /* Light background color */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            transition: border-color 0.3s ease; /* Smooth transition for focus effect */
        }

        .search-bar:focus {
            border-color: #007bff; /* Change border color on focus */
            outline: none; /* Remove default outline */
        }

        .search-icon {
            position: absolute;
            right: 15px; /* Positioning the icon on the right */
            top: 50%;
            transform: translateY(-50%); /* Center the icon vertically */
            color: #007bff; /* Icon color */
            font-size: 1rem; /* Icon size */
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
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .overlay-link {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10; /* Ensure overlay is in front of other elements */
            text-decoration: none;
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
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            text-align: left;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden; /* To contain the hover effect */
            display: flex; /* Enable flexbox */
            flex-direction: column; /* Stack children vertically */
             /* Ensure the card takes full height */
        }

        .product-card img {
            width: 100%;
            height: 200px; /* Fixed height for uniformity */
            object-fit: cover; /* Ensures the image covers the area */
            border-radius: 10px 10px 0 0; /* Rounded top corners */
            transition: transform 0.3s; /* Smooth image hover effect */
            padding-bottom: 5%;
        }


        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .product-card h3 {
            font-size: 1.25rem;
            margin: 1px 0;
            color: #333;
            font-weight: 600;
            margin: 0;
            white-space: nowrap; /* Prevent text wrapping */
            overflow: hidden; /* Hide overflowing text */
            text-overflow: ellipsis; /* Add ellipsis for overflowed text */
        }

        .description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
            min-height: 30px; /* Minimum height for consistency */
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Max 2 lines */
            -webkit-box-orient: vertical;
            text-overflow: ellipsis; /* Add ellipsis for overflowed text */
        }


        .price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff; /* Highlight price */
        }

        .btn {
            text-align: center;
            display: inline-block;
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: auto; /* Push the button to the bottom */
            font-weight: 500;
            width: 80%;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .no-results {
            margin-top: 20px;
            font-size: 1.2rem;
            color: #ff0000; /* Red color for no results message */
            text-align: center; /* Center the message */
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

    <script>
        function filterProducts() {
            const searchInput = document.getElementById('product-search').value.toLowerCase();
            const productCards = document.querySelectorAll('.product-card');
            const promotionsSection = document.getElementById('promotions-section');
            const noResultsMessage = document.getElementById('no-results-message');
            const searchResultsHeading = document.getElementById('search-results-heading');
            let hasVisibleProducts = false;

            productCards.forEach(card => {
                const productName = card.getAttribute('data-name');
                if (productName.includes(searchInput)) {
                    card.style.display = '';
                    hasVisibleProducts = true; // Found a visible product
                } else {
                    card.style.display = 'none';
                }
            });

            // Show or hide promotions and no results message based on search results
            if (searchInput === '') {
                promotionsSection.style.display = 'block'; // Show promotions if search is empty
                noResultsMessage.style.display = 'none'; // Hide no results message
                searchResultsHeading.textContent = 'Featured Products'; // Reset heading
            } else {
                promotionsSection.style.display = 'none'; // Hide promotions if there is a search
                noResultsMessage.style.display = hasVisibleProducts ? 'none' : 'block'; // Show no results message if no products are visible
                searchResultsHeading.textContent = hasVisibleProducts ? 'Search Results' : 'Search Results'; // Update heading
            }
        }
    </script>

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
