@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="module-title-header mb-0">Student Route Assignment Details</div>
        <a href="{{ route('student-route-assignments.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $assignment->id }}</td></tr>
        <tr><th>Student</th><td>{{ $assignment->student?->full_name ?: '-' }}</td></tr>
        <tr><th>Route</th><td>{{ $assignment->route?->display_name ?: '-' }}</td></tr>
        <tr><th>Stop</th><td>{{ $assignment->stop?->stop_name ?: '-' }}</td></tr>
        <tr><th>Assigned From</th><td>{{ $assignment->assigned_from ? $assignment->assigned_from->format('d-m-Y') : '-' }}</td></tr>
        <tr><th>Assigned To</th><td>{{ $assignment->assigned_to ? $assignment->assigned_to->format('d-m-Y') : '-' }}</td></tr>
        <tr><th>Status</th><td>{{ $assignment->is_active ? 'Active' : 'Inactive' }}</td></tr>
        <tr><th>Created By</th><td>{{ $assignment->createdBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Updated By</th><td>{{ $assignment->updatedBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Created At</th><td>{{ $assignment->created_at ? $assignment->created_at->format('d-m-Y H:i') : '-' }}</td></tr>
        <tr><th>Updated At</th><td>{{ $assignment->updated_at ? $assignment->updated_at->format('d-m-Y H:i') : '-' }}</td></tr>
    </table>
</div>
@endsection
