@extends('admin.layoutadm.layoutadm')

@section('content')

<!-- Main content -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #0077FF; font-weight: bold;">License</h1>
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
                <a href="{{ route('showLicense') }}" class="btn btn-primary me-2" style="background-color: #1c65b9; color: white; font-weight: bold; margin-right: 3%; margin-bottom: 1%;">
                    License List
                </a>
                <a href="{{ route('getActivityData') }}" class="btn btn-primary me-2" style="background-color: #0077FF; color: white; font-weight: bold; margin-right: 3%; margin-bottom: 1%;">
                    Activity
                </a>
                <a href="{{ route('getHooksData') }}" class="btn btn-primary me-2" style="background-color: #0077FF; color: white; font-weight: bold; margin-right: 3%; margin-bottom: 1%;">
                    Hooks
                </a>
                <a href="{{ route('getLicenseOrderData') }}" class="btn btn-primary" style="background-color: #0077FF; color: white; font-weight: bold; margin-right: 3%; margin-bottom: 1%;">
                    License Order
                </a>
                <a href="{{ route('getSerialNumberData') }}" class="btn btn-primary" style="background-color: #0077FF; color: white; font-weight: bold; margin-right: 3%; margin-bottom: 1%;">
                    Serial Number
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
            .modal {
                display: none; /* Hidden by default */
                position: fixed;
                z-index: 1;
                left: 0;
                top: 1%;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
            }
            .modal-backdrop {
                display: none; /* Hides backdrop when modal is closed */
            }

            .modal-content {
                background-color: white;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
                border-radius: 8px;
            }
            .modal-header .btn-close {
                font-size: 1.5rem; /* Adjust icon size */
                color: #0077FF;    /* Change the color of the icon */
            }

            .modal-header .btn-close:hover {
                color: #ff0000; /* Change color on hover */
            }

            .close-btn {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close-btn:hover,
            .close-btn:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
 /* Responsive Layout for Mobile */
        @media (max-width: 768px) {
            .modal-content {
                width: 80%; /* Ensure the modal takes up more space on smaller screens */
                margin-top: 30% auto; /* Adjust the top margin */
                padding: 15px; /* Reduce padding for mobile */
            }

            .modal-header .btn-close {
                font-size: 1.2rem; /* Smaller close icon size for mobile */
            }

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

            @media screen and (max-width: 480px) {
                .modal-content {
                    width: 90%; /* Make modal even wider on very small screens */
                    margin-top: 25% auto; /* Adjust margin */
                    padding: 10px; /* Reduce padding further */
                }

                .modal-header .btn-close {
                    font-size: 1rem; /* Further reduce the close button size */
                }
            }
        </style>

<div class="table-container">
    <table class="table custom-table mt-0">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">License Key</th>
                <th scope="col">Status</th>
                <th scope="col">Type</th>
                <th scope="col">Total Used</th>
                <th scope="col">Created Date</th>
                <th scope="col">Activated Date</th>
                <th scope="col">Expired Date</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
          @foreach($data as $no => $license)
                <tr>
                <th scope="row">{{ $no + 1 }}</th>
                <td>{{ isset($license['license_key']) ? '****' . substr($license['license_key'], -4) : 'N/A' }}</td>
                <td>{{ $license['status'] ?? 'N/A' }}</td>
                <td>{{ $license['type'] ?? 'N/A' }}</td>
                <td>{{ $license['total_used'] ?? '0' }}</td>
                <td>{{ \Carbon\Carbon::parse($license['created_date'])->format('d-m-Y H:i') ?? 'N/A' }}</td>
                <td>{{ isset($license['activated_date']) ? \Carbon\Carbon::parse($license['activated_date'])->format('d-m-Y H:i') : 'N/A' }}</td>
                <td>{{ isset($license['expired_date']) ? \Carbon\Carbon::parse($license['expired_date'])->format('d-m-Y H:i') : 'N/A' }}</td>
                <td class="action-buttons text-center">
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <form action="javascript:void(0);" onsubmit="showLicenseDetails('{{ $license['license_id'] }}')">
                            <button type="submit" class="btn btn-outline-primary btn-sm custom-outline-btn" title="More details">
                                <i class="fa fa-info-circle action-icon"></i>
                            </button>
                        </form>
                    </div>
                </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
    </div>
</section>

<!-- Modal for License Details -->
<div class="modal fade" id="licenseDetailsModal" tabindex="-1" aria-labelledby="licenseDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="licenseDetailsModalLabel">License Details</h5>
                {{-- <button type="button" id="closeModalBtn" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>  <!-- Use 'X' icon instead of the minus -->
                </button> --}}

            </div>
            <div class="modal-body">
                <div id="licenseDetailsContent">
                    <!-- Dynamic license details will be populated here -->
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('script')
<script>
//    function showLicenseDetails(licenseId) {
//     // Open the modal
//     var myModal = new bootstrap.Modal(document.getElementById('licenseDetailsModal'), {
//         keyboard: false
//     });
//     myModal.show();

//     // Send an AJAX request to fetch the license details by ID
//     fetch('/licenses/details', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': '{{ csrf_token() }}'
//         },
//         body: JSON.stringify({ id: licenseId })
//     })
//     .then(response => response.json()) // Parse response as JSON
//     .then(data => {
//         // Log the full JSON response from the API
//         console.log("API Response:", JSON.stringify(data, null, 2));

//         if (data.success && data.data.length > 0) {
//             // Access the first license in the data array
//             const license = data.data[0];

//             // Populate the modal with the license details
//             document.getElementById('licenseDetailsContent').innerHTML = `
//                 <p><strong>License Key:</strong> ****${license.license_key.slice(-4)}</p>
//                 <p><strong>License Type:</strong> ${license.type ?? 'N/A'}</p>
//                 <p><strong>Status:</strong> ${license.status ?? 'N/A'}</p>
//                 <p><strong>Total Used:</strong> ${license.total_used ?? '0'}</p>
//                 <p><strong>Created Date:</strong> ${new Date(license.created_date).toLocaleString()}</p>
//                 <p><strong>Activated Date:</strong> ${license.activated_date ? new Date(license.activated_date).toLocaleString() : 'N/A'}</p>
//                 <p><strong>Expired Date:</strong> ${license.expired_date ? new Date(license.expired_date).toLocaleString() : 'N/A'}</p>
//             `;
//         } else {
//             // Display error if the license details are not found
//             document.getElementById('licenseDetailsContent').innerHTML = `
//                 <p class="text-danger">License details not found or unavailable.</p>
//             `;
//         }
//     })
//     .catch(error => {
//         // Handle network or JavaScript errors
//         console.error('Error fetching license details:', error);
//         document.getElementById('licenseDetailsContent').innerHTML = `
//             <p class="text-danger">An error occurred while fetching the details: ${error.message}</p>
//         `;
//     });
// }
function showLicenseDetails(licenseId) {
    // Open the modal
    var myModal = new bootstrap.Modal(document.getElementById('licenseDetailsModal'), {
        keyboard: false
    });
    myModal.show(); // Show the modal

    const apiUrl = 'http://192.168.1.24:14041/api/license/get_license_data_by_id.json';
    const accessToken = '{{ session('access_token') }}'; // Access token from Blade session

    // Send an AJAX request to fetch license details
    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Authorization': accessToken,
            'x-api-key': '5af97cb7eed7a5a4cff3ed91698d2ffb',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: licenseId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data && data.data.length > 0) {
            const license = data.data[0];

            // Populate the modal with the license details
            document.getElementById('licenseDetailsContent').innerHTML = `
                <p><strong>License Key:</strong> ${license.license_key}</p>
                <p><strong>License Type:</strong> ${license.type ?? 'N/A'}</p>
                <p><strong>Status:</strong> ${license.status ?? 'N/A'}</p>
                <p><strong>Total Used:</strong> ${license.total_used ?? '0'}</p>
                <p><strong>Created Date:</strong> ${new Date(license.created_date).toLocaleString()}</p>
                <p><strong>Activated Date:</strong> ${license.activated_date ? new Date(license.activated_date).toLocaleString() : 'N/A'}</p>
                <p><strong>Expired Date:</strong> ${license.expired_date ? new Date(license.expired_date).toLocaleString() : 'N/A'}</p>
            `;
        } else {
            document.getElementById('licenseDetailsContent').innerHTML = `
                <p class="text-danger">License details not found or unavailable.</p>
            `;
        }
    })
    .catch(error => {
        console.error('Error fetching license details:', error);
        document.getElementById('licenseDetailsContent').innerHTML = `
            <p class="text-danger">An error occurred while fetching the details: ${error.message}</p>
        `;
    });
}

// Close modal functionality (No need to refresh the page)
document.getElementById('closeModalBtn').addEventListener('click', function() {
    var myModal = new bootstrap.Modal(document.getElementById('licenseDetailsModal'));
    myModal.hide(); // This will hide the modal
});



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
</script>
@endsection
