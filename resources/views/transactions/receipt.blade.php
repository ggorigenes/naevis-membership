@extends('layout')

@section('content')
<style>
    @media print {
        .navbar, .no-print { display: none !important; }
        body { background: #fff !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
    }
</style>

<div class="card mx-auto" style="max-width: 700px;">
    <div class="card-body p-5">
        <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4">
            <div>
                <h3 class="fw-bold mb-0">Nævis</h3>
                <small class="text-muted">Official Membership Transaction Receipt</small>
            </div>
            <div class="text-end">
                <h5 class="font-monospace mb-0">{{ $transaction->reference_number }}</h5>
                <small class="text-muted">Date: {{ $transaction->created_at->format('M d, Y h:i A') }}</small>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <h6 class="text-muted text-uppercase small">Member Details</h6>
                <p class="mb-1 fw-bold">{{ $transaction->member->first_name }} {{ $transaction->member->last_name }}</p>
                <p class="mb-1 small">ID: {{ $transaction->member->membership_number }}</p>
                <p class="mb-0 small">Category: {{ $transaction->member->category->name }}</p>
            </div>
            <div class="col-6 text-end">
                <h6 class="text-muted text-uppercase small">Transaction Status</h6>
                <span class="badge bg-dark fs-6">{{ $transaction->status }}</span>
                @if($transaction->start_date)
                    <p class="small mt-2 mb-0">Valid: {{ $transaction->start_date }} to {{ $transaction->end_date }}</p>
                @endif
            </div>
        </div>

        <table class="table table-bordered mb-4">
            <thead class="table-light">
                <tr>
                    <th>Description</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $transaction->transaction_type }} — {{ $transaction->level->name }} Tier ({{ $transaction->level->validity_period }} mos)</td>
                    <td class="text-end">₱{{ number_format($transaction->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        @if($transaction->payments->count())
            <h6 class="text-muted text-uppercase small">Payment Details</h6>
            @foreach($transaction->payments as $payment)
                <p class="mb-1 small">
                    <strong>Ref:</strong> {{ $payment->payment_reference }} |
                    <strong>Method:</strong> {{ $payment->payment_method }} |
                    <strong>Paid:</strong> ₱{{ number_format($payment->amount_paid, 2) }}
                </p>
            @endforeach
        @else
            <p class="text-danger small">No payment recorded yet.</p>
        @endif

        <div class="mt-4 pt-3 border-top d-flex justify-content-between no-print">
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Back</a>
            <button onclick="window.print()" class="btn btn-primary">Print Receipt</button>
        </div>
    </div>
</div>
@endsection