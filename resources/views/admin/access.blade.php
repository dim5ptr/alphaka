@extends('admin.layoutadm.layoutadm')

@section('content')
<!-- Konten Header (Header Halaman) -->
<div class="content-header">
    <!-- resources/views/dashboard.blade.php, atau file blade lainnya -->
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Role Access</li>
                </ol>
                <h1 class="m-0" style="color: #3200af;">Role Access</h1>
                <p class="text-bold">Role: {{ $role }}</p> <!-- Deskripsi -->
            </div><!-- /.col -->
            <div class="col-sm-6">
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Konten Utama -->
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
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Menu</th>
                    <th scope="col">Izin Akses</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Edit Organization</td>
                    <td>
                        <input type="checkbox" class="styled-checkbox" name="permissions[main_admin]" value="1">
                    </td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Add Organization</td>
                    <td>
                        <input type="checkbox" class="styled-checkbox" name="permissions[jacob]" value="1">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
<!-- /.content -->
@endsection
