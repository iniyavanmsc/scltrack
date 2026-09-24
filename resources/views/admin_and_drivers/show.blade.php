@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="module-title-header mb-0">Admin / Driver Details</div>
        <a href="{{ route('admin-and-drivers.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $adminAndDriver->id }}</td></tr>
        <tr><th>Full Name</th><td>{{ $adminAndDriver->full_name }}</td></tr>
        <tr><th>Phone</th><td>{{ $adminAndDriver->phone ?: '-' }}</td></tr>
        <tr><th>Email</th><td>{{ $adminAndDriver->email_id ?: '-' }}</td></tr>
        <tr><th>Username</th><td>{{ $adminAndDriver->username }}</td></tr>
        <tr><th>User Role</th><td>{{ ucwords($adminAndDriver->user_role) }}</td></tr>
        <tr><th>License Number</th><td>{{ $adminAndDriver->license_number ?: '-' }}</td></tr>
        <tr><th>License Expiry Date</th><td>{{ $adminAndDriver->license_expiry_date ? $adminAndDriver->license_expiry_date->format('d-m-Y') : '-' }}</td></tr>
        <tr><th>Address</th><td>{{ $adminAndDriver->address ?: '-' }}</td></tr>
        <tr><th>Status</th><td>{{ $adminAndDriver->is_active ? 'Active' : 'Inactive' }}</td></tr>
        <tr><th>Created By</th><td>{{ $adminAndDriver->createdBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Updated By</th><td>{{ $adminAndDriver->updatedBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Created At</th><td>{{ $adminAndDriver->created_at ? $adminAndDriver->created_at->format('d-m-Y H:i') : '-' }}</td></tr>
        <tr><th>Updated At</th><td>{{ $adminAndDriver->updated_at ? $adminAndDriver->updated_at->format('d-m-Y H:i') : '-' }}</td></tr>
    </table>
</div>
@endsection
