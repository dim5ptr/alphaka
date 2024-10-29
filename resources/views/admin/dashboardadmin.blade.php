@extends('admin.layoutadm.layoutadm')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #3200af;">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <!-- You can add additional header content here if needed -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div id="main-content" class="main-content">

        @if (session('success_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success">
                {{ session('success_message') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" id="alert-success"></button>
            </div>
        @endif

        <div class="container">
        @if(isset($organizations))
    <div class="display" id="organization-list">
        @foreach($organizations as $organization)
            <div class="card-body organization-card" data-type="{{ $organization['type'] ?? 'default_type' }}">
                <div class="card-id">
                    <div class="card-data">
                        <h3 class="card-title">{{ $organization['organization_name'] }}</h3>
                        <p class="card-description">{{ $organization['description'] }}</p>
                    </div>
                    <img id="profile_picture" src="{{ asset('img/user.png') }}" alt="Foto Profil" class="profile-picture">
                </div>
                <div class="card-footer text-center">
                    <div class="members-count">
                        <p><i class="fa-solid fa-user-group"></i>{{ $organization['member_count'] }}</p>
                    </div>
                    <a href="{{ route('showvieworganization', ['organization_name' => $organization['organization_name']]) }}" class="btn btn-secondary">Lihat</a>
                </div>
            </div>
        @endforeach
    </div>
@endif

        </div>
    </div>
</section>
<!-- /.content -->
@endsection
