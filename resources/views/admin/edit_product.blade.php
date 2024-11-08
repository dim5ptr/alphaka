@extends('admin.layoutadm.layoutadm')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #0077FF; font-weight: bold;">Create Role</h1>
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

        @section('content')
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0" style="color: #0077FF; font-weight: bold;">Update Role</h1>
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

                <form action="{{ route('updateProduct') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="product_name" id="product_name" value="{{ session('product_name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="product_code" class="form-label">Product Code</label>
                        <input type="text" class="form-control" name="product_code" id="product_code" value="{{ session('product_code') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" value="{{ session('description') }}"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" name="price" id="price" value="{{ session('price') }}" step="0.001" required>
                    </div>

                    <div class="mb-3">
                        <label for="product_type" class="form-label">Product Type</label>
                        <input type="number" class="form-control" name="product_type" id="product_type" value="{{ session('product_type') }}" required>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="enabled" id="enabled" {{ session('enable') ? 'checked' : '' }}>
                        <label class="form-check-label" for="enabled">Enabled</label>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="{{ route('showProducts') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
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
