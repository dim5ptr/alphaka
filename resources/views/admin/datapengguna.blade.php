@extends('admin.layoutadm.layoutadm')

@section('content')

<!-- Main content -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #0077FF; font-weight: bold;">User List</h1>
            </div>
            <div class="col-sm-6">
                <!-- You can add additional header content here if needed -->
            </div>
        </div>
    </div>
</div>

<!-- Table and Search -->
<section class="content">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-4 offset-md-8">
                <div class="input-group rounded shadow-sm">
                    <span class="input-group-text" style="background-color: #0077FF; color: white; border: none;">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="search" id="searchInput" class="form-control rounded" placeholder="Search..." style="border: none; solid #0077FF; padding: 10px;">
                </div>
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
        </style>

        <div class="table-container">
            <table class="table custom-table mt-4">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">User Name</th>
                        <th scope="col">Role</th>
                        <th scope="col">Created Date</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ $user['full_name'] ?? 'N/A' }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>{{ $user['username'] ?? 'N/A' }}</td>
                            <td>
                                {{ $user['id_role'] == 1 ? 'PENGGUNA' : ($user['id_role'] == 2 ? 'ADMIN' : 'PENGGUNA') }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($user['created_date'])->format('d-m-Y H:i') }}</td>
                            <td class="action-buttons">
                                <a href="{{ route('showedituseradm') }}" class="btn btn-outline-primary btn-sm custom-outline-btn" title="More details">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="{{ route('showmoredetailsadm') }}" class="btn btn-outline-primary btn-sm custom-outline-btn" title="More details">
                                    <i class="fa fa-info-circle"></i>
                                </a>
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
                var name = row.cells[1].textContent.toLowerCase();
                var email = row.cells[2].textContent.toLowerCase();
                var username = row.cells[3].textContent.toLowerCase();

                if (name.includes(searchText) || email.includes(searchText) || username.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
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
