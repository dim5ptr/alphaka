@extends('admin.layoutadm.layoutadm')

@section('content')
<!-- Navigation links -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">User Role</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Content Header (Page header) -->
<section class="content">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #3200af;">User Role</h1>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container">
        <!-- Tambahkan CSS khusus untuk garis tabel hitam -->
        <style>
            .table,
            .table th,
            .table td {
                border: 1px solid black !important;
            }
        </style>

        <!-- Input pencarian dengan ikon -->
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

        <!-- Tabel -->
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">User Role</th>
                    <th scope="col">Menu Permission</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Main Admin</td>
                    <td>
                        <a href="{{ route('showaccess', ['role' => 'Main Admin']) }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-info-circle"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Sector Admin</td>
                    <td>
                        <a href="{{ route('showaccess', ['role' => 'Sector Admin'])}}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-info-circle"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td>Member</td>
                    <td>
                        <a href="{{ route('showaccess', ['role' => 'Member'])}}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-info-circle"></i>
                        </a>
                    </td>
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
            // Ambil nilai input pencarian
            var searchText = event.target.value.toLowerCase();

            // Ambil semua baris dalam tabel
            var rows = document.querySelectorAll('.table tbody tr');

            // Loop melalui setiap baris
            rows.forEach(function(row) {
                // Ambil teks dari kolom kedua (User Role)
                var userRole = row.cells[1].textContent.toLowerCase();

                // Periksa apakah teks pencarian cocok dengan teks di kolom User Role
                if (userRole.includes(searchText)) {
                    // Jika cocok, tampilkan baris
                    row.style.display = '';
                } else {
                    // Jika tidak cocok, sembunyikan baris
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
