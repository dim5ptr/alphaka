@extends('admin.layoutadm.layoutadm')

@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="icon" type="image/x-icon" href="img/logo_sti.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

    <style>
        /* General Styles */
        body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        /* Organization Info Styles */
        .organization-info {
            margin-bottom: 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .organization-info h1 {
            margin: 0;
            font-size: 2rem;
            color: #365AC2;
        }

        .organization-info small {
            font-size: 1rem;
            color: #555;
        }

        /* Table Container */
        .table-container {
            margin-top: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto; /* Enable horizontal scrolling */
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px; /* Set a minimum width for the table */
        }

        .table thead {
            background-color: #365AC2;
            color: #ffffff;
        }

        .table th, .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* No Members Message Styles */
        .no-members {
            text-align: center;
            padding: 20px;
            color: #888;
            font-size: 1.2rem;
        }

        /* No Search Results Message Styles */
        .no-results {
            text-align: center;
            padding: 10px;
            color: #888; /* gray color for emphasis */
            font-size: 1rem;
            display: none; /* Initially hidden */
        }

        /* Button Styles */
        .btn-primary {
            padding: 10px 15px;
            background-color: #365AC2;
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-top: 2%;
        }

        .btn-primary:hover {
            background-color: #2e4a8c;
            transform: scale(1.05);
        }

        /* Back Button Styles */
        .btn-back {
            padding: 10px 15px;
            background-color: #e0e0e0;
            color: #333;
            border: none;
            border-radius: 20px;
            font-size: 0.9rem;
            cursor: pointer;
            margin-bottom: 20px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-back:hover {
            background-color: #d0d0d0;
            transform: scale(1.05);
        }

        /* Search Bar Styles */
        .search-bar {
            margin-bottom: 20px;
            padding: 12px 20px;
            border-radius: 25px;
            border: 1px solid #ccc;
            width: 100%;
            max-width: 400px; /* Optional: set a max width for the search bar */
            background-color: #f9f9f9; /* Light background color */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            transition: border-color 0.3s ease; /* Smooth transition for focus effect */
        }

        .search-bar:focus {
            border-color: #365AC2; /* Change border color on focus */
            outline: none; /* Remove default outline */
        }
    </style>
@endsection

@section('content')
    <button class="btn-back" onclick="window.history.back()">Back</button>

    <div class="organization-info">
        <h1>{{ $organization['organization_name'] }}</h1>
        <small>{{ $organization['description'] }}</small>
    </div>

    <input type="text" id="member-search" class="search-bar" placeholder="Search members by name, ID, or email..." onkeyup="filterMembers()">
    <div id="no-results" class="no-results">No members found matching your search.</div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="member-list">
                    @if(count($members) === 0)
                        <tr>
                            <td colspan="5" class="no-members">No members found.</td>
                        </tr>
                    @else
                        @foreach($members as $index => $member)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $member['user_id'] ?? 'N/A' }}</td>
                                <td>{{ $member['full_name'] ?? 'N/A' }}</td>
                                <td>{{ $member['email'] ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('showmoredetailsadm', ['email' => $member['email']]) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function filterMembers() {
            const searchInput = document.getElementById('member-search').value.toLowerCase();
            const memberRows = document.querySelectorAll('#member-list tr');
            let hasResults = false;

            memberRows.forEach(row => {
                const userId = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const fullName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

                if (userId.includes(searchInput) || fullName.includes(searchInput) || email.includes(searchInput)) {
                    row.style.display = '';
                    hasResults = true;
                } else {
                    row.style.display = 'none';
                }
            });

            const noResultsMessage = document.getElementById('no-results');
            noResultsMessage.style.display = hasResults ? 'none' : 'block';
        }
    </script>
@endsection