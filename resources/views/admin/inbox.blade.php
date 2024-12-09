@extends('admin.layoutadm.layoutadm')

@section('content')

<div class="content-header bg-light p-4 shadow-sm rounded" style="margin-bottom: 2%;">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0" style="color: #0077FF; font-weight: bold; font-size: 2rem;">Inbox</h1>
            </div>
        </div>
    </div>
</div>
<!-- Main content -->
<section class="content">
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
                <h5 class="fw-bold">{{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</h5>
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
    .container {
        padding-top: 2%;
        padding-left: 10%;
        padding-bottom: 5%;
    }

    .date-group h5 {
        margin-top: 4%;
        margin-bottom: 2%;
          font-size: 1.2rem;
          color: #757576;
          font-weight: bold;
        }

    .table {
        border-spacing: 0 15px; /* Adds space between rows */
        width: 100%; /* Make sure the table takes the full width */
    }

    .table td {
        padding: 12px;
    }

    .message-row td {
    }

    .message-row  {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border: none;
    }

    /* Border-radius only on first and last columns */
    .first-column {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
        width: 5%; /* Set width for the last column */

    }

    .last-column{
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
        width: 5%; /* Set width for the last column */
    }



    /* Style for the badge */
    .badge {
        font-size: 0.9rem;
        padding: 5px 10px;
        border-radius: 12px;
    }


    /* Responsive styles */
    @media (max-width: 768px) {
        .date-group h5 {
            font-size: 0.8rem;
        }


    .content {
        min-height: 90vh;
        padding-top : 15%;
    }
        .table td {
        }
    }
</style>


@endsection
