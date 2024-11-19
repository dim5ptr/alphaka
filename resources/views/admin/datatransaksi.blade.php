@extends('admin.layoutadm.layoutadm')
@section('head')
<!-- Bootstrap 5 JavaScript and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

@endsection
@section('content')

 <!-- Custom CSS for smoother table design and search bar -->
 <style>
    /* Search Bar */
    .input-group-text {
        display: flex;
        /* align-items: center; */
        justify-content: flex-end;
        background-color: #0077FF;
        color: white;
        border-radius: 10px 0 0 10px;
        padding: 8px;
    }

    .input-group {

    }

    #searchInput {
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        font-size: 1rem;
    }

    #searchInput:focus {
        outline: none;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
    }

    .table-container {
        width: 100%;
        overflow-x: auto; /* Horizontal scroll for table */
        -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS devices */
        margin-bottom: 10%;
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

    /* Action Buttons Styling */
    /* Action Buttons Styling */
    .action-buttons {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    .btn-group {
        display: flex;
        justify-content: space-between;
    }

    .dropdown-menu {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .dropdown-item {
        padding: 10px 20px;
        color: #365AC2;
        font-size: 14px;
    }

    .dropdown-item:hover {
        background-color: #365AC2;
        color: white;
    }
    .custom-btn {
        margin: 0;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        border: none;
        border-radius: 0px;
        transition: all 0.3s ease;
        background-color: #365AC2;
        color: white;
    }

    .custom-btn:hover {
        background-color: #2c458d;
        transform: scale(1.05);
    }

    .btn-primary {
        background-color: #365AC2;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2c458d;
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
        font-size: 1.5rem;
        color: #0077FF;
    }

    .modal-header .btn-close:hover {
        color: #ff0000;
    }


</style>

<!-- Main content -->
<div class="content-header bg-light p-4 shadow-sm rounded" style="margin-bottom: 2%;">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #0077FF; font-weight: bold; font-size: 2rem;">Transaction</h1>
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
        </div>
        <div class="row mb-4">
            <!-- Section for links aligned to the left -->
            <div class="col-12 d-flex flex-wrap justify-content-start align-items-center">
                <a href="/transactions" class="btn btn-primary me-2" style="background-color: #2175d5; color: white; font-weight: bold; margin-right: 3%;  margin-bottom: 1%;">
                    Product Transaction
                </a>
                <a href="/payment-receipt" class="btn btn-primary me-2" style="background-color: #0077FF; color: white; font-weight: bold; margin-right: 3%;  margin-bottom: 1%;">
                    Cash Receipt
                </a>
            </div>
        </div>

        <div class="table-container">
            <table class="table custom-table mt-0">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Id</th>
                        <th scope="col">Created Date</th>
                        <th scope="col">Transactions Number</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-center">Action</th> <!-- Centered Action column header -->
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $index => $transaction)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ $transaction['id'] ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction['created_date'])->format('d-m-Y') }}</td>
                            <td>{{ $transaction['transaction_number'] }}</td>
                            <td>{{ $transaction['is_done'] ? 'success' : 'pending' }}</td>
                            <td class="action-buttons text-center">
                                <div class="btn-group" role="group" aria-label="Action Buttons">
                                    <!-- Dropdown Button -->
                                    <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-cogs"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <!-- Acc Transaction Option -->
                                        <li>
                                            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#licenseModal" title="Acc Transaction">
                                                <i class="fa fa-check-circle"></i> Acc Transaction
                                            </button>
                                        </li>
                                        <!-- Resend License Option -->
                                        <li>
                                            <form action="{{ route('resendLicense') }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="transaction_id" value="{{ $transaction['id'] }}">
                                                <button type="submit" class="dropdown-item" title="Resend License">
                                                    <i class="fa fa-envelope"></i> Resend License
                                                </button>
                                            </form>
                                        </li>
                                        <!-- More Details Option -->
                                        <li>
                                            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#transactionModal" title="More Details" onclick="showTransactionDetails('{{ $transaction['id'] }}')">
                                                <i class="fa fa-info-circle"></i> More Details
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No transactions available.</td>
                        </tr>
                    @endforelse
                    <!-- Message for no match data -->
                    <tr id="noMatchRow" style="display: none;">
                        <td colspan="7" class="text-center">No match data found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionModalLabel">Transaction Details</h5>
                </div>
                <div class="modal-body">
                    <div id="transactionDetailsContent">
                        <!-- Dynamic transaction details will be populated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for selecting license type -->
