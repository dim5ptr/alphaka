@extends('admin.layoutadm.layoutadm')

@section('content')
<div class="content-header bg-light p-4 shadow-sm rounded mb-3">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-primary font-weight-bold" style="font-size: 2rem;">Update Product</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container">

        @if(session('success'))
            <div class="alert alert-success shadow-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger shadow-sm mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="card p-4 shadow-sm rounded">
            <form action="{{ route('updateProduct') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" class="form-control" name="product_id" id="product_id" value="{{ session('product_id') }}" required>

                <div class="mb-4">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" name="product_name" id="product_name" value="{{ session('product_name') }}" required>
                </div>

                <div class="mb-4">
                    <label for="product_code" class="form-label">Product Code</label>
                    <input type="text" class="form-control" name="product_code" id="product_code" value="{{ session('product_code') }}" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" name="description" id="description">{{ session('description') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" name="price" id="price" value="{{ session('price') }}" step="0.001" required>
                </div>

                <div class="mb-4">
                    <label for="product_type" class="form-label">Product Type</label>
                    <input type="number" class="form-control" name="product_type" id="product_type" value="{{ session('product_type') }}" required>
                </div>
                <div class="mb-4">
                    <label for="logo" class="form-label">Logo</label>
                    @if(session('logo_path'))
                        <div class="mb-3 text-center">
                            <img src="{{ asset(session('logo_path')) }}" alt="Current Logo" class="img-thumbnail" style="max-width: 100px;">
                        </div>
                    @endif
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="logo" id="logo">
                        <label class="custom-file-label btn btn-primary text-black" for="logo">Choose file</label>
                    </div>
                </div>

                <!-- Display Images Upload -->
                <div class="mb-4">
                    <label for="display_images" class="form-label">Display Images</label>
                    @if(session('display_images'))
                        <div class="row mb-3">
                            @foreach(session('display_images') as $image)
                                <div class="col-3">
                                    <img src="{{ asset($image) }}" alt="Display Image" class="img-thumbnail" style="max-width: 100px;">
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="display_images[]" id="display_images" multiple>
                        <label class="custom-file-label btn btn-primary text-black" for="display_images">Choose files</label>
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="enabled" id="enabled" {{ session('enabled') ? 'checked' : '' }}>
                    <label class="form-check-label" for="enabled">Enabled</label>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('showProducts') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary ">Save Changes</button>

                </div>
            </form>
        </div>
    </div>
</section>

<style>
    /* Custom Styles */
    .alert-success, .alert-danger {
        border-radius: 10px;
        font-size: 0.9rem;
        text-align: center;
    }

    /* Card and Form Styling */
    .card {
        background-color: #ffffff;
        border-radius: 15px;
        padding: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #555;
    }

    .form-control {
        border-radius: 5px;
        border: 1px solid #ced4da;
    }

    .form-control:focus {
        outline: none;
        border-color: #0077FF;
        box-shadow: 0 0 5px rgba(0, 119, 255, 0.2);
    }

    .btn-primary, .btn-secondary {
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056cc;
        border-color: #0056cc;
    }

    .btn-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    /* Image Thumbnail Styling */
    .img-thumbnail.preview-img {
        border-radius: 8px;
        max-width: 100px;
        max-height: 100px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .img-thumbnail.preview-img:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    /* Image Gallery Layout */
    .image-gallery .col-3 {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .form-control-file {

        display: block;
        max-width: 250px;
    }
    .custom-file-input:lang(en) ~ .custom-file-label::after {
        content: "Browse";
    }

</style>
<script>
    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.files.length > 1 ? `${this.files.length} files selected` : this.files[0].name;
            this.nextElementSibling.innerHTML = fileName;
        });
    });
</script>
@endsection
