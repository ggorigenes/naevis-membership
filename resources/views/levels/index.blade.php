@extends('layout')

@section('content')
<h2 class="mb-4">Membership Levels</h2>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-primary fw-bold">Add Level</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('levels.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Level Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g., Diamond" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fee (₱)</label>
                        <input type="number" step="0.01" name="fee" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Validity (Months)</label>
                        <input type="number" name="validity_period" class="form-control" value="12" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Benefits</label>
                        <textarea name="benefits" class="form-control" rows="2" placeholder="Describe perks and inclusions..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Save Level</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Level</th>
                            <th>Fee</th>
                            <th>Validity</th>
                            <th>Benefits</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($levels as $level)
                        <tr>
                            <td class="fw-semibold">{{ $level->name }}</td>
                            <td>₱{{ number_format($level->fee, 2) }}</td>
                            <td>{{ $level->validity_period }} mos</td>
                            <td class="small text-muted">{{ $level->benefits ?? '—' }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('levels.edit', $level->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('levels.destroy', $level->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this level?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection