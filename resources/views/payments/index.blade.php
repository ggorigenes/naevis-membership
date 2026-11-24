@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Recorded Payments</h2>
    <a href="{{ route('payments.create') }}" class="btn btn-primary shadow-sm">+ Record New Payment</a>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Payment Ref</th>
                    <th>Transaction Ref</th>
                    <th>Member</th>
                    <th>Method</th>
                    <th>Amount Paid</th>
                    <th>Date</th>
                    <th class="text-end">Receipt</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td class="fw-bold font-monospace">{{ $payment->payment_reference }}</td>
                    <td class="font-monospace text-muted">{{ $payment->transaction->reference_number }}</td>
                    <td>
                        <div class="fw-semibold">{{ $payment->transaction->member->first_name }} {{ $payment->transaction->member->last_name }}</div>
                        <div class="small text-muted">{{ $payment->transaction->member->membership_number }}</div>
                    </td>
                    <td><span class="badge bg-light text-dark border">{{ $payment->payment_method }}</span></td>
                    <td class="fw-semibold text-success">₱{{ number_format($payment->amount_paid, 2) }}</td>
                    <td class="small text-muted">{{ $payment->payment_date }}</td>
                    <td class="text-end">
                        <a href="{{ route('transactions.receipt', $payment->membership_transaction_id) }}" class="btn btn-sm btn-outline-primary">Print</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No payments recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $payments->links('pagination::bootstrap-5') }}</div>
@endsection