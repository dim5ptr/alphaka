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
                            <a href="{{ route('admin.organizations') }}" class="btn btn-outline-primary custom-outline-btn">View All Organizations</a>
                        </div>
                    </div>
                    <canvas id="orgChart" width="400" height="200"></canvas>
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
                    <canvas id="userChart" width="400" height="200"></canvas>
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
                            <a href="{{ route('showProducts') }}" class="btn btn-outline-primary custom-outline-btn">View All Products</a>
                        </div>
                    </div>
                    <canvas id="productChart" width="400" height="200"></canvas>
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
                    <canvas id="transactionChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Combined Chart -->
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="card text-center shadow-sm">
                        <div class="card-header" style="background-color: #0077FF; color: white;">
                            Combined Counts
                        </div>
                        <div class="card-body">
                            <canvas id="combinedChart" width="200" height="200"></canvas> <!-- Smaller chart -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Organization Chart
    const orgChart = document.getElementById('orgChart').getContext('2d');
    new Chart(orgChart, {
        type: 'line',
        data: {
            labels: ['Organization Count'],
            datasets: [{
                label: 'Organization Count',
                data: [{{ $organizationCount }}],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // User Chart
    const userChart = document.getElementById('userChart').getContext('2d');
    new Chart(userChart, {
        type: 'pie',
        data: {
            labels: ['User Count'],
            datasets: [{
                label: 'User Count',
                data: [{{ $userCount }}],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Product Chart
    const productChart = document.getElementById('productChart').getContext('2d');
    new Chart(productChart, {
        type: 'bar',
        data: {
            labels: ['Product Count'],
            datasets: [{
                label: 'Product Count',
                data: [{{ $productCount }}],
                backgroundColor: [
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Transaction Chart
    const transactionChart = document.getElementById('transactionChart').getContext('2d');
    new Chart(transactionChart, {
        type: 'doughnut',
        data: {
            labels: ['Transaction Count'],
            datasets: [{
                label: 'Transaction Count',
                data: [{{ $transactionCount }}],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Combined Chart
    const combinedChart = document.getElementById('combinedChart').getContext('2d');
    new Chart(combinedChart, {
        type: 'bar',
        data: {
            labels: ['Organization Count', 'User  Count', 'Product Count', 'Transaction Count'],
            datasets: [{
                label: 'Combined Counts',
                data: [{{ $organizationCount }}, {{ $userCount }}, {{ $productCount }}, {{ $transactionCount }}],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)', // Organization Count
                    'rgba(54, 162, 235, 0.6)', // User Count
                    'rgba(255, 206, 86, 0.6)', // Product Count
                    'rgba(75, 192, 192, 0.6)'  // Transaction Count
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)', // Organization Count
                    'rgba(54, 162, 235, 1)', // User Count
                    'rgba(255, 206, 86, 1)', // Product Count
                    'rgba(75, 192, 192, 1)'  // Transaction Count
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });
</script>

@endsection