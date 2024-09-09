@extends('layout.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color: transparent;">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #3200af;">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('organization') }}" style="color: #3200af;">Organization</a></li>
                <li class="breadcrumb-item"><a href="{{ route('showvieworganization', ['organization_name' => $organization['organization_name']]) }}" style="color: #3200af;">{{ $organization['organization_name'] }}</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #3200af;">Edit Organization</li>
            </ol>
        </nav>
        <div class="container-fluid">
            <div class="row">
                <div class="col-10 offset-1"> <!-- Adjusted column class -->
                    <!-- Breadcrumbs for navigation history -->
                  
                    
                    <!-- Title for the page -->
                    <h1 class="m-0 text-center" style="color: #3200af;">Edit Organization</h1> <!-- Centered text -->
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
                            <!-- Form for editing organization details -->
                            <form action="{{ route('editorganization', ['organization_name' => $organization['organization_name']]) }}" method="POST">
                                @csrf
                                <div class="mb-3" style="color: #3200af;">
                                    <label for="organization_name" class="form-label">Organization Name</label>
                                    <input type="text" name="organization_name" class="form-control" value="{{ $organization['organization_name'] }}">
                                </div>
                                <div class="mb-3" style="color: #3200af;">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" name="description" class="form-control" value="{{ $organization['description'] }}">
                                </div>
                                <button type="submit" class="btn btn-primary btn-block" style="background-color: #7773d4;"> <!-- Adjusted button class -->
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


@endsection
