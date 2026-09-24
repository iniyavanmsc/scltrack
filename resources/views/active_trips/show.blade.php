@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="module-title-header mb-0">Active Trip Details</div>
        <a href="{{ route('active-trips.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $activeTrip->id }}</td></tr>
        <tr><th>Route</th><td>{{ $activeTrip->route?->display_name ?: '-' }}</td></tr>
        <tr><th>Vehicle</th><td>{{ $activeTrip->vehicle?->vehicle_number ?: '-' }}</td></tr>
        <tr><th>Driver</th><td>{{ $activeTrip->driver?->full_name ?: '-' }}</td></tr>
        <tr><th>Trip Date</th><td>{{ $activeTrip->trip_date ? $activeTrip->trip_date->format('d-m-Y') : '-' }}</td></tr>
        <tr><th>Trip Type</th><td>{{ $activeTrip->route?->trip_type ?: '-' }}</td></tr>
        <tr><th>Trip Status</th><td>{{ $activeTrip->trip_status ? ucfirst($activeTrip->trip_status) : '-' }}</td></tr>
        <tr><th>Started At</th><td>{{ $activeTrip->started_at ? $activeTrip->started_at->format('d-m-Y H:i') : '-' }}</td></tr>
        <tr><th>Ended At</th><td>{{ $activeTrip->ended_at ? $activeTrip->ended_at->format('d-m-Y H:i') : '-' }}</td></tr>
        <tr><th>Notes</th><td>{{ $activeTrip->notes ?: '-' }}</td></tr>
        <tr><th>Created By</th><td>{{ $activeTrip->createdBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Updated By</th><td>{{ $activeTrip->updatedBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Created At</th><td>{{ $activeTrip->created_at ? $activeTrip->created_at->format('d-m-Y H:i') : '-' }}</td></tr>
        <tr><th>Updated At</th><td>{{ $activeTrip->updated_at ? $activeTrip->updated_at->format('d-m-Y H:i') : '-' }}</td></tr>
    </table>
</div>
@endsection
