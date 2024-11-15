@extends('layout.userlayout')
@section('content')

<!-- Main content -->
<!-- Table and Search -->
<section class="content">
    <div class="container">
<!-- Inbox table -->
<div class="table-container">
    <table class="table custom-table mt-0">
        <tbody>
            @if(is_array(session('messages')) || is_object(session('messages')))
            @foreach(session('messages') as $message)
                <tr>
                    <td style="color: {{ $message['type'] == 'success' ? '#28a745' : '#dc3545' }};">
                        {{ ucfirst($message['type']) }}
                    </td>
                    <td>{{ $message['message'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($message['created_at'])->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($message['created_at'])->format('H:i:s') }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" class="text-center">Tidak ada pesan di inbox Anda.</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>


<!-- Custom CSS for the inbox table -->
<style>
    .table-container {
        padding: 10%;
        width: 80%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 1%;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        overflow: hidden;
        border-collapse: separate;
        border-spacing: 0 10px; /* Menambahkan spasi vertikal 10px */
    }

    .table td {
        padding: 15px;
        text-align: left;
        background-color: white;
        white-space: nowrap;
    }

    .table tr {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        border: 1px solid #f0f0f0;
    }

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
</style>




@endsection

@section('script')
<script>
    // Function to close all modals
function closeAllModals() {
    document.querySelectorAll('.custom-modal').forEach(modal => {
        modal.style.display = "none";
    });
}

// Show Change Password modal when page loads
document.addEventListener('DOMContentLoaded', function() {
    closeAllModals(); // Close any open modals
    const passwordModal = document.getElementById('password-modal');
    if (passwordModal) {
        passwordModal.style.display = 'block'; // Open Change Password modal
    }
});

// Handle menu clicks to show corresponding modals
document.querySelectorAll('.nav-mini').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('data-target');
        const modalId = targetId + '-modal';

        closeAllModals(); // Close all modals before opening a new one
        const targetModal = document.getElementById(modalId);
        if (targetModal) {
            targetModal.style.display = "block";
        }
    });
});

// Toggle visibility for New Password
document.getElementById('toggle-new-password').addEventListener('click', function () {
    const passwordField = document.getElementById('new-password');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        this.innerHTML = '<i class="fa fa-eye-slash"></i>';
    } else {
        passwordField.type = 'password';
        this.innerHTML = '<i class="fa fa-eye"></i>';
    }
});

// Toggle visibility for Confirm New Password
document.getElementById('toggle-confirm-password').addEventListener('click', function () {
    const confirmPasswordField = document.getElementById('confirm-password');
    if (confirmPasswordField.type === 'password') {
        confirmPasswordField.type = 'text';
        this.innerHTML = '<i class="fa fa-eye-slash"></i>';
    } else {
        confirmPasswordField.type = 'password';
        this.innerHTML = '<i class="fa fa-eye"></i>';
    }
});


document.addEventListener('DOMContentLoaded', function() {
    var newPasswordInput = document.getElementById('new-password');
    var confirmPasswordInput = document.getElementById('confirm-password');
    var newPasswordError = document.getElementById('new-password-error');
    var confirmPasswordError = document.getElementById('confirm-password-error');

    // Real-time validation function
    function validatePasswords() {
        var newPasswordValue = newPasswordInput.value;
        var confirmPasswordValue = confirmPasswordInput.value;

        // Clear previous error messages
        newPasswordError.textContent = '';
        confirmPasswordError.textContent = '';

        // Password length check
        if (newPasswordValue.length < 8) {
            newPasswordError.textContent = 'Password must be at least 8 characters.';
        }

        // Match check
        if (newPasswordValue !== confirmPasswordValue) {
            confirmPasswordError.textContent = 'Passwords do not match.';
        }
    }

    newPasswordInput.addEventListener('input', validatePasswords);
    confirmPasswordInput.addEventListener('input', validatePasswords);
});

</script>


<script>
function toggleSidebar() {
var sidebar = document.getElementById("sidebar");
var mainContent = document.getElementById("main-content");

if (sidebar.style.left === "0px") {
    sidebar.style.left = "-270px";
    mainContent.style.marginLeft = "10%";
} else {
    sidebar.style.left = "0px";
    mainContent.style.marginLeft = "19%";
}
}
</script>
@endsection
