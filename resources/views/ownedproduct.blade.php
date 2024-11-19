@extends('layout.userlayout')

@section('content')
<style>
    html {

    }

    body {
        font-family: Arial, sans-serif;

    }

    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
        padding-top: 6%;
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #343a40;
    }

    .product-card {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .product-card:hover {
        transform: scale(1.02);
    }

    .product-name {
        font-size: 1.5em;
        margin: 0 0 10px;
        color: #007bff;
    }

    .product-version {
        font-size: 1em;
        color: #6c757d;
    }

    .alert {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .no-products {
        text-align: center;
        margin-top: 50px;
        font-size: 1.2em;
        color: #6c757d;
    }

    @media (max-width: 768px) {
        .container {
        padding-top: 13%;
    }
}
</style>

<div class="container">
    <h1>Owned Products</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(count($products) > 0)
        @foreach($products as $product)
            <div class="product-card">
                <h2 class="product-name">{{ $product['product_name'] }}</h2>
                <p class="product-version"><strong>Version:</strong> {{ $product['product_version'] ?: 'N/A' }}</p>
            </div>
        @endforeach
    @else
        <div class="no-products">No owned products found.</div>
    @endif
</div>
@endsection
