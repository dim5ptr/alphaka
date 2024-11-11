@extends('admin.layoutadm.layoutadm')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #0077FF; font-weight: bold;">Organization List</h1>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <!-- Search Bar -->
    <div class="mb-4">
        <input type="text" id="search-bar" class="form-control" placeholder="Search by organization name..." onkeyup="filterOrganizations()">
    </div>

    <div class="row" id="organization-list">
        @foreach($organizations as $organization)
            <div class="col-md-4 mb-4 organization-item">
                <div class="card organization-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <img id="profile_picture" src="{{ asset('img/user.png') }}" alt="Profile Picture" class="profile-picture">
                            <div class="flex-grow-1 ms-3">
                                <h3 class="card-title">{{ $organization['organization_name'] }}</h3>
                                <p class="card-description">{{ $organization['description'] }}</p> <!-- Description below the title -->
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <div class="members-count mb-2">
                            <p><i class="fa-solid fa-user-group"></i> {{ $organization['member_count'] }} Members</p>
                        </div>
                        <a href="{{ route('admin.detailorganizations', ['id' => $organization['id'], 'organization_name' => $organization['organization_name']]) }}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    /* Enhanced styles for the organization cards */
    .organization-card {
        background-color: #ffffff;
        border: none;
        border-radius: 15px; /* Rounded corners */
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden; /* Prevent overflow of content */
        height: 270px; /* Fixed height for even card sizes */
    }

    .organization-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    }

    .card-body {
        padding: 20px; /* Increased padding for better spacing */
        height: calc(100% - 60px); /* Adjust height to fit within the card */
        display: flex;
        flex-direction: column; /* Ensure the body grows vertically */
        justify-content: space-between; /* Space out content */
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 0.5rem; /* Space below title */
        width: 100%;
    }

    .card-description {
        font-size: 1rem;
        color: #666;
        margin-bottom: 1rem; /* Space below description */
        line-height: 1.5; /* Improved readability */
    }

    .profile-picture {
        width: 70px; /* Slightly larger profile picture */
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #007bff;
        margin-right: 5%;
    }

    .members-count {
        font-size: 1rem;
        color: #007bff;
        font-weight: 500;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 10px 20px; /* Increased padding for buttons */
        border-radius: 5px; /* Rounded button corners */
        transition: background-color 0.3s, transform 0.3s;
        font-weight: bold; /* Bold text for buttons */
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-2px); /* Button lift effect */
    }

    /* Additional styles for better aesthetics */
    .card -footer {
        background-color: #f8f9fa; /* Light background for footer */
        border-top: 1px solid #e9ecef; /* Subtle border on top */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .organization-card {
            margin-bottom: 20px;
        }
    }

    /* Additional styles for the search bar */
    #search-bar {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ced4da;
        margin-bottom: 20px;
    }
</style>

<script>
    function filterOrganizations() {
        const input = document.getElementById('search-bar');
        const filter = input.value.toLowerCase();
        const organizationItems = document.querySelectorAll('.organization-item');

        organizationItems.forEach(item => {
            const title = item.querySelector('.card-title').textContent.toLowerCase();
            if (title.includes(filter)) {
                item.style.display = ''; // Show the item
            } else {
                item.style.display = 'none'; // Hide the item
            }
        });
    }
</script>
@endsection