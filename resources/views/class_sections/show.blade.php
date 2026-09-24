@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="module-title-header mb-0">Class / Section Details</div>
        <a href="{{ route('class-sections.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $classSection->id }}</td></tr>
        <tr><th>Class</th><td>{{ $classSection->class_name }}</td></tr>
        <tr><th>Section</th><td>{{ $classSection->section ?: '-' }}</td></tr>
        <tr><th>Status</th><td>{{ $classSection->is_active ? 'Active' : 'Inactive' }}</td></tr>
        <tr><th>Created By</th><td>{{ $classSection->createdBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Updated By</th><td>{{ $classSection->updatedBy?->full_name ?: '-' }}</td></tr>
        <tr><th>Created At</th><td>{{ $classSection->created_at ? $classSection->created_at->format('d-m-Y H:i') : '-' }}</td></tr>
        <tr><th>Updated At</th><td>{{ $classSection->updated_at ? $classSection->updated_at->format('d-m-Y H:i') : '-' }}</td></tr>
    </table>
</div>
@endsection
