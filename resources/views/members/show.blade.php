@extends('layout')

@section('content')
<div class="card max-w-lg mx-auto mb-5">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Member Profile: {{ $member->membership_number }}</h4>
        <span class="badge bg-{{ $member->status == 'Active' ? 'success' : 'danger' }} fs-6">{{ $member->status }}</span>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <h6 class="text-muted border-bottom pb-2">Personal Information</h6>
                <p class="mb-1"><strong>Name:</strong> {{ $member->first_name }} {{ $member->last_name }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $member->email }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $member->phone ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted border-bottom pb-2">Classification</h6>
                <p class="mb-1"><strong>Category:</strong> {{ $member->category->name }}</p>
                <p class="mb-1"><strong>Level:</strong> {{ $member->level->name }}</p>
            </div>
        </div>
        
        <div class="p-3 bg-light border rounded border-info mb-4">
            <h6 class="text-info fw-bold border-bottom pb-2 mb-3">Tier Details ({{ $member->level->name }})</h6>
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Standard Fee:</strong> ₱{{ number_format($member->level->fee, 2) }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Validity:</strong> {{ $member->level->validity_period }} Months</p>
                </div>
                <div class="col-md-12 mt-2">
                    <p class="mb-0"><strong>Benefits:</strong> {{ $member->level->benefits }}</p>
                </div>
            </div>
        </div>
        
        <hr>
        <a href="{{ route('members.edit', $member->id) }}" class="btn btn-warning">Edit Details</a>
        <a href="{{ route('members.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
</div>
<div class="card max-w-lg mx-auto mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Transaction History</h5>
        <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary">New Transaction</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Reference #</th>
                    <th>Type</th>
                    <th>Level</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th class="text-end">Receipt</th>
                </tr>
            </thead>
            <tbody>
                @forelse($member->transactions()->with('level')->latest()->get() as $txn)
                <tr>
                    <td class="font-monospace">{{ $txn->reference_number }}</td>
                    <td>{{ $txn->transaction_type }}</td>
                    <td>{{ $txn->level->name }}</td>
                    <td>₱{{ number_format($txn->amount, 2) }}</td>
                    <td><span class="badge bg-secondary">{{ $txn->status }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('transactions.receipt', $txn->id) }}" class="btn btn-sm btn-dark">Print</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-3 text-muted">No transactions recorded for this member.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection