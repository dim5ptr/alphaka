@extends('admin.layoutadm.layoutadm')

@section('content')

<!-- Main content -->
<div class="content-header bg-light p-4 shadow-sm rounded" style="margin-bottom: 2%;">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #0077FF; font-weight: bold; font-size: 2rem;">Inbox</h1>
            </div>
        </div>
    </div>
</div>

<!-- Table and Search -->
<section class="content">
    <div class="container">
        <!-- Search bar aligned to the right -->
        <div class="col-12 mt-3 d-flex justify-content-start" style="margin-bottom: 3%;">
            <div class="input-group rounded shadow-sm" style="max-width: 90%; width: 100%;">
                <span class="input-group-text" style="background-color: #0077FF; color: white; border: none;">
                    <i class="fa fa-search"></i>
                </span>
                <input type="search" id="searchInput" class="form-control rounded" placeholder="Search..." style="border: none; padding: 10px;">
            </div>
        </div>

        <!-- Inbox table -->
        <div class="table-container">
            <table class="table custom-table mt-0">
                <tbody>
                    @forelse($messages as $message)
                        <tr class="{{ $message['type'] == 'success' ? 'table-success' : 'table-danger' }}">
                            <td>{{ ucfirst($message['type']) }}</td>
                            <td>{{ $message['message'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($message['created_at'])->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada pesan di inbox Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Custom CSS for the inbox table -->
<style>
    .table-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
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


    .table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #f0f0f0;
        white-space: nowrap;
    }

    .table tbody tr {
        transition: background-color 0.2s;
    }

    .table tbody tr:hover {
        background-color: #eef5ff;
    }

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
</style>

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
