@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="module-title-header mb-0">Add Route</div>
        <a href="{{ route('vehicle-routes.index') }}" class="btn btn-secondary">Back</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('vehicle-routes.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Route Name <span class="text-danger">*</span></label>
                <input type="text" name="route_name" class="form-control" value="{{ old('route_name') }}" required>
            </div>
            <div class="col-md-6">
                @php
                    $tripTypeOptions = collect(['Morning', 'Afternoon', 'Evening', 'Special Trips'])
                        ->map(fn ($tripType) => ['id' => $tripType, 'text' => $tripType])
                        ->values();
                @endphp
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Trip Type <span class="text-danger">*</span></label>
                <div class="position-relative" data-searchable-dropdown data-options='@json($tripTypeOptions)'>
                    <input type="text" class="form-control" data-search-input value="{{ old('trip_type') }}" placeholder="Search trip type" autocomplete="off" required>
                    <input type="hidden" name="trip_type" id="route-trip-type" data-value-input value="{{ old('trip_type') }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Start Location</label>
                <input type="text" name="start_location" class="form-control" value="{{ old('start_location') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">End Location</label>
                <input type="text" name="end_location" class="form-control" value="{{ old('end_location') }}">
            </div>
            <div class="col-md-6">
                @php
                    $statusOptions = collect([
                        ['id' => '1', 'text' => 'Active'],
                        ['id' => '0', 'text' => 'Inactive'],
                    ]);
                    $selectedStatus = (string) old('is_active', '1');
                @endphp
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Status <span class="text-danger">*</span></label>
                <div class="position-relative" data-searchable-dropdown data-options='@json($statusOptions)'>
                    <input type="text" class="form-control" data-search-input value="{{ $selectedStatus === '1' ? 'Active' : 'Inactive' }}" placeholder="Search status" autocomplete="off" required>
                    <input type="hidden" name="is_active" id="route-status" data-value-input value="{{ $selectedStatus }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('vehicle-routes.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
