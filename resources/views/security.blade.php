@extends('layout.settings')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-0">
                    <a href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-house fa-xl" style="color: #7773d4;"></i>
                    </a>
                </div>
                <div class="col-12">
                    <h1 class="m-0 text-center"style="color: #3200af;">Security</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
    <div class="container">
        <div class="row justify-content-center p-0">
            <div class="col-md-8 p-5 shadow-sm rounded-5" style="background-color: #ffff;">
                <h3 style="color: #3200af;" >Password</h3>
                <div class="card shadow-none border-0">
                    <div class="card-body">
                        <a class="card-text" href="{{ route('showeditpassword') }}">
                            <button type="submit" class="btn btn-md px-5 rounded-pill text-light" style="background-color: #7773d4;">
                            <i class="fa-solid fa-lock fa-md me-2"></i>
                                Change Password
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
