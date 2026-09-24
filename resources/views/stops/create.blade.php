@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="module-title-header mb-0">Add Stop</div>
        <a href="{{ route('stops.index') }}" class="btn btn-secondary">Back</a>
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

    <form method="POST" action="{{ route('stops.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                @php
                    $selectedRouteId = old('route_id');
                    $selectedRoute = $routes->firstWhere('id', (int) $selectedRouteId);
                    $selectedRouteLabel = $selectedRoute
                        ? collect([$selectedRoute->route_name, $selectedRoute->route_code, $selectedRoute->trip_type])->filter()->implode(' - ')
                        : '';
                @endphp
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Route <span class="text-danger">*</span></label>
                <div class="position-relative" data-searchable-dropdown data-url="{{ route('lookups.routes') }}" data-fill-target="#stop-order-input" data-fill-field="next_order">
                    <input
                        type="text"
                        class="form-control"
                        data-search-input
                        name="route_search"
                        value="{{ old('route_search', $selectedRouteLabel) }}"
                        placeholder="Search by route name or code"
                        autocomplete="off"
                        required
                    >
                    <input type="hidden" name="route_id" id="stop-route-id" data-value-input value="{{ $selectedRouteId }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Stop Name <span class="text-danger">*</span></label>
                <input type="text" name="stop_name" class="form-control" value="{{ old('stop_name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Stop Order <span class="text-danger">*</span></label>
                <input type="number" name="stop_order" id="stop-order-input" class="form-control" value="{{ old('stop_order', 1) }}" min="1" required>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Latitude</label>
                <input type="number" step="0.0000001" name="latitude" class="form-control" value="{{ old('latitude') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Longitude</label>
                <input type="number" step="0.0000001" name="longitude" class="form-control" value="{{ old('longitude') }}">
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
                    <input type="hidden" name="is_active" id="stop-status" data-value-input value="{{ $selectedStatus }}">
                    <div class="list-group searchable-dropdown-results shadow-sm d-none" data-search-results></div>
                </div>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('stops.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
