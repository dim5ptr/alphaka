@extends('admin.layoutadm.layoutadm')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="color: #3200af;">Edit Profile</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container" style="color: #3200af;">
            <div class="card-body">
                <form action="{{ route('edituseradm') }}" method="POST">
                    @csrf

                    <!-- User ID (hidden) -->
                    <input type="hidden" name="user_id" value="{{ session('user_id') }}">

                    <!-- Full Name -->
                    <div class="form-group">
                        <label for="fullname">Full Name</label>
                        <input type="text" name="fullname" id="fullname" class="form-control"
                               placeholder="Enter your Full Name" value="{{ session('fullname') }}" required>
                    </div>

                    <!-- Username -->
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control"
                               placeholder="Enter your Username" value="{{ session('username') }}" required>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                               placeholder="Enter your Email" value="{{ session('email') }}" required>
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" placeholder="Enter your Phone Number"
                               class="form-control" value="{{ session('phone') }}" required>
                    </div>

                    <!-- Date of Birth -->
                    <div class="form-group">
                        <label for="dateofbirth">Date of Birth</label>
                        <input type="date" name="dateofbirth" id="dateofbirth" class="form-control"
                               value="{{ session('dateofbirth') }}" required>
                    </div>

                    <!-- Gender -->
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="0" {{ session('gender') == 0 ? 'selected' : '' }}>Female</option>
                            <option value="1" {{ session('gender') == 1 ? 'selected' : '' }}>Male</option>
                        </select>
                    </div>

                    <!-- Role -->
                    <div class="form-group">
                        <label for="role_id">Role</label>
                        <select id="role_id" name="role_id" class="form-control" required>
                            <option value="1" {{ session('role_id') == 1 ? 'selected' : '' }}>PENGGUNA</option>
                            <option value="2" {{ session('role_id') == 2 ? 'selected' : '' }}>ADMIN</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>

            </div>
        </div>
    </section>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

<script>
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif
</script>
@endsection
