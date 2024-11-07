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

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height:  100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-content {
            background-color: #ffffff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Notification Styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            transition: opacity 0.5s ease;
        }
    </style>
@endsection

@section('content')
    <button class="btn-back" onclick="window.history.back()">Back</button>

    <div class="organization-info">
        <h1>{{ $organization['organization_name'] }}</h1>
        <small>{{ $organization['description'] }}</small>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Actions</th> <!-- New Action Column -->
                    </tr>
                </thead>
                <tbody>
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
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <button class="btn-primary" onclick="openModal()">Add Member</button>
    </footer>

    <div class="modal" id="addMemberModal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Add New Member</h2>
            <form action="#" method="POST">
                @csrf
                <input type="text" name="member_name" placeholder="Member Name" required>
                <input type="email" name="member_email" placeholder="Email" required>
                <button type="submit" class="btn-primary">Add Member</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('addMemberModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('addMemberModal').style.display = 'none';
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('addMemberModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
@endsection