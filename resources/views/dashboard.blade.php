@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Dashboard</h2>
    <div>
        <a href="{{ route('members.create') }}" class="btn btn-primary me-2">Add Member</a>
        <a href="{{ route('transactions.create') }}" class="btn btn-outline-primary">New Transaction</a>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card p-3">
            <div class="text-muted small text-uppercase fw-bold">Total Members</div>
            <h2 class="fw-bold mb-0 mt-1">{{ $totalMembers }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <div class="text-muted small text-uppercase fw-bold">Active Members</div>
            <h2 class="fw-bold text-success mb-0 mt-1">{{ $activeMembers }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <div class="text-muted small text-uppercase fw-bold">Pending Transactions</div>
            <h2 class="fw-bold text-warning mb-0 mt-1">{{ $pendingTransactions }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <div class="text-muted small text-uppercase fw-bold">Collected Payments</div>
            <h2 class="fw-bold text-primary mb-0 mt-1">₱{{ number_format($collectedPayments, 2) }}</h2>
        </div>
    </div>
</div>

<!-- Recent Transactions Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Transactions</h5>
        <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-secondary">View All</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Reference #</th>
                    <th>Member</th>
                    <th>Type</th>
                    <th>Level</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions as $txn)
                <tr>
                    <td class="font-monospace fw-bold">{{ $txn->reference_number }}</td>
                    <td>
                        @if ($txn->member)
                        {{ $txn->member->first_name }} {{ $txn->member->last_name }}
                        @if ($txn->member->trashed())
                        <span class="badge bg-secondary ms-1 small" style="font-size: 0.7rem;">Deleted</span>
                        @endif
                        @else
                        <span class="text-muted fst-italic">Unknown Member</span>
                        @endif
                    </td>
                    <td>{{ $txn->transaction_type }}</td>
                    <td>{{ $txn->level->name }}</td>
                    <td>₱{{ number_format($txn->amount, 2) }}</td>
                    <td><span class="badge bg-secondary">{{ $txn->status }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No recent transactions yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection