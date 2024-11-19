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
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            @foreach($messages as $message)
                            <tr class="message-row">
                                <td class="text-center first-column badge text small"
                                    style="color: {{ $message['type'] == 'success' ? '#28a745' : '#dc3545' }}; border-radius: 12px 0 0 12px;">
                                    {{ ucfirst($message['type']) }}
                                </td>
                                <td class="message-cell badge text small" style="width: 80%;">
                                    {{ $message['message'] }}
                                </td>
                                <td class="last-column">
                                    <span class="badge text small" style="color: #757576;">
                                        {{ \Carbon\Carbon::parse($message['created_at'])->format('H:i:s') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
        min-height: 90vh;
        padding-left: 10%;
        padding-top:5%;
        padding-bottom: 5%;
    }

    .date-group h5 {
            font-size: 1rem;
            color: #545455;
            margin-top: 5%;
            margin-bottom: 1%;
        }

    .table {
        border-spacing: 0 15px; /* Adds space between rows */
        width: 90%; /* Make sure the table takes the full width */
        table-layout: relative; /* Ensures all columns have equal width */
    }

    .table td {
        padding: 12px;
        vertical-align: middle;
    }

    .message-row td {
        background-color: white;
    }

    .message-row  {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Light shadow */
    }

    /* Border-radius only on first and last columns */
    .first-column {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
        width: 10%; /* Set width for the last column */

    }

    .last-column{
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
        width: 10%; /* Set width for the last column */
    }



    /* Style for the badge */
    .badge {
        font-size: 0.9rem;
        padding: 5px 10px;
        border-radius: 12px;
    }

    /* Ensure the message cell has consistent height */
    .message-cell {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .date-group h5 {
            font-size: 0.8rem;
        }


    .content {
        background-color: #f8f9fa;
        min-height: 90vh;
        padding-top : 15%;
    }
        .table td {
        }
    }
</style>

@endsection
