@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="module-title-header mb-0">Vehicle Location History Details</div>
        <a href="{{ route('vehicle-location-history.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $locationHistory->id }}</td></tr>
        <tr><th>Vehicle</th><td>{{ $locationHistory->vehicle?->vehicle_number ?: '-' }}</td></tr>
        <tr><th>Active Trip</th><td>{{ $locationHistory->activeTrip?->display_name ?: '-' }}</td></tr>
        <tr><th>Latitude</th><td>{{ $locationHistory->latitude }}</td></tr>
        <tr><th>Longitude</th><td>{{ $locationHistory->longitude }}</td></tr>
        <tr><th>Speed</th><td>{{ $locationHistory->speed !== null ? $locationHistory->speed : '-' }}</td></tr>
        <tr><th>Heading</th><td>{{ $locationHistory->heading !== null ? $locationHistory->heading : '-' }}</td></tr>
        <tr><th>Ignition</th><td>{{ $locationHistory->ignition_on ? 'On' : 'Off' }}</td></tr>
        <tr><th>Recorded At</th><td>{{ $locationHistory->recorded_at ? $locationHistory->recorded_at->format('d-m-Y H:i') : '-' }}</td></tr>
        <tr><th>Raw Payload</th><td><pre class="mb-0">{{ $locationHistory->raw_payload ? json_encode($locationHistory->raw_payload, JSON_PRETTY_PRINT) : '-' }}</pre></td></tr>
        <tr><th>Created By</th><td>{{ $locationHistory->createdBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Updated By</th><td>{{ $locationHistory->updatedBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Created At</th><td>{{ $locationHistory->created_at ? $locationHistory->created_at->format('d-m-Y H:i') : '-' }}</td></tr>
        <tr><th>Updated At</th><td>{{ $locationHistory->updated_at ? $locationHistory->updated_at->format('d-m-Y H:i') : '-' }}</td></tr>
    </table>
</div>
@endsection
