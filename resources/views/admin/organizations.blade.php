@extends('admin.layoutadm.layoutadm')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header bg-light p-4 shadow-sm rounded">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #0077FF; font-weight: bold; font-size: 2rem;">Organization List</h1>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <!-- Search Bar -->
    <div class="mb-4">
        <input type="text" id="search-bar" class="form-control search-bar" placeholder="Search by organization name..." onkeyup="filterOrganizations()">
    </div>

    <div id="no-results" class="no-results" style="display: none;">
        <div class="alert alert-info text-center">
            <i class="fa-solid fa-info-circle" style="font-size: 2rem; color: #007bff;"></i>
            <h4>No Organizations Found</h4>
            <p>Try adjusting your search criteria or adding a new organization.</p>
        </div>
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
                                <p class="card-description">{{ truncateDescription($organization['description'], 10) }}</p>
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
    /* Enhanced styles for the search bar */
    .search-bar {
        border-radius: 25px;
        border: 1px solid #007bff;
        padding: 10px 20px;
        transition: border-color 0.3s;
    }

    .search-bar:focus {
        outline: none;
        border-color: #0056b3;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    /* Styles for the no results message */
    .no-results {
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .no-results .alert {
        border-radius: 15px;
        padding: 20px;
        background-color: #f8f9fa;
        color: #333;
    }

    .organization-card {
        background-color: #ffffff;
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
        height: 270px;
    }

    .organization-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    }

    .card-body {
        padding: 20px;
        height: calc(100% - 60px);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 0.5rem;
        width: 100%;
    }

    .card-description {
        font-size: 1rem;
        color: #666;
        margin-bottom: 1rem;
        line-height: 1.5;
    }

    .profile-picture {
        width: 70px;
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
        padding: 10px 20px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }
</style>

<script>
    function filterOrganizations() {
        const searchInput = document.getElementById('search-bar').value.toLowerCase();
        const organizationItems = document.querySelectorAll('.organization-item');
        let hasResults = false;

        organizationItems.forEach(item => {
            const title = item.querySelector('.card-title').textContent.toLowerCase();
            if (title.includes(searchInput)) {
                item.style.display = '';
                hasResults = true;
            } else {
                item.style.display = 'none';
            }
        });

        const noResultsMessage = document.getElementById('no-results');
        noResultsMessage.style.display = hasResults ? 'none' : 'block';
    }
</script>
@endsection