<div class="modal fade" id="licenseModal" tabindex="-1" role="dialog" aria-labelledby="licenseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="licenseModalLabel">Select License Type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{ route('createLicense') }}" method="POST" id="licenseForm">
              @csrf
              <input type="hidden" name="transaction_id" value="{{ $transaction['id'] }}">

              <!-- License Type Dropdown -->
              <div class="form-group">
                  <label for="license_type">Select License Type:</label>
                  <select name="license_type" id="license_type" class="form-control">
                      <option value="0">Trial</option>
                      <option value="1">Paid</option>
                  </select>
              </div>

              <!-- Submit Button inside Modal -->
              <button type="submit" class="btn btn-outline-primary btn-sm">
                  <i class="fa fa-check-circle"></i> Submit License
              </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('script')
<script>

function showTransactionDetails(transactionId) {

    console.log("Transaction ID:", transactionId);
    // Open the modal
    var myModal = new bootstrap.Modal(document.getElementById('transactionModal'), { keyboard: false });
    myModal.show();

    const apiUrl = 'http://192.168.1.24:14041/api/product/get_transaction_details.json';
    const accessToken = '{{ session('access_token') }}'; // Access token from Blade session

    // Send an AJAX request to fetch transaction details
    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Authorization': accessToken,
            'x-api-key': '5af97cb7eed7a5a4cff3ed91698d2ffb',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ transaction_id: transactionId })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        // Debugging: log the full response for analysis
        console.log('Response data:', data);

        if (data.success) {
            const transaction = data.transaction; // Assuming the main transaction details are here
            const license = data.license; // License details
            const productDetails = data.product_details[0]; // Assuming there's at least one product

            // Populate the modal with the transaction details
            document.getElementById('transactionDetailsContent').innerHTML = `
                <p><strong>Transaction ID:</strong> ${transaction.id}</p>
                <p><strong>Transaction Number:</strong> ${transaction.transaction_number}</p>
                <p><strong>Status:</strong> ${transaction.is_done ? 'Success' : 'Pending'}</p>
                <p><strong>Created Date:</strong> ${new Date(transaction.created_date).toLocaleString()}</p>
                <p><strong>User Email:</strong> ${data.user_email ?? 'N/A'}</p>
                <p><strong>Total Amount:</strong> $${transaction.total_amount.toFixed(2)}</p>
                <hr>
                <h5>License Details</h5>
                <p><strong>License Key:</strong> ${license.license_key}</p>
                <p><strong>License Type:</strong> ${license.license_type}</p>
                <p><strong>Notes:</strong> ${license.notes}</p>
                <p><strong>Expiration Date:</strong> ${new Date(license.expired_date).toLocaleString()}</p>
                <hr>
                <h5>Product Details</h5>
                <p><strong>Product Name:</strong> ${productDetails.product_name}</p>
                <p><strong>Product Description:</strong> ${productDetails.description}</p>
            `;
        } else {
            // If data.success is false, log additional information
            console.error('Error: ', data.message);
            document.getElementById('transactionDetailsContent').innerHTML = `
                <p class="text-danger">Transaction details not found or unavailable. Error: ${data.message || 'Unknown error'}</p>
            `;
        }
    })
    .catch(error => {
        // Log the error details for debugging
        console.error('Error fetching transaction details:', error);
        document.getElementById('transactionDetailsContent').innerHTML = `
            <p class="text-danger">An error occurred while fetching the details: ${error.message}</p>
        `;
    });
}

    // For instance, you can open the modal when a button is clicked
$('#openLicenseModal').click(function() {
    $('#licenseModal').modal('show');
});

document.getElementById('searchInput').addEventListener('keyup', function() {
    const query = this.value.toLowerCase();  // Ambil input pencarian dan ubah menjadi huruf kecil
    const rows = document.querySelectorAll('.table-container tbody tr:not(#noMatchRow)');  // Ambil semua baris tabel, kecuali baris 'no match'
    let matchFound = false;  // Menandakan apakah ada data yang cocok dengan pencarian

    rows.forEach(row => {
        const id = row.cells[1]?.textContent.toLowerCase() || "";  // Ambil nilai ID
        const createdDate = row.cells[2]?.textContent.toLowerCase() || "";  // Ambil nilai Created Date
        const transactionNumber = row.cells[3]?.textContent.toLowerCase() || "";  // Ambil nilai Transaction Number
        const status = row.cells[4]?.textContent.toLowerCase() || "";  // Ambil nilai Status

        // Periksa apakah salah satu kolom mengandung kata kunci pencarian
        if (
            id.includes(query) ||
            createdDate.includes(query) ||
            transactionNumber.includes(query) ||
            status.includes(query)
        ) {
            row.style.display = '';  // Tampilkan baris yang cocok
            matchFound = true;  // Tandai bahwa ada pencocokan
        } else {
            row.style.display = 'none';  // Sembunyikan baris yang tidak cocok
        }
    });

    // Tampilkan pesan "no match data found" jika tidak ada data yang cocok
    document.getElementById('noMatchRow').style.display = matchFound ? 'none' : '';
});
</script>
@endsection
