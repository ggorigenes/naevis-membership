@extends('layout')

@section('content')
<!-- Leaflet Map Stylesheet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="card max-w-lg mx-auto mb-5">
    <div class="card-header">
        <h4>Add New Member</h4>
    </div>
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

        <form action="{{ route('members.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                </div>
                <div class="col-md-6">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label>Category</label>
                    <select name="membership_category_id" class="form-select" required>
                        <option value="">Select...</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('membership_category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Level</label>
                    <select name="membership_level_id" id="level_select" class="form-select" required>
                        <option value="">Select...</option>
                        @foreach($levels as $level)
                        <option value="{{ $level->id }}"
                            data-fee="{{ $level->fee }}"
                            data-validity="{{ $level->validity_period }}"
                            data-benefits="{{ $level->benefits }}"
                            {{ old('membership_level_id') == $level->id ? 'selected' : '' }}>
                            {{ $level->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Level Details Display Box -->
            <div class="row mb-4 d-none" id="level_details_box">
                <div class="col-md-12">
                    <div class="p-3 bg-light border rounded border-info">
                        <h6 class="text-info fw-bold border-bottom pb-2 mb-2">Level Details</h6>
                        <p class="mb-1"><strong>Validity Period:</strong> <span id="display_validity"></span> Months</p>
                        <p class="mb-2"><strong>Benefits:</strong> <span id="display_benefits"></span></p>
                        <hr>
                        <label class="fw-bold text-primary mt-2">Required Initial Payment (₱)</label>
                        <input type="number" step="0.01" name="payment_amount" id="payment_amount" class="form-control" placeholder="Enter amount to pay" value="{{ old('payment_amount') }}" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label>Address</label>
                <input type="text" name="address" class="form-control" placeholder="Street, City" value="{{ old('address') }}">
            </div>

            <div class="mb-4">
                <label class="fw-bold">Location on Map</label>
                <div id="map" style="height: 300px; border-radius: 8px; border: 1px solid #dee2e6;"></div>
                <input id="lat" type="hidden" name="latitude" value="{{ old('latitude', '14.5995') }}">
                <input id="lon" type="hidden" name="longitude" value="{{ old('longitude', '120.9842') }}">
                <div class="d-flex justify-content-between mt-2 small text-muted">
                    <span id="coordinates">Click the map to choose a location.</span>
                    <span id="weather" class="fw-semibold text-primary">Weather has not been loaded.</span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Member & Process Fee</button>
            <a href="{{ route('members.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<!-- Dynamic Level Select Script -->
<script>
    document.getElementById('level_select').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var fee = selectedOption.getAttribute('data-fee');
        var validity = selectedOption.getAttribute('data-validity');
        var benefits = selectedOption.getAttribute('data-benefits');

        var detailsBox = document.getElementById('level_details_box');
        var paymentInput = document.getElementById('payment_amount');

        if (fee) {
            detailsBox.classList.remove('d-none');

            document.getElementById('display_validity').textContent = validity;
            document.getElementById('display_benefits').textContent = benefits;

            paymentInput.min = fee;
            paymentInput.value = fee;
        } else {
            detailsBox.classList.add('d-none');
            paymentInput.min = 0;
            paymentInput.value = '';
        }
    });
</script>

<!-- Leaflet Map & Live Weather Script -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const defaultLat = Number(document.getElementById('lat').value);
    const defaultLon = Number(document.getElementById('lon').value);

    // Initialize Leaflet map centered at Manila default coordinates
    const map = L.map('map').setView([defaultLat, defaultLon], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker = L.marker([defaultLat, defaultLon]).addTo(map);

    function loadWeather(lat, lon) {
        document.getElementById('weather').textContent = 'Loading weather...';
        fetch(`/weather?lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lon)}`)
            .then(r => r.ok ? r.json() : Promise.reject())
            .then(w => {
                document.getElementById('weather').textContent =
                    `${w.place}: ${w.temperature}°C, ${w.description}, humidity ${w.humidity}%`;
            })
            .catch(() => {
                document.getElementById('weather').textContent = 'Weather is currently unavailable.';
            });
    }

    function setLocation(lat, lon) {
        document.getElementById('lat').value = lat.toFixed(7);
        document.getElementById('lon').value = lon.toFixed(7);
        document.getElementById('coordinates').textContent = `Lat: ${lat.toFixed(5)}, Lon: ${lon.toFixed(5)}`;

        if (marker) {
            marker.setLatLng([lat, lon]);
        } else {
            marker = L.marker([lat, lon]).addTo(map);
        }

        loadWeather(lat, lon);
    }

    // Update location and weather on click
    map.on('click', function(e) {
        setLocation(e.latlng.lat, e.latlng.lng);
    });

    // Initial weather fetch on page load
    loadWeather(defaultLat, defaultLon);
</script>
@endsection