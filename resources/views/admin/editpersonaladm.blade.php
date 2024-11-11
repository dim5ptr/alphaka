@extends('admin.layoutadm.settingsadm')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header bg-light p-4 shadow-sm rounded" style="margin-bottom: 2%;">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #0077FF; font-weight: bold; font-size: 2rem;">Edit Profile</h1>
            </div>
        </div>
    </div>
</div>

    <!-- Main content -->
    <section class="content">
        <div class="container" style="color: #3200af;">
            <div class="card-body">
                <form action="{{ route('editpersonaladm') }}" method="POST">
                    @csrf
                    <!-- Personal information fields -->
                    @csrf
                    <!-- Personal information fields -->
                    <div class="form-group" class="form-label">
                        <label for="name">Full Name</label>
                        <input type="text" name="fullname" id="name" class="form-control" placeholder="Enter your Full Name" value="{{ session('full_name') }}">
                    </div>
                    <div class="form-group" class="form-label">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Enter your Username" value="{{ session('username') }}">
                    </div>
                    <div class="form-group" class="form-label">
                        <label for="dateofbirth">Date of Birth</label>
                        <input type="date" name="dateofbirth" id="dateofbirth" class="form-control" value="{{ session('dateofbirth') }}">
                    </div>
                    <div class="form-group">
                        <label for="gender" class="form-label">Gender</label>
                        <select id="gender" name="gender" class="form-control">
                            <option value="0" {{ session('gender') == 0 ? 'selected' : '' }}>Female</option>
                            <option value="1" {{ session('gender') == 1 ? 'selected' : '' }}>Male</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" id="phone" placeholder="Enter your Phone Number" class="form-control" value="{{ session('phone') }}">
                    </div>
                    <button type="submit" class="btn rounded text-light" style="background-color: #7773d4;">Save</button>
                </form>
            </div>
        </div>
    </section>
    <!-- /.content -->
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

    function enableEdit() {
        document.getElementById('name').removeAttribute('disabled');
        document.getElementById('username').removeAttribute('disabled');
        document.getElementById('dateofbirth').removeAttribute('disabled');
        document.getElementById('gender').removeAttribute('disabled');
        document.getElementById('phone').removeAttribute('disabled');
    }
</script>
@endsection
