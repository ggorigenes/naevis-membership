@extends('layout')

@section('content')
<div class="card max-w-lg mx-auto mb-5">
    <div class="card-header"><h4>Edit Member: {{ $member->membership_number }}</h4></div>
    <div class="card-body">
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('members.update', $member->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" value="{{ $member->first_name }}" required>
                </div>
                <div class="col-md-6">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="{{ $member->last_name }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $member->email }}" required>
                </div>
                <div class="col-md-6">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ $member->phone }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Category</label>
                    <select name="membership_category_id" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $member->membership_category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Level</label>
                    <select name="membership_level_id" id="level_select" class="form-select" required>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" 
                                    data-fee="{{ $level->fee }}" 
                                    data-validity="{{ $level->validity_period }}" 
                                    data-benefits="{{ $level->benefits }}"
                                    {{ $member->membership_level_id == $level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Active" {{ $member->status == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ $member->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Dynamic Level Details Box -->
            <div class="row mb-4" id="level_details_box">
                <div class="col-md-12">
                    <div class="p-3 bg-light border rounded border-info">
                        <h6 class="text-info fw-bold border-bottom pb-2 mb-2">Selected Level Details</h6>
                        <p class="mb-1"><strong>Standard Fee:</strong> ₱<span id="display_fee"></span></p>
                        <p class="mb-1"><strong>Validity Period:</strong> <span id="display_validity"></span> Months</p>
                        <p class="mb-0"><strong>Benefits:</strong> <span id="display_benefits"></span></p>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-warning">Update Member Details</button>
            <a href="{{ route('members.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
    function updateLevelDetails() {
        var select = document.getElementById('level_select');
        var selectedOption = select.options[select.selectedIndex];
        
        var fee = selectedOption.getAttribute('data-fee');
        var validity = selectedOption.getAttribute('data-validity');
        var benefits = selectedOption.getAttribute('data-benefits');
        
        document.getElementById('display_fee').textContent = parseFloat(fee).toFixed(2);
        document.getElementById('display_validity').textContent = validity;
        document.getElementById('display_benefits').textContent = benefits;
    }

    // Run immediately on page load to show the current level's details
    document.addEventListener('DOMContentLoaded', updateLevelDetails);
    
    // Run whenever the user changes the dropdown
    document.getElementById('level_select').addEventListener('change', updateLevelDetails);
</script>
@endsection