@extends('layout')

@section('content')
<div class="card mx-auto" style="max-width: 550px;">
    <div class="card-header"><h5 class="mb-0">Edit Level: {{ $level->name }}</h5></div>
    <div class="card-body">
        <form action="{{ route('levels.update', $level->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Level Name</label>
                <input type="text" name="name" class="form-control" value="{{ $level->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Fee (₱)</label>
                <input type="number" step="0.01" name="fee" class="form-control" value="{{ $level->fee }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Validity (Months)</label>
                <input type="number" name="validity_period" class="form-control" value="{{ $level->validity_period }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Benefits</label>
                <textarea name="benefits" class="form-control" rows="3">{{ $level->benefits }}</textarea>
            </div>
            <button type="submit" class="btn btn-warning">Update Level</button>
            <a href="{{ route('levels.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection