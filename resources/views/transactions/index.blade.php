@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Membership Transactions</h2>
    <div>
        <a href="{{ route('payments.create') }}" class="btn btn-outline-primary me-2">Record Payment</a>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary shadow-sm">+ New Application / Renewal</a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('transactions.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(['Pending', 'Paid', 'Approved', 'Expired', 'Cancelled'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Reference #</th>
                    <th>Member</th>
                    <th>Type & Level</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Validity</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $txn)
                <tr>
                    <td class="fw-bold font-monospace">{{ $txn->reference_number }}</td>
                    <td>
                        <a href="{{ route('members.show', $txn->member_id) }}" class="text-decoration-none fw-semibold">
                            {{ $txn->member->first_name }} {{ $txn->member->last_name }}
                        </a>
                        <div class="small text-muted">{{ $txn->member->membership_number }}</div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $txn->transaction_type }}</span>
                        <span class="ms-1 fw-semibold">{{ $txn->level->name }}</span>
                    </td>
                    <td class="fw-semibold">₱{{ number_format($txn->amount, 2) }}</td>
                    <td>
                        @php
                            $badge = match($txn->status) {
                                'Pending' => 'warning text-dark',
                                'Paid' => 'info text-dark',
                                'Approved' => 'success',
                                'Expired' => 'secondary',
                                'Cancelled' => 'danger',
                            };
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ $txn->status }}</span>
                    </td>
                    <td class="small text-muted">
                        @if($txn->start_date && $txn->end_date)
                            {{ $txn->start_date }} to {{ $txn->end_date }}
                        @else
                            <span>—</span>
                        @endif
                    </td>
                    <td class="text-end text-nowrap">
                        @if($txn->status === 'Pending')
                            <a href="{{ route('payments.create', ['transaction_id' => $txn->id]) }}" class="btn btn-sm btn-success">Pay</a>
                        @endif

                        @if($txn->status === 'Paid')
                            <form action="{{ route('transactions.updateStatus', $txn->id) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="Approved">
                                <button type="submit" class="btn btn-sm btn-primary">Approve</button>
                            </form>
                        @endif

                        @if(in_array($txn->status, ['Pending', 'Paid']))
                            <form action="{{ route('transactions.updateStatus', $txn->id) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="Cancelled">
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancel this transaction?')">Cancel</button>
                            </form>
                        @endif

                        <a href="{{ route('transactions.receipt', $txn->id) }}" class="btn btn-sm btn-outline-secondary">Receipt</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No transactions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $transactions->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
@endsection