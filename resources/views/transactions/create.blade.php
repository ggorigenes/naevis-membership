@extends('layout')

@section('content')
<div class="card mx-auto" style="max-width: 650px;">
    <div class="card-header"><h4 class="mb-0">New Membership Application / Renewal</h4></div>
    <div class="card-body">
        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Select Member</label>
                <select name="member_id" class="form-select" required>
                    <option value="">Choose member...</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}">
                            {{ $member->membership_number }} — {{ $member->first_name }} {{ $member->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Transaction Type</label>
                <select name="transaction_type" class="form-select" required>
                    <option value="Application">New Membership Application</option>
                    <option value="Renewal">Membership Renewal</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label">Membership Level</label>
                <select name="membership_level_id" class="form-select" required>
                    <option value="">Choose level...</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}">
                            {{ $level->name }} — ₱{{ number_format($level->fee, 2) }} ({{ $level->validity_period }} mos)
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create Transaction</button>
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection