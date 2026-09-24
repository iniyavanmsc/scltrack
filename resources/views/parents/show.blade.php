@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="module-title-header mb-0">Parent Details</div>
        <a href="{{ route('parents.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $parent->id }}</td></tr>
        <tr><th>Full Name</th><td>{{ $parent->full_name }}</td></tr>
        <tr><th>Phone</th><td>{{ $parent->phone ?: '-' }}</td></tr>
        <tr><th>Email</th><td>{{ $parent->email ?: '-' }}</td></tr>
        <tr><th>Address</th><td>{{ $parent->address ?: '-' }}</td></tr>
        <tr><th>Emergency Contact Name</th><td>{{ $parent->emergency_contact_name ?: '-' }}</td></tr>
        <tr><th>Emergency Contact Phone</th><td>{{ $parent->emergency_contact_phone ?: '-' }}</td></tr>
        <tr><th>Status</th><td>{{ $parent->is_active ? 'Active' : 'Inactive' }}</td></tr>
        <tr><th>Created By</th><td>{{ $parent->createdBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Updated By</th><td>{{ $parent->updatedBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Created At</th><td>{{ $parent->created_at ? $parent->created_at->format('d-m-Y H:i') : '-' }}</td></tr>
        <tr><th>Updated At</th><td>{{ $parent->updated_at ? $parent->updated_at->format('d-m-Y H:i') : '-' }}</td></tr>
    </table>
</div>
@endsection
