@extends('layout.layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3" style="font-weight: bold;">Confirm</h4>
                    @if(session()->has('username'))
                        <p class="card-text">You are already logged in as <strong>{{ session('username') }}</strong>, you need to log out before logging in as different user.</p>
                    @else
                        <p class="card-text">You are already logged in, you need to log out before logging in as different user.</p>
                    @endif
                    <form method="GET" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn text-light" style="background-color: #7773d4;">Log out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
