@extends('admin.layoutadm.layoutadm')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #0077FF; font-weight: bold;">Create Role</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('createrole') }}" method="POST" class="mt-4 shadow-sm p-4 rounded" style="background-color: #ffffff; border-radius: 15px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
            @csrf
            <div class="form-group mb-3">
                <label for="role" style="color: #0077FF; font-weight: bold;">Role Name</label>
                <input type="text" class="form-control shadow-sm" id="role" name="role" required style="border-radius: 10px; padding: 10px; border: 1px solid #ddd; font-size: 1rem;">
                @error('role')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100" style="background-color: #0077FF; color: white; font-weight: bold; border: none; padding: 12px; border-radius: 10px;">
                Create Role
            </button>
            <a href="{{ route('showuserrole') }}" class="btn btn-secondary w-100" style="background-color: #6c757d; color: white; font-weight: bold; border: none; padding: 12px; border-radius: 10px; margin-top: 10px;">
                Back
            </a>
        </form>
    </div>
</section>
@endsection

<style>
    /* Custom Styles */
    .alert-success, .alert-danger {
        border-radius: 10px;
        font-size: 0.9rem;
        text-align: center;
        margin-top: 10px;
    }

    /* Form Input Focus Effect */
    .form-control:focus {
        outline: none;
        border-color: #0077FF;
        box-shadow: 0 0 5px rgba(0, 119, 255, 0.2);
    }
</style>
