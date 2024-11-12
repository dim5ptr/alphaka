@extends('admin.layoutadm.layoutadm')

@section('content')

<!-- Main content -->
<div class="content-header bg-light p-4 shadow-sm rounded" style="margin-bottom: 2%;">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #0077FF; font-weight: bold; font-size: 2rem;">Product Release</h1>
            </div>
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
                <input type="search" id="searchInput" class="form-control rounded shadow-sm" placeholder="Search..." style="border: none; padding: 10px;">
            </div>
                <!-- Tombol Create Product -->
            <a href="{{ route('createProductForm') }}">
                <button class="btn btn-primary ms-2" style="background-color: #2175d5; font-weight: bold; margin-left: 20%;">
                    <i class="fas fa-plus"></i>
                </button>
            </a>
        </div>
        <div class="row mb-4">
            <!-- Section for links aligned to the left -->
            <div class="col-12 d-flex flex-wrap justify-content-start align-items-center">
                <a href="{{ route('showProducts') }}" class="btn btn-primary me-2" style="background-color: #0077FF; color: white; font-weight: bold; margin-right: 3%;  margin-bottom: 1%;">
                    Product List
                </a>
                <a href="{{ route('showProductsFolder') }}" class="btn btn-primary me-2" style="background-color: #0077FF; color: white; font-weight: bold; margin-right: 3%;  margin-bottom: 1%;">
                    Product Folder
                </a>
                <a href="{{ route('showProductsFeatures') }}" class="btn btn-primary me-2" style="background-color: #0077FF; color: white; font-weight: bold; margin-right: 3%; margin-bottom: 1%;">
                    Product Features
                </a>
                <a href="{{ route('showProductsRelease') }}" class="btn btn-primary" style="background-color: #1c65b9; color: white; font-weight: bold; margin-right: 3%;  margin-bottom: 1%;">
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
        </style>

            <div class="table-container">
                <table class="table custom-table mt-0">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Created Date</th>
                            <th scope="col">Created By</th>
                            <th scope="col">Product Id</th>
                            <th scope="col">Product Version</th>
                            <th scope="col">Notes</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $no => $product)
                            <tr>
                                <th scope="row">{{ $no + 1 }}</th> <!-- Displaying the no + 1 -->
                                <td>{{ \Carbon\Carbon::parse($product['created_date'])->format('d-m-Y H:i') }}</td>
                                <td>{{ $product['created_by'] ?? 'N/A' }}</td>
                                <td>{{ $product['product_id'] ?? 'N/A' }}</td>
                                <td>{{ $product['product_version'] ?? 'N/A' }}</td>
                                <td>{{ $product['notes'] ?? 'N/A' }}</td>
                                <td class="action-buttons text-center">
                                    <div class="btn-group" role="group" aria-label="Action Buttons">
                                        <form action="{{ route('showmoredetailsadm') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary btn-sm custom-outline-btn" title="More details">
                                                <i class="fa fa-info-circle action-icon"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No products available.</td>
                            </tr>
                        @endforelse
                        <!-- Baris pesan "no match data found" -->
                        <tr id="noMatchRow" style="display: none;">
                            <td colspan="7" class="text-center">No match data found.</td>
                        </tr>
                    </tbody>
                </table>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
   document.getElementById('searchInput').addEventListener('keyup', function() {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll('.table-container tbody tr');
    let matchFound = false;

    rows.forEach(row => {
        // Skip baris "no match data found" saat pengecekan
        if (row.id === 'noMatchRow') return;

        const productId = row.cells[1].textContent.toLowerCase();
        const productName = row.cells[2].textContent.toLowerCase();
        const productCode = row.cells[3].textContent.toLowerCase();
        const createdBy = row.cells[5].textContent.toLowerCase();

        if (productId.includes(query) || productName.includes(query) || productCode.includes(query) || createdBy.includes(query)) {
            row.style.display = ''; // Tampilkan baris yang cocok
            matchFound = true;
        } else {
            row.style.display = 'none'; // Sembunyikan baris yang tidak cocok
        }
    });

    // Tampilkan "no match data found" jika tidak ada data yang cocok
    document.getElementById('noMatchRow').style.display = matchFound ? 'none' : '';
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
</script>
@endsection
