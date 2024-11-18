@extends('layout.userlayout')

@section('content')
<div id="main-content" class="main-content">
    <div class="product-detail-container">
        <div class="product-image-gallery">
            @if (isset($logo) && $logo)
                <img id="main-image" src="{{ asset($logo) }}" alt="{{ $product['product_name'] ?? 'Product' }}" class="main-image">
            @else
                <img id="main-image" src="{{ asset('img/App.gif') }}" alt="Default Product" class="main-image">
            @endif

            <div class="thumbnail-gallery">
                @if (isset($displayImages) && is_array($displayImages) && count($displayImages) > 0)
                    @foreach ($displayImages as $index => $image)
                        @if (isset($image['image_path']) && is_string($image['image_path']))
                            <img src="{{ asset($image['image_path']) }}" alt="Thumbnail {{ $index + 1 }}" class="thumbnail" data-index="{{ $index }}">
                        @else
                            <p>Invalid image data.</p>
                        @endif
                    @endforeach
                @else
                    <img src="{{ asset('img/App.gif') }}" alt="Default Thumbnail" class="thumbnail">
                @endif
            </div>
        </div>

        <div class="product-info">
            <h1 class="product-title">{{ $product['product_name'] ?? 'Product Name Unavailable' }}</h1>
            <p class="product-description">{{ $product['description'] ?? 'No description available.' }}</p>

            <div class="product-price">
                Rp.{{ number_format($product['price'] ?? 0, 0, ',', '.') }}
                <span class="product-subtext">
                    {{ $product['price'] ? 'Suggested payments with 6 months special financing' : '' }}
                </span>
            </div>

            <form id="transaction-form">
                @csrf
                <input type="hidden" name="product_ids[]" value="{{ $product['product_id'] ?? '' }}">
                <button type="submit" class="buy-now-btn">Buy Now</button>
            </form>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Transaction Successful</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Your transaction was successful! Please wait for admin acceptance.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Transaction Failed</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="errorMessage">
                An error occurred during the transaction. Please try again.
            </div>
            < <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#transaction-form').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            $.ajax({
                url: '{{ route('createTransaction') }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#successModal .modal-body').text(response.message);
                        $('#successModal').modal('show');
                    } else {
                        $('#errorMessage').text(response.message);
                        $('#errorModal').modal('show');
                    }
                },
                error: function(xhr) {
                    $('#errorMessage').text("An unexpected error occurred. Please try again.");
                    $('#errorModal').modal('show');
                }
            });
        });
    });
</script>

<style>

    .modal-content {
        border-radius: 10px;
    }

    .btn-secondary {
        background-color: #365AC2;
    }

    /* Style yang ada tetap sama */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #d5def7;
        margin: 0;
        padding: 0;
    }

    .main-content {
        display: flex;
        justify-content: center;
        padding: 7%;
    }

    .product-detail-container {
        display: flex;
        gap: 40px;
        padding: 30px;
        max-width: 900px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        margin: 20px;
        position: relative;
    }

    .product-image-gallery {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        max-width: 400px;
    }

    .main-image {
        width: 100%;
        max-width: 250px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .thumbnail-gallery {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .thumbnail-gallery img {
        width: 60px;
        height: 60px;
        border-radius: 4px;
        cursor: pointer;
        border: 1px solid #AFC3FC;
        transition: transform 0.2s;
    }

    .thumbnail-gallery img:hover {
        transform: scale(1.1);
    }

    .product-info {
        flex: 1;
        padding: 10px 20px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .product-title {
        font-size: 26px;
        color: #333333;
        font-weight: bold;
        margin: 0;
    }

    .product-description {
        font-size: 16px;
        color: #666666;
    }

    .product-price {
        font-size: 22px;
        color: #365AC2;
        font-weight: bold;
        margin-bottom: 25%;
    }

    .product-subtext {
 font-size: 12px;
        color: #999999;
        display: block;
        margin-top: 5px;
    }

    .buy-now-btn {
        position: absolute;
        bottom: 20px;
        right: 20px;
        padding: 12px 25px;
        border-radius: 5px;
        background-color: #365AC2;
        color: #ffffff;
        font-weight: bold;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .buy-now-btn:hover {
        background-color: #AFC3FC;
    }

    @media (max-width: 768px) {
        .product-detail-container {
            flex-direction: column;
            align-items: center;
        }

        .product-info {
            text-align: center;
            padding: 0 15px;
            gap: 10px;
        }

        .product-price {
            font-size: 20px;
        }

        .buy-now-btn {
            position: static;
            margin-top: 15px;
        }
    }
</style>

{{-- <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script> --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const mainImage = document.getElementById("main-image");
        const thumbnails = document.querySelectorAll(".thumbnail");
        let displayImages = @json($displayImages);
        let currentIndex = 0;
        let intervalId;

        // Function to change main image automatically every 5 seconds
        function startAutoSwitch() {
            intervalId = setInterval(() => {
                currentIndex = (currentIndex + 1) % displayImages.length;
                mainImage.src = displayImages[currentIndex].image_path;
            }, 5000);
        }

        // Stop the automatic switching temporarily
        function stopAutoSwitch() {
            clearInterval(intervalId);
        }

        // Event listener for clicking on thumbnails
        thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener("click", () => {
                stopAutoSwitch();
                currentIndex = index;
                mainImage.src = displayImages[index].image_path;
                startAutoSwitch();
            });
        });

        // Start the auto-switch when page loads
        if (displayImages && displayImages.length > 0) {
            startAutoSwitch();
        }

        // Payment button functionality
        // document.getElementById('pay-button').onclick = function () {
        //     const productPrice = {{ $product['price'] ?? 0 }};
        //     fetch('/create-transaction', {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //         },
        //         body: JSON.stringify({ price: productPrice })
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         snap.pay(data.snap_token);
        //     });
        // };
    });
</script>
@endsection
