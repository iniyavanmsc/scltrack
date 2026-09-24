@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="module-title-header mb-3">Edit Route</div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('vehicle-routes.update', $route) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Route Name <span class="text-danger">*</span></label>
                <input type="text" name="route_name" class="form-control" value="{{ old('route_name', $route->route_name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Route Code</label>
                <div class="form-control bg-light">{{ $route->route_code ?: 'Will be generated after update' }}</div>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Trip Type <span class="text-danger">*</span></label>
                <select name="trip_type" class="form-select" required>
                    <option value="">Select Trip Type</option>
                    @foreach (['Morning', 'Afternoon', 'Evening', 'Special Trips'] as $tripType)
                        <option value="{{ $tripType }}" {{ old('trip_type', $route->trip_type) === $tripType ? 'selected' : '' }}>{{ $tripType }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Start Location</label>
                <input type="text" name="start_location" class="form-control" value="{{ old('start_location', $route->start_location) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">End Location</label>
                <input type="text" name="end_location" class="form-control" value="{{ old('end_location', $route->end_location) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Status <span class="text-danger">*</span></label>
                <select name="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', $route->is_active) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active', $route->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('vehicle-routes.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
