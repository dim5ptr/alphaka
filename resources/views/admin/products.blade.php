@extends('admin.layoutadm.layoutadm')

@section('content')

<!-- Main content -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #0077FF; font-weight: bold;">Products List</h1>
                </div>
            <div class="col-sm-6">
                <!-- You can add additional header content here if needed -->
            </div>
            <br><br>
        </div>
    </div>
</div>

<!-- Table and Search -->
<section class="content">
    <div class="container">
         <!-- Search bar aligned to the right and below links -->
         <div class="col-12 mt-3 d-flex justify-content-start" style="margin-bottom: 3%;">
            <div class="input-group rounded shadow-sm" style="max-width: 90%; width: 100%;">
                <span class="input-group-text" style="background-color: #0077FF; color: white; border: none;">
                    <i class="fa fa-search"></i>
                </span>
                <input type="search" id="searchInput" class="form-control rounded" placeholder="Search..." style="border: none; padding: 10px;">
            </div>
                <!-- Button to Create New Input -->
                <button class="btn btn-primary ms-2" style="background-color: #2175d5; font-weight: bold;  margin-left: 3%;">
                    <i class="fas fa-plus"></i>
                </button>
        </div>

        <div class="row mb-4">
            <!-- Section for links aligned to the left -->
            <div class="col-12 d-flex flex-wrap justify-content-start align-items-center">
                <a href="{{ route('showProducts') }}" class="btn btn-primary me-2" style="background-color: #1c65b9; color: white; font-weight: bold; margin-right: 3%; margin-bottom: 1%;">
                    Product List
                </a>
                <a href="{{ route('showProductsFolder') }}" class="btn btn-primary me-2" style="background-color: #0077FF; color: white; font-weight: bold; margin-right: 3%; margin-bottom: 1%;">
                    Product Folder
                </a>
                <a href="{{ route('showProductsFeatures') }}" class="btn btn-primary me-2" style="background-color: #0077FF; color: white; font-weight: bold; margin-right: 3%; margin-bottom: 1%;">
                    Product Features
                </a>
                <a href="{{ route('showProductsRelease') }}" class="btn btn-primary" style="background-color: #0077FF; color: white; font-weight: bold; margin-right: 3%; margin-bottom: 1%;">
                    Product Release
                </a>
            </div>

        </div>


        <!-- Custom CSS for smoother table design and search bar -->
        <style>
            /* Search Bar */
            .input-group-text {
                display: flex;
                align-items: center;
                background-color: #0077FF;
                color: white;
                border-radius: 10px 0 0 10px;
                padding: 8px;
            }

            #searchInput {
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
                font-size: 1rem;
            }

            #searchInput:focus {
                outline: none;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
            }

            /* Table Design */
            .table-container {
                width: 100%;
                overflow-x: auto; /* Horizontal scroll for table */
                -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS devices */
                margin-bottom: 1%;
            }

            .table {
                width: 100%;
                border-collapse: collapse;
                background-color: white;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .table thead {
                background-color: #0077FF;
                color: #ffffff;
            }

            .table th, .table td {
                padding: 15px;
                text-align: left;
                border-bottom: 1px solid #f0f0f0;
                white-space: nowrap; /* Prevents table cells from wrapping */
            }

            /* Row styles */
            .table tbody tr {
                transition: background-color 0.2s;
            }

            .table tbody tr:hover {
                background-color: #eef5ff;
            }

            .table tbody tr.selected {
                background-color: #0066ff;
                color: white;
            }

            .table tbody tr.selected td {
                color: white;
            }

            .action-buttons {
                padding: 10px; /* Adds padding around the action buttons */
            }

            .btn-group {
                display: flex;
                justify-content: center; /* Center align the buttons */
            }

            .custom-outline-btn {
                margin: 0 5px; /* Add space between buttons */
                border: 2px solid transparent; /* Default border style */
                transition: border-color 0.3s ease; /* Smooth transition for border color */
            }

            .custom-outline-btn:hover {
                border-color: #365AC2; /* Change border color on hover */
            }

            /* Optional: style for buttons */
            .btn-outline-primary {
                color: #365AC2; /* Set text color */
            }

            .btn-outline-primary:hover {
                background-color: #365AC2; /* Background color on hover */
                color: white; /* Change text color on hover */
            }
            .action-icon {
                font-size: 1.2em; /* Adjust to your desired size */
            }
 /* Responsive Layout for Mobile */
        @media (max-width: 768px) {
                .col-6 {
                    padding: 5px;
                }

                .row.mb-4.align-items-center > .col-6 {
                    flex: 1 1 auto;
                }

                .btn, .input-group {
                    width: 100%;
                }
            }

              /* Menambahkan efek transisi halus pada modal */
    .modal.fade .modal-dialog {
        transform: translate(0, -50%);
        transition: transform 0.3s ease-out;
    }

    .modal.fade.show .modal-dialog {
        transform: translate(0, 0);
    }

    /* Memastikan modal memiliki tinggi yang cukup besar */
    .modal-dialog {
        max-width: 600px;
        height: auto;
    }

    .modal-content {
        border-radius: 8px;
    }

    /* Gaya tambahan untuk header modal */
    .modal-header {
        background-color: #007bff;
        color: white;
        font-size: 1.25rem;
        font-weight: bold;
    }

    /* Gaya untuk tombol di modal */
    .modal-footer .btn {
        font-weight: bold;
    }

    /* Membuat tombol close lebih besar dan lebih terlihat */
    .btn-close {
        font-size: 1.5rem;
        color: white;
    }

    /* Memberikan margin bawah pada modal body */
    .modal-body {
        padding-bottom: 20px;
    }
        </style>

      <div class="table-container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Product Id</th>
                <th>Product Name</th>
                <th>Product Code</th>
                <th>Description</th>
                <th>Created By</th>
                <th>Created Date</th>
                <th>Price</th>
                <th>Enabled</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($products) && count($products) > 0)
                @foreach($products as $no => $product)
                    <tr>
                        <th scope="row">{{ $no + 1 }}</th>
                        <td>{{ $product['product_id'] ?? 'N/A' }}</td>
                        <td>{{ $product['product_name'] ?? 'N/A' }}</td>
                        <td>{{ $product['product_code'] ?? 'N/A' }}</td>
                        <td>{{ $product['description'] ?? 'N/A' }}</td>
                        <td>{{ $product['created_by'] ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($product['created_date'])->format('d-m-Y H:i') }}</td>
                        <td>Rp.{{ $product['price'] ?? '0' }}</td>
                        <td>{{ $product['enabled'] ? 'Yes' : 'No' }}</td>
                        <td class="text-center">
                            <a href="{{ route('showproductsedit', ['id' => $product['product_id']]) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fa fa-edit action-icon"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="text-center">No products available.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Modal Edit Product -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="updateProductForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST"> <!-- Add hidden _method for POST compatibility -->
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="product_id">

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
                        <input class="form-check-input" type="checkbox" name="enabled" id="enabled">
                        <label class="form-check-label" for="enabled">Enabled</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>



        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('searchInput');

    searchInput.addEventListener('keyup', function(event) {
        var searchText = event.target.value.toLowerCase();
        var rows = document.querySelectorAll('.custom-table tbody tr');

        rows.forEach(function(row) {
            // Initialize a variable to check if the row should be displayed
            var shouldDisplay = false;

            // Loop through all cells in the current row
            for (var i = 0; i < row.cells.length; i++) {
                var cellText = row.cells[i].textContent.toLowerCase();
                // Check if the search text is found in the current cell
                if (cellText.includes(searchText)) {
                    shouldDisplay = true;
                    break; // No need to check further, we found a match
                }
            }

            // Display the row if there's a match, otherwise hide it
            row.style.display = shouldDisplay ? '' : 'none';
        });
    });
});

    function editUser(userId) {
        window.location.href = '/users/edit/' + userId;
    }

    function confirmDelete(button) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
    // Attach event listener to all edit buttons to open modal with product data
    document.querySelectorAll('.edit-product-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const productCode = this.getAttribute('data-product-code');
            const description = this.getAttribute('data-description');
            const price = this.getAttribute('data-price');
            const productType = this.getAttribute('data-product-type');
            const enabled = this.getAttribute('data-enabled') === '1';

            // Verify if data attributes are present and not empty
            if (!productId || !productName || !productCode) {
                console.error('Missing required product data');
                return; // Exit if essential data is missing
            }

            // Set form action to include product_id
            const form = document.getElementById('updateProductForm');
            if (form) {
                form.action = `/products/update/${productId}`;
            }

            // Set form values
            document.getElementById('product_id').value = productId;
            document.getElementById('product_name').value = productName;
            document.getElementById('product_code').value = productCode;
            document.getElementById('description').value = description;
            document.getElementById('price').value = price;
            document.getElementById('product_type').value = productType;
            document.getElementById('enabled').checked = enabled;

            // Log data to console
            console.log(`Opening modal for product ${productId}: ${productName}`);
            console.log(`Form action set to: ${form.action}`);

            // Show the modal using Bootstrap's modal function
            $('#editProductModal').modal('show');

            // Log modal opening confirmation
            console.log('Modal opened successfully.');
        });
    });
});

</script>
@endsection
