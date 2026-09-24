@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="module-title-header mb-0">Live Vehicle Location Details</div>
        <a href="{{ route('live-vehicle-locations.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $liveLocation->id }}</td></tr>
        <tr><th>Vehicle</th><td>{{ $liveLocation->vehicle?->vehicle_number ?: '-' }}</td></tr>
        <tr><th>Active Trip</th><td>{{ $liveLocation->activeTrip?->display_name ?: '-' }}</td></tr>
        <tr><th>Latitude</th><td>{{ $liveLocation->latitude }}</td></tr>
        <tr><th>Longitude</th><td>{{ $liveLocation->longitude }}</td></tr>
        <tr><th>Speed</th><td>{{ $liveLocation->speed !== null ? $liveLocation->speed : '-' }}</td></tr>
        <tr><th>Heading</th><td>{{ $liveLocation->heading !== null ? $liveLocation->heading : '-' }}</td></tr>
        <tr><th>Ignition</th><td>{{ $liveLocation->ignition_on ? 'On' : 'Off' }}</td></tr>
        <tr><th>Recorded At</th><td>{{ $liveLocation->recorded_at ? $liveLocation->recorded_at->format('d-m-Y H:i') : '-' }}</td></tr>
        <tr><th>Created By</th><td>{{ $liveLocation->createdBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Updated By</th><td>{{ $liveLocation->updatedBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Created At</th><td>{{ $liveLocation->created_at ? $liveLocation->created_at->format('d-m-Y H:i') : '-' }}</td></tr>
        <tr><th>Updated At</th><td>{{ $liveLocation->updated_at ? $liveLocation->updated_at->format('d-m-Y H:i') : '-' }}</td></tr>
    </table>
</div>
@endsection
