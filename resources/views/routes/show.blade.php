@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="module-title-header mb-0">Route Details</div>
        <a href="{{ route('vehicle-routes.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $route->id }}</td></tr>
        <tr><th>Route Name</th><td>{{ $route->route_name }}</td></tr>
        <tr><th>Route Code</th><td>{{ $route->route_code ?: '-' }}</td></tr>
        <tr><th>Trip Type</th><td>{{ $route->trip_type ?: '-' }}</td></tr>
        <tr><th>Start Location</th><td>{{ $route->start_location ?: '-' }}</td></tr>
        <tr><th>End Location</th><td>{{ $route->end_location ?: '-' }}</td></tr>
        <tr><th>Status</th><td>{{ $route->is_active ? 'Active' : 'Inactive' }}</td></tr>
        <tr><th>Created By</th><td>{{ $route->createdBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Updated By</th><td>{{ $route->updatedBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Created At</th><td>{{ $route->created_at ? $route->created_at->format('d-m-Y H:i') : '-' }}</td></tr>
        <tr><th>Updated At</th><td>{{ $route->updated_at ? $route->updated_at->format('d-m-Y H:i') : '-' }}</td></tr>
    </table>
</div>
@endsection
