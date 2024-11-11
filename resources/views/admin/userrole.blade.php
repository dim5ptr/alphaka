@extends('admin.layoutadm.layoutadm')

@section('content')
<!-- Page header -->
<div class="content-header bg-light p-4 shadow-sm rounded" style="margin-bottom: 2%;">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #0077FF; font-weight: bold; font-size: 2rem;">User Role</h1>
            </div>
        </div>
    </div>
</div>

<!-- Table and Search -->
<section class="content">
    <div class="container">
        <div class="row mb-4 align-items-center">
            <!-- Left-aligned button linking to user list page -->
            <div class="col-6 col-md-4">
                <a href="{{ route('showcreaterole') }}" class="btn btn-primary" style="background-color: #0077FF; color: white; font-weight: bold;">
                    Create New Role
                </a>
            </div>

            <!-- Search bar on the right side -->
            <div class="col-6 col-md-4 offset-md-4">
                <div class="input-group rounded shadow-sm">
                    <span class="input-group-text" style="background-color: #0077FF; color: white; border: none;">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="search" id="searchInput" class="form-control rounded" placeholder="Search..." style="border: none; padding: 10px;">
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
                overflow-x: auto;
                margin-bottom: 10%;
            }

            .table {
                width: 100%;
                border-collapse: collapse;
                background-color: white;
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                margin-top: 20px;
            }

            .table thead {
                background-color: #0077FF;
                color: #ffffff;
                text-align: center;
            }

            .table th, .table td {
                padding: 20px;
                font-size: 1.1rem;
                text-align: center;
                border-bottom: 1px solid #f0f0f0;
                white-space: nowrap;
            }

            /* Row styles */
            .table tbody tr {
                transition: background-color 0.2s;
            }

            .table tbody tr:hover {
                background-color: #eef5ff;
            }

            /* Align columns center */
            .table th, .table td {
                vertical-align: middle;
                font-weight: 500;
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

        <!-- Table Content -->
        <div class="table-container">
            <table class="table custom-table mt-4">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">User Role</th>
                        <th scope="col">Action</th>
                        {{-- <th scope="col">Menu Permission</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @if(isset($roles) && count($roles) > 0)
                        @foreach($roles as $index => $role)
                            <tr>
                                <th scope="row">{{ $index + 1 }}</th>
                                <td>{{ $role['role_name'] }}</td>
                                <td>
                                    <!-- Edit icon button -->
                                    <a href="{{ route('showupdaterole', ['idrole' => $role['id']]) }}" class="btn btn-primary btn-sm" title="Edit Role" style="background-color: #0077FF; color: white;">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                                {{-- <td>
                                    <a href="{{ route('showaccess', ['role' => $role['role_name']]) }}" class="btn btn-outline-primary btn-sm custom-outline-btn" title="View Permissions">
                                        <i class="fa fa-info-circle"></i>
                                    </a>
                                </td> --}}
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">No roles available.</td>
                        </tr>
                    @endif
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

        // Search filter logic
        searchInput.addEventListener('keyup', function(event) {
            var searchText = event.target.value.toLowerCase();
            var rows = document.querySelectorAll('.custom-table tbody tr');

            rows.forEach(function(row) {
                var userRole = row.cells[1].textContent.toLowerCase();
                row.style.display = userRole.includes(searchText) ? '' : 'none';
            });
        });
    });
</script>
@endsection
