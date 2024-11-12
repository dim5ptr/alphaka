@extends('admin.layoutadm.layoutadm')

@section('content')
<div class="content-header bg-light p-4 shadow-sm rounded" style="margin-bottom: 2%;">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #0077FF; font-weight: bold; font-size: 2rem;">New Product</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @extends('admin.layoutadm.layoutadm')


                <form action="{{ route('storeProduct') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="product_name" id="product_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="product_code" class="form-label">Product Code</label>
                        <input type="text" class="form-control" name="product_code" id="product_code" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" name="price" id="price" step="0.001" required>
                    </div>

                    <div class="mb-3">
                        <label for="product_type" class="form-label">Product Type</label>
                        <input type="number" class="form-control" name="product_type" id="product_type" required>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="enabled" id="enabled" checked>
                        <label class="form-check-label" for="enabled">Enabled</label>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Create Product</button>
                        <a href="{{ route('showProducts') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </section>

        <style>
            /* Custom Styles */
            .alert-success, .alert-danger {
                border-radius: 10px;
                font-size: 0.9rem;
                text-align: center;
                margin-top: 10px;
            }

            /* Form Input Focus Effect */
            .form-control:focus {
                outline: none;
                border-color: #0077FF;
                box-shadow: 0 0 5px rgba(0, 119, 255, 0.2);
            }
        </style>

    </div>
</section>
@endsection

<style>
    /* Custom Styles */
    .alert-success, .alert-danger {
        border-radius: 10px;
        font-size: 0.9rem;
        text-align: center;
        margin-top: 10px;
    }

    /* Form Input Focus Effect */
    .form-control:focus {
        outline: none;
        border-color: #0077FF;
        box-shadow: 0 0 5px rgba(0, 119, 255, 0.2);
    }
</style>
