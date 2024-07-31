@extends('layout.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <!-- Breadcrumbs for navigation history -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: transparent;">
                    <li class="breadcrumb-item">
                        <a href="{{ route('organization') }}" style="color: #7773d4;">Organization</a> <!-- Kembali ke Organization Overview -->
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #3200af;">Create Organization</li> <!-- Halaman saat ini -->
                </ol>
            </nav>

            <div class="row">
                <div class="col-10 offset-1"> <!-- Adjusted column class -->
                    <h1 class="m-0 text-center" style="color: #3200af;">Create Organization</h1> <!-- Centered text -->
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row justify-content-center"> <!-- Centered form -->
                <div class="col-md-8 p-5 shadow-sm" style="background-color: #bac1ef;">
                    <div class="card shadow-none" style="background-color: #bac1ef;">
                        <div class="card-body">
                            <!-- Form for creating organization -->
                            <form action="{{ route('addorganization') }}" method="POST">
                                @csrf
                                <div class="mb-3" style="color: #3200af;">
                                    <label for="organization_name" class="form-label">Organization Name</label>
                                    <input type="text" name="organization_name" class="form-control" placeholder="Enter Organization Name">
                                </div>
                                <div class="mb-3" style="color: #3200af;">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" name="description" class="form-control" placeholder="Enter Organization Description">
                                </div>
                                <button type="submit" class="btn btn-primary btn-block" style="background-color: #7773d4;">
                                    Save
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
