@extends('admin.layoutadm.layoutadm')

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

    /* Table Design */
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

    /* Container for the action buttons */
/* Container for the action buttons */
.action-buttons {
    padding: 10px; /* Adds padding around the action buttons */
    display: flex;
    justify-content: center; /* Center align the buttons */
    gap: 10px; /* Adds space between the buttons */
}

/* Group of buttons */
.btn-group {
    display: flex;
    justify-content: space-between; /* Distribute buttons evenly */
}

/* Custom button style */
.custom-btn {
    margin: 0;
    padding: 10px 20px; /* Adjust padding for a rectangular button */
    font-size: 14px; /* Font size for better legibility */
    font-weight: bold; /* Make text bold */
    text-align: center; /* Align text centrally */
    border: none; /* No border */
    border-radius: 0px; /* No rounded corners */
    transition: all 0.3s ease; /* Smooth transition for all properties */
    background-color: #365AC2; /* Background color */
    color: white; /* Text color */
}

/* Button hover effect */
.custom-btn:hover {
    background-color: #2c458d; /* Slightly darker background on hover */
    transform: scale(1.05); /* Slightly enlarge the button */
}

/* Optionally style the outline button the same way */
.btn-primary {
    background-color: #365AC2; /* Primary background color */
    color: white; /* Text color */
}

.btn-primary:hover {
    background-color: #2c458d; /* Darker background color on hover */
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
                        <th scope="col">Action</th>
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
                                <div class="action-buttons">
                                    <div class="btn-group" role="group" aria-label="Action Buttons">
                                        <!-- Button to trigger modal -->
                                        <button type="button" class="btn btn-outline-primary btn-sm custom-outline-btn" data-toggle="modal" data-target="#licenseModal" title="Acc Transaction">
                                            <i class="fa fa-check-circle"></i>
                                        </button>

                                        <!-- More details button -->
                                        <form action="{{ route('showmoredetailsadm') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="transaction_id" value="{{ $transaction['id'] }}">
                                            <button type="submit" class="btn btn-outline-primary btn-sm custom-outline-btn" title="More details">
                                                <i class="fa fa-info-circle"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No transactions available.</td>
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
