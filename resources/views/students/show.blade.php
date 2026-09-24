@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="module-title-header mb-0">Student Details</div>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $student->id }}</td></tr>
        <tr><th>Admission No</th><td>{{ $student->admission_no ?: '-' }}</td></tr>
        <tr><th>Full Name</th><td>{{ $student->full_name }}</td></tr>
        <tr><th>Parent</th><td>{{ $student->parent?->full_name ?: '-' }}</td></tr>
        <tr><th>Class</th><td>{{ $student->classSection?->class_name ?: $student->class_name ?: '-' }}</td></tr>
        <tr><th>Section</th><td>{{ $student->classSection?->section ?: $student->section ?: '-' }}</td></tr>
        <tr><th>Pickup Address</th><td>{{ $student->pickup_address ?: '-' }}</td></tr>
        <tr><th>Drop Address</th><td>{{ $student->drop_address ?: '-' }}</td></tr>
        <tr><th>Status</th><td>{{ $student->is_active ? 'Active' : 'Inactive' }}</td></tr>
        <tr><th>Created By</th><td>{{ $student->createdBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Updated By</th><td>{{ $student->updatedBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Created At</th><td>{{ $student->created_at ? $student->created_at->format('d-m-Y H:i') : '-' }}</td></tr>
        <tr><th>Updated At</th><td>{{ $student->updated_at ? $student->updated_at->format('d-m-Y H:i') : '-' }}</td></tr>
    </table>
</div>
@endsection
