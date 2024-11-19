@extends('layout.userlayout')
@section('content')

<!-- Main content -->
<section class="content py-5">
    <div class="container">
        <!-- Check if messages exist -->
        @if(session('messages') && is_array(session('messages')))
            @php
                $groupedMessages = collect(session('messages'))->groupBy(function($msg) {
                    return \Carbon\Carbon::parse($msg['created_at'])->format('Y-m-d');
                });
            @endphp

            @foreach($groupedMessages as $date => $messages)
            <div class="date-group mb-4">
                <h5 class="text-primary fw-bold">{{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</h5>
                <div class="row gy-3">
                    @foreach($messages as $message)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 rounded">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="card-title text-{{ $message['type'] == 'success' ? 'success' : 'danger' }}">
                                        {{ ucfirst($message['type']) }}
                                    </h6>
                                    <p class="card-text">{{ $message['message'] }}</p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="text-muted small">
                                        {{ \Carbon\Carbon::parse($message['created_at'])->format('H:i:s') }}
                                    </span>
                                    <span class="badge text-{{ $message['type'] == 'success' ? 'success' : 'danger' }}">
                                        {{ ucfirst($message['type']) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        @else
            <div class="text-center" style="padding-top: 10%;">
                <h5 class="text-secondary" style="margin-left: 5%;">Tidak ada pesan di inbox Anda.</h5>
            </div>
        @endif
    </div>
</section>

<!-- Custom CSS -->
<style>
    .content {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding: 10%;
        padding-top: 10%;
    }

    .card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Light shadow */
        margin-bottom: 15px;  /* Add spacing between cards */
    }

    .card-title {
        font-weight: bold;
        text-transform: capitalize;
    }

    .date-group h5 {
        padding-left: 10px;
        margin-bottom: 20px;
    }

    /* Ensure the card body is space-efficient */
    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .card-text {
        margin-bottom: 0;
    }

    /* Style for the badge */
    .badge {
        font-size: 0.8rem;
        padding: 5px 10px;
        border-radius: 12px;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .date-group h5 {
            font-size: 1rem;
        }

        .card {
            font-size: 0.9rem;
        }
    }
</style>

@endsection
