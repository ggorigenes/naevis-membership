@extends('layout')

@section('content')
<div class="card mx-auto" style="max-width: 650px;">
    <div class="card-header"><h4 class="mb-0">Record Payment</h4></div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('payments.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Pending Transaction</label>
                <select name="membership_transaction_id" id="txn_select" class="form-select" required>
                    <option value="">Select pending transaction...</option>
                    @foreach($pendingTransactions as $txn)
                        <option value="{{ $txn->id }}" data-amount="{{ $txn->amount }}"
                            {{ ($selectedTransactionId == $txn->id || old('membership_transaction_id') == $txn->id) ? 'selected' : '' }}>
                            {{ $txn->reference_number }} — {{ $txn->member->first_name }} {{ $txn->member->last_name }} ({{ $txn->level->name }}: ₱{{ number_format($txn->amount, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select" required>
                    <option value="Cash">Cash</option>
                    <option value="GCash">GCash</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Card">Credit / Debit Card</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label">Amount Paid (₱)</label>
                <input type="number" step="0.01" name="amount_paid" id="amount_paid" class="form-control" value="{{ old('amount_paid') }}" required>
            </div>
            <button type="submit" class="btn btn-success">Confirm Payment</button>
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
    function syncAmount() {
        const select = document.getElementById('txn_select');
        const amount = select.options[select.selectedIndex]?.getAttribute('data-amount');
        if (amount) document.getElementById('amount_paid').value = amount;
    }
    document.addEventListener('DOMContentLoaded', syncAmount);
    document.getElementById('txn_select').addEventListener('change', syncAmount);
</script>
@endsection