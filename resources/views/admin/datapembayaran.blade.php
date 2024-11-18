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
                <a href="/transactions" class="btn btn-primary me-2" style="background-color: #0077FF; color: white; font-weight: bold; margin-right: 3%;  margin-bottom: 1%;">
                    Product Transaction
                </a>
                <a href="/payment-receipt" class="btn btn-primary me-2" style="background-color: #2175d5; color: white; font-weight: bold; margin-right: 3%;  margin-bottom: 1%;">
                    Cash Receipt
                </a>
            </div>
        </div>

          <div class="table-container">
            <table class="table custom-table mt-0">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Transaction Date</th>
                        <th scope="col">Transaction Number</th>
                        <th scope="col">Created Date</th>
                        <th scope="col">Total Amount</th>
                        <th scope="col">Notes</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($receipt as $index => $receipt)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ \Carbon\Carbon::parse($receipt['transaction_date'])->format('d-m-Y') }}</td>
                            <td>{{ $receipt['transaction_number'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($receipt['created_date'])->format('d-m-Y H:i') }}</td>
                            <td>{{ number_format($receipt['total_amount'], 2) }}</td>
                            <td>{{ $receipt['notes'] ?? 'N/A' }}</td>
                            <td class="action-buttons text-center">
                                <div class="btn-group" role="group" aria-label="Action Buttons">
                                    <form action="{{ route('showmoredetailsadm') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="transaction_id" value="{{ $receipt['id'] }}">
                                        <button type="submit" class="btn btn-outline-primary btn-sm custom-outline-btn" title="More details">
                                            <i class="fa fa-info-circle"></i>
                                        </button>
                                    </form>
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
</section>
@endsection

@section('script')
<script>
 document.getElementById('searchInput').addEventListener('keyup', function() {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll('.table-container tbody tr:not(#noMatchRow)');
    let matchFound = false;

    rows.forEach(row => {
        const transaction_date = row.cells[1]?.textContent.toLowerCase() || "";
        const transaction_number = row.cells[2]?.textContent.toLowerCase() || "";
        const created_date = row.cells[3]?.textContent.toLowerCase() || "";
        const notes = row.cells[5]?.textContent.toLowerCase() || "";

        if (
            transactionDate.includes(query) ||
            transactionNumber.includes(query) ||
            createdDate.includes(query) ||
            notes.includes(query)
        ) {
            row.style.display = ''; // Tampilkan baris yang cocok
            matchFound = true;
        } else {
            row.style.display = 'none'; // Sembunyikan baris yang tidak cocok
        }
    });

    // Tampilkan "no match data found" jika tidak ada data yang cocok
    document.getElementById('noMatchRow').style.display = matchFound ? 'none' : '';
});
</script>
@endsection
