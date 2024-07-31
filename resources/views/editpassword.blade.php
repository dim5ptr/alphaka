@extends('layout.settings')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <!-- Breadcrumbs for navigation history -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: transparent;">
                    <li class="breadcrumb-item">
                        <a href="{{ route('showsecurity') }}" style="color: #7773d4;">Security</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #3200af;">Edit Password</li>
                </ol>
            </nav>

            <!-- Title for the page -->
            <div class="row">
                <div class="col-10 text-center">
                    <h1 class="m-0" style="color: #3200af;">Security</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row justify-content-center" style="color: #3200af;">
                <div class="col-md-8 p-5 shadow-sm" style="background-color: #bac1ef;">
                    <h3>Change Password</h3>
                    <div class="card shadow-none border-0" style="background-color: #bac1ef;">
                        <div class="card-body">
                            <!-- Form for changing password -->
                            <form action="{{ route('editpassword') }}" method="POST">
                                @csrf
                               
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_new_password" class="form-label">Confirm New Password</label>
                                    <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-md px-5 rounded-pill text-light" style="background-color: #2f58cc;">
                                    Save Password
                                </button>
                            </form>
                            <!-- End of form -->
                        </div>
                    </div>
                </div>
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
</script>
@endsection
