@extends('admin.layoutadm.layoutadm')

@section('content')
<!-- Navigation links -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">User List</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #3200af;">User List</h1>
            </div>
            <div class="col-sm-6">
            </div>
        </div>
    </div>
</section>

<!-- Tabel -->
<section class="content">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-4 offset-md-8">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                    <input type="search" id="searchInput" class="form-control" placeholder="Search...">
                </div>
            </div>
        </div>

        <!-- Tambahkan CSS khusus untuk garis tabel hitam -->
        <style>
            .table,
            .table th,
            .table td {
                border: 1px solid black !important;
            }
        </style>

        <table class="table mt-4">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">User Name</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>mark@example.com</td>
                    <td>@mdo</td>
                    <td>
                        <button type="button" class="btn btn-secondary btn-sm" title="Delete" onclick="confirmDelete(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                        <a href="{{ route('showmoredetailsadm') }}" class="btn btn-secondary btn-sm" title="More details"><i class="fa fa-info-circle"></i></a>
                    </td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
<!-- /.content -->
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var searchInput = document.getElementById('searchInput');

        // Tambahkan event listener untuk tindakan pencarian
        searchInput.addEventListener('keyup', function(event) {
            var searchText = event.target.value.toLowerCase();

            var rows = document.querySelectorAll('.table tbody tr');

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
