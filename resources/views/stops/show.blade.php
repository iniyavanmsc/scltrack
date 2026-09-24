@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="module-title-header mb-0">Stop Details</div>
        <a href="{{ route('stops.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $stop->id }}</td></tr>
        <tr><th>Route</th><td>{{ $stop->route?->display_name ?: '-' }}</td></tr>
        <tr><th>Stop Name</th><td>{{ $stop->stop_name }}</td></tr>
        <tr><th>Stop Code</th><td>{{ $stop->stop_code ?: '-' }}</td></tr>
        <tr><th>Latitude</th><td>{{ $stop->latitude !== null ? $stop->latitude : '-' }}</td></tr>
        <tr><th>Longitude</th><td>{{ $stop->longitude !== null ? $stop->longitude : '-' }}</td></tr>
        <tr><th>Stop Order</th><td>{{ $stop->stop_order }}</td></tr>
        <tr><th>Status</th><td>{{ $stop->is_active ? 'Active' : 'Inactive' }}</td></tr>
        <tr><th>Created By</th><td>{{ $stop->createdBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Updated By</th><td>{{ $stop->updatedBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Created At</th><td>{{ $stop->created_at ? $stop->created_at->format('d-m-Y H:i') : '-' }}</td></tr>
        <tr><th>Updated At</th><td>{{ $stop->updated_at ? $stop->updated_at->format('d-m-Y H:i') : '-' }}</td></tr>
    </table>
</div>
@endsection
