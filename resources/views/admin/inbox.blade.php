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
<!-- Inbox table -->
<div class="table-container">
    <table class="table custom-table mt-0">
        <tbody>

            @if(is_array($messages) || is_object($messages))
                @foreach($messages as $message)
                    <tr>
                        <td style="color: {{ $message['type'] == 'success' ? '#28a745' : '#dc3545' }};">
                            {{ ucfirst($message['type']) }}
                        </td>
                        <td>{{ $message['message'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($message['created_at'])->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($message['created_at'])->format('H:i:s') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">Tidak ada pesan di inbox Anda.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>


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
        overflow: hidden;
        border-collapse: separate;
        border-spacing: 0 10px; /* Menambahkan spasi vertikal 10px */
    }

    .table td {
        padding: 15px;
        text-align: left;
        background-color: white;
        white-space: nowrap;
    }

    .table tr {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        border: 1px solid #f0f0f0;
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
