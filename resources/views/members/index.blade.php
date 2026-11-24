@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Member Management</h2>
    <a href="{{ route('members.create') }}" class="btn btn-primary shadow-sm">+ Add New Member</a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('members.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search name, ID, or email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="level_id" class="form-select">
                    <option value="">All Levels</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
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
                    <th>ID Number</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Level</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                <tr>
                    <td class="font-monospace fw-semibold">{{ $member->membership_number }}</td>
                    <td>
                        <span class="fw-semibold">{{ $member->first_name }} {{ $member->last_name }}</span>
                        <div class="small text-muted">{{ $member->email }}</div>
                    </td>
                    <td>{{ $member->category->name }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $member->level->name }}</span></td>
                    <td>
                        <span class="badge bg-{{ $member->status == 'Active' ? 'success' : 'danger' }}">{{ $member->status }}</span>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('members.show', $member->id) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        <a href="{{ route('members.edit', $member->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('members.destroy', $member->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this member?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No members found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">
    {{ $members->links('pagination::bootstrap-5') }}
</div>
@endsection