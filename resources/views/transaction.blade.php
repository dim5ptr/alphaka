@extends('layout.userlayout')

@section('content')
<div class="container">
    <h1>Your Transactions</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(count($transactions) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Transaction Number</th>
                    <th>Product Name</th>
                    <th>Total Amount</th>
                    <th>Created Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction['transaction_number'] }}</td>
                        <td>{{ $transaction['product_name'] }}</td>
                        <td>Rp.{{ number_format($transaction['total_amount'], 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaction['created_date'])->format('d M Y H:i') }}</td>
                        <td>{{ $transaction['is_done'] ? 'Completed' : 'In Process' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No transactions found.</p>
    @endif
</div>
@endsection
