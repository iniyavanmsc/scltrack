@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="module-title-header mb-0">Vehicle Details</div>
        <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $vehicle->id }}</td></tr>
        <tr><th>Vehicle Number</th><td>{{ $vehicle->vehicle_number }}</td></tr>
        <tr><th>Registration Number</th><td>{{ $vehicle->registration_number ?: '-' }}</td></tr>
        <tr><th>Status</th><td>{{ $vehicle->is_active ? 'Active' : 'Inactive' }}</td></tr>
        <tr><th>Created By</th><td>{{ $vehicle->createdBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Updated By</th><td>{{ $vehicle->updatedBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Created At</th><td>{{ $vehicle->created_at ? $vehicle->created_at->format('d-m-Y H:i') : '-' }}</td></tr>
        <tr><th>Updated At</th><td>{{ $vehicle->updated_at ? $vehicle->updated_at->format('d-m-Y H:i') : '-' }}</td></tr>
    </table>
</div>
@endsection
