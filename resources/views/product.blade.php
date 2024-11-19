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
        <section style="margin-top: 5%;" class="product-section">
            <div class="product-list" id="product-list">
                @foreach($products as $product)
                <div class="product-card" data-name="{{ strtolower($product['product_name']) }}">
                    <!-- Link to product details page, still working even if no image is available -->
                    <a href="{{ route('showDetailProductu', ['id' => $product['product_id']]) }}" class="overlay-link"></a>

                    <!-- Check if a logo image is available -->
                    @php
                        $logoImage = collect($product['images'])->firstWhere('image_type', 'logo')['image_path'] ?? null;
                    @endphp

                    <!-- If logo image exists, display it, otherwise fall back to a default image -->
                    @if ($logoImage)
                        <img src="{{ asset($logoImage) }}" alt="Product Logo">
                    @else
                        <!-- Fallback content if logo image is not found (can be a default image or just leave empty) -->
                        <img src="{{ asset('img/A.jpg') }}" alt="Default Logo">
                    @endif

                    <!-- Product Name (with fallback if not available) -->
                    <h3>{{ $product['product_name'] ?? 'Product Name Unavailable' }}</h3>

                    <!-- Product Description (with fallback if not available) -->
                    <p class="description">{{ $product['description'] ?? 'No description available.' }}</p>

                    <!-- Product Price (with fallback if not available) -->
                    <p class="price">Rp. {{ number_format($product['price'] ?? 0, 0, ',', '.') }}</p>

                    <!-- Link to purchase the product -->
                    <a href="/purchase/{{ $product['product_id'] }}" class="btn">Buy Now</a>
                </div>
                @endforeach
            </div>

            <!-- No results message if no products are found -->
            @if(count($products) == 0)
                <div id="no-results-message" class="no-results">
                    <p>No products found matching your search criteria.</p>
                </div>
            @endif
        </section>


    <style>

        html {
            background-color: ;
        }

        .homepage {
            padding: 10%;
            /* background-color: #f9f9f9; Light background for better contrast */
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
            border-radius: 10px;
            border: 1px solid #ccc;
            width: 94%;
            background-color: #ffffff; /* Light background color */
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
            width: 85%;
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

    function sortProducts() {
        const sortOption = document.getElementById('sort-options').value;
        const productList = document.getElementById('product-list');
        const productCards = Array.from(productList.children);

        productCards.sort((a, b) => {
            const priceA = parseFloat(a.querySelector('.price').textContent.replace(/[^0-9]/g, ''));
            const priceB = parseFloat(b.querySelector('.price').textContent.replace(/[^0-9]/g, ''));
            const nameA = a.querySelector('h3').textContent.toLowerCase();
            const nameB = b.querySelector('h3').textContent.toLowerCase();

            switch (sortOption) {
                case 'price-asc':
                    return priceA - priceB;
                case 'price-desc':
                    return priceB - priceA;
                case 'name-asc':
                    return nameA.localeCompare(nameB);
                case 'name-desc':
                    return nameB.localeCompare(nameA);
                default:
                    return 0; // Default case (no sorting)
            }
        });

        // Re-append sorted products to the product list
        productCards.forEach(card => productList.appendChild(card));
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
