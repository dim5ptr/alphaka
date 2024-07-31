@extends('layout.layout')

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection

@section('breadcrumbs')
<!-- Tambahkan breadcrumbs ke bagian atas -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb" style="background-color: transparent;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('organization') }}">Organization</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $organization['organization_name'] }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    @yield('breadcrumbs') <!-- Tambahkan breadcrumbs ke tampilan -->

    <h1 class="m-0" style="color: #3200af;">{{ $organization['organization_name'] }}</h1>
    <div class="row mt-3 pb-3 border-bottom">
        <div class="col-md-8" style="color: #3200af;">
            <small>{{ $organization['description'] }}</small>
        </div>
        <div class="col-md-4 text-center">
            <a href="{{ route('showeditorganization', ['organization_name' => $organization['organization_name']]) }}"
                class="btn btn-md px-5 rounded-pill text-light btn-pengurus" style="background-color: #7773d4;">
                Edit Organization
            </a>
        </div>
    </div>

    @include('listmember') <!-- Menampilkan daftar anggota organisasi -->
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif
</script>
@endsection
