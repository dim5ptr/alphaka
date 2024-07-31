@extends('admin.layoutadm.settingsadm')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <!-- Title for the page -->
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="color: #3200af;">Edit Profile</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container" style="color: #3200af;">
            <div class="card-body">
                <form action="{{ route('editpersonaladm') }}" method="POST">
                    @csrf
                    <!-- Personal information fields -->
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" name="fullname" id="name" class="form-control" placeholder="Enter your Full Name" >
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Enter your Username" >
                    </div>
                    <div class="form-group">
                        <label for="dateofbirth">Date of Birth</label>
                        <input type="date" name="dateofbirth" id="dateofbirth" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" class="form-control">
                            <option value="1" {{ session('gender') == 1 ? 'selected' : '' }}>Male</option>
                            <option value="0" {{ session('gender') == 0 ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" placeholder="Enter your Phone Number" class="form-control">
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
