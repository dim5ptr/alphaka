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
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #0077FF; font-weight: bold;">Transaction List</h1>
            </div>
        </div>
    </div>
</div>

<!-- Table and Search -->
<section class="content">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="input-group rounded shadow-sm">
                    <span class="input-group-text" style="background-color: #0077FF; color: white; border: none;">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="search" id="searchInput" class="form-control rounded" placeholder="Search..." style="border: none; padding: 10px;">
                </div>
            </div>
        </div>

        <div class="table-container">
            <table class="table custom-table mt-4">
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
                    @foreach ($transactions as $index => $transaction)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ \Carbon\Carbon::parse($transaction['transaction_date'])->format('d-m-Y') }}</td>
                            <td>{{ $transaction['transaction_number'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction['created_date'])->format('d-m-Y H:i') }}</td>
                            <td>{{ number_format($transaction['total_amount'], 2) }}</td>
                            <td>{{ $transaction['notes'] ?? 'N/A' }}</td>
                            <td class="action-buttons text-center">
                                <div class="btn-group" role="group" aria-label="Action Buttons">
                                    <form action="{{ route('showmoredetailsadm') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="transaction_id" value="{{ $transaction['id'] }}">
                                        <button type="submit" class="btn btn-outline-primary btn-sm custom-outline-btn" title="More details">
                                            <i class="fa fa-info-circle"></i>
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
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('keyup', function(event) {
            var searchText = event.target.value.toLowerCase();
            var rows = document.querySelectorAll('.custom-table tbody tr');
            rows.forEach(function(row) {
                var shouldDisplay = false;
                for (var i = 0; i < row.cells.length; i++) {
                    var cellText = row.cells[i].textContent.toLowerCase();
                    if (cellText.includes(searchText)) {
                        shouldDisplay = true;
                        break;
                    }
                }
                row.style.display = shouldDisplay ? '' : 'none';
            });
        });
    });
</script>
@endsection
