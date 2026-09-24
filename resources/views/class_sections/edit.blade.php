@extends('layouts.headerfooter')

@section('content')
<div class="module-card">
    <div class="module-title-header mb-3">Edit Class / Section</div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('class-sections.update', $classSection) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Class <span class="text-danger">*</span></label>
                <input type="text" name="class_name" class="form-control" value="{{ old('class_name', $classSection->class_name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Section</label>
                <input type="text" name="section" class="form-control" value="{{ old('section', $classSection->section) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label mb-1 text-muted" style="font-size: 0.82rem; font-weight: 500;">Status <span class="text-danger">*</span></label>
                <select name="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', $classSection->is_active) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active', $classSection->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('class-sections.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
