@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="module-title-header mb-3">Edit Active Trip</div>

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
        $selectedRouteId = old('route_id', $activeTrip->route_id);
        $selectedRoute = $routes->firstWhere('id', (int) $selectedRouteId);
        $selectedVehicleId = old('vehicle_id', $activeTrip->vehicle_id);
        $selectedVehicle = $vehicles->firstWhere('id', (int) $selectedVehicleId);
        $selectedDriverId = old('driver_id', $activeTrip->driver_id);
        $selectedDriver = $drivers->firstWhere('id', (int) $selectedDriverId);
        $selectedStatus = old('trip_status', $activeTrip->trip_status);

        $routeOptions = $routes->map(fn ($route) => [
            'id' => $route->id,
            'text' => $route->display_name,
        ])->values();
        $vehicleOptions = $vehicles->map(fn ($vehicle) => [
            'id' => $vehicle->id,
            'text' => $vehicle->vehicle_number . ($vehicle->registration_number ? ' - ' . $vehicle->registration_number : ''),
        ])->values();
        $driverOptions = $drivers->map(fn ($driver) => [
            'id' => $driver->id,
            'text' => $driver->full_name,
        ])->values();
        $statusOptions = collect($tripStatuses)->map(fn ($label, $value) => [
            'id' => $value,
            'text' => $label,
        ])->values();
    @endphp

    <form method="POST" action="{{ route('active-trips.update', $activeTrip) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Route <span class="text-danger">*</span></label>
                <div class="position-relative" data-searchable-dropdown data-options='@json($routeOptions)'>
                    <input type="text" class="form-control" data-search-input value="{{ $selectedRoute?->display_name }}" placeholder="Search route" autocomplete="off" required>
                    <input type="hidden" name="route_id" id="active-trip-route-id" data-value-input value="{{ $selectedRouteId }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Vehicle <span class="text-danger">*</span></label>
                <div class="position-relative" data-searchable-dropdown data-options='@json($vehicleOptions)'>
                    <input type="text" class="form-control" data-search-input value="{{ $selectedVehicle ? $selectedVehicle->vehicle_number . ($selectedVehicle->registration_number ? ' - ' . $selectedVehicle->registration_number : '') : '' }}" placeholder="Search vehicle" autocomplete="off" required>
                    <input type="hidden" name="vehicle_id" id="active-trip-vehicle-id" data-value-input value="{{ $selectedVehicleId }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Driver <span class="text-danger">*</span></label>
                <div class="position-relative" data-searchable-dropdown data-options='@json($driverOptions)'>
                    <input type="text" class="form-control" data-search-input value="{{ $selectedDriver?->full_name }}" placeholder="Search driver" autocomplete="off" required>
                    <input type="hidden" name="driver_id" id="active-trip-driver-id" data-value-input value="{{ $selectedDriverId }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Trip Date <span class="text-danger">*</span></label>
                <input type="date" name="trip_date" class="form-control" value="{{ old('trip_date', $activeTrip->trip_date ? $activeTrip->trip_date->format('Y-m-d') : '') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Trip Status <span class="text-danger">*</span></label>
                <div class="position-relative" data-searchable-dropdown data-options='@json($statusOptions)'>
                    <input type="text" class="form-control" data-search-input value="{{ $selectedStatus ? $tripStatuses[$selectedStatus] ?? '' : '' }}" placeholder="Search trip status" autocomplete="off" required>
                    <input type="hidden" name="trip_status" id="active-trip-status" data-value-input value="{{ $selectedStatus }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Notes</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $activeTrip->notes) }}</textarea>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('active-trips.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
