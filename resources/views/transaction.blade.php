@extends('layout.userlayout')

@section('content')
<style>
    html {
        background-color: #f8f9fa;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa; /* Light background color */
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 5%;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
    }

    .sort-container {
        margin-bottom: 20px;
        text-align: left;
        display: flex;
        align-items: center;
    }

    #search {
        width: 70%
    }

    .sort-container select,
    .sort-container input {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 16px;
        margin-left: 10px;
    }

    .transaction-card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .transaction-card h2 {
        font-size: 1.5em;
        margin: 0 0 10px;
    }

    .transaction-details {
        margin-bottom: 10px;
    }

    .transaction-status {
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 5px;
        color: #fff;
    }

    .completed {
        background-color: #28a745; /* Green */
    }

    .in-process {
        background-color: #365AC2; /* Blue */
    }

    .alert {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .info {
        background-color: #d1ecf1;
        color: #0c5460;
    }
</style>

<div class="container">
    <pre> </pre>

    @if(session('error'))
        <div class="alert">{{ session('error') }}</div>
    @endif

    <div class="sort-container">
        <label for="sort">Sort by:</label>
        <select id="sort" onchange="sortTransactions()">
            <option value="none">None</option>
            <option value="date">Date Created</option>
            <option value="status">Status</option>
        </select>

        <input type="text" id="search" placeholder="Search by Transaction Number or Product Name" onkeyup="searchTransactions()">
    </div>

    @if(count($transactions) > 0)
        <div id="transaction-list">
            @foreach($transactions as $transaction)
                <div class="transaction-card"
                     data-status="{{ $transaction['is_done'] ? 'completed' : 'in-process' }}"
                     data-date="{{ \Carbon\Carbon::parse($transaction['created_date'])->timestamp }}"
                     data-product="{{ strtolower($transaction['product_name']) }}"
                     data-transaction-number="{{ strtolower($transaction['transaction_number']) }}">
                    <h2>Transaction Number: {{ $transaction['transaction_number'] }}</h2>
                    <div class="transaction-details">
                        <strong>Product Name:</strong> {{ $transaction['product_name'] }}<br>
                        <strong>Total Amount:</strong> Rp.{{ number_format($transaction['total_amount'], 0, ',', '.') }}<br>
                        <strong>Created Date:</strong> {{ \Carbon\Carbon::parse($transaction['created_date'])->format('d M Y H:i') }}
                    </div>
                    <div class="transaction-status {{ $transaction['is_done'] ? 'completed' : 'in-process' }}">
                        {{ $transaction['is_done'] ? 'Completed' : 'In Process' }}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert info">No transactions found.</div>
    @endif
</div>

<script>
    let originalOrder = [];

    // Store the original order of transactions
    document.addEventListener('DOMContentLoaded', () => {
        const transactionList = document.getElementById('transaction-list');
        originalOrder = Array.from(transactionList.children);
    });

 function sortTransactions() {
        const sortValue = document.getElementById('sort').value;
        const transactionList = document.getElementById('transaction-list');
        const transactions = Array.from(transactionList.children);

        if (sortValue === 'status') {
            transactions.sort((a, b) => {
                const statusA = a.getAttribute('data-status');
                const statusB = b.getAttribute('data-status');
                return (statusA === 'in-process' ? 1 : 0) - (statusB === 'in-process' ? 1 : 0);
            });
        } else if (sortValue === 'date') {
            transactions.sort((a, b) => {
                const dateA = parseInt(a.getAttribute('data-date'));
                const dateB = parseInt(b.getAttribute('data-date'));
                return dateB - dateA; // Sort by date descending
            });
        } else if (sortValue === 'none') {
            transactions.sort((a, b) => {
                return originalOrder.indexOf(a) - originalOrder.indexOf(b); // Return to original order
            });
        }

        // Clear the current list and append the sorted transactions
        transactionList.innerHTML = '';
        transactions.forEach(transaction => transactionList.appendChild(transaction));
    }

    function searchTransactions() {
        const searchValue = document.getElementById('search').value.toLowerCase();
        const transactionList = document.getElementById('transaction-list');
        const transactions = Array.from(transactionList.children);

        transactions.forEach(transaction => {
            const productName = transaction.getAttribute('data-product');
            const transactionNumber = transaction.getAttribute('data-transaction-number');
            if (productName.includes(searchValue) || transactionNumber.includes(searchValue)) {
                transaction.style.display = '';
            } else {
                transaction.style.display = 'none';
            }
        });
    }
</script>
@endsection
