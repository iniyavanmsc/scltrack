@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="module-title-header mb-3">Edit Live Vehicle Location</div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $selectedVehicleId = old('vehicle_id', $liveLocation->vehicle_id);
        $selectedVehicle = $vehicles->firstWhere('id', (int) $selectedVehicleId);
        $selectedTripId = old('active_trip_id', $liveLocation->active_trip_id);
        $selectedTrip = $activeTrips->firstWhere('id', (int) $selectedTripId);
        $selectedIgnition = (string) old('ignition_on', $liveLocation->ignition_on ? '1' : '0');

        $vehicleOptions = $vehicles->map(fn ($vehicle) => [
            'id' => $vehicle->id,
            'text' => $vehicle->vehicle_number . ($vehicle->registration_number ? ' - ' . $vehicle->registration_number : ''),
        ])->values();
        $tripOptions = $activeTrips->map(fn ($trip) => [
            'id' => $trip->id,
            'text' => $trip->display_name,
        ])->values();
        $ignitionOptions = collect([
            ['id' => '1', 'text' => 'On'],
            ['id' => '0', 'text' => 'Off'],
        ]);
    @endphp

    <form method="POST" action="{{ route('live-vehicle-locations.update', $liveLocation) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Vehicle <span class="text-danger">*</span></label>
                <div class="position-relative" data-searchable-dropdown data-options='@json($vehicleOptions)'>
                    <input type="text" class="form-control" data-search-input value="{{ $selectedVehicle ? $selectedVehicle->vehicle_number . ($selectedVehicle->registration_number ? ' - ' . $selectedVehicle->registration_number : '') : '' }}" placeholder="Search vehicle" autocomplete="off" required>
                    <input type="hidden" name="vehicle_id" data-value-input value="{{ $selectedVehicleId }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Active Trip</label>
                <div class="position-relative" data-searchable-dropdown data-options='@json($tripOptions)'>
                    <input type="text" class="form-control" data-search-input value="{{ $selectedTrip?->display_name }}" placeholder="Search active trip" autocomplete="off">
                    <input type="hidden" name="active_trip_id" data-value-input value="{{ $selectedTripId }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Latitude <span class="text-danger">*</span></label>
                <input type="number" step="0.0000001" name="latitude" class="form-control" value="{{ old('latitude', $liveLocation->latitude) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Longitude <span class="text-danger">*</span></label>
                <input type="number" step="0.0000001" name="longitude" class="form-control" value="{{ old('longitude', $liveLocation->longitude) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Speed</label>
                <input type="number" step="0.01" name="speed" class="form-control" value="{{ old('speed', $liveLocation->speed) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Heading</label>
                <input type="number" step="0.01" name="heading" class="form-control" value="{{ old('heading', $liveLocation->heading) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Ignition <span class="text-danger">*</span></label>
                <div class="position-relative" data-searchable-dropdown data-options='@json($ignitionOptions)'>
                    <input type="text" class="form-control" data-search-input value="{{ $selectedIgnition === '1' ? 'On' : 'Off' }}" placeholder="Search ignition" autocomplete="off" required>
                    <input type="hidden" name="ignition_on" data-value-input value="{{ $selectedIgnition }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Recorded At</label>
                <input type="datetime-local" name="recorded_at" class="form-control" value="{{ old('recorded_at', $liveLocation->recorded_at ? $liveLocation->recorded_at->format('Y-m-d\TH:i') : '') }}">
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('live-vehicle-locations.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
