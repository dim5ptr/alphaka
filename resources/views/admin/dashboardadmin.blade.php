@extends('admin.layoutadm.layoutadm')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #0077FF; font-weight: bold;">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <!-- You can add additional header content here if needed -->
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div id="main-content" class="main-content">

        @if (session('success_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success">
                {{ session('success_message') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" id="alert-success"></button>
            </div>
        @endif

        <div class="container">
            <div class="row">
                <!-- Organization Count Card -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center shadow-sm">
                        <div class="card-header" style="background-color: #0077FF; color: white;">
                            Organization Count
                        </div>
                        <div class="card-body">
                            <h3>{{ $organizationCount }}</h3>
                        </div>
                        <div class="card-footer">
                            <a href="" class="btn btn-outline-primary custom-outline-btn">View All Organizations</a>
                        </div>
                    </div>
                </div>

                <!-- User Count Card -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center shadow-sm">
                        <div class="card-header" style="background-color: #0077FF; color: white;">
                            User Count
                        </div>
                        <div class="card-body">
                            <h3>{{ $userCount }}</h3>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('showuserdata') }}" class="btn btn-outline-primary custom-outline-btn">View All Users</a>
                        </div>
                    </div>
                </div>

                <!-- Product Count Card -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center shadow-sm">
                        <div class="card-header" style="background-color: #0077FF; color: white;">
                            Product Count
                        </div>
                        <div class="card-body">
                            <h3>{{ $productCount }}</h3>
                        </div>
                        <div class="card-footer">
                            <a href="" class="btn btn-outline-primary custom-outline-btn">View All Products</a>
                        </div>
                    </div>
                </div>

                <!-- Transaction Count Card -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center shadow-sm">
                        <div class="card-header" style="background-color: #0077FF; color: white;">
                            Transaction Count
                        </div>
                        <div class="card-body">
                            <h3>{{ $transactionCount }}</h3>
                        </div>
                        <div class="card-footer">
                            <a href="{{route ('showtransaction')}}" class="btn btn-outline-primary custom-outline-btn">View All Transactions</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